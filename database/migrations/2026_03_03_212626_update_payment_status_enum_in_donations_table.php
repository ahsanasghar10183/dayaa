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
        // First, modify the enum to include both 'success' and 'completed'
        DB::statement("ALTER TABLE donations MODIFY COLUMN payment_status ENUM('success', 'completed', 'failed', 'pending', 'processing') NOT NULL DEFAULT 'pending'");

        // Then, update existing 'success' values to 'completed'
        DB::statement("UPDATE donations SET payment_status = 'completed' WHERE payment_status = 'success'");

        // Finally, remove 'success' from the enum
        DB::statement("ALTER TABLE donations MODIFY COLUMN payment_status ENUM('completed', 'failed', 'pending', 'processing') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert 'completed' back to 'success'
        DB::statement("UPDATE donations SET payment_status = 'success' WHERE payment_status = 'completed'");

        // Revert enum back to use 'success'
        DB::statement("ALTER TABLE donations MODIFY COLUMN payment_status ENUM('success', 'failed', 'pending', 'processing') NOT NULL DEFAULT 'pending'");
    }
};
