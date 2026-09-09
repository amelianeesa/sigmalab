<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->enum('jenis_kontrol', ['in_house', 'crm'])->default('in_house')->after('kategori_parameter');
        });
    }

    public function down(): void
    {
        Schema::table('parameter_uji', function (Blueprint $table) {
            $table->dropColumn('jenis_kontrol');
        });
    }
};
