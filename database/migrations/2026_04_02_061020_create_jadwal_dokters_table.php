// database/migrations/2024_01_01_000003_create_jadwal_dokters_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_dokters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dokter_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('poli_id')->constrained('polis')->onDelete('cascade');
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']);
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->integer('kuota')->default(20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_dokters');
    }
};