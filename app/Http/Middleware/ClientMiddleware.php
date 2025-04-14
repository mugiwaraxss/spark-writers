<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ClientMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Debug info
        Log::info('ClientMiddleware processing', [
            'user' => Auth::check() ? Auth::user()->toArray() : 'Not authenticated',
            'path' => $request->path(),
            'method' => $request->method()
        ]);
        
        if (!Auth::check() || Auth::user()->role !== 'client') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized. Client access required.'], 403);
            }
            
            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this area.');
        }
        
        // Check if client is active
        if (Auth::user()->status !== 'active') {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Your account is inactive. Please contact an administrator.'], 403);
            }
            
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            return redirect()->route('login')->with('error', 'Your account is inactive. Please contact an administrator.');
        }
        
        // Check if client profile exists and create one if not
        if (!Auth::user()->clientProfile) {
            Auth::user()->clientProfile()->create([
                'institution' => '',
                'study_level' => 'undergraduate',
            ]);
            
            Log::info('Created client profile for user', [
                'user_id' => Auth::user()->id
            ]);
        }
        
        try {
            return $next($request);
        } catch (\Exception $e) {
            Log::error('Error in client middleware', [
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->view('errors.500', [
                'message' => 'Application error: ' . $e->getMessage()
            ], 500);
        }
    }
} 