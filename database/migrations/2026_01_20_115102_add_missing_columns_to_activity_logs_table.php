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
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->string('action')->nullable()->after('organization_id');
            $table->string('model_type')->nullable()->after('action');
            $table->unsignedBigInteger('model_id')->nullable()->after('model_type');
            $table->string('ip_address')->nullable()->after('description');
            $table->text('user_agent')->nullable()->after('ip_address');

            $table->index(['model_type', 'model_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropIndex(['model_type', 'model_id']);
            $table->dropColumn(['action', 'model_type', 'model_id', 'ip_address', 'user_agent']);
        });
    }
};
