<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\WriterProfile;
use App\Models\WriterApplication;
use Illuminate\Support\Facades\Hash;

class WriterManagementController extends Controller
{
    public function index()
    {
        $writers = User::where('role', 'writer')->with('writerProfile')->paginate(10);
        return view('admin.writers.index', compact('writers'));
    }

    public function create()
    {
        return view('admin.writers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            // Add other validation rules as needed
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role' => 'writer',
            'status' => 'active'
        ]);

        // Create writer profile
        $user->writerProfile()->create([
            'expertise_areas' => $request->expertise_areas ?? [],
            'bio' => $request->bio ?? null,
            'availability_status' => 'available',
            'hourly_rate' => $request->hourly_rate ?? 10,
            'rating' => 0
        ]);

        return redirect()->route('admin.writers.index')->with('success', 'Writer created successfully.');
    }

    public function show(User $writer_user)
    {
        if ($writer_user->role !== 'writer') {
            return redirect()->route('admin.writers.index')
                           ->with('error', 'User is not a writer.');
        }
        
        $writer_user->load('writerProfile');
        
        // Calculate statistics
        $stats = [
            'total_orders' => $writer_user->writtenOrders()->count(),
            'completed_orders' => $writer_user->writtenOrders()->where('status', 'completed')->count(),
            'active_orders' => $writer_user->writtenOrders()->whereIn('status', ['in_progress', 'assigned', 'revision'])->count(),
            'total_earnings' => $writer_user->writerPayments()->where('status', 'processed')->sum('amount'),
            'pending_earnings' => $writer_user->writerPayments()->where('status', 'pending')->sum('amount'),
            'average_rating' => $writer_user->writerProfile->rating ?? 0
        ];
        
        // Rename the variable for the view
        $writer = $writer_user;
        
        return view('admin.writers.show', compact('writer', 'stats'));
    }

    public function edit(User $writer_user)
    {
        if ($writer_user->role !== 'writer') {
            return redirect()->route('admin.writers.index')
                           ->with('error', 'User is not a writer.');
        }

        $writer_user->load('writerProfile');
        // Rename the variable for the view
        $writer = $writer_user;
        
        return view('admin.writers.edit', compact('writer'));
    }

    public function update(Request $request, User $writer_user)
    {
        if ($writer_user->role !== 'writer') {
            return redirect()->route('admin.writers.index')
                           ->with('error', 'User is not a writer.');
        }

        $writer_user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($writer_user->writerProfile) {
            $writer_user->writerProfile->update([
                'expertise_areas' => $request->expertise_areas ?? $writer_user->writerProfile->expertise_areas,
                'bio' => $request->bio ?? $writer_user->writerProfile->bio,
                'hourly_rate' => $request->hourly_rate,
            ]);
        }

        return redirect()->route('admin.writers.show', $writer_user)
                       ->with('success', 'Writer updated successfully.');
    }

    public function toggleStatus(User $writer_user)
    {
        if ($writer_user->role !== 'writer') {
            return redirect()->route('admin.writers.index')
                           ->with('error', 'User is not a writer.');
        }

        $writer_user->status = $writer_user->status === 'active' ? 'inactive' : 'active';
        $writer_user->save();

        $status_text = $writer_user->status === 'active' ? 'activated' : 'deactivated';
        return back()->with('success', "Writer account has been {$status_text}.");
    }

    public function destroy(User $writer_user)
    {
        if ($writer_user->role !== 'writer') {
            return redirect()->route('admin.writers.index')
                ->with('error', 'User is not a writer.');
        }
        
        // Check if writer has any active orders
        $activeOrders = $writer_user->writtenOrders()->whereNotIn('status', ['completed', 'cancelled'])->count();
        if ($activeOrders > 0) {
            return redirect()->route('admin.writers.index')
                ->with('error', 'Cannot delete writer with active orders.');
        }
        
        // Delete writer profile first (due to foreign key constraint)
        if ($writer_user->writerProfile) {
            $writer_user->writerProfile->delete();
        }
        
        // Delete the writer
        $writer_user->delete();
        
        return redirect()->route('admin.writers.index')
            ->with('success', 'Writer deleted successfully.');
    }

    // Writer application methods
    public function applications(Request $request)
    {
        $query = WriterApplication::query();
        
        // Filter by status if provided
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        $applications = $query->latest()->paginate(15);
        $status_options = ['all', 'pending', 'approved', 'rejected'];
        
        return view('admin.writers.applications', compact('applications', 'status_options'));
    }

    public function viewApplication(WriterApplication $application)
    {
        return view('admin.writers.application_view', compact('application'));
    }

    public function approveApplication(Request $request, WriterApplication $application)
    {
        // Only pending applications can be approved
        if ($application->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be approved.');
        }
        
        // Create user account
        $user = User::create([
            'name' => $application->name,
            'email' => $application->email,
            'password' => Hash::make(\Illuminate\Support\Str::random(10)),
            'role' => 'writer',
            'status' => 'active',
        ]);
        
        // Create writer profile
        WriterProfile::create([
            'user_id' => $user->id,
            'education_level' => $application->education_level,
            'bio' => $application->cover_letter,
            'expertise_areas' => json_decode($application->specialization_areas),
            'hourly_rate' => $request->hourly_rate ?? 10, // Default or from request
            'rating' => 0,
            'availability_status' => 'available',
        ]);
        
        // Update application status
        $application->update([
            'status' => 'approved',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
            'user_id' => $user->id,
        ]);
        
        return redirect()->route('admin.applications.index')
            ->with('success', 'Application approved and writer account created.');
    }

    public function rejectApplication(Request $request, WriterApplication $application)
    {
        // Only pending applications can be rejected
        if ($application->status !== 'pending') {
            return back()->with('error', 'Only pending applications can be rejected.');
        }
        
        // Validate rejection reason
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);
        
        // Update application status
        $application->update([
            'status' => 'rejected',
            'processed_at' => now(),
            'processed_by' => auth()->id(),
            'rejection_reason' => $request->rejection_reason,
        ]);
        
        return redirect()->route('admin.applications.index')
            ->with('success', 'Application rejected successfully.');
    }

    public function changePassword(Request $request, User $writer_user)
    {
        if ($writer_user->role !== 'writer') {
            return redirect()->route('admin.writers.index')
                           ->with('error', 'User is not a writer.');
        }

        $validated = $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $writer_user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Writer password has been updated successfully.');
    }
} 