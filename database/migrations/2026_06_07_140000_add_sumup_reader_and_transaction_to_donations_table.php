<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->foreignId('sumup_reader_id')->nullable()->after('device_id')
                ->constrained('sumup_readers')->nullOnDelete();
            $table->string('client_transaction_id', 64)->nullable()->unique()->after('sumup_transaction_id');
            $table->string('sumup_status', 32)->nullable()->after('client_transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropForeign(['sumup_reader_id']);
            $table->dropColumn(['sumup_reader_id', 'client_transaction_id', 'sumup_status']);
        });
    }
};
