<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFile;
use App\Models\Payment;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        // Middleware is now applied via routes
    }

    public function create()
    {
        $academic_levels = ['High School', 'College', 'Undergraduate', 'Master', 'PhD'];
        $subject_areas = ['English', 'History', 'Business', 'Marketing', 'Psychology', 
                       'Sociology', 'Political Science', 'Economics', 'Computer Science', 
                       'Mathematics', 'Biology', 'Chemistry', 'Physics', 'Engineering'];
        
        return view('client.orders.create', compact('academic_levels', 'subject_areas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'academic_level' => 'required|string',
            'subject_area' => 'required|string',
            'word_count' => 'required|integer|min:250|max:10000',
            'deadline' => 'required|date|after:now',
            'citation_style' => 'required|string',
            'sources_required' => 'nullable|integer|min:0',
            'files.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        // Calculate price based on factors
        $base_price = 10; // Base price per 250 words
        $words_multiplier = ceil($request->word_count / 250);
        
        // Academic level multiplier
        $level_multipliers = [
            'High School' => 1,
            'College' => 1.2,
            'Undergraduate' => 1.5,
            'Master' => 2,
            'PhD' => 2.5,
        ];
        
        $level_multiplier = $level_multipliers[$request->academic_level] ?? 1;
        
        // Deadline urgency multiplier
        $days_until_deadline = now()->diffInDays($request->deadline) + 1;
        $urgency_multiplier = 1 + (1 / $days_until_deadline);
        
        $price = $base_price * $words_multiplier * $level_multiplier * $urgency_multiplier;
        $price = ceil($price); // Round up to nearest dollar
        
        // Create the order
        $order = Order::create([
            'client_id' => Auth::id(),
            'writer_id' => null, // No writer assigned yet
            'title' => $request->title,
            'description' => $request->description,
            'academic_level' => $request->academic_level,
            'subject_area' => $request->subject_area,
            'word_count' => $request->word_count,
            'deadline' => $request->deadline,
            'status' => 'pending',
            'price' => $price,
            'citation_style' => $request->citation_style,
            'sources_required' => $request->sources_required,
        ]);

        // Upload instruction files if any
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('order_files/' . $order->id, 'public');
                
                OrderFile::create([
                    'order_id' => $order->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'uploaded_by' => Auth::id(),
                    'file_category' => 'instruction',
                ]);
            }
        }

        // Create pending payment record
        Payment::create([
            'order_id' => $order->id,
            'amount' => $price,
            'status' => 'pending',
            'payment_method' => null,
            'transaction_id' => null,
            'payment_date' => null,
        ]);

        return redirect()->route('client.orders.payment', $order)
                       ->with('success', 'Order created successfully. Please complete the payment to submit your order.');
    }

    public function show(Order $order)
    {
        // Security check: only order owner can view
        if ($order->client_id !== Auth::id()) {
            return redirect()->route('client.orders')
                           ->with('error', 'You do not have permission to view this order.');
        }

        $order->load(['writer', 'files', 'messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }, 'payment']);

        return view('client.orders.show', compact('order'));
    }

    public function payment(Order $order)
    {
        // Security check: only order owner can pay
        if ($order->client_id !== Auth::id()) {
            return redirect()->route('client.orders')
                           ->with('error', 'You do not have permission to access this payment.');
        }

        $order->load('payment');
        
        if ($order->payment->status !== 'pending') {
            return redirect()->route('client.orders.show', $order)
                           ->with('info', 'This order has already been paid for.');
        }

        return view('client.orders.payment', compact('order'));
    }

    public function processPayment(Request $request, Order $order)
    {
        // Security check: only order owner can pay
        if ($order->client_id !== Auth::id()) {
            return redirect()->route('client.orders')
                           ->with('error', 'You do not have permission to access this payment.');
        }

        $order->load('payment');
        
        if ($order->payment->status !== 'pending') {
            return redirect()->route('client.orders.show', $order)
                           ->with('info', 'This order has already been paid for.');
        }

        $request->validate([
            'payment_method' => 'required|in:credit_card,paypal,mpesa',
            'transaction_id' => 'required|string',
        ]);

        // In a real app, you would integrate with payment processor here
        
        // Update payment record
        $order->payment->update([
            'status' => 'completed',
            'payment_method' => $request->payment_method,
            'transaction_id' => $request->transaction_id,
            'payment_date' => now(),
        ]);

        return redirect()->route('client.orders.show', $order)
                       ->with('success', 'Payment processed successfully. Your order is now live and waiting for a writer.');
    }

    public function requestRevision(Request $request, Order $order)
    {
        // Security check: only order owner can request revision
        if ($order->client_id !== Auth::id()) {
            return redirect()->route('client.orders')
                           ->with('error', 'You do not have permission to request revision for this order.');
        }

        // Verify order is completed
        if ($order->status !== 'completed') {
            return back()->with('error', 'You can only request revision for completed orders.');
        }

        $request->validate([
            'revision_comment' => 'required|string',
            'files.*' => 'nullable|file|max:10240', // 10MB max per file
        ]);

        // Update order status
        $order->status = 'revision';
        $order->save();

        // Add a message for the revision request
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $order->writer_id,
            'order_id' => $order->id,
            'content' => $request->revision_comment,
            'read_status' => false,
            'sender_type' => 'client',
            'recipient_type' => 'writer',
            'has_revision_request' => true,
        ]);

        // Upload additional files if any
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                $path = $file->store('order_files/' . $order->id, 'public');
                
                OrderFile::create([
                    'order_id' => $order->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'uploaded_by' => Auth::id(),
                    'file_category' => 'revision',
                ]);
            }
        }

        // Create notification for writer
        if ($order->writer) {
            $order->writer->notifications()->create([
                'type' => 'order_update',
                'message' => "Order #{$order->id} requires revision. Check the order details for more information.",
                'read_status' => false,
            ]);
        }

        return back()->with('success', 'Revision requested successfully. The writer will be notified.');
    }

    public function sendMessage(Request $request, Order $order)
    {
        // Security check: only order owner can message
        if ($order->client_id !== Auth::id()) {
            return redirect()->route('client.orders')
                           ->with('error', 'You do not have permission to send messages for this order.');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

        // Make sure there's a recipient
        if (!$order->writer_id) {
            return back()->with('error', 'There is no writer assigned to this order yet.');
        }

        // Handle file attachment if present
        $attachmentPath = null;
        $attachmentName = null;
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('message_attachments/' . $order->id, 'public');
        }

        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $order->writer_id,
            'order_id' => $order->id,
            'content' => $request->message,
            'read_status' => false,
            'sender_type' => 'client',
            'recipient_type' => 'writer',
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    public function disputeOrder(Request $request, Order $order)
    {
        // Security check: only order owner can dispute
        if ($order->client_id !== Auth::id()) {
            return redirect()->route('client.orders')
                           ->with('error', 'You do not have permission to dispute this order.');
        }

        // Verify order is eligible for dispute (completed or in revision)
        if (!in_array($order->status, ['completed', 'revision'])) {
            return back()->with('error', 'This order is not eligible for dispute at this time.');
        }

        $request->validate([
            'dispute_reason' => 'required|string',
        ]);

        // Update order status
        $order->status = 'disputed';
        $order->save();

        // Add a message documenting the dispute
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => null, // System message visible to all
            'order_id' => $order->id,
            'content' => "Order disputed. Reason: " . $request->dispute_reason,
            'read_status' => false,
        ]);

        // Create notification for admin (we'll use first admin for now)
        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notifications()->create([
                'type' => 'dispute',
                'message' => "Order #{$order->id} has been disputed by the client. Please review.",
                'read_status' => false,
            ]);
        }

        // Create notification for writer
        if ($order->writer) {
            $order->writer->notifications()->create([
                'type' => 'dispute',
                'message' => "Order #{$order->id} has been disputed by the client. An admin will review the case.",
                'read_status' => false,
            ]);
        }

        return back()->with('success', 'Order disputed successfully. An administrator will review your case.');
    }
    
    /**
     * Download completed work files as a zip archive
     */
    public function download(Order $order)
    {
        // Security check: only order owner can download
        if ($order->client_id !== Auth::id()) {
            return redirect()->route('client.orders')
                           ->with('error', 'You do not have permission to download files for this order.');
        }

        // Verify order is completed
        if ($order->status !== 'completed') {
            return back()->with('error', 'You can only download files for completed orders.');
        }

        // Get all submission files
        $submissionFiles = $order->files->where('file_category', 'submission');
        if ($submissionFiles->count() === 0) {
            return back()->with('error', 'No submission files found for this order.');
        }

        // If only one file, return it directly
        if ($submissionFiles->count() === 1) {
            $file = $submissionFiles->first();
            return response()->download(
                storage_path('app/public/' . $file->file_path),
                $file->original_filename ?? $file->file_name
            );
        }

        // If multiple files, create a zip archive
        $zipFileName = 'order_' . $order->id . '_files.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Create temp directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        // Create a new zip archive
        $zip = new \ZipArchive();
        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($submissionFiles as $file) {
                $filePath = storage_path('app/public/' . $file->file_path);
                $fileName = $file->original_filename ?? $file->file_name;
                
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $fileName);
                }
            }
            $zip->close();
            
            // Return the zip file
            return response()->download($zipPath, $zipFileName)->deleteFileAfterSend(true);
        }
        
        return back()->with('error', 'Could not create zip file. Please try again later.');
    }
} 