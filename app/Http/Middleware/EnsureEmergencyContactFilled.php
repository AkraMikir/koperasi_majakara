<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureEmergencyContactFilled
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
            if ($user->role === 'nasabah') {
                $nasabah = $user->nasabah;
                if (!$nasabah || !$nasabah->darurat) {
                    return redirect()->route('nasabah.profile', ['focus' => 'kontak-darurat'])
                        ->with('error', 'Silakan lengkapi kontak darurat terlebih dahulu untuk mengakses fitur pinjaman dan gadai.');
                }
            }
        }

        return $next($request);
    }
}
