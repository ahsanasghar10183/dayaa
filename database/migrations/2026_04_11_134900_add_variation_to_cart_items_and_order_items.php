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
        // Update cart_items table
        Schema::table('cart_items', function (Blueprint $table) {
            $table->foreignId('product_variation_id')->nullable()->after('product_id')->constrained('product_variations')->onDelete('cascade');
            $table->string('variation_name')->nullable()->after('product_variation_id'); // Store variation name for display

            $table->index('product_variation_id');
        });

        // Update order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->foreignId('product_variation_id')->nullable()->after('product_id')->constrained('product_variations')->onDelete('set null');
            $table->string('variation_name')->nullable()->after('product_variation_id'); // Store variation name for historical record

            $table->index('product_variation_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['product_variation_id']);
            $table->dropIndex(['product_variation_id']);
            $table->dropColumn(['product_variation_id', 'variation_name']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['product_variation_id']);
            $table->dropIndex(['product_variation_id']);
            $table->dropColumn(['product_variation_id', 'variation_name']);
        });
    }
};
