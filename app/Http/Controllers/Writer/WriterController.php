<?php

namespace App\Http\Controllers\Writer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\WriterPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\AssignmentController;

class WriterController extends Controller
{
    public function __construct()
    {
        $this->middleware([\App\Http\Middleware\Authenticate::class, \App\Http\Middleware\WriterMiddleware::class]);
    }

    public function dashboard()
    {
        $writer = Auth::user();
        $writer->load('writerProfile');
        
        // Check if the writer is available
        $writer->available = ($writer->writerProfile && $writer->writerProfile->availability_status === 'available');
        
        // Get current assignments (active orders)
        $currentAssignments = Order::where('writer_id', $writer->id)
                                 ->whereIn('status', ['assigned', 'in_progress', 'revision'])
                                 ->latest()
                                 ->with('client')
                                 ->take(5)
                                 ->get();
        
        // Get available orders that match writer's expertise
        $writer_expertise = $writer->writerProfile->expertise_areas ?? [];
        $availableOrders = collect();
        
        if (!empty($writer_expertise)) {
            $availableOrders = Order::whereNull('writer_id')
                               ->where('status', 'pending')
                               ->with('client')
                               ->latest()
                               ->take(5)
                               ->get()
                               ->filter(function ($order) use ($writer_expertise) {
                                   return in_array($order->subject_area, $writer_expertise);
                               });
        }
        
        // Calculate writer statistics
        $stats = [
            'active_assignments' => Order::where('writer_id', $writer->id)
                                      ->whereIn('status', ['assigned', 'in_progress', 'revision'])
                                      ->count(),
            'completed_orders' => Order::where('writer_id', $writer->id)
                                     ->where('status', 'completed')
                                     ->count(),
            'satisfaction_rate' => 95, // Placeholder or calculate from reviews
            'monthly_earnings' => WriterPayment::where('writer_id', $writer->id)
                                             ->sum('amount'),
        ];
        
        return view('writer.dashboard', compact('writer', 'currentAssignments', 'availableOrders', 'stats'));
    }

    public function profile()
    {
        $writer = Auth::user();
        $writer->load('writerProfile');
        
        $subjects = [
            'essays' => 'Essays',
            'research_papers' => 'Research Papers',
            'dissertations' => 'Dissertations',
            'technical_writing' => 'Technical Writing',
            'creative_writing' => 'Creative Writing'
        ];
        
        $writerSubjects = $writer->writerProfile->expertise_areas ?? [];
        
        // Initialize empty documents array for file upload component
        $documents = [];
        
        return view('writer.profile', compact('writer', 'subjects', 'writerSubjects', 'documents'));
    }

    public function updateProfile(Request $request)
    {
        $writer = Auth::user();
        $writer->load('writerProfile');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($writer->id)],
            'phone' => 'nullable|string|max:15',
            'bio' => 'required|string',
            'education_level' => 'required|string|in:Bachelor,Master,PhD',
            'hourly_rate' => 'required|numeric|min:5|max:100',
            'expertise_areas' => 'required|array|min:1',
            'availability_status' => 'required|in:available,busy',
        ]);

        // Update user info
        $writer->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $writer->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Update writer profile
        $writer->writerProfile->update([
            'bio' => $request->bio,
            'education_level' => $request->education_level,
            'expertise_areas' => $request->expertise_areas,
            'hourly_rate' => $request->hourly_rate,
            'availability_status' => $request->availability_status,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function orders()
    {
        $writer = Auth::user();
        
        $active_orders = Order::where('writer_id', $writer->id)
                             ->whereIn('status', ['assigned', 'in_progress', 'revision'])
                             ->latest()
                             ->with('client')
                             ->paginate(10, ['*'], 'active_page');
        
        $completed_orders = Order::where('writer_id', $writer->id)
                                ->where('status', 'completed')
                                ->latest()
                                ->with('client')
                                ->paginate(10, ['*'], 'completed_page');
        
        return view('writer.orders', compact('active_orders', 'completed_orders'));
    }

    public function availableOrders()
    {
        $writer = Auth::user();
        $writer->load('writerProfile');
        
        // Get all available orders without filtering by expertise
        $available_orders = Order::whereNull('writer_id')
                               ->where('status', 'pending')
                               ->with('client')
                               ->latest()
                               ->paginate(15);
        
        return view('writer.available_orders', compact('available_orders'));
    }

    /**
     * View details of a specific order before claiming
     */
    public function viewOrder(Order $order)
    {
        // Check if this order is available
        $writer = Auth::user();
        $writer->load('writerProfile');
        
        // Security check: only show pending, unassigned orders
        if ($order->writer_id !== null || $order->status !== 'pending') {
            return redirect()->route('writer.available-orders')
                           ->with('error', 'This order is not available for claiming.');
        }
        
        $order->load(['client', 'files']);
        
        return view('writer.view_order', compact('order'));
    }
    
    public function claimOrder(Order $order)
    {
        return app(AssignmentController::class)->claimOrder($order);
    }

    public function earnings()
    {
        $writer = Auth::user();
        
        $processed_payments = WriterPayment::where('writer_id', $writer->id)
                                         ->where('status', 'processed')
                                         ->with('order')
                                         ->latest()
                                         ->paginate(10, ['*'], 'processed_page');
        
        $pending_payments = WriterPayment::where('writer_id', $writer->id)
                                       ->where('status', 'pending')
                                       ->with('order')
                                       ->latest()
                                       ->paginate(10, ['*'], 'pending_page');
        
        $total_earnings = WriterPayment::where('writer_id', $writer->id)
                                      ->where('status', 'processed')
                                      ->sum('amount');
        
        $pending_amount = WriterPayment::where('writer_id', $writer->id)
                                      ->where('status', 'pending')
                                      ->sum('amount');
        
        return view('writer.earnings', compact(
            'processed_payments', 
            'pending_payments', 
            'total_earnings', 
            'pending_amount'
        ));
    }

    /**
     * Toggle the writer's availability status between available and busy
     */
    public function toggleAvailability(Request $request)
    {
        $writer = Auth::user();
        $writer->load('writerProfile');
        
        if (!$writer->writerProfile) {
            return redirect()->route('writer.profile')
                           ->with('error', 'Please complete your profile first.');
        }
        
        // Toggle the availability status
        $currentStatus = $writer->writerProfile->availability_status;
        $newStatus = ($currentStatus === 'available') ? 'busy' : 'available';
        
        // Use DB facade for direct query with quoted string values
        DB::table('writer_profiles')
            ->where('id', $writer->writerProfile->id)
            ->update([
                'availability_status' => $newStatus,
                'updated_at' => now()
            ]);
        
        return back()->with('success', 'Your availability status has been updated to ' . $newStatus . '.');
    }
} 