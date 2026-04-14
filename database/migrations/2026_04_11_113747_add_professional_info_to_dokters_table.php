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
        Schema::table('dokters', function (Blueprint $table) {
            $table->text('bio')->nullable();
            $table->string('alumni')->nullable();
            $table->integer('pengalaman_tahun')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dokters', function (Blueprint $table) {
            $table->dropColumn(['bio', 'alumni', 'pengalaman_tahun']);
        });
    }
};
