<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id',
        'name',
        'sku',
        'image_path',
        'price',
        'compare_price',
        'quantity',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'quantity' => 'integer',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the parent product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get cart items for this variation
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get order items for this variation
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get all images for this variation
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductVariationImage::class, 'product_variation_id')->ordered();
    }

    /**
     * Get the price to use (variation price or parent product price)
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->price ?? $this->product->price;
    }

    /**
     * Get the compare price to use (variation or parent)
     */
    public function getEffectiveComparePriceAttribute(): ?float
    {
        return $this->compare_price ?? $this->product->compare_price;
    }

    /**
     * Check if variation is in stock
     */
    public function isInStock(): bool
    {
        return $this->is_active && $this->quantity > 0;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '€' . number_format($this->effective_price, 2, ',', '.');
    }

    /**
     * Get formatted compare price
     */
    public function getFormattedComparePriceAttribute(): ?string
    {
        if (!$this->effective_compare_price) {
            return null;
        }

        return '€' . number_format($this->effective_compare_price, 2, ',', '.');
    }

    /**
     * Check if variation is on sale
     */
    public function isOnSale(): bool
    {
        $comparePrice = $this->effective_compare_price;
        $price = $this->effective_price;

        return $comparePrice && $comparePrice > $price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute(): ?float
    {
        if (!$this->isOnSale()) {
            return null;
        }

        $comparePrice = $this->effective_compare_price;
        $price = $this->effective_price;

        return round((($comparePrice - $price) / $comparePrice) * 100);
    }

    /**
     * Decrease quantity
     */
    public function decreaseQuantity(int $amount): void
    {
        $this->decrement('quantity', $amount);
    }

    /**
     * Increase quantity
     */
    public function increaseQuantity(int $amount): void
    {
        $this->increment('quantity', $amount);
    }

    /**
     * Scope: Active variations only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: In stock variations
     */
    public function scopeInStock($query)
    {
        return $query->where('is_active', true)->where('quantity', '>', 0);
    }

    /**
     * Get variation primary image URL or fallback to product primary image
     */
    public function getImageUrlAttribute(): string
    {
        // First try to get from images gallery (primary or first image)
        $primaryImage = $this->images()->where('is_primary', true)->first();
        if ($primaryImage) {
            return $primaryImage->image_url;
        }

        $firstImage = $this->images()->first();
        if ($firstImage) {
            return $firstImage->image_url;
        }

        // Fallback to old single image_path for backward compatibility
        if ($this->image_path) {
            if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
                return $this->image_path;
            }
            return '/' . 'storage/' . $this->image_path;
        }

        // Fallback to parent product's primary image
        return $this->product->image_url;
    }

    /**
     * Get all image URLs for this variation
     */
    public function getImageGalleryAttribute(): array
    {
        $imageUrls = $this->images->map(function ($image) {
            return $image->image_url;
        })->toArray();

        // If no images in gallery, use single image or product image as fallback
        if (empty($imageUrls)) {
            $imageUrls[] = $this->image_url;
        }

        return $imageUrls;
    }

    /**
     * Get thumbnail URL for variation image
     */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->image_url;
    }

    /**
     * Check if variation has its own images
     */
    public function hasOwnImage(): bool
    {
        return $this->images()->exists() || !empty($this->image_path);
    }

    /**
     * Get count of variation images
     */
    public function getImagesCountAttribute(): int
    {
        return $this->images()->count();
    }
}
