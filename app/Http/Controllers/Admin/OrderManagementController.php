<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderManagementController extends Controller
{
    // Remove constructor to test if middleware is the issue
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'admin']);
    // }

    public function index(Request $request)
    {
        // Build the query with filters
        $query = Order::query()->with(['client', 'writer']);
        
        // Apply status filter if set and not 'all'
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        // Get orders with pagination
        $orders = $query->latest()->paginate(15);
        
        // Status options for filter dropdown
        $status_options = ['all', 'pending', 'assigned', 'in_progress', 'completed', 'revision', 'disputed'];

        return view('admin.orders.index', compact('orders', 'status_options'));
    }

    public function show(Order $order)
    {
        $order->load(['client', 'writer', 'files', 'messages', 'payment', 'writerPayment']);
        $availableWriters = User::where('role', 'writer')
                               ->where('status', 'active')
                               ->orderBy('name')
                               ->get();
        return view('admin.orders.show', compact('order', 'availableWriters'));
    }

    public function assignWriter(Order $order, Request $request)
    {
        $request->validate([
            'writer_id' => 'required|exists:users,id',
        ]);

        // Check if selected user is actually a writer
        $writer = User::find($request->writer_id);
        if ($writer->role !== 'writer') {
            return back()->with('error', 'Selected user is not a writer.');
        }

        $order->writer_id = $request->writer_id;
        $order->status = 'assigned';
        $order->save();

        // You might want to send notifications here
        
        return back()->with('success', 'Writer assigned successfully.');
    }

    public function changeStatus(Order $order, Request $request)
    {
        $request->validate([
            'status' => 'required|in:pending,assigned,in_progress,completed,revision,disputed',
        ]);

        $order->status = $request->status;
        $order->save();
        
        return back()->with('success', 'Order status updated successfully.');
    }

    public function resolveDispute(Order $order, Request $request)
    {
        $request->validate([
            'resolution' => 'required|string',
            'resolved_status' => 'required|in:in_progress,completed,revision',
        ]);

        $order->status = $request->resolved_status;
        $order->save();

        // Add a message documenting the resolution
        $order->messages()->create([
            'sender_id' => auth()->id(),
            'recipient_id' => null, // System message visible to all parties
            'content' => "Dispute resolved by admin: " . $request->resolution,
            'read_status' => false,
        ]);
        
        return back()->with('success', 'Dispute resolved successfully.');
    }
} 