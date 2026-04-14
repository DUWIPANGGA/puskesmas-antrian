<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reseps', function (Blueprint $table) {
            // Extend existing status enum - we do it via raw for PostgreSQL compatibility
            // Also add apoteker fields
            $table->string('nomor_resep', 30)->nullable()->unique()->after('id');
            $table->foreignId('apoteker_id')->nullable()->constrained('users')->nullOnDelete()->after('pasien_id');
            $table->foreignId('poli_id')->nullable()->constrained('polis')->nullOnDelete()->after('apoteker_id');
            $table->text('catatan_apoteker')->nullable()->after('catatan');
            $table->timestamp('diambil_at')->nullable()->after('selesai_at');
        });
    }

    public function down(): void
    {
        Schema::table('reseps', function (Blueprint $table) {
            $table->dropColumn(['nomor_resep', 'apoteker_id', 'poli_id', 'catatan_apoteker', 'diambil_at']);
        });
    }
};
