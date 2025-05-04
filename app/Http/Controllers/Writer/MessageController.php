<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\WriterMiddleware::class]);
    }

    /**
     * Display a list of all messages for the writer
     */
    public function index()
    {
        $writer = Auth::user();
        
        // Get messages grouped by order
        $orderMessages = Message::where('recipient_id', $writer->id)
            ->whereNotNull('order_id')
            ->select('order_id')
            ->distinct()
            ->with(['order'])
            ->get()
            ->sortByDesc(function($message) {
                return $message->order->updated_at;
            });
            
        // Get direct messages (not related to orders)
        $directMessages = Message::where('recipient_id', $writer->id)
            ->whereNull('order_id')
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Mark all unread messages as read
        Auth::user()->notifications()
            ->where(function($query) {
                $query->where('type', 'message')
                    ->orWhere('type', 'direct_message');
            })
            ->update(['read_status' => true]);
        
        return view('writer.messages.index', compact('orderMessages', 'directMessages'));
    }
    
    /**
     * Display messages for a specific order
     */
    public function showOrderMessages(Order $order)
    {
        // Security check: only assigned writer can view
        if ($order->writer_id !== Auth::id()) {
            return redirect()->route('writer.messages.index')
                ->with('error', 'You are not authorized to view these messages.');
        }
        
        $order->load(['client', 'messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }]);
        
        // Mark messages for this order as read
        Message::where('order_id', $order->id)
            ->where('recipient_id', Auth::id())
            ->where('read_status', false)
            ->update(['read_status' => true]);
        
        return view('writer.messages.order', compact('order'));
    }
    
    /**
     * Reply to a message in an order
     */
    public function replyToOrder(Request $request, Order $order)
    {
        // Security check: only assigned writer can message
        if ($order->writer_id !== Auth::id()) {
            return redirect()->route('writer.messages.index')
                ->with('error', 'You are not authorized to send messages for this order.');
        }
        
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB limit
        ]);
        
        // Handle file attachment if present
        $attachmentPath = null;
        $attachmentName = null;
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('message_attachments/' . $order->id, 'public');
        }
        
        // Determine recipient (client or admin based on the previous message)
        $lastMessage = $order->messages()->latest()->first();
        $recipientId = $order->client_id; // Default to client
        $recipientType = 'client';
        
        if ($lastMessage && $lastMessage->sender_type === 'admin') {
            $recipientId = $lastMessage->sender_id;
            $recipientType = 'admin';
        }
        
        // Create the message
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $recipientId,
            'order_id' => $order->id,
            'content' => $request->message,
            'read_status' => false,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'sender_type' => 'writer',
            'recipient_type' => $recipientType,
        ]);
        
        // Create notification for recipient
        $order->client->notifications()->create([
            'type' => 'message',
            'message' => "You have a new message from writer regarding Order #{$order->id}.",
            'read_status' => false,
        ]);
        
        return back()->with('success', 'Message sent successfully.');
    }
    
    /**
     * Show all direct messages with a specific admin
     */
    public function showDirectMessages()
    {
        $writer = Auth::user();
        
        $messages = Message::where(function($query) use ($writer) {
                $query->where('recipient_id', $writer->id)
                    ->orWhere('sender_id', $writer->id);
            })
            ->whereNull('order_id')
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        // Mark messages as read
        Message::where('recipient_id', $writer->id)
            ->whereNull('order_id')
            ->where('read_status', false)
            ->update(['read_status' => true]);
        
        return view('writer.messages.direct', compact('messages'));
    }
    
    /**
     * Reply to a direct message
     */
    public function replyToDirectMessage(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'required|string',
            'attachment' => 'nullable|file|max:10240', // 10MB limit
        ]);
        
        // Handle file attachment if present
        $attachmentPath = null;
        $attachmentName = null;
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('message_attachments/direct', 'public');
        }
        
        // Create the message
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'order_id' => null, // Direct message
            'content' => $request->message,
            'read_status' => false,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'sender_type' => 'writer',
            'recipient_type' => 'admin', // Direct messages for writers are always to admins
        ]);
        
        return back()->with('success', 'Message sent successfully.');
    }
} 