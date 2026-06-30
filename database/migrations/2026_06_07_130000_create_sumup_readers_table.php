<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sumup_readers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
            $table->string('sumup_reader_id', 64)->index();
            $table->string('name');
            $table->string('pairing_code', 16)->nullable();
            $table->string('status', 20)->default('pending');
            $table->string('device_model')->nullable();
            $table->string('device_serial_number')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['organization_id', 'sumup_reader_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sumup_readers');
    }
};
