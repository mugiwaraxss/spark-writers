<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\WriterPayment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentManagementController extends Controller
{
    public function __construct()
    {
        // Middleware is now applied via routes
    }

    public function clientPayments(Request $request)
    {
        $query = Payment::with(['order', 'order.client']);

        // Filter by status if provided
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort by column
        $sort_field = $request->sort_by ?? 'created_at';
        $sort_direction = $request->sort_direction ?? 'desc';
        $query->orderBy($sort_field, $sort_direction);

        $payments = $query->paginate(15);
        $status_options = ['all', 'pending', 'completed', 'refunded'];

        return view('admin.payments.client', compact('payments', 'status_options'));
    }

    public function writerPayments(Request $request)
    {
        $query = WriterPayment::with(['writer', 'order']);

        // Filter by status if provided
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }

        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort by column
        $sort_field = $request->sort_by ?? 'created_at';
        $sort_direction = $request->sort_direction ?? 'desc';
        $query->orderBy($sort_field, $sort_direction);

        $payments = $query->paginate(15);
        $status_options = ['all', 'pending', 'processed'];

        return view('admin.payments.writer', compact('payments', 'status_options'));
    }

    public function showClientPayment(Payment $payment)
    {
        $payment->load(['order', 'order.client']);
        return view('admin.payments.show_client', compact('payment'));
    }

    public function showWriterPayment(WriterPayment $payment)
    {
        $payment->load(['writer', 'order']);
        return view('admin.payments.show_writer', compact('payment'));
    }

    public function processWriterPayment(WriterPayment $payment, Request $request)
    {
        if ($payment->status === 'processed') {
            return back()->with('error', 'Payment has already been processed.');
        }

        $request->validate([
            'transaction_id' => 'required|string',
        ]);

        $payment->status = 'processed';
        $payment->payment_date = now();
        $payment->save();

        // Create notification for writer
        $payment->writer->notifications()->create([
            'type' => 'payment',
            'message' => "Your payment of $" . number_format($payment->amount, 2) . " for Order #{$payment->order_id} has been processed.",
            'read_status' => false,
        ]);

        return back()->with('success', 'Writer payment has been processed successfully.');
    }

    public function createWriterPayment(Order $order, Request $request)
    {
        // Validate the order is completed and has a writer
        if ($order->status !== 'completed') {
            return back()->with('error', 'Only completed orders can have writer payments.');
        }

        if (!$order->writer_id) {
            return back()->with('error', 'This order does not have an assigned writer.');
        }

        // Check if payment already exists
        if ($order->writerPayment) {
            return back()->with('error', 'A payment for this writer already exists.');
        }

        // Calculate writer's payment (40% of order amount)
        $writerAmount = $order->amount * 0.4;

        // Create the writer payment
        $payment = WriterPayment::create([
            'writer_id' => $order->writer_id,
            'order_id' => $order->id,
            'amount' => $writerAmount,
            'status' => 'pending',
            'payment_date' => null,
        ]);

        // Create notification for writer
        $order->writer->notifications()->create([
            'type' => 'payment',
            'message' => "A new payment of $" . number_format($payment->amount, 2) . " for Order #{$order->id} has been created.",
            'read_status' => false,
        ]);

        return back()->with('success', 'Writer payment has been created successfully.');
    }

    public function refundClientPayment(Payment $payment, Request $request)
    {
        if ($payment->status === 'refunded') {
            return back()->with('error', 'Payment has already been refunded.');
        }

        $request->validate([
            'refund_reason' => 'required|string',
        ]);

        $payment->status = 'refunded';
        $payment->save();

        // Create notification for client
        $payment->order->client->notifications()->create([
            'type' => 'payment',
            'message' => "Your payment of $" . number_format($payment->amount, 2) . " for Order #{$payment->order_id} has been refunded. Reason: {$request->refund_reason}",
            'read_status' => false,
        ]);

        return back()->with('success', 'Client payment has been refunded successfully.');
    }
} 