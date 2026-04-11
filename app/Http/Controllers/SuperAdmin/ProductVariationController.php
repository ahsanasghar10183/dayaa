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
        ]);

        // Ensure product is set to variable type
        if ($product->product_type !== 'variable') {
            $product->update(['product_type' => 'variable']);
        }

        ProductVariation::create([
            'product_id' => $product->id,
            'name' => $request->name,
            'sku' => $request->sku,
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
