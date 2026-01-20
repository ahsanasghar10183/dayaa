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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'inactive', 'scheduled', 'ended'])->default('active');
            $table->string('language', 2)->default('en');
            $table->string('currency', 3)->default('EUR');
            $table->string('reference_code')->nullable();

            // Template & Design
            $table->string('template_id')->nullable();
            $table->json('design_settings')->nullable(); // Colors, fonts, images, logo
            $table->json('content_settings')->nullable(); // Title, subtitle, CTA, thank you message
            $table->json('amount_settings')->nullable(); // Preset amounts, min, max, custom allowed

            $table->timestamps();
            $table->softDeletes();

            $table->index('organization_id');
            $table->index('status');
            $table->index('start_date');
            $table->index('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
