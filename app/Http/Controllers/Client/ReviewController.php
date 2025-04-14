<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function __construct()
    {
        // Middleware is now applied via routes
    }

    public function create(Order $order)
    {
        // Security check: only order owner can review
        if ($order->client_id !== Auth::id()) {
            return redirect()->route('client.orders')
                           ->with('error', 'You do not have permission to review this order.');
        }

        // Check if order is completed and has a writer
        if ($order->status !== 'completed' || !$order->writer_id) {
            return redirect()->route('client.orders.show', $order)
                           ->with('error', 'You can only review completed orders.');
        }

        // Check if already reviewed
        if ($order->review) {
            return redirect()->route('client.orders.show', $order)
                           ->with('error', 'You have already reviewed this order.');
        }

        $order->load('writer');
        return view('client.reviews.create', compact('order'));
    }

    public function store(Request $request, Order $order)
    {
        // Security check: only order owner can review
        if ($order->client_id !== Auth::id()) {
            return redirect()->route('client.orders')
                           ->with('error', 'You do not have permission to review this order.');
        }

        // Check if order is completed and has a writer
        if ($order->status !== 'completed' || !$order->writer_id) {
            return redirect()->route('client.orders.show', $order)
                           ->with('error', 'You can only review completed orders.');
        }

        // Check if already reviewed
        if ($order->review) {
            return redirect()->route('client.orders.show', $order)
                           ->with('error', 'You have already reviewed this order.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        // Create the review
        $review = Review::create([
            'order_id' => $order->id,
            'client_id' => Auth::id(),
            'writer_id' => $order->writer_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Update writer's average rating
        $writer = $order->writer;
        $writer->load('writerProfile');
        
        $average_rating = Review::where('writer_id', $writer->id)->avg('rating');
        $writer->writerProfile->update([
            'rating' => $average_rating,
        ]);

        // Create notification for writer
        $writer->notifications()->create([
            'type' => 'review',
            'message' => "You have received a {$request->rating}-star review for Order #{$order->id}.",
            'read_status' => false,
        ]);

        return redirect()->route('client.orders.show', $order)
                       ->with('success', 'Thank you for your review! It helps other clients and improves our service.');
    }

    public function edit(Review $review)
    {
        $order = $review->order;
        
        // Security check: only review owner can edit
        if ($review->client_id !== Auth::id()) {
            return redirect()->route('client.orders')
                           ->with('error', 'You do not have permission to edit this review.');
        }

        // Check if within edit window (e.g., 7 days)
        $edit_window = now()->subDays(7);
        if ($review->created_at < $edit_window) {
            return redirect()->route('client.orders.show', $order)
                           ->with('error', 'Reviews can only be edited within 7 days of submission.');
        }

        $order->load('writer');
        return view('client.reviews.edit', compact('review', 'order'));
    }

    public function update(Request $request, Review $review)
    {
        $order = $review->order;
        
        // Security check: only review owner can update
        if ($review->client_id !== Auth::id()) {
            return redirect()->route('client.orders')
                           ->with('error', 'You do not have permission to update this review.');
        }

        // Check if within edit window (e.g., 7 days)
        $edit_window = now()->subDays(7);
        if ($review->created_at < $edit_window) {
            return redirect()->route('client.orders.show', $order)
                           ->with('error', 'Reviews can only be edited within 7 days of submission.');
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:500',
        ]);

        // Update the review
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Update writer's average rating
        $writer = $order->writer;
        $writer->load('writerProfile');
        
        $average_rating = Review::where('writer_id', $writer->id)->avg('rating');
        $writer->writerProfile->update([
            'rating' => $average_rating,
        ]);

        // Create notification for writer
        $writer->notifications()->create([
            'type' => 'review',
            'message' => "A client has updated their review for Order #{$order->id}.",
            'read_status' => false,
        ]);

        return redirect()->route('client.orders.show', $order)
                       ->with('success', 'Review updated successfully.');
    }
} 