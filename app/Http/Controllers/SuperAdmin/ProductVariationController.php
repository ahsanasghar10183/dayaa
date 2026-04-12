<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\ProductVariationImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductVariationController extends Controller
{
    /**
     * Store a new variation for a product
     */
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:product_variations,sku',
            'price' => 'nullable|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Ensure product is set to variable type
        if ($product->product_type !== 'variable') {
            $product->update(['product_type' => 'variable']);
        }

        $variation = ProductVariation::create([
            'product_id' => $product->id,
            'name' => $request->name,
            'sku' => $request->sku,
            'image_path' => null, // No longer using single image
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'quantity' => $request->quantity,
            'is_active' => $request->has('is_active') ? true : false,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products/variations', 'public');

                ProductVariationImage::create([
                    'product_variation_id' => $variation->id,
                    'image_path' => $imagePath,
                    'sort_order' => $index,
                    'is_primary' => $index === 0, // First image is primary
                ]);
            }
        }

        return back()->with('success', 'Variation added successfully with ' . ($request->hasFile('images') ? count($request->file('images')) : 0) . ' images.');
    }

    /**
     * Update a variation
     */
    public function update(Request $request, Product $product, ProductVariation $variation)
    {
        // Verify variation belongs to product
        if ($variation->product_id !== $product->id) {
            abort(403);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|max:100|unique:product_variations,sku,' . $variation->id,
            'price' => 'nullable|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'sort_order' => 'nullable|integer|min:0',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $variation->update([
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'quantity' => $request->quantity,
            'is_active' => $request->has('is_active') ? true : false,
            'sort_order' => $request->sort_order ?? $variation->sort_order,
        ]);

        // Handle multiple image uploads
        if ($request->hasFile('images')) {
            $currentImagesCount = $variation->images()->count();

            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products/variations', 'public');

                ProductVariationImage::create([
                    'product_variation_id' => $variation->id,
                    'image_path' => $imagePath,
                    'sort_order' => $currentImagesCount + $index,
                    'is_primary' => $currentImagesCount === 0 && $index === 0, // First image if no existing images
                ]);
            }
        }

        return back()->with('success', 'Variation updated successfully.');
    }

    /**
     * Delete a variation
     */
    public function destroy(Product $product, ProductVariation $variation)
    {
        // Verify variation belongs to product
        if ($variation->product_id !== $product->id) {
            abort(403);
        }

        // Delete all variation images
        foreach ($variation->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        // Delete old single image if exists (backward compatibility)
        if ($variation->image_path && Storage::disk('public')->exists($variation->image_path)) {
            Storage::disk('public')->delete($variation->image_path);
        }

        $variation->delete();

        // If product has no more variations, set it back to simple
        if ($product->variations()->count() === 0) {
            $product->update(['product_type' => 'simple']);
        }

        return back()->with('success', 'Variation deleted successfully.');
    }

    /**
     * Toggle variation active status
     */
    public function toggleStatus(Product $product, ProductVariation $variation)
    {
        // Verify variation belongs to product
        if ($variation->product_id !== $product->id) {
            abort(403);
        }

        $variation->update(['is_active' => !$variation->is_active]);

        return back()->with('success', 'Variation status updated successfully.');
    }

    /**
     * Delete a single variation image
     */
    public function deleteImage(Product $product, ProductVariation $variation, ProductVariationImage $image)
    {
        // Verify variation belongs to product and image belongs to variation
        if ($variation->product_id !== $product->id || $image->product_variation_id !== $variation->id) {
            abort(403);
        }

        // Delete image file
        if (Storage::disk('public')->exists($image->image_path)) {
            Storage::disk('public')->delete($image->image_path);
        }

        // If this was the primary image, make another image primary
        $wasPrimary = $image->is_primary;
        $image->delete();

        if ($wasPrimary) {
            $firstImage = $variation->images()->first();
            if ($firstImage) {
                $firstImage->update(['is_primary' => true]);
            }
        }

        return back()->with('success', 'Image deleted successfully.');
    }

    /**
     * Get variation images as JSON (for AJAX requests)
     */
    public function getImages(Product $product, ProductVariation $variation)
    {
        // Verify variation belongs to product
        if ($variation->product_id !== $product->id) {
            abort(403);
        }

        $images = $variation->images()->ordered()->get()->map(function ($image) {
            return [
                'id' => $image->id,
                'url' => $image->image_url,
                'is_primary' => $image->is_primary,
                'sort_order' => $image->sort_order,
            ];
        });

        return response()->json([
            'images' => $images,
        ]);
    }
}
