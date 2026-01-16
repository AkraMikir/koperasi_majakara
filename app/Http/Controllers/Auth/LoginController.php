<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Handle login request (Step 1: Email & Password).
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('login')
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        // Verify credentials without logging in first
        if (Auth::validate($credentials)) {
            // Get user without logging in
            $user = \App\Models\User::where('email', $credentials['email'])->first();

            if (!$user) {
                return back()->withErrors([
                    'email' => 'Email atau password yang Anda masukkan salah.',
                ])->withInput();
            }

            // Check if user has PIN
            // If PIN exists, require PIN verification before login
            if ($user->pin !== null && $user->pin !== '') {
                // Store user ID and remember flag in session for PIN verification
                $request->session()->put('login_user_id', $user->id);
                $request->session()->put('login_remember', $remember);
                
                // Return JSON response for AJAX to show PIN modal
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'requires_pin' => true,
                        'message' => 'Silakan masukkan PIN Anda'
                    ]);
                }
                
                // For non-AJAX, redirect back with flag to show PIN modal
                return redirect()->route('login')
                    ->with('requires_pin', true)
                    ->with('user_id', $user->id);
            }

            // No PIN required, login and redirect directly
            Auth::login($user, $remember);
            $request->session()->regenerate();
            return $this->redirectAfterLogin($user);
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->withInput();
    }

    /**
     * Handle PIN verification (Step 2: PIN Verification).
     */
    public function verifyPin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pin' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PIN harus 6 digit',
                    'errors' => $validator->errors()
                ], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        // Get user ID from session
        $userId = $request->session()->get('login_user_id');
        if (!$userId) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expired. Silakan login kembali.'
                ], 401);
            }
            return redirect()->route('login')
                ->with('error', 'Session expired. Silakan login kembali.');
        }

        // Get user
        $user = \App\Models\User::find($userId);
        if (!$user) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User tidak ditemukan'
                ], 404);
            }
            return redirect()->route('login')
                ->with('error', 'User tidak ditemukan.');
        }

        // Verify PIN
        // PIN stored as integer in database, but input is string
        // Convert input PIN to integer for comparison
        $inputPin = (int) str_replace(['.', ','], '', $request->pin);
        $userPin = (int) $user->pin;

        if ($inputPin !== $userPin) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'PIN yang Anda masukkan salah.'
                ], 422);
            }
            return back()->withErrors([
                'pin' => 'PIN yang Anda masukkan salah.'
            ])->withInput();
        }

        // PIN verified, login user and clear session
        $remember = $request->session()->get('login_remember', false);
        Auth::login($user, $remember);
        
        $request->session()->forget('login_user_id');
        $request->session()->forget('login_remember');
        $request->session()->regenerate();

        // Redirect based on role
        if ($request->expectsJson() || $request->ajax()) {
            $redirectUrl = $this->getRedirectUrl($user);
            return response()->json([
                'success' => true,
                'message' => 'Login berhasil!',
                'redirect_url' => $redirectUrl
            ]);
        }

        return $this->redirectAfterLogin($user);
    }

    /**
     * Redirect user after successful login.
     */
    private function redirectAfterLogin($user)
    {
        if ($user->role === 'nasabah') {
            return redirect()->intended(route('nasabah.dashboard'))
                ->with('success', 'Selamat datang, ' . $user->nama . '!');
        } elseif ($user->role === 'admin_operasional' || $user->role === 'admin_utama') {
            return redirect()->intended('/admin/dashboard')
                ->with('success', 'Selamat datang, ' . $user->nama . '!');
        }

        return redirect()->intended('/')
            ->with('success', 'Selamat datang!');
    }

    /**
     * Get redirect URL based on user role.
     */
    private function getRedirectUrl($user)
    {
        if ($user->role === 'nasabah') {
            return route('nasabah.dashboard');
        } elseif ($user->role === 'admin_operasional' || $user->role === 'admin_utama') {
            return '/admin/dashboard';
        }
        return '/';
    }

    /**
     * Handle logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

