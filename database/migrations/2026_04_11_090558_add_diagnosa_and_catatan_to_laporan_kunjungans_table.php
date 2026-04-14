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
        Schema::table('laporan_kunjungans', function (Blueprint $table) {
            $table->string('diagnosa')->nullable()->after('status_pelayanan');
            $table->text('catatan')->nullable()->after('diagnosa');
        });
        
        Schema::table('reseps', function (Blueprint $table) {
            $table->text('obat')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('laporan_kunjungans', function (Blueprint $table) {
            $table->dropColumn(['diagnosa', 'catatan']);
        });
        
        Schema::table('reseps', function (Blueprint $table) {
            $table->dropColumn(['obat']);
        });
    }
};
