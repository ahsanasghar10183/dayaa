<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'name_en',
        'name_de',
        'description_en',
        'description_de',
        'specifications',
        'price',
        'compare_price',
        'cost_price',
        'sku',
        'barcode',
        'quantity',
        'weight',
        'dimensions',
        'is_active',
        'is_featured',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'specifications' => 'array',
        'price' => 'decimal:2',
        'compare_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'weight' => 'decimal:2',
        'quantity' => 'integer',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the category
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Get product images
     */
    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * Get primary image
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    /**
     * Get order items
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get cart items
     */
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Scope: Active products only
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope: In stock
     */
    public function scopeInStock($query)
    {
        return $query->where('quantity', '>', 0);
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(): bool
    {
        return $this->quantity > 0;
    }

    /**
     * Get is_in_stock attribute (accessor for views)
     */
    public function getIsInStockAttribute(): bool
    {
        return $this->isInStock();
    }

    /**
     * Check if product is on sale
     */
    public function isOnSale(): bool
    {
        return $this->compare_price && $this->compare_price > $this->price;
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute(): ?float
    {
        if (!$this->isOnSale()) {
            return null;
        }

        return round((($this->compare_price - $this->price) / $this->compare_price) * 100);
    }

    /**
     * Get product URL
     */
    public function getUrlAttribute(): string
    {
        return route('shop.product', $this->slug);
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '€' . number_format($this->price, 2, ',', '.');
    }

    /**
     * Get formatted compare price
     */
    public function getFormattedComparePriceAttribute(): ?string
    {
        if (!$this->compare_price) {
            return null;
        }

        return '€' . number_format($this->compare_price, 2, ',', '.');
    }

    /**
     * Get primary image URL or placeholder
     */
    public function getImageUrlAttribute(): string
    {
        $primaryImage = $this->primaryImage;

        if ($primaryImage) {
            // Check if the image_path is an external URL (http/https)
            if (filter_var($primaryImage->image_path, FILTER_VALIDATE_URL)) {
                return $primaryImage->image_path;
            }
            // Otherwise, treat as local storage path - use relative path with leading slash
            return '/' . 'storage/' . $primaryImage->image_path;
        }

        // Return placeholder if no image
        return '/' . 'marketing/assets/img/placeholder-product.svg';
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
     * Get localized product name based on current locale
     */
    public function getLocalizedNameAttribute(): string
    {
        $locale = app()->getLocale();

        if ($locale === 'de' && $this->name_de) {
            return $this->name_de;
        } elseif ($locale === 'en' && $this->name_en) {
            return $this->name_en;
        }

        // Fallback to base name
        return $this->name;
    }

    /**
     * Get localized product description based on current locale
     */
    public function getLocalizedDescriptionAttribute(): ?string
    {
        $locale = app()->getLocale();

        if ($locale === 'de' && $this->description_de) {
            return $this->description_de;
        } elseif ($locale === 'en' && $this->description_en) {
            return $this->description_en;
        }

        // Fallback to base description
        return $this->description;
    }
}
