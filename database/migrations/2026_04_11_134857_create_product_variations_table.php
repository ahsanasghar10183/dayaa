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
        Schema::create('product_variations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');

            // Variation details
            $table->string('name'); // e.g., "Style 1", "Blue", "Large"
            $table->string('sku')->nullable()->unique(); // Optional unique SKU for this variation

            // Price override (optional - if null, uses parent product price)
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('compare_price', 10, 2)->nullable();

            // Stock for this specific variation
            $table->integer('quantity')->default(0);

            // Status
            $table->boolean('is_active')->default(true);

            // Sort order for display
            $table->integer('sort_order')->default(0);

            $table->timestamps();

            // Indexes
            $table->index('product_id');
            $table->index('is_active');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variations');
    }
};
