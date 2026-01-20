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
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campaign_id')->constrained()->onDelete('cascade');
            $table->foreignId('device_id')->constrained()->onDelete('cascade');
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');

            // Donation Details
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('EUR');
            $table->string('receipt_number')->unique();

            // Payment Gateway Details
            $table->string('transaction_id')->nullable()->index();
            $table->string('payment_method')->nullable(); // Visa, Mastercard, etc.
            $table->enum('payment_status', ['success', 'failed', 'pending', 'processing'])->default('pending');

            // Transaction Metadata
            $table->timestamp('transaction_timestamp')->nullable();
            $table->integer('processing_duration')->nullable(); // in milliseconds
            $table->decimal('sumup_fee', 10, 2)->nullable();
            $table->decimal('net_amount', 10, 2)->nullable();

            // Error Tracking
            $table->string('error_code')->nullable();
            $table->text('error_message')->nullable();

            // Security & Tracking
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();

            // Indexes for performance
            $table->index('campaign_id');
            $table->index('device_id');
            $table->index('organization_id');
            $table->index('payment_status');
            $table->index('transaction_timestamp');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
