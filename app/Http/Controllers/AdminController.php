<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\Payment;
use App\Models\WriterPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // Constructor is removed since middleware is already applied in routes/web.php
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'admin']);
    // }

    public function dashboard()
    {
        // Summary statistics
        $stats = [
            'total_orders' => Order::count(),
            'active_writers' => User::where('role', 'writer')
                                  ->where('status', 'active')
                                  ->count(),
            'total_clients' => User::where('role', 'client')->count(),
            'monthly_revenue' => Payment::where('status', 'completed')
                                     ->whereMonth('created_at', now()->month)
                                     ->sum('amount'),
        ];

        // Recent orders
        $recentOrders = Order::with(['client', 'writer'])
                            ->latest()
                            ->take(10)
                            ->get();

        // Get actual writer applications instead of mock data
        $writerApplications = \App\Models\WriterApplication::latest()
                            ->take(5)
                            ->get();

        // Get order status data for pie chart
        $orderStatusData = [
            'labels' => ['Pending', 'In Progress', 'Completed', 'Revision', 'Cancelled'],
            'data' => [
                Order::where('status', 'pending')->count(),
                Order::where('status', 'in_progress')->count(),
                Order::where('status', 'completed')->count(),
                Order::where('status', 'revision')->count(),
                Order::where('status', 'cancelled')->count(),
            ],
        ];

        // Get monthly revenue data for bar chart (last 6 months)
        $revenueLabels = [];
        $revenueData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $revenueLabels[] = $month->format('M');
            $revenueData[] = Payment::where('status', 'completed')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
        }

        $revenueChartData = [
            'labels' => $revenueLabels,
            'data' => $revenueData
        ];

        return view('admin.dashboard', compact(
            'stats', 
            'recentOrders', 
            'writerApplications',
            'orderStatusData',
            'revenueChartData'
        ));
    }

    /**
     * Display the settings page.
     */
    public function settings()
    {
        return view('admin.settings');
    }

    /**
     * Update general settings.
     */
    public function updateSettings(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string',
            'contact_email' => 'required|email|max:255',
        ]);

        // Update .env file or database settings
        // This is a simplified example - in a production environment, you'd want to use a more secure method
        config(['app.name' => $validated['site_name']]);
        config(['app.description' => $validated['site_description']]);
        config(['mail.from.address' => $validated['contact_email']]);

        return redirect()->route('admin.settings')
            ->with('success', 'General settings updated successfully.');
    }

    /**
     * Update payment settings.
     */
    public function updatePaymentSettings(Request $request)
    {
        $validated = $request->validate([
            'writer_commission' => 'required|numeric|min:0|max:100',
            'minimum_payout' => 'required|numeric|min:0',
        ]);

        // Update settings in database or config
        config(['settings.writer_commission' => $validated['writer_commission']]);
        config(['settings.minimum_payout' => $validated['minimum_payout']]);

        return redirect()->route('admin.settings')
            ->with('success', 'Payment settings updated successfully.');
    }

    /**
     * Update email settings.
     */
    public function updateEmailSettings(Request $request)
    {
        $validated = $request->validate([
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'nullable|string|max:255',
        ]);

        // Update mail settings
        config(['mail.mailers.smtp.host' => $validated['mail_host']]);
        config(['mail.mailers.smtp.port' => $validated['mail_port']]);
        config(['mail.mailers.smtp.username' => $validated['mail_username']]);
        
        // Only update password if provided
        if (!empty($validated['mail_password'])) {
            config(['mail.mailers.smtp.password' => $validated['mail_password']]);
        }

        return redirect()->route('admin.settings')
            ->with('success', 'Email settings updated successfully.');
    }

    public function reports()
    {
        $monthly_revenue = Payment::select(
            DB::raw('sum(amount) as revenue'), 
            DB::raw("strftime('%m', created_at) as month")
        )
        ->where('status', 'completed')
        ->whereYear('created_at', now()->year)
        ->groupBy('month')
        ->get();

        $order_counts = Order::select(
            DB::raw('count(*) as order_count'),
            'status',
            DB::raw("strftime('%m', created_at) as month")
        )
        ->whereYear('created_at', now()->year)
        ->groupBy('status', 'month')
        ->get();

        return view('admin.reports', compact('monthly_revenue', 'order_counts'));
    }

    public function disputes()
    {
        $disputed_orders = Order::where('status', 'disputed')
                               ->with(['client', 'writer'])
                               ->paginate(15);

        return view('admin.disputes', compact('disputed_orders'));
    }
} 