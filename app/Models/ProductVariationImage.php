<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariationImage extends Model
{
    protected $fillable = [
        'product_variation_id',
        'image_path',
        'sort_order',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the variation that owns the image
     */
    public function variation(): BelongsTo
    {
        return $this->belongsTo(ProductVariation::class, 'product_variation_id');
    }

    /**
     * Get the full image URL
     */
    public function getImageUrlAttribute(): string
    {
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }
        return '/' . 'storage/' . $this->image_path;
    }

    /**
     * Scope to get images ordered by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }
}
