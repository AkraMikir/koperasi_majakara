<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PinController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    /**
     * Update PIN dengan verifikasi PIN lama
     * 
     * Route: POST /nasabah/pin/update
     */
    public function updatePin(Request $request)
    {
        $user = auth()->user();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'pin_lama' => 'required|string|size:6',
            'pin_baru' => 'required|string|size:6|confirmed',
            'pin_baru_confirmation' => 'required|string|size:6',
        ], [
            'pin_lama.required' => 'PIN lama harus diisi',
            'pin_lama.size' => 'PIN lama harus 6 digit',
            'pin_baru.required' => 'PIN baru harus diisi',
            'pin_baru.size' => 'PIN baru harus 6 digit',
            'pin_baru.confirmed' => 'Konfirmasi PIN baru tidak cocok',
            'pin_baru_confirmation.required' => 'Konfirmasi PIN baru harus diisi',
            'pin_baru_confirmation.size' => 'Konfirmasi PIN baru harus 6 digit',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Terdapat kesalahan pada input Anda.')
                ->withInput();
        }

        try {
            // Cek apakah PIN lama benar
            $pinLamaInput = (int) $request->pin_lama;
            $pinLamaDb = (int) $user->pin;

            if ($pinLamaInput !== $pinLamaDb) {
                Log::warning('PIN lama salah', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]);

                return redirect()->back()
                    ->with('error', 'PIN lama yang Anda masukkan salah.')
                    ->withInput();
            }

            // Cek apakah PIN baru sama dengan PIN lama
            $pinBaruInput = (int) $request->pin_baru;
            if ($pinBaruInput === $pinLamaDb) {
                return redirect()->back()
                    ->with('error', 'PIN baru tidak boleh sama dengan PIN lama.')
                    ->withInput();
            }

            // Update PIN
            $user->update(['pin' => $pinBaruInput]);

            Log::info('PIN berhasil diupdate', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return redirect()->back()
                ->with('success', 'PIN berhasil diubah! Gunakan PIN baru Anda untuk transaksi selanjutnya.');

        } catch (\Exception $e) {
            Log::error('Error update PIN', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengubah PIN. Silakan coba lagi.')
                ->withInput();
        }
    }

    /**
     * Send OTP untuk Lupa PIN
     * 
     * Route: POST /nasabah/pin/send-otp-lupa
     */
    public function sendOtpLupaPin(Request $request)
    {
        $user = auth()->user();

        try {
            // Generate session ID untuk tracking
            $sessionId = 'lupa-pin-' . $user->id . '-' . Str::uuid()->toString();
            $request->session()->put('lupa_pin_session_id', $sessionId);

            // Send OTP
            $otpResult = $this->otpService->generateAndSend(
                $user->nomor_hp,
                $sessionId,
                $user->id,
                'pin' // Type: pin reset
            );

            if ($otpResult['success']) {
                Log::info('OTP Lupa PIN sent successfully', [
                    'user_id' => $user->id,
                    'phone' => $user->nomor_hp,
                ]);

                $request->session()->put('lupa_pin_otp_sent_at', now()->toDateTimeString());

                return redirect()->back()
                    ->with('success', 'Kode OTP telah dikirim ke WhatsApp nomor ' . $user->nomor_hp . '. Silakan cek pesan masuk Anda.')
                    ->with('lupa_pin_otp_sent', true);
            } else {
                Log::error('Failed to send OTP Lupa PIN', [
                    'user_id' => $user->id,
                    'phone' => $user->nomor_hp,
                    'error' => $otpResult['message'],
                ]);

                return redirect()->back()
                    ->with('error', 'Gagal mengirim OTP: ' . $otpResult['message']);
            }

        } catch (\Exception $e) {
            Log::error('Error send OTP Lupa PIN', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim OTP. Silakan coba lagi.');
        }
    }

    /**
     * Resend OTP untuk Lupa PIN
     * 
     * Route: POST /nasabah/pin/resend-otp-lupa
     */
    public function resendOtpLupaPin(Request $request)
    {
        $user = auth()->user();
        $sessionId = $request->session()->get('lupa_pin_session_id');

        if (!$sessionId) {
            return redirect()->back()
                ->with('error', 'Session tidak valid. Silakan mulai dari awal.');
        }

        try {
            // Resend OTP
            $otpResult = $this->otpService->resend(
                $user->nomor_hp,
                $sessionId,
                $user->id,
                'pin'
            );

            if ($otpResult['success']) {
                Log::info('OTP Lupa PIN resent successfully', [
                    'user_id' => $user->id,
                    'phone' => $user->nomor_hp,
                ]);

                $request->session()->put('lupa_pin_otp_sent_at', now()->toDateTimeString());

                return redirect()->back()
                    ->with('success', 'Kode OTP baru telah dikirim ke WhatsApp Anda.')
                    ->with('lupa_pin_otp_sent', true);
            } else {
                return redirect()->back()
                    ->with('error', 'Gagal mengirim OTP: ' . $otpResult['message']);
            }

        } catch (\Exception $e) {
            Log::error('Error resend OTP Lupa PIN', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengirim ulang OTP. Silakan coba lagi.');
        }
    }

    /**
     * Verify OTP dan Update PIN (Lupa PIN)
     * 
     * Route: POST /nasabah/pin/verify-otp-lupa
     */
    public function verifyOtpLupaPin(Request $request)
    {
        $user = auth()->user();
        $sessionId = $request->session()->get('lupa_pin_session_id');

        if (!$sessionId) {
            return redirect()->back()
                ->with('error', 'Session tidak valid. Silakan mulai dari awal.');
        }

        // Validasi input
        $validator = Validator::make($request->all(), [
            'otp_code' => 'required|string|size:6',
            'pin_baru' => 'required|string|size:6|confirmed',
            'pin_baru_confirmation' => 'required|string|size:6',
        ], [
            'otp_code.required' => 'Kode OTP harus diisi',
            'otp_code.size' => 'Kode OTP harus 6 digit',
            'pin_baru.required' => 'PIN baru harus diisi',
            'pin_baru.size' => 'PIN baru harus 6 digit',
            'pin_baru.confirmed' => 'Konfirmasi PIN baru tidak cocok',
            'pin_baru_confirmation.required' => 'Konfirmasi PIN baru harus diisi',
            'pin_baru_confirmation.size' => 'Konfirmasi PIN baru harus 6 digit',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->with('error', 'Terdapat kesalahan pada input Anda.')
                ->with('lupa_pin_otp_sent', true) // Keep modal open
                ->withInput();
        }

        try {
            // Verify OTP
            $verifyResult = $this->otpService->verify(
                $request->otp_code,
                $user->nomor_hp,
                $sessionId
            );

            if (!$verifyResult['success']) {
                Log::warning('OTP verification failed for Lupa PIN', [
                    'user_id' => $user->id,
                    'phone' => $user->nomor_hp,
                    'message' => $verifyResult['message'],
                ]);

                return redirect()->back()
                    ->with('error', $verifyResult['message'])
                    ->with('lupa_pin_otp_sent', true) // Keep modal open
                    ->withInput();
            }

            // OTP verified successfully - Update PIN
            $pinBaru = (int) $request->pin_baru;
            $user->update(['pin' => $pinBaru]);

            // Clear session
            $request->session()->forget(['lupa_pin_session_id', 'lupa_pin_otp_sent_at']);

            Log::info('PIN berhasil direset via Lupa PIN', [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);

            return redirect()->back()
                ->with('success', 'PIN berhasil diubah! Gunakan PIN baru Anda untuk transaksi selanjutnya.');

        } catch (\Exception $e) {
            Log::error('Error verify OTP Lupa PIN', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memverifikasi OTP. Silakan coba lagi.')
                ->with('lupa_pin_otp_sent', true) // Keep modal open
                ->withInput();
        }
    }

    /**
     * Get remaining cooldown for OTP resend
     * 
     * Route: GET /nasabah/pin/get-cooldown
     */
    public function getCooldown(Request $request)
    {
        $user = auth()->user();
        
        try {
            $remainingCooldown = $this->otpService->getRemainingCooldown($user->nomor_hp);
            
            return response()->json([
                'success' => true,
                'remaining_cooldown' => $remainingCooldown,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting cooldown',
            ], 500);
        }
    }
}
