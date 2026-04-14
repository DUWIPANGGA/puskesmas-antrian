<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('antrians', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_antrian')->unique();
            $table->foreignId('pasien_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('poli_id')->constrained('polis')->onDelete('cascade');
            $table->foreignId('jadwal_dokter_id')->nullable()->constrained('jadwal_dokters');
            $table->date('tanggal');
            $table->integer('nomor_urut');
            $table->enum('status', ['menunggu', 'check_in', 'dipanggil', 'dilayani', 'selesai', 'batal'])->default('menunggu');
            $table->timestamp('check_in_at')->nullable();
            $table->timestamp('dipanggil_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
            
            $table->index(['poli_id', 'tanggal', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('antrians');
    }
};