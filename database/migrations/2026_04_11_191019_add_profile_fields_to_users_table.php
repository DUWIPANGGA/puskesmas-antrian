<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $columns = DB::select("SELECT column_name FROM information_schema.columns WHERE table_name = 'users'");
        $existing = array_column($columns, 'column_name');

        Schema::table('users', function (Blueprint $table) use ($existing) {
            if (!in_array('golongan_darah', $existing)) {
                $table->string('golongan_darah', 5)->nullable()->after('nik');
            }
            if (!in_array('gender', $existing)) {
                $table->string('gender', 20)->nullable()->after('golongan_darah');
            }
            if (!in_array('photo', $existing)) {
                $table->string('photo', 255)->nullable()->after('gender');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumnIfExists(['golongan_darah', 'gender', 'photo']);
        });
    }
};
