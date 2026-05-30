<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Kategori Master
        Schema::create('tbl_gadai_master_kategori', function (Blueprint $table) {
            $table->id();
            $table->string('kode_kategori')->unique(); // electronic, vehicle, gold
            $table->string('nama_kategori'); // Elektronik, Kendaraan, Emas
            $table->decimal('rate_jasa', 5, 2)->default(0); // 10%, 3.5%
            $table->decimal('rate_denda', 5, 2)->default(0); // 5%, 2%
            $table->decimal('rate_inap_persen', 5, 2)->default(0); // 1% for gold
            $table->integer('max_extend_default')->default(3);
            $table->integer('masa_gadai_hari')->default(30);
            $table->integer('masa_tenggang_hari')->default(15);
            $table->timestamps();
        });

        // 2. Master Item
        Schema::create('tbl_gadai_master_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_id')->constrained('tbl_gadai_master_kategori')->onDelete('cascade');
            $table->string('nama_item'); // HP, Laptop, Motor
            $table->decimal('max_taksiran', 15, 2)->default(0);
            $table->decimal('rate_inap_nominal', 15, 2)->default(0); // 50000 for Motor, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 3. Grid Tables (Electronic)
        Schema::create('tbl_gadai_grid_electronic', function (Blueprint $table) {
            $table->id();
            $table->string('kode_slot')->unique(); // EL-0101
            $table->integer('baris'); // from 1 (bottom) to N
            $table->integer('kolom'); // from 1 (left) to N
            $table->boolean('is_occupied')->default(false);
            $table->unsignedBigInteger('active_gadai_id')->nullable(); // no strict FK yet to avoid circular initially or self-reference later
            $table->timestamps();
        });

        // 4. Grid Tables (Vehicle)
        Schema::create('tbl_gadai_grid_vehicle', function (Blueprint $table) {
            $table->id();
            $table->string('kode_slot')->unique(); // VK-0101
            $table->integer('baris');
            $table->integer('kolom');
            $table->boolean('is_occupied')->default(false);
            $table->unsignedBigInteger('active_gadai_id')->nullable();
            $table->timestamps();
        });

        // 5. Grid Tables (Gold)
        Schema::create('tbl_gadai_grid_gold', function (Blueprint $table) {
            $table->id();
            $table->string('kode_slot')->unique(); // EM-0101
            $table->integer('baris');
            $table->integer('kolom');
            $table->boolean('is_occupied')->default(false);
            $table->unsignedBigInteger('active_gadai_id')->nullable();
            $table->timestamps();
        });

        // 6. Gadai Active (Transaksi)
        Schema::create('tbl_gadai_active', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nasabah_id')->constrained('tbl_nasabah');
            $table->foreignId('kategori_id')->constrained('tbl_gadai_master_kategori');
            $table->foreignId('item_id')->constrained('tbl_gadai_master_item');
            $table->foreignId('lokasi_id')->constrained('jns_lokasi_perusahaan');
            
            $table->string('slot_kode'); // EL-0101
            $table->enum('slot_table', ['electronic', 'vehicle', 'gold']);
            
            $table->decimal('nominal_deal', 15, 2);
            $table->decimal('biaya_jasa', 15, 2);
            $table->decimal('denda_aktif', 15, 2)->default(0);
            $table->decimal('biaya_inap', 15, 2)->default(0);
            
            $table->dateTime('tgl_mulai');
            $table->dateTime('tgl_jatuh_tempo');
            $table->dateTime('tgl_tenggang');
            $table->integer('jumlah_perpanjangan')->default(0);
            
            $table->enum('status', [
                'active',          // Gadai sedang berjalan
                'grace_period',    // Masuk masa tenggang
                'lunas',           // Sudah lunas
                'extended',        // Status sementara saat riwayat dicatat, biasanya tetap active
                'auctioned',       // Sudah dilelang
                'expired_final',   // Lewat masa tenggang (hangus)
                'returned'         // Barang dikembalikan
            ])->default('active');
            
            $table->foreignId('admin_id')->constrained('users'); // Who created/approved
            $table->timestamps();
        });

        // Now we can add foreign key to the grids if needed, but managing via code is often simpler for dynamic polymorphic relations.
        // We will stick to managing it in code to avoid complex circular dependencies.

        // 7. Gadai History
        Schema::create('tbl_gadai_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_active_id')->constrained('tbl_gadai_active')->onDelete('cascade');
            $table->enum('aksi', ['create', 'extend', 'lunas', 'auction', 'return', 'expired']);
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        // 8. Gadai Files
        Schema::create('tbl_gadai_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_active_id')->constrained('tbl_gadai_active')->onDelete('cascade');
            $table->string('path_file');
            $table->enum('tipe_foto', ['barang', 'penyerahan', 'lainnya'])->default('barang');
            $table->timestamps();
        });

        // 9. Gadai Slot Log
        Schema::create('tbl_gadai_slot_log', function (Blueprint $table) {
            $table->id();
            $table->string('slot_kode');
            $table->string('kategori');
            $table->enum('aksi', ['fill', 'empty']);
            $table->unsignedBigInteger('gadai_active_id')->nullable();
            $table->timestamps();
        });

        // 10. Gadai Payment Log
        Schema::create('tbl_gadai_payment_log', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gadai_active_id')->constrained('tbl_gadai_active')->onDelete('cascade');
            $table->enum('jenis_pembayaran', ['jasa', 'denda', 'inap', 'tebus', 'perpanjangan', 'lelang']);
            $table->decimal('nominal', 15, 2);
            $table->enum('metode', ['cash', 'transfer']);
            $table->string('petty_cash_ref')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_gadai_payment_log');
        Schema::dropIfExists('tbl_gadai_slot_log');
        Schema::dropIfExists('tbl_gadai_files');
        Schema::dropIfExists('tbl_gadai_history');
        Schema::dropIfExists('tbl_gadai_active');
        Schema::dropIfExists('tbl_gadai_grid_gold');
        Schema::dropIfExists('tbl_gadai_grid_vehicle');
        Schema::dropIfExists('tbl_gadai_grid_electronic');
        Schema::dropIfExists('tbl_gadai_master_item');
        Schema::dropIfExists('tbl_gadai_master_kategori');
    }
};
