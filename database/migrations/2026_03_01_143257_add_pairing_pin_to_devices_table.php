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
        Schema::table('devices', function (Blueprint $table) {
            $table->string('pairing_pin', 6)->nullable()->after('device_id');
            $table->timestamp('pairing_pin_expires_at')->nullable()->after('pairing_pin');
            $table->boolean('is_paired')->default(false)->after('pairing_pin_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn(['pairing_pin', 'pairing_pin_expires_at', 'is_paired']);
        });
    }
};
