<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('detail_reseps', function (Blueprint $table) {
            $table->boolean('is_checked')->default(false)->after('keterangan');
            $table->string('jenis')->default('obat')->after('is_checked'); // obat / racikan
            $table->integer('stok_tersedia')->nullable()->after('jenis');
        });
    }

    public function down(): void
    {
        Schema::table('detail_reseps', function (Blueprint $table) {
            $table->dropColumn(['is_checked', 'jenis', 'stok_tersedia']);
        });
    }
};
