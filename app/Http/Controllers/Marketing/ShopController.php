<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ShopController extends Controller
{
    /**
     * Show all products in shop
     */
    public function index(Request $request)
    {
        $query = Product::active()
            ->with('primaryImage')
            ->inStock();

        // Search
        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // Sort
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12);

        return view('marketing.shop.index', compact('products'));
    }

    /**
     * Show single product
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->active()
            ->with('images', 'inStockVariations')
            ->firstOrFail();

        // Related products - just get other available products
        $relatedProducts = Product::active()
            ->where('id', '!=', $product->id)
            ->inStock()
            ->with('primaryImage')
            ->limit(4)
            ->inRandomOrder()
            ->get();

        return view('marketing.shop.product', compact('product', 'relatedProducts'));
    }
}
