<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For PostgreSQL, we might need to handle the ENUM type change or use change() 
        // with a native DB statement if change() doesn't support enums well in older versions.
        // However, in Laravel 10+, simple change() should work if doctrine/dbal or similar is configured, 
        // but adding native SQL is safer for ENUMs.
        
        DB::statement("ALTER TABLE antrians DROP CONSTRAINT IF EXISTS antrians_status_check");
        
        // Add new statuses: siap_pemeriksaan, dipanggil_dokter
        // We'll use a string column first to make it easier or just a long enum.
        Schema::table('antrians', function (Blueprint $table) {
            $table->string('status')->default('menunggu')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('antrians', function (Blueprint $table) {
            // Restore original enum if needed, but string is safer now.
        });
    }
};
