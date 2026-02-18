<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Halaman utama setting (Security & Privacy)
     */
    public function index()
    {
        $user = auth()->user();
        
        return view('nasabah.setting.index', [
            'user' => $user,
        ]);
    }

    // ==========================================
    // PASSWORD MANAGEMENT
    // ==========================================

    /**
     * Method 1: Change Password (Ingat Password Lama)
     * Route: POST /nasabah/setting/change-password
     */
    public function changePassword(Request $request)
    {
        $user = auth()->user();

        // Validasi input
        $request->validate([
            'password_lama' => 'required|string',
            'password_baru' => 'required|string|min:8|confirmed',
            'password_baru_confirmation' => 'required|string|min:8',
        ], [
            'password_lama.required' => 'Password lama harus diisi',
            'password_baru.required' => 'Password baru harus diisi',
            'password_baru.min' => 'Password baru minimal 8 karakter',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok',
            'password_baru_confirmation.required' => 'Konfirmasi password harus diisi',
        ]);

        try {
            // Cek apakah password lama benar
            if (!Hash::check($request->password_lama, $user->password)) {
                Log::warning('Password lama salah', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);

                return redirect()->back()
                    ->with('error', 'Password lama yang Anda masukkan salah.')
                    ->withInput();
            }

            // Cek apakah password baru sama dengan password lama
            if (Hash::check($request->password_baru, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Password baru tidak boleh sama dengan password lama.')
                    ->withInput();
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->password_baru),
            ]);

            Log::info('Password berhasil diubah', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            app(ActivityLogService::class)->logUbahPassword();

            return redirect()->back()
                ->with('success', 'Password berhasil diubah! Gunakan password baru Anda untuk login selanjutnya.');

        } catch (\Exception $e) {
            Log::error('Error change password', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah password. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Method 2: Reset Password dengan OTP (Lupa Password)
     * Step 1: Kirim OTP ke nomor HP
     * Route: POST /nasabah/setting/send-otp-password-reset
     */
    public function sendOtpPasswordReset(Request $request)
    {
        $user = auth()->user();

        try {
            // Generate session ID untuk reset password
            $sessionId = 'pwd-reset-' . $user->id . '-' . Str::random(10);
            
            // Simpan session ID di session
            session(['password_reset_session' => $sessionId]);

            // Kirim OTP
            $result = $this->otpService->generateAndSend(
                $user->nomor_hp,
                $sessionId,
                $user->id,
                'password_reset'
            );

            if ($result['success']) {
                Log::info('OTP password reset sent', [
                    'user_id' => $user->id,
                    'phone' => $user->nomor_hp,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Kode OTP telah dikirim ke WhatsApp Anda (***' . substr($user->nomor_hp, -4) . ')',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending OTP for password reset', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.',
            ]);
        }
    }

    /**
     * Step 2: Verify OTP dan reset password
     * Route: POST /nasabah/setting/verify-otp-reset-password
     */
    public function verifyOtpAndResetPassword(Request $request)
    {
        $user = auth()->user();

        // Validasi input
        $request->validate([
            'otp_code' => 'required|string|size:6',
            'password_baru' => 'required|string|min:8|confirmed',
            'password_baru_confirmation' => 'required|string|min:8',
        ], [
            'otp_code.required' => 'Kode OTP harus diisi',
            'otp_code.size' => 'Kode OTP harus 6 digit',
            'password_baru.required' => 'Password baru harus diisi',
            'password_baru.min' => 'Password baru minimal 8 karakter',
            'password_baru.confirmed' => 'Konfirmasi password tidak cocok',
        ]);

        try {
            // Ambil session ID
            $sessionId = session('password_reset_session');
            
            if (!$sessionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Session expired. Silakan minta OTP baru.',
                ]);
            }

            // Verify OTP
            $verifyResult = $this->otpService->verify(
                $request->otp_code,
                $user->nomor_hp,
                $sessionId
            );

            if (!$verifyResult['success']) {
                return response()->json([
                    'success' => false,
                    'message' => $verifyResult['message'],
                ]);
            }

            // Cek apakah password baru sama dengan password lama
            if (Hash::check($request->password_baru, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password baru tidak boleh sama dengan password lama.',
                ]);
            }

            // Update password
            $user->update([
                'password' => Hash::make($request->password_baru),
            ]);

            // Clear session
            session()->forget('password_reset_session');

            Log::info('Password berhasil direset via OTP', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil direset! Gunakan password baru Anda untuk login selanjutnya.',
            ]);

        } catch (\Exception $e) {
            Log::error('Error verify OTP and reset password', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. Silakan coba lagi.',
            ]);
        }
    }

    // ==========================================
    // PIN MANAGEMENT
    // ==========================================

    /**
     * Change PIN (Ingat PIN Lama)
     * Route: POST /nasabah/setting/change-pin
     */
    public function changePin(Request $request)
    {
        $user = auth()->user();

        // Validasi input
        $request->validate([
            'pin_lama' => 'required|digits:6',
            'pin_baru' => 'required|digits:6|confirmed',
            'pin_baru_confirmation' => 'required|digits:6',
        ], [
            'pin_lama.required' => 'PIN lama harus diisi',
            'pin_lama.digits' => 'PIN lama harus 6 digit',
            'pin_baru.required' => 'PIN baru harus diisi',
            'pin_baru.digits' => 'PIN baru harus 6 digit',
            'pin_baru.confirmed' => 'Konfirmasi PIN baru tidak cocok',
            'pin_baru_confirmation.required' => 'Konfirmasi PIN baru harus diisi',
            'pin_baru_confirmation.digits' => 'Konfirmasi PIN baru harus 6 digit',
        ]);

        try {
            // Cek apakah user sudah punya PIN
            if (!$user->pin) {
                return redirect()->back()
                    ->with('error', 'Anda belum memiliki PIN. Silakan buat PIN terlebih dahulu.');
            }

            // Cek apakah PIN lama benar
            $pinLamaInput = (int) $request->pin_lama;
            $pinLamaDb = (int) $user->pin;

            if ($pinLamaInput !== $pinLamaDb) {
                Log::warning('PIN lama salah', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);

                return redirect()->back()
                    ->with('error', 'PIN lama yang Anda masukkan salah.');
            }

            // Cek apakah PIN baru sama dengan PIN lama
            $pinBaruInput = (int) $request->pin_baru;
            if ($pinBaruInput === $pinLamaDb) {
                return redirect()->back()
                    ->with('error', 'PIN baru tidak boleh sama dengan PIN lama.');
            }

            // Update PIN
            $user->update(['pin' => $pinBaruInput]);

            Log::info('PIN berhasil diubah', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            app(ActivityLogService::class)->logUbahPin();

            return redirect()->back()
                ->with('success_pin', 'PIN berhasil diubah! Gunakan PIN baru Anda untuk transaksi selanjutnya.');

        } catch (\Exception $e) {
            Log::error('Error change PIN', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah PIN. Silakan coba lagi.');
        }
    }

    /**
     * Get remaining cooldown untuk OTP
     * Route: GET /nasabah/setting/otp-cooldown
     */
    public function getOtpCooldown(Request $request)
    {
        $user = auth()->user();
        $remaining = $this->otpService->getRemainingCooldown($user->nomor_hp);

        return response()->json([
            'remaining' => $remaining,
            'canRequest' => $remaining <= 0,
        ]);
    }
}
