<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiService;
use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Log;

class FonnteWebhookController extends Controller
{
    protected $aiService;
    protected $waService;

    public function __construct(AiService $aiService, WhatsAppService $waService)
    {
        $this->aiService = $aiService;
        $this->waService = $waService;
    }

    /**
     * Handle incoming Fonnte webhook
     */
    public function handle(Request $request)
    {
        try {
            Log::info('Received Fonnte Webhook', $request->all());

            $sender = $request->input('sender'); // Nomor pengirim (misal: 62812...)
            $message = $request->input('message'); // Teks pesan yang dikirim

            // Pastikan sender dan message ada
            if (!$sender || !$message) {
                return response()->json(['status' => 'ignored', 'reason' => 'Missing sender or message']);
            }

            // Cegah Fonnte mengirim webhook berulang (retry) karena delay AI
            $timestamp = $request->input('timestamp');
            $cacheKey = 'webhook_fonnte_' . $sender . '_' . $timestamp;

            if (\Illuminate\Support\Facades\Cache::has($cacheKey)) {
                Log::info('Ignored duplicate webhook from Fonnte');
                return response()->json(['status' => 'ignored', 'reason' => 'Duplicate webhook']);
            }

            // Kunci webhook ini selama 5 menit agar tidak diproses ulang
            \Illuminate\Support\Facades\Cache::put($cacheKey, true, now()->addMinutes(5));

            // Jika ada pesan masuk, tanyakan ke AI
            $aiResponse = $this->aiService->askGemini($message);

            // Kirim balasan AI via Fonnte
            $this->waService->sendMessage($sender, $aiResponse);

            return response()->json(['status' => 'success', 'message' => 'Replied via AI']);
        } catch (\Exception $e) {
            Log::error('Fonnte Webhook Error', ['error' => $e->getMessage()]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
