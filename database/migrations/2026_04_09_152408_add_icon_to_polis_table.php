<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('polis', function (Blueprint $table) {
            $table->string('icon', 100)->default('fa-solid fa-hospital')->after('kuota_harian_default');
        });
    }

    public function down(): void
    {
        Schema::table('polis', function (Blueprint $table) {
            $table->dropColumn('icon');
        });
    }
};
