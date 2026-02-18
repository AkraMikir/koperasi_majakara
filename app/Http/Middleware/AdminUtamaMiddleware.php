<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminUtamaMiddleware
{
    /**
     * Handle an incoming request.
     * Restrict access only for admin_utama role.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu.');
        }

        // Check if user is admin_utama
        $user = auth()->user();
        if ($user->role !== 'admin_utama') {
            // If it's an AJAX request, return JSON error
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat melakukan aksi ini.'
                ], 403);
            }

            // For web requests, abort with 403
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat melakukan aksi ini.');
        }

        return $next($request);
    }
}
