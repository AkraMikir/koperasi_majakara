<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration//adwad
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tbl_pekerjaan', function (Blueprint $table) {
            // Change penghasilan from decimal to string to support income ranges
            $table->string('penghasilan', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_pekerjaan', function (Blueprint $table) {
            // Revert back to decimal if needed
            $table->decimal('penghasilan', 15, 2)->nullable()->change();
        });
    }
};
