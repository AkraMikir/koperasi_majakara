<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GadaiActive;
use App\Models\GadaiMasterItem;
use App\Models\GadaiMasterKategori;
use App\Models\Nasabah;
use App\Models\JnsLokasiPerusahaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GadaiTestSeeder extends Seeder
{
    public function run()
    {
        $nasabahId = Nasabah::first()?->id ?? 1;
        $kategoriId = GadaiMasterKategori::first()?->id ?? 1;
        $itemId = GadaiMasterItem::first()?->id ?? 1;
        $lokasiId = JnsLokasiPerusahaan::first()?->id ?? 1;
        $adminId = User::where('role', 'admin_operasional')->first()?->id ?? 1;

        $now = Carbon::now();

        $data = [
            [
                'label' => 'Jatuh Tempo 1 Hari Lagi',
                'slot_kode' => 'EL-0101',
                'nominal' => 1000000,
                'jatuh_tempo' => $now->copy()->addDays(1)->endOfDay(),
                'tenggang' => $now->copy()->addDays(1)->addDays(15)->endOfDay(),
                'status' => 'active'
            ],
            [
                'label' => 'Jatuh Tempo 2 Hari Lagi',
                'slot_kode' => 'EL-0102',
                'nominal' => 2000000,
                'jatuh_tempo' => $now->copy()->addDays(2)->endOfDay(),
                'tenggang' => $now->copy()->addDays(2)->addDays(15)->endOfDay(),
                'status' => 'active'
            ],
            [
                'label' => 'Jatuh Tempo 3 Hari Lagi',
                'slot_kode' => 'EL-0103',
                'nominal' => 3000000,
                'jatuh_tempo' => $now->copy()->addDays(3)->endOfDay(),
                'tenggang' => $now->copy()->addDays(3)->addDays(15)->endOfDay(),
                'status' => 'active'
            ],
            [
                'label' => 'Jatuh Tempo 4 Hari Lagi',
                'slot_kode' => 'EL-0104',
                'nominal' => 4000000,
                'jatuh_tempo' => $now->copy()->addDays(4)->endOfDay(),
                'tenggang' => $now->copy()->addDays(4)->addDays(15)->endOfDay(),
                'status' => 'active'
            ],
            [
                'label' => 'Jatuh Tempo 5 Hari Lagi',
                'slot_kode' => 'EL-0105',
                'nominal' => 5000000,
                'jatuh_tempo' => $now->copy()->addDays(5)->endOfDay(),
                'tenggang' => $now->copy()->addDays(5)->addDays(15)->endOfDay(),
                'status' => 'active'
            ],
        ];

        foreach ($data as $d) {
            $gadai = GadaiActive::create([
                'nasabah_id' => $nasabahId,
                'kategori_id' => $kategoriId,
                'item_id' => $itemId,
                'lokasi_id' => $lokasiId,
                'slot_kode' => $d['slot_kode'],
                'slot_table' => 'electronic', // Defaulting to electronic for test
                'nominal_deal' => $d['nominal'],
                'biaya_jasa' => $d['nominal'] * 0.05,
                'tgl_mulai' => $now->copy()->subDays(30),
                'tgl_jatuh_tempo' => $d['jatuh_tempo'],
                'tgl_tenggang' => $d['tenggang'],
                'status' => $d['status'],
                'admin_id' => $adminId,
                'denda_aktif' => $d['status'] == 'grace_period' ? ($d['nominal'] * 0.02) : 0,
                'biaya_inap' => 0
            ]);

            // Update slot to occupied
            DB::table('tbl_gadai_grid_electronic')
                ->where('kode_slot', $d['slot_kode'])
                ->update([
                    'is_occupied' => true,
                    'active_gadai_id' => $gadai->id
                ]);
        }
    }
}
