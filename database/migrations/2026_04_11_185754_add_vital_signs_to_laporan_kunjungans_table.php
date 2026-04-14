<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('laporan_kunjungans', function (Blueprint $table) {
            $table->decimal('detak_jantung', 5, 1)->nullable()->after('catatan');   // bpm, e.g. 72.0
            $table->decimal('suhu_tubuh', 4, 1)->nullable()->after('detak_jantung'); // Celsius, e.g. 36.7
            $table->string('tekanan_darah', 10)->nullable()->after('suhu_tubuh');   // e.g. "120/80"
            $table->decimal('saturasi_oksigen', 5, 1)->nullable()->after('tekanan_darah'); // SpO2 %, e.g. 98.0
        });
    }

    public function down(): void
    {
        Schema::table('laporan_kunjungans', function (Blueprint $table) {
            $table->dropColumn(['detak_jantung', 'suhu_tubuh', 'tekanan_darah', 'saturasi_oksigen']);
        });
    }
};
