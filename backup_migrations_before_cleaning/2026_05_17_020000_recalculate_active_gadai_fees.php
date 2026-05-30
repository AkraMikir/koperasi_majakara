<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\GadaiActive;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $activeGadais = GadaiActive::with(['kategori', 'item'])->get();
        foreach ($activeGadais as $gadai) {
            if (!$gadai->kategori || !$gadai->item) {
                continue;
            }

            // Calculate correct administrative fee
            $rateJasa = $gadai->kategori->rate_jasa;
            $biayaJasa = ($gadai->nominal_deal * $rateJasa) / 100;

            // Calculate correct storage (inap) fee
            $biayaInap = 0;
            if ($gadai->item->nominal_inap > 0) {
                $biayaInap = $gadai->item->nominal_inap;
            } else {
                $biayaInap = ($gadai->nominal_deal * $gadai->kategori->rate_inap_persen) / 100;
            }

            $updateData = [
                'biaya_jasa' => $biayaJasa
            ];

            // Only update inap if it is currently 0 or has not been initialized
            if ($gadai->biaya_inap == 0 && $biayaInap > 0) {
                $updateData['biaya_inap'] = $biayaInap;
            }

            $gadai->update($updateData);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No-op
    }
};
