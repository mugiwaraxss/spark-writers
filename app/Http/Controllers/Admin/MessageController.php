<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MessageController extends Controller
{
    /**
     * Display a list of all messages grouped by order.
     */
    public function index()
    {
        $messagesGroups = Message::select('order_id')
            ->distinct()
            ->with(['order.client', 'order.writer'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
            
        return view('admin.messages.index', compact('messagesGroups'));
    }
    
    /**
     * Display messages for a specific order.
     */
    public function showOrderMessages(Order $order)
    {
        $order->load(['client', 'writer', 'messages' => function($query) {
            $query->orderBy('created_at', 'asc');
        }]);
        
        return view('admin.messages.show', compact('order'));
    }
    
    /**
     * Send a message to a user (client or writer) regarding an order.
     */
    public function sendMessage(Request $request, Order $order)
    {
        $request->validate([
            'message' => 'required|string',
            'recipient_type' => 'required|in:client,writer',
            'attachment' => 'nullable|file|max:10240', // 10MB limit
        ]);
        
        // Determine recipient
        $recipientId = null;
        if ($request->recipient_type === 'client') {
            $recipientId = $order->client_id;
        } elseif ($request->recipient_type === 'writer' && $order->writer_id) {
            $recipientId = $order->writer_id;
        }
        
        if (!$recipientId) {
            return back()->with('error', 'Invalid recipient or the order does not have an assigned writer yet.');
        }
        
        // Handle file attachment if present
        $attachmentPath = null;
        $attachmentName = null;
        
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentName = $file->getClientOriginalName();
            $attachmentPath = $file->store('message_attachments/' . $order->id, 'public');
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
            'sender_type' => 'admin',
            'recipient_type' => $request->recipient_type,
        ]);
        
        // Create notification for recipient
        $recipient = User::find($recipientId);
        $recipient->notifications()->create([
            'type' => 'message',
            'message' => "You have a new message from admin regarding Order #{$order->id}.",
            'read_status' => false,
        ]);
        
        return back()->with('success', 'Message sent successfully.');
    }
    
    /**
     * Display a list of direct messages with a specific user.
     */
    public function showUserMessages(User $user)
    {
        $messages = Message::where(function($query) use ($user) {
                $query->where('sender_id', Auth::id())
                    ->where('recipient_id', $user->id);
            })
            ->orWhere(function($query) use ($user) {
                $query->where('sender_id', $user->id)
                    ->where('recipient_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('admin.messages.user', compact('user', 'messages'));
    }
    
    /**
     * Send a direct message to a user (not related to an order).
     */
    public function sendDirectMessage(Request $request, User $user)
    {
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
            $attachmentPath = $file->store('message_attachments/direct', 'public');
        }
        
        // Determine recipient type
        $recipientType = $user->role;
        
        // Create the message
        Message::create([
            'sender_id' => Auth::id(),
            'recipient_id' => $user->id,
            'order_id' => null, // Direct message, not related to an order
            'content' => $request->message,
            'read_status' => false,
            'attachment_path' => $attachmentPath,
            'attachment_name' => $attachmentName,
            'sender_type' => 'admin',
            'recipient_type' => $recipientType,
        ]);
        
        // Create notification for recipient
        $user->notifications()->create([
            'type' => 'direct_message',
            'message' => "You have a new direct message from the admin.",
            'read_status' => false,
        ]);
        
        return back()->with('success', 'Direct message sent successfully.');
    }
    
    /**
     * List all active users for direct messaging.
     */
    public function listUsers()
    {
        $clients = User::where('role', 'client')->get();
        $writers = User::where('role', 'writer')->get();
        
        return view('admin.messages.users', compact('clients', 'writers'));
    }
    
    /**
     * Mark a message as read.
     */
    public function markAsRead(Message $message)
    {
        // Only mark if the admin is the recipient
        if ($message->recipient_id === Auth::id()) {
            $message->update(['read_status' => true]);
        }
        
        return response()->json(['success' => true]);
    }
}
