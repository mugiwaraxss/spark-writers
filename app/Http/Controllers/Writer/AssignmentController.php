<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderFile;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\WriterMiddleware::class]);
    }

    public function index()
    {
        $writer = Auth::user();
        
        $active_assignments = Order::where('writer_id', $writer->id)
                               ->whereIn('status', ['assigned', 'in_progress', 'revision'])
                               ->latest()
                               ->with(['client', 'files'])
                               ->paginate(10);
        
        return view('writer.assignments.index', compact('active_assignments'));
    }

    public function show(Order $order)
    {
        // Security check: only assigned writer can view
        if ($order->writer_id !== Auth::id()) {
            return redirect()->route('writer.orders')
                           ->with('error', 'You are not assigned to this order.');
        }

        $order->load(['client', 'files', 'messages' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }]);

        return view('writer.assignments.show', compact('order'));
    }

    public function acceptOrder(Order $order)
    {
        // Verify the order is pending and writer is assigned
        if ($order->status !== 'assigned' || $order->writer_id !== Auth::id()) {
            return back()->with('error', 'You cannot accept this order.');
        }

        $order->status = 'in_progress';
        $order->save();

        // Create notification for client
        $order->client->notifications()->create([
            'type' => 'order_update',
            'message' => "Writer has accepted your order #{$order->id} and started working on it.",
            'read_status' => false,
        ]);

        return back()->with('success', 'Order accepted successfully. You can now start working on it.');
    }

    public function submitWork(Request $request, Order $order)
    {
        // Security check: only assigned writer can submit
        if ($order->writer_id !== Auth::id()) {
            return redirect()->route('writer.orders')
                           ->with('error', 'You are not assigned to this order.');
        }

        // Verify order can be submitted - allowing more statuses while we debug
        if (in_array($order->status, ['completed', 'disputed', 'cancelled'])) {
            return back()->with('error', 'This order cannot be submitted at this time.');
        }

        $request->validate([
            'comment' => 'required|string',
            'files' => 'required|array',
            'files.*' => 'file|max:10240', // 10MB max per file
        ]);

        // Upload each file
        foreach ($request->file('files') as $file) {
            $path = $file->store('order_files/' . $order->id, 'public');
            
            OrderFile::create([
                'order_id' => $order->id,
                'file_path' => $path,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientMimeType(),
                'uploaded_by' => Auth::id(),
                'file_category' => 'submission',
            ]);
        }

        // Add message for submission
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $order->client_id,
            'order_id' => $order->id,
            'content' => $request->comment,
            'read_status' => false,
        ]);

        // Update order status
        $order->status = 'completed';
        $order->save();

        // Store the comment as a submission note
        $order->submission_note = $request->comment;
        $order->save();

        // Create notification for client
        $order->client->notifications()->create([
            'type' => 'order_update',
            'message' => "Your order #{$order->id} has been completed and is ready for review.",
            'read_status' => false,
        ]);

        return redirect()->route('writer.orders')
                       ->with('success', 'Your work has been submitted successfully.');
    }

    public function startRevision(Order $order)
    {
        // Security check: only assigned writer can revise
        if ($order->writer_id !== Auth::id()) {
            return redirect()->route('writer.orders')
                           ->with('error', 'You are not assigned to this order.');
        }

        // Verify order is in revision status
        if ($order->status !== 'revision') {
            return back()->with('error', 'This order is not currently in revision.');
        }

        // Change status to in_progress
        $order->status = 'in_progress';
        $order->save();

        // Create notification for client
        $order->client->notifications()->create([
            'type' => 'order_update',
            'message' => "Writer has started working on the revision for order #{$order->id}.",
            'read_status' => false,
        ]);

        return back()->with('success', 'You have started working on the revision.');
    }

    public function sendMessage(Request $request, Order $order)
    {
        // Security check: only assigned writer can message
        if ($order->writer_id !== Auth::id()) {
            return redirect()->route('writer.orders')
                           ->with('error', 'You are not assigned to this order.');
        }

        $request->validate([
            'message' => 'required|string',
        ]);

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
            'recipient_id' => $order->client_id,
            'order_id' => $order->id,
            'content' => $request->message,
            'read_status' => false,
            'sender_type' => 'writer',
            'recipient_type' => 'client',
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
        ]);

        return back()->with('success', 'Message sent successfully.');
    }

    public function claimOrder(Order $order)
    {
        // Verify writer is eligible to claim this order
        $writer = Auth::user();
        $writer->load('writerProfile');
        
        if ($writer->writerProfile->availability_status !== 'available') {
            return back()->with('error', 'You must be available to claim new orders.');
        }
        
        if ($order->writer_id !== null) {
            return back()->with('error', 'This order has already been assigned to a writer.');
        }
        
        if ($order->status !== 'pending') {
            return back()->with('error', 'This order is not available for claiming.');
        }
        
        // Check if writer has expertise in the subject
        $writer_expertise = $writer->writerProfile->expertise_areas ?? [];
        if (!in_array($order->subject_area, $writer_expertise)) {
            return back()->with('error', 'You do not have the required expertise for this order.');
        }
        
        // Assign order to writer
        $order->writer_id = $writer->id;
        $order->status = 'assigned';
        $order->save();
        
        // Create notification for client
        $order->client->notifications()->create([
            'type' => 'order_update',
            'message' => "A writer has been assigned to your order #{$order->id}.",
            'read_status' => false,
        ]);
        
        return redirect()->route('writer.assignments.show', $order)
                       ->with('success', 'You have successfully claimed this order.');
    }
    
    /**
     * Lists all revision requests for the writer
     */
    public function revisions()
    {
        $writer = Auth::user();
        
        $revision_orders = Order::where('writer_id', $writer->id)
                              ->where('status', 'revision')
                              ->latest()
                              ->with(['client', 'files', 'messages' => function($query) {
                                  $query->where('has_revision_request', true)
                                        ->orderBy('created_at', 'desc');
                              }])
                              ->paginate(10);
        
        return view('writer.assignments.revisions', compact('revision_orders'));
    }
    
    /**
     * View details of a revision request
     */
    public function viewRevision(Order $order)
    {
        // Security check: only assigned writer can view
        if ($order->writer_id !== Auth::id()) {
            return redirect()->route('writer.orders')
                           ->with('error', 'You are not assigned to this order.');
        }
        
        // Check if order is in revision status
        if ($order->status !== 'revision') {
            return redirect()->route('writer.assignments.show', $order)
                           ->with('error', 'This order is not currently in revision.');
        }
        
        $order->load(['client', 'files', 'messages' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }]);
        
        // Get the latest revision request message
        $revisionRequest = $order->messages()
                                ->where('has_revision_request', true)
                                ->orderBy('created_at', 'desc')
                                ->first();
        
        return view('writer.assignments.revision_details', compact('order', 'revisionRequest'));
    }
    
    /**
     * Download the submission files
     */
    public function downloadSubmission(Order $order)
    {
        // Security check: only assigned writer can download
        if ($order->writer_id !== Auth::id()) {
            return redirect()->route('writer.orders')
                           ->with('error', 'You are not assigned to this order.');
        }
        
        // Get submission files
        $submissionFiles = $order->files()->where('file_category', 'submission')->get();
        
        if ($submissionFiles->isEmpty()) {
            return back()->with('error', 'No submission files found for this order.');
        }
        
        // For single file download
        if ($submissionFiles->count() === 1) {
            $file = $submissionFiles->first();
            return Storage::disk('public')->download($file->file_path, $file->file_name);
        }
        
        // For multiple files, create a zip archive
        // Note: This requires the ZipArchive PHP extension
        $zipFileName = "order_{$order->id}_submission.zip";
        $zipFilePath = storage_path("app/temp/{$zipFileName}");
        
        // Create directory if it doesn't exist
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }
        
        $zip = new \ZipArchive();
        if ($zip->open($zipFilePath, \ZipArchive::CREATE) === TRUE) {
            foreach ($submissionFiles as $file) {
                $filePath = storage_path("app/public/{$file->file_path}");
                if (file_exists($filePath)) {
                    $zip->addFile($filePath, $file->file_name);
                }
            }
            $zip->close();
            
            return response()->download($zipFilePath, $zipFileName)->deleteFileAfterSend(true);
        }
        
        return back()->with('error', 'Could not create zip file for download.');
    }
} 