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
        Schema::table('devices', function (Blueprint $table) {
            // Make registration_date nullable or set default
            $table->timestamp('registration_date')->nullable()->change();
        });

        // Update status enum to include 'maintenance'
        DB::statement("ALTER TABLE devices MODIFY COLUMN status ENUM('online', 'offline', 'maintenance') DEFAULT 'offline'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->timestamp('registration_date')->nullable(false)->change();
        });

        // Revert status enum
        DB::statement("ALTER TABLE devices MODIFY COLUMN status ENUM('online', 'offline') DEFAULT 'offline'");
    }
};
