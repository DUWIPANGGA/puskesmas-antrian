<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'dokter', 'apoteker', 'pasien'])->default('pasien');
            $table->string('nik', 16)->nullable()->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->date('birth_date')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'nik', 'phone', 'address', 'birth_date']);
        });
    }
};