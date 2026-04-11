<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'product_variation_id',
        'variation_name',
        'quantity',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * Get the product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user (if logged in)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the product variation (if applicable)
     */
    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }

    /**
     * Get subtotal for this cart item
     * Uses variation price if variation is selected, otherwise product price
     */
    public function getSubtotalAttribute(): float
    {
        $price = $this->variation ? $this->variation->effective_price : $this->product->price;
        return $this->quantity * $price;
    }

    /**
     * Get the price for this cart item
     */
    public function getPriceAttribute(): float
    {
        return $this->variation ? $this->variation->effective_price : $this->product->price;
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return '€' . number_format($this->subtotal, 2, ',', '.');
    }

    /**
     * Increase quantity
     */
    public function increaseQuantity(int $amount = 1): void
    {
        $this->increment('quantity', $amount);
    }

    /**
     * Decrease quantity
     */
    public function decreaseQuantity(int $amount = 1): void
    {
        $newQuantity = $this->quantity - $amount;

        if ($newQuantity <= 0) {
            $this->delete();
        } else {
            $this->decrement('quantity', $amount);
        }
    }
}
