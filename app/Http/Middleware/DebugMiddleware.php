<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class DebugMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Log request details (without session data if not available)
        $logData = [
            'method' => $request->method(),
            'url' => $request->fullUrl(),
            'path' => $request->path(),
            'route' => $request->route() ? $request->route()->getName() : 'No route',
            'user' => auth()->check() ? auth()->user()->toArray() : 'Not authenticated',
            'middleware' => $request->route() ? $request->route()->middleware() : 'No middleware',
        ];
        
        // Only add session data if session is available
        if ($request->hasSession() && $request->session()->isStarted()) {
            $logData['session'] = $request->session()->all();
        } else {
            $logData['session'] = 'Session not available';
        }
        
        Log::info('DEBUG Request', $logData);
        
        try {
            $response = $next($request);
            
            // Log response details
            Log::info('DEBUG Response', [
                'status' => $response->getStatusCode(),
                'content_type' => $response->headers->get('Content-Type'),
                'redirectTo' => method_exists($response, 'getTargetUrl') ? $response->getTargetUrl() : 'Not a redirect',
            ]);
            
            return $response;
        } catch (\Exception $e) {
            // Log the error
            Log::error('DEBUG Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'previous' => $e->getPrevious() ? $e->getPrevious()->getMessage() : 'No previous exception',
            ]);
            
            throw $e;
        }
    }
}
