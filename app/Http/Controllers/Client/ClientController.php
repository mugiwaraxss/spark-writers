<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    public function __construct()
    {
        // Middleware is now applied via routes
    }

    public function dashboard()
    {
        $client = Auth::user();
        $client->load('clientProfile');

        // Active orders
        $active_orders = Order::where('client_id', $client->id)
                             ->whereNotIn('status', ['completed'])
                             ->latest()
                             ->take(5)
                             ->get();

        // Recent completed orders
        $completed_orders = Order::where('client_id', $client->id)
                                ->where('status', 'completed')
                                ->latest()
                                ->take(3)
                                ->get();

        // Payment summary
        $payments = [
            'total_spent' => Payment::whereHas('order', function ($query) use ($client) {
                                $query->where('client_id', $client->id);
                            })
                            ->where('status', 'completed')
                            ->sum('amount'),
            'pending_payments' => Payment::whereHas('order', function ($query) use ($client) {
                                    $query->where('client_id', $client->id);
                                })
                                ->where('status', 'pending')
                                ->count(),
        ];

        // Recent notifications
        $notifications = $client->notifications()
                               ->latest()
                               ->take(5)
                               ->get();

        return view('client.dashboard', compact(
            'client', 
            'active_orders', 
            'completed_orders', 
            'payments', 
            'notifications'
        ));
    }

    public function profile()
    {
        $client = Auth::user();
        $client->load('clientProfile');
        
        return view('client.profile', compact('client'));
    }

    public function updateProfile(Request $request)
    {
        $client = Auth::user();
        $client->load('clientProfile');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($client->id)],
            'phone' => 'nullable|string|max:15',
            'institution' => 'required|string|max:255',
            'study_level' => 'required|string|max:255',
        ]);

        // Update user info
        $client->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);

        // Update password if provided
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            $client->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Update client profile
        $client->clientProfile->update([
            'institution' => $request->institution,
            'study_level' => $request->study_level,
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function orders()
    {
        $client = Auth::user();
        
        $active_orders = Order::where('client_id', $client->id)
                             ->whereNotIn('status', ['completed'])
                             ->latest()
                             ->paginate(10, ['*'], 'active_page');
        
        $completed_orders = Order::where('client_id', $client->id)
                                ->where('status', 'completed')
                                ->latest()
                                ->paginate(10, ['*'], 'completed_page');
        
        return view('client.orders', compact('active_orders', 'completed_orders'));
    }

    public function payments()
    {
        $client = Auth::user();
        
        $completed_payments = Payment::whereHas('order', function ($query) use ($client) {
                                    $query->where('client_id', $client->id);
                                })
                                ->where('status', 'completed')
                                ->with('order')
                                ->latest()
                                ->paginate(10, ['*'], 'completed_page');
        
        $pending_payments = Payment::whereHas('order', function ($query) use ($client) {
                                   $query->where('client_id', $client->id);
                                })
                                ->where('status', 'pending')
                                ->with('order')
                                ->latest()
                                ->paginate(10, ['*'], 'pending_page');
        
        $total_spent = Payment::whereHas('order', function ($query) use ($client) {
                             $query->where('client_id', $client->id);
                         })
                         ->where('status', 'completed')
                         ->sum('amount');
        
        return view('client.payments', compact(
            'completed_payments', 
            'pending_payments', 
            'total_spent'
        ));
    }

    public function notifications()
    {
        $client = Auth::user();
        
        $notifications = $client->notifications()
                               ->latest()
                               ->paginate(20);
        
        $unread_count = $client->notifications()
                              ->where('read_status', false)
                              ->count();
        
        // Don't automatically mark all as read when viewing the page
        // Let users do it manually
        
        return view('client.notifications', compact('notifications', 'unread_count'));
    }
    
    public function markNotificationAsRead(\App\Models\Notification $notification)
    {
        // Security check - ensure the notification belongs to this client
        if ($notification->user_id !== Auth::id()) {
            return back()->with('error', 'You do not have permission to update this notification.');
        }
        
        $notification->update(['read_status' => true]);
        
        return back()->with('success', 'Notification marked as read.');
    }
    
    public function markAllNotificationsAsRead()
    {
        $client = Auth::user();
        
        $client->notifications()
               ->where('read_status', false)
               ->update(['read_status' => true]);
               
        return back()->with('success', 'All notifications marked as read.');
    }
} 