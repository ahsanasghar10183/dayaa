<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\CartItem;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    /**
     * Show shopping cart
     */
    public function index()
    {
        $cartItems = $this->getCartItems();

        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        $tax = $subtotal * 0.19; // 19% VAT in Germany
        $shipping = $subtotal > 100 ? 0 : 9.99; // Free shipping over €100
        $total = $subtotal + $tax + $shipping;

        return view('marketing.cart.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $product = Product::findOrFail($productId);

        if (!$product->isInStock()) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Product is out of stock'], 400);
            }
            return back()->with('error', 'Product is out of stock');
        }

        if ($product->quantity < $request->quantity) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Not enough stock available'], 400);
            }
            return back()->with('error', 'Not enough stock available');
        }

        $sessionId = $this->getSessionId();

        $cartItem = CartItem::where('session_id', $sessionId)
            ->where('product_id', $productId)
            ->first();

        if ($cartItem) {
            $newQuantity = $cartItem->quantity + $request->quantity;

            if ($newQuantity > $product->quantity) {
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Not enough stock available'], 400);
                }
                return back()->with('error', 'Not enough stock available');
            }

            $cartItem->update(['quantity' => $newQuantity]);
        } else {
            CartItem::create([
                'session_id' => $sessionId,
                'product_id' => $productId,
                'quantity' => $request->quantity,
            ]);
        }

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Product added to cart successfully']);
        }
        return back()->with('success', 'Product added to cart successfully');
    }

    /**
     * Buy product now (add to cart and redirect to checkout)
     */
    public function buyNow(Request $request, $productId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $product = Product::findOrFail($productId);

        if (!$product->isInStock()) {
            return back()->with('error', 'Product is out of stock');
        }

        if ($product->quantity < $request->quantity) {
            return back()->with('error', 'Not enough stock available');
        }

        $sessionId = $this->getSessionId();

        // Clear existing cart for "Buy Now" functionality
        CartItem::where('session_id', $sessionId)->delete();

        // Add the product to cart
        CartItem::create([
            'session_id' => $sessionId,
            'product_id' => $productId,
            'quantity' => $request->quantity,
        ]);

        // Redirect directly to checkout
        return redirect()->route('marketing.checkout.index');
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $cartItemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        $sessionId = $this->getSessionId();

        $cartItem = CartItem::where('id', $cartItemId)
            ->where('session_id', $sessionId)
            ->firstOrFail();

        if ($cartItem->product->quantity < $request->quantity) {
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Not enough stock available'], 400);
            }
            return back()->with('error', 'Not enough stock available');
        }

        $cartItem->update(['quantity' => $request->quantity]);

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Cart updated successfully']);
        }
        return back()->with('success', 'Cart updated successfully');
    }

    /**
     * Remove item from cart
     */
    public function remove(Request $request, $cartItemId)
    {
        $sessionId = $this->getSessionId();

        $cartItem = CartItem::where('id', $cartItemId)
            ->where('session_id', $sessionId)
            ->firstOrFail();

        $cartItem->delete();

        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Item removed from cart']);
        }
        return back()->with('success', 'Item removed from cart');
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $sessionId = $this->getSessionId();

        CartItem::where('session_id', $sessionId)->delete();

        return back()->with('success', 'Cart cleared successfully');
    }

    /**
     * Get cart item count
     */
    public function count()
    {
        $count = $this->getCartItems()->sum('quantity');

        return response()->json(['count' => $count]);
    }

    /**
     * Get cart data for sidebar
     */
    public function data()
    {
        $cartItems = $this->getCartItems();

        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        $tax = $subtotal * 0.19; // 19% VAT in Germany
        $shipping = $subtotal > 100 ? 0 : 9.99; // Free shipping over €100
        $total = $subtotal + $tax + $shipping;

        // Format cart items for JSON response
        $items = $cartItems->map(function($item) {
            return [
                'id' => $item->id,
                'name' => $item->product->name,
                'price' => '€' . number_format($item->product->price, 2),
                'quantity' => $item->quantity,
                'image' => $item->product->image_url,
                'subtotal' => '€' . number_format($item->product->price * $item->quantity, 2),
            ];
        });

        // Get related products (random products)
        $relatedProducts = Product::active()
            ->whereNotIn('id', $cartItems->pluck('product_id'))
            ->inStock()
            ->inRandomOrder()
            ->limit(10)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'price' => $product->formatted_price,
                    'image' => $product->image_url,
                    'url' => route('marketing.shop.product', $product->slug),
                ];
            });

        return response()->json([
            'items' => $items,
            'totals' => [
                'subtotal' => '€' . number_format($subtotal, 2),
                'tax' => '€' . number_format($tax, 2),
                'shipping' => $shipping > 0 ? '€' . number_format($shipping, 2) : __('marketing.cart.free'),
                'total' => '€' . number_format($total, 2),
            ],
            'relatedProducts' => $relatedProducts,
        ]);
    }

    /**
     * Get session ID for cart
     */
    protected function getSessionId(): string
    {
        if (!Session::has('cart_session_id')) {
            Session::put('cart_session_id', uniqid('cart_', true));
        }

        return Session::get('cart_session_id');
    }

    /**
     * Get cart items for current session
     */
    protected function getCartItems()
    {
        return CartItem::where('session_id', $this->getSessionId())
            ->with('product.primaryImage')
            ->get();
    }
}
