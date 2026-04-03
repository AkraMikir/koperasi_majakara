<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Models\JanjiTemuUniversal;
use App\Models\PettyCashSetoranKantor;
use App\Models\PettyCashPenerimaan;
use App\Models\PengajuanPerubahanData;

class SidebarComposer
{
    public function compose(View $view)
    {
        $userId = auth()->id() ?? 0;
        $userRole = auth()->user()?->role ?? 'guest';
        $janjiTemuSeenAt = session('admin_janji_temu_seen_at', '1970-01-01 00:00:00');

        // Cache 5 menit untuk sidebar stats
        $stats = Cache::remember('sidebar_stats_' . $userId, 300, function () use ($userId, $userRole, $janjiTemuSeenAt) {
            
            // 1. Janji Temu
            $janjiTemu = 0;
            try {
                $janjiTemu = JanjiTemuUniversal::where('status', '1')
                    ->where('created_at', '>', $janjiTemuSeenAt)
                    ->count();
            } catch (\Exception $e) {}

            // 2. Petty Cash
            $pettyPending = 0;
            try {
                if ($userRole === 'admin_utama') {
                    $pettyPending = PettyCashSetoranKantor::where('status', 'pending')->count();
                } elseif ($userRole === 'admin_operasional') {
                    $pettyPending = PettyCashPenerimaan::where('admin_id', $userId)->where('status', 'pending')->count();
                }
            } catch (\Exception $e) {}

            // 3. Perubahan Data
            $pengajuanPerubahan = 0;
            try {
                $pengajuanPerubahan = PengajuanPerubahanData::where('status', 'pending')->count();
            } catch (\Exception $e) {}

            return [
                'janjiTemuCount' => $janjiTemu,
                'pettyCashCount' => $pettyPending,
                'perubahanDataCount' => $pengajuanPerubahan,
            ];
        });

        $view->with('sidebarStats', $stats);
    }
}
