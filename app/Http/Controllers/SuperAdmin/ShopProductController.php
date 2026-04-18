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
     * Generate a unique slug for the product
     */
    private function generateUniqueSlug($name, $excludeId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        // Check if slug exists, if yes append number
        while (true) {
            $query = Product::where('slug', $slug);

            // Exclude current product when updating
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }

            if (!$query->exists()) {
                break;
            }

            // Append counter to make it unique
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with(['primaryImage', 'images' => function($q) {
            $q->orderBy('sort_order')->limit(1);
        }])->withCount('variations');

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
        // Log incoming request for debugging
        \Log::info('Product creation attempt', [
            'product_type' => $request->product_type,
            'has_variations' => $request->has('variations'),
            'variations_count' => $request->has('variations') ? count($request->variations) : 0,
        ]);

        // Base validation rules
        $validationRules = [
            'name' => 'required|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'name_de' => 'nullable|string|max:255',
            'description' => 'required|string',
            'description_en' => 'nullable|string',
            'description_de' => 'nullable|string',
            'product_type' => 'required|in:simple,variable',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:100',
            'specifications' => 'nullable|array',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ];

        // Add validation rules based on product type
        if ($request->product_type === 'simple') {
            $validationRules['price'] = 'required|numeric|min:0';
            $validationRules['compare_price'] = 'nullable|numeric|min:0';
            $validationRules['sku'] = 'required|string|max:100|unique:products,sku';
            $validationRules['barcode'] = 'nullable|string|max:100';
            $validationRules['quantity'] = 'required|integer|min:0';
            $validationRules['images.*'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        } else {
            // Variable products - variations are required with SKU and price
            $validationRules['variations'] = 'required|array|min:1';
            $validationRules['variations.*.name'] = 'required|string|max:255';
            $validationRules['variations.*.sku'] = 'required|string|max:100|unique:product_variations,sku';
            $validationRules['variations.*.price'] = 'required|numeric|min:0';
            $validationRules['variations.*.compare_price'] = 'nullable|numeric|min:0';
            $validationRules['variations.*.quantity'] = 'required|integer|min:0';
            $validationRules['variations.*.is_active'] = 'nullable|boolean';
            $validationRules['variations.*.images.*'] = 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048';
        }

        // Custom validation messages
        $validationMessages = [
            'name.required' => 'Product name is required.',
            'description.required' => 'Product description is required.',
            'product_type.required' => 'Please select a product type.',
            'sku.unique' => 'This SKU is already in use. Please use a different SKU.',
            'sku.required' => 'SKU is required.',
            'price.required' => 'Price is required.',
            'quantity.required' => 'Stock quantity is required.',
            'variations.required' => 'Please add at least one variation for variable products.',
            'variations.*.name.required' => 'Each variation must have a name.',
            'variations.*.sku.required' => 'Each variation must have an SKU.',
            'variations.*.sku.unique' => 'This variation SKU is already in use. Each variation must have a unique SKU.',
            'variations.*.price.required' => 'Each variation must have a price.',
            'variations.*.quantity.required' => 'Each variation must have a stock quantity.',
        ];

        try {
            $request->validate($validationRules, $validationMessages);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Product validation failed', [
                'errors' => $e->errors(),
                'request_data' => $request->except(['images', '_token'])
            ]);
            throw $e;
        }

        try {
            // Prepare product data - common fields
            $productData = [
                'name' => $request->name,
                'slug' => $this->generateUniqueSlug($request->name),
                'description' => $request->description,
                'name_en' => $request->name_en,
                'name_de' => $request->name_de,
                'description_en' => $request->description_en,
                'description_de' => $request->description_de,
                'specifications' => $request->specifications,
                'product_type' => $request->product_type,
                'weight' => $request->weight,
                'dimensions' => $request->dimensions,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
            ];

            // Add pricing/inventory fields only for simple products
            if ($request->product_type === 'simple') {
                $productData['price'] = $request->price;
                $productData['compare_price'] = $request->compare_price;
                $productData['sku'] = $request->sku;
                $productData['barcode'] = $request->barcode;
                $productData['quantity'] = $request->quantity;
            } else {
                // Variable products: Set minimal required values
                // Stock and pricing are managed through variations
                $productData['price'] = 0;
                $productData['quantity'] = 0;
            }

            $product = Product::create($productData);

            // Handle simple product images
            if ($request->product_type === 'simple' && $request->hasFile('images')) {
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

            // Handle variable product variations
            if ($request->product_type === 'variable' && $request->has('variations')) {
                foreach ($request->variations as $index => $variationData) {
                    $variation = \App\Models\ProductVariation::create([
                        'product_id' => $product->id,
                        'name' => $variationData['name'],
                        'sku' => $variationData['sku'] ?? null,
                        'price' => $variationData['price'] ?? null,
                        'compare_price' => $variationData['compare_price'] ?? null,
                        'quantity' => $variationData['quantity'],
                        'is_active' => isset($variationData['is_active']) ? true : false,
                        'sort_order' => $index,
                    ]);

                    // Handle variation images - they come through request->file()
                    $variationImagesKey = "variations.{$index}.images";
                    if ($request->hasFile($variationImagesKey)) {
                        $variationImages = $request->file($variationImagesKey);
                        foreach ($variationImages as $imgIndex => $image) {
                            $imagePath = $image->store('products/variations', 'public');

                            \App\Models\ProductVariationImage::create([
                                'product_variation_id' => $variation->id,
                                'image_path' => $imagePath,
                                'sort_order' => $imgIndex,
                                'is_primary' => $imgIndex === 0, // First image is primary
                            ]);
                        }
                    }
                }
            }

            $message = $request->product_type === 'simple'
                ? 'Simple product created successfully.'
                : 'Variable product created successfully with ' . count($request->variations) . ' variations.';

            \Log::info('Product created successfully', ['product_id' => $product->id, 'type' => $product->product_type]);

            return redirect()->route('super-admin.shop.products.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            \Log::error('Product creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['images', '_token'])
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
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
        try {
            // Base validation rules
            $validationRules = [
                'name' => 'required|string|max:255',
                'name_en' => 'nullable|string|max:255',
                'name_de' => 'nullable|string|max:255',
                'description' => 'required|string',
                'description_en' => 'nullable|string',
                'description_de' => 'nullable|string',
                'product_type' => 'required|in:simple,variable',
                'weight' => 'nullable|numeric|min:0',
                'dimensions' => 'nullable|string|max:100',
                'specifications' => 'nullable|array',
                'is_active' => 'boolean',
                'is_featured' => 'boolean',
                'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'delete_images' => 'nullable|array',
                'delete_images.*' => 'exists:product_images,id',
            ];

            // Add validation rules for simple products only
            if ($request->product_type === 'simple') {
                $validationRules['price'] = 'required|numeric|min:0';
                $validationRules['compare_price'] = 'nullable|numeric|min:0';
                $validationRules['sku'] = 'nullable|string|max:100|unique:products,sku,' . $product->id;
                $validationRules['barcode'] = 'nullable|string|max:100';
                $validationRules['quantity'] = 'required|integer|min:0';
            }

            $request->validate($validationRules, [
                'sku.unique' => 'This SKU is already in use. Please use a different SKU.',
                'price.required' => 'Price is required for simple products.',
                'quantity.required' => 'Stock quantity is required for simple products.',
            ]);

            // Prepare update data - common fields for all product types
            $updateData = [
                'name' => $request->name,
                'slug' => $this->generateUniqueSlug($request->name, $product->id),
                'description' => $request->description,
                'name_en' => $request->name_en,
                'name_de' => $request->name_de,
                'description_en' => $request->description_en,
                'description_de' => $request->description_de,
                'specifications' => $request->specifications,
                'product_type' => $request->product_type,
                'weight' => $request->weight,
                'dimensions' => $request->dimensions,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
            ];

            // Only update price, SKU, quantity for simple products
            // Variable products DON'T use these fields - they're managed through variations
            if ($request->product_type === 'simple') {
                $updateData['price'] = $request->price;
                $updateData['compare_price'] = $request->compare_price;
                $updateData['sku'] = $request->sku;
                $updateData['barcode'] = $request->barcode;
                $updateData['quantity'] = $request->quantity;
            }
            // For variable products: Don't update price/quantity/sku at all
            // Leave them as they are - only variations matter for stock and pricing

            $product->update($updateData);

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

                // Recalculate has primary after deletions
                $hasPrimary = ProductImage::where('product_id', $product->id)->where('is_primary', true)->exists();

                // If no primary image exists after deletions, set the first remaining image as primary
                if (!$hasPrimary) {
                    $firstImage = ProductImage::where('product_id', $product->id)->orderBy('sort_order')->first();
                    if ($firstImage) {
                        $firstImage->update(['is_primary' => true]);
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

            \Log::info('Product updated successfully', ['product_id' => $product->id]);

            return redirect()->route('super-admin.shop.products.index')
                ->with('success', 'Product updated successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Product update validation failed', [
                'product_id' => $product->id,
                'errors' => $e->errors(),
                'request_data' => $request->except(['images', '_token'])
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Product update failed', [
                'product_id' => $product->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['images', '_token'])
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
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
