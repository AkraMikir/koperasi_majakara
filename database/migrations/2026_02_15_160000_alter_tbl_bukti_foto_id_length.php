<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Perbaikan: kolom id (dan owner_id) di tbl_bukti_foto harus cukup panjang
     * untuk ID hasil IdGenerator (format: DDMMYYYY + 4 digit + suffix, mis. 150220260001TCSJNJT).
     * Jika kolom terlalu pendek akan terjadi "Data truncated for column 'id'".
     */
    public function up(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE tbl_bukti_foto MODIFY id VARCHAR(50) NOT NULL');
            DB::statement('ALTER TABLE tbl_bukti_foto MODIFY owner_id VARCHAR(50) NOT NULL');
        }
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE tbl_bukti_foto MODIFY id VARCHAR(30) NOT NULL');
            DB::statement('ALTER TABLE tbl_bukti_foto MODIFY owner_id VARCHAR(30) NOT NULL');
        }
    }
};
