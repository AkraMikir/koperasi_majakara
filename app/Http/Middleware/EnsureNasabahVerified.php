<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNasabahVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->role === 'nasabah' && is_null($user->verified)) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Akun Anda belum diverifikasi oleh admin. Harap tunggu verifikasi untuk menggunakan fitur ini.'
                    ], 403);
                }
                
                return redirect()->route('nasabah.dashboard')
                    ->with('error', 'Akun Anda belum diverifikasi oleh admin. Harap tunggu verifikasi untuk menggunakan fitur ini.');
            }
        }

        return $next($request);
    }
}
