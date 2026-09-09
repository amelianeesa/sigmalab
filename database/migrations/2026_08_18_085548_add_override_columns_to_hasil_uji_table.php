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
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->string('override_status')->nullable()->after('status_berketerimaan');
            $table->string('override_kode')->nullable()->after('override_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hasil_uji', function (Blueprint $table) {
            $table->dropColumn(['override_status', 'override_kode']);
        });
    }
};
