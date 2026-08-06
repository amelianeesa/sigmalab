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
        Schema::table('barang', function (Blueprint $table) {
            if (!Schema::hasColumn('barang', 'saldo_awal')) {
                $table->decimal('saldo_awal', 12, 4)->default(0.0000)->after('minimal_stok');
            }
            if (!Schema::hasColumn('barang', 'penerimaan')) {
                $table->decimal('penerimaan', 12, 4)->default(0.0000)->after('saldo_awal');
            }
            if (!Schema::hasColumn('barang', 'pengeluaran')) {
                $table->decimal('pengeluaran', 12, 4)->default(0.0000)->after('penerimaan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn(['saldo_awal', 'penerimaan', 'pengeluaran']);
        });
    }
};