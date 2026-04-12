<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Http\Request;

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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Ensure product is set to variable type
        if ($product->product_type !== 'variable') {
            $product->update(['product_type' => 'variable']);
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products/variations', 'public');
        }

        ProductVariation::create([
            'product_id' => $product->id,
            'name' => $request->name,
            'sku' => $request->sku,
            'image_path' => $imagePath,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'quantity' => $request->quantity,
            'is_active' => $request->has('is_active') ? true : false,
            'sort_order' => $request->sort_order ?? 0,
        ]);

        return back()->with('success', 'Variation added successfully.');
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload
        $updateData = [
            'name' => $request->name,
            'sku' => $request->sku,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'quantity' => $request->quantity,
            'is_active' => $request->has('is_active') ? true : false,
            'sort_order' => $request->sort_order ?? $variation->sort_order,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($variation->image_path && \Storage::disk('public')->exists($variation->image_path)) {
                \Storage::disk('public')->delete($variation->image_path);
            }
            // Store new image
            $updateData['image_path'] = $request->file('image')->store('products/variations', 'public');
        }

        $variation->update($updateData);

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

        // Delete variation image if exists
        if ($variation->image_path && \Storage::disk('public')->exists($variation->image_path)) {
            \Storage::disk('public')->delete($variation->image_path);
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
}
