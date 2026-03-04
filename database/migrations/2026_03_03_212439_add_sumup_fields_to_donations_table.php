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
        Schema::table('donations', function (Blueprint $table) {
            // Add SumUp transaction code field
            $table->string('sumup_transaction_code')->nullable()->after('sumup_transaction_id');

            // Add donor phone and anonymous fields
            $table->string('donor_phone', 50)->nullable()->after('donor_email');
            $table->boolean('anonymous')->default(true)->after('donor_phone');

            // Add notes field for failure reasons and other info
            $table->text('notes')->nullable()->after('error_message');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropColumn([
                'sumup_transaction_code',
                'donor_phone',
                'anonymous',
                'notes',
            ]);
        });
    }
};
