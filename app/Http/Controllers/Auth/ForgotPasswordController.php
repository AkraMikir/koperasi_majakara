<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Tampilkan form untuk input nomor WhatsApp lupa password
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim OTP ke WhatsApp nasabah berdasarkan nomor WhatsApp yang diinput
     */
    public function sendOtp(Request $request)
    {
        $request->validate([
            'whatsapp_number' => 'required|string',
        ], [
            'whatsapp_number.required' => 'Nomor WhatsApp harus diisi.',
        ]);

        try {
            // Normalisasi nomor HP (e.g. 628123456789 -> 08123456789)
            $nomorHpInput = $request->whatsapp_number;
            $digits = preg_replace('/[^0-9]/', '', $nomorHpInput);
            if (str_starts_with($digits, '62') && strlen($digits) > 10) {
                $digits = '0' . substr($digits, 2);
            }

            // Cari user berdasarkan nomor HP yang sudah dinormalisasi
            $user = User::where('nomor_hp', $digits)->first();

            if (!$user) {
                return redirect()->back()
                    ->with('error', 'Nomor WhatsApp tidak terdaftar dalam sistem.')
                    ->withInput();
            }

            // Generate session ID unik untuk reset password guest
            $sessionId = 'pwd-reset-guest-' . $user->id . '-' . Str::random(10);

            // Simpan detail reset password di session
            session([
                'forgot_password_user_id' => $user->id,
                'forgot_password_phone' => $user->nomor_hp,
                'forgot_password_session_id' => $sessionId,
            ]);

            // Kirim OTP via OtpService — kirim ke email user
            $result = $this->otpService->generateAndSend(
                $user->nomor_hp,
                $sessionId,
                $user->id,
                'password_reset',
                $user->email
            );

            if ($result['success']) {
                Log::info('Forgot password OTP sent to guest user via Email', [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                ]);

                // Masking email untuk ditampilkan
                $maskedEmail = preg_replace('/(?<=.{2}).(?=[^@]*@)/', '*', $user->email);

                // Redirect ke halaman verifikasi OTP
                return redirect()->route('password.verify')
                    ->with('success', 'Kode OTP telah dikirim ke email Anda (' . $maskedEmail . '). Silakan cek kotak masuk.');
            } else {
                return redirect()->back()
                    ->with('error', $result['message'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Error in ForgotPasswordController@sendOtp', [
                'whatsapp_number' => $request->whatsapp_number,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memproses permintaan. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Tampilkan halaman verifikasi OTP & input password baru
     */
    public function showVerifyForm()
    {
        $userId = session('forgot_password_user_id');
        $phone = session('forgot_password_phone');
        $sessionId = session('forgot_password_session_id');

        if (!$userId || !$phone || !$sessionId) {
            return redirect()->route('password.request')
                ->with('error', 'Session Anda telah berakhir. Silakan masukkan nomor WhatsApp Anda kembali.');
        }

        return view('auth.verify-otp-forgot-password', [
            'phone' => $phone,
        ]);
    }

    /**
     * Verifikasi OTP dan update password pengguna
     */
    public function verifyOtpAndResetPassword(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
            'password_baru' => 'required|string|min:8|confirmed',
            'password_baru_confirmation' => 'required|string|min:8',
        ], [
            'otp_code.required' => 'Kode OTP harus diisi.',
            'otp_code.size' => 'Kode OTP harus 6 digit.',
            'password_baru.required' => 'Password baru harus diisi.',
            'password_baru.min' => 'Password baru minimal 8 karakter.',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok.',
            'password_baru_confirmation.required' => 'Konfirmasi password harus diisi.',
        ]);

        $userId = session('forgot_password_user_id');
        $phone = session('forgot_password_phone');
        $sessionId = session('forgot_password_session_id');

        if (!$userId || !$phone || !$sessionId) {
            return redirect()->route('password.request')
                ->with('error', 'Session Anda telah berakhir. Silakan masukkan nomor WhatsApp Anda kembali.');
        }

        try {
            $user = User::find($userId);
            if (!$user) {
                return redirect()->route('password.request')
                    ->with('error', 'Pengguna tidak ditemukan.');
            }

            // Verifikasi OTP
            $verifyResult = $this->otpService->verify(
                $request->otp_code,
                $phone,
                $sessionId
            );

            if (!$verifyResult['success']) {
                return redirect()->back()
                    ->with('error', $verifyResult['message'])
                    ->withInput();
            }

            // Update password user
            $user->update([
                'password' => Hash::make($request->password_baru),
            ]);

            // Bersihkan session data reset password
            session()->forget([
                'forgot_password_user_id',
                'forgot_password_phone',
                'forgot_password_session_id',
            ]);

            Log::info('User reset password successfully via guest OTP using WhatsApp number', [
                'user_id' => $user->id,
                'phone' => $user->nomor_hp,
            ]);

            return redirect()->route('login')
                ->with('success', 'Password berhasil direset! Silakan login menggunakan password baru Anda.');

        } catch (\Exception $e) {
            Log::error('Error in ForgotPasswordController@verifyOtpAndResetPassword', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mereset password. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Kirim ulang OTP
     */
    public function resendOtp(Request $request)
    {
        $userId = session('forgot_password_user_id');
        $phone = session('forgot_password_phone');
        $sessionId = session('forgot_password_session_id');

        if (!$userId || !$phone || !$sessionId) {
            return response()->json([
                'success' => false,
                'message' => 'Session expired. Silakan masukkan nomor HP Anda kembali.'
            ]);
        }

        try {
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengguna tidak ditemukan.'
                ]);
            }

            // Generate session ID baru
            $newSessionId = 'pwd-reset-guest-' . $user->id . '-' . Str::random(10);
            session(['forgot_password_session_id' => $newSessionId]);

            // Panggil service resend — kirim ke email user
            $result = $this->otpService->resend(
                $phone,
                $newSessionId,
                $user->id,
                'password_reset',
                $user->email
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP baru telah dikirim ke email Anda.'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error in ForgotPasswordController@resendOtp', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengirim ulang OTP.'
            ]);
        }
    }

    /**
     * Sisa waktu cooldown kirim OTP
     */
    public function getOtpCooldown(Request $request)
    {
        $phone = session('forgot_password_phone');

        if (!$phone) {
            return response()->json([
                'remaining' => 0,
                'canRequest' => true
            ]);
        }

        $remaining = $this->otpService->getRemainingCooldown($phone);

        return response()->json([
            'remaining' => $remaining,
            'canRequest' => $remaining <= 0
        ]);
    }
}
