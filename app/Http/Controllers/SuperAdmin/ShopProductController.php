<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ShopProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('primaryImage')->withCount('variations');

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status);
        }

        $products = $query->latest()->paginate(20);

        return view('super-admin.shop.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('super-admin.shop.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_de' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'description_de' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products,sku',
            'barcode' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'specifications' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'name_en' => $request->name_en,
            'name_de' => $request->name_de,
            'description_en' => $request->description_en,
            'description_de' => $request->description_de,
            'specifications' => $request->specifications,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'quantity' => $request->quantity,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured'),
        ]);

        // Handle multiple images upload
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'alt_text' => $product->name,
                    'is_primary' => $index === 0, // First image is primary
                    'sort_order' => $index + 1,
                ]);
            }
        }

        return redirect()->route('super-admin.shop.products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        $product->load('images');
        return view('super-admin.shop.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product->load('images', 'variations');
        return view('super-admin.shop.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_de' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'description_de' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'compare_price' => 'nullable|numeric|min:0',
            'sku' => 'required|string|max:100|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:0',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'specifications' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'exists:product_images,id',
        ]);

        $product->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => $request->description,
            'name_en' => $request->name_en,
            'name_de' => $request->name_de,
            'description_en' => $request->description_en,
            'description_de' => $request->description_de,
            'specifications' => $request->specifications,
            'price' => $request->price,
            'compare_price' => $request->compare_price,
            'sku' => $request->sku,
            'barcode' => $request->barcode,
            'quantity' => $request->quantity,
            'weight' => $request->weight,
            'dimensions' => $request->dimensions,
            'is_active' => $request->has('is_active'),
            'is_featured' => $request->has('is_featured'),
        ]);

        // Handle image deletions
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imageId) {
                $image = ProductImage::where('id', $imageId)
                    ->where('product_id', $product->id)
                    ->first();

                if ($image) {
                    // Delete from storage
                    if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    $image->delete();
                }
            }
        }

        // Handle new images upload
        if ($request->hasFile('images')) {
            $currentMaxOrder = ProductImage::where('product_id', $product->id)->max('sort_order') ?? 0;
            $hasPrimary = ProductImage::where('product_id', $product->id)->where('is_primary', true)->exists();

            foreach ($request->file('images') as $index => $image) {
                $imagePath = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $imagePath,
                    'alt_text' => $product->name,
                    'is_primary' => !$hasPrimary && $index === 0, // First new image becomes primary if no primary exists
                    'sort_order' => $currentMaxOrder + $index + 1,
                ]);
            }
        }

        return redirect()->route('super-admin.shop.products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        // Delete associated images from storage
        foreach ($product->images as $image) {
            if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
            $image->delete();
        }

        $product->delete();

        return redirect()->route('super-admin.shop.products.index')
            ->with('success', 'Product deleted successfully.');
    }

    /**
     * Toggle product active status
     */
    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        return back()->with('success', 'Product status updated successfully.');
    }

    /**
     * Set primary product image
     */
    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        // Verify the image belongs to this product
        if ($image->product_id !== $product->id) {
            abort(403);
        }

        // Set all images to not primary
        ProductImage::where('product_id', $product->id)
            ->update(['is_primary' => false]);

        // Set this image as primary
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated successfully.');
    }
}
