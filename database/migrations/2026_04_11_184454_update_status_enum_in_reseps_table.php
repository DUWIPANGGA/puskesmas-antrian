<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // PostgreSQL doesn't support modifying enum directly.
        // We drop the old check constraint and add a new one with extended values.

        // 1. Drop the old check constraint
        DB::statement('ALTER TABLE reseps DROP CONSTRAINT IF EXISTS reseps_status_check');

        // 2. Add the new check constraint with extended status values
        DB::statement("ALTER TABLE reseps ADD CONSTRAINT reseps_status_check 
            CHECK (status IN ('pending', 'diproses', 'siap_ambil', 'diambil', 'selesai', 'batal'))");
    }

    public function down(): void
    {
        // Revert to original constraint
        DB::statement('ALTER TABLE reseps DROP CONSTRAINT IF EXISTS reseps_status_check');
        DB::statement("ALTER TABLE reseps ADD CONSTRAINT reseps_status_check 
            CHECK (status IN ('pending', 'diproses', 'selesai'))");
    }
};
