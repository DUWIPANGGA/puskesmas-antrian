<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan_kunjungans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antrian_id')->constrained('antrians')->onDelete('cascade');
            $table->date('tanggal');
            $table->foreignId('poli_id')->constrained('polis');
            $table->foreignId('pasien_id')->constrained('users');
            $table->foreignId('dokter_id')->constrained('users');
            $table->time('waktu_check_in');
            $table->time('waktu_dipanggil')->nullable();
            $table->time('waktu_selesai')->nullable();
            $table->integer('lama_pelayanan')->nullable(); // dalam menit
            $table->enum('status_pelayanan', ['selesai', 'batal']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan_kunjungans');
    }
};