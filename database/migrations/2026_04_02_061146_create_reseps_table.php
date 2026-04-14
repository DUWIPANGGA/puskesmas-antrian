<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reseps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('antrian_id')->constrained('antrians')->onDelete('cascade');
            $table->foreignId('dokter_id')->constrained('users');
            $table->foreignId('pasien_id')->constrained('users');
            $table->text('diagnosa');
            $table->text('catatan')->nullable();
            $table->enum('status', ['pending', 'diproses', 'selesai'])->default('pending');
            $table->timestamp('diproses_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reseps');
    }
};