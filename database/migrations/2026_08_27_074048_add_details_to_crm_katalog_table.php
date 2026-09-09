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
        Schema::table('crm_katalog', function (Blueprint $table) {
            $table->string('produsen', 150)->nullable()->after('nomor_lot');
            // We rename nama_produk to tipe_material but wait, SQLite doesn't support renameColumn easily if Doctrine DBAL is missing.
            // Let's just add `tipe_material` and `cert_value` `cert_u` directly for THIS parameter!
            // Wait, cert_value and cert_u belong to `crm_sertifikat`, NOT `crm_katalog`!
            // Because one CRM catalog (bottle) has MULTIPLE cert_values for different parameters!
            // This is why we have two tables.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('crm_katalog', function (Blueprint $table) {
            $table->dropColumn('produsen');
        });
    }
};
