<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('sumup_merchant_code', 32)->nullable()->after('bank_account');
            $table->text('sumup_api_key')->nullable()->after('sumup_merchant_code');
            $table->string('sumup_connection_status', 20)->default('disconnected')->after('sumup_api_key');
            $table->timestamp('sumup_connected_at')->nullable()->after('sumup_connection_status');
            $table->string('sumup_merchant_name')->nullable()->after('sumup_connected_at');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn([
                'sumup_merchant_code',
                'sumup_api_key',
                'sumup_connection_status',
                'sumup_connected_at',
                'sumup_merchant_name',
            ]);
        });
    }
};
