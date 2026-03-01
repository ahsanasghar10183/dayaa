<?php

namespace App\Http\Controllers\Marketing;

use App\Http\Controllers\Controller;
use App\Mail\NewOrderNotification;
use App\Mail\OrderConfirmation;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        $subtotal = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        $tax = $subtotal * 0.19;
        $shipping = $subtotal > 100 ? 0 : 9.99;
        $total = $subtotal + $tax + $shipping;

        return view('marketing.checkout.index', compact('cartItems', 'subtotal', 'tax', 'shipping', 'total'));
    }

    /**
     * Process checkout and create order
     */
    public function process(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'nullable|string|max:50',
            'billing_address' => 'required|string|max:1000',
            'shipping_address' => 'nullable|string|max:1000',
            'payment_method' => 'required|in:stripe,paypal,bank_transfer',
        ]);

        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty');
        }

        // Calculate totals
        $subtotal = 0;
        foreach ($cartItems as $item) {
            if (!$item->product->isInStock() || $item->product->quantity < $item->quantity) {
                return back()->with('error', "Product '{$item->product->name}' is no longer available in requested quantity");
            }
            $subtotal += $item->product->price * $item->quantity;
        }

        $tax = $subtotal * 0.19;
        $shipping = $subtotal > 100 ? 0 : 9.99;
        $total = $subtotal + $tax + $shipping;

        try {
            DB::beginTransaction();

            // Create order
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'billing_address' => $request->billing_address,
                'shipping_address' => $request->shipping_address ?? $request->billing_address,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'discount_amount' => 0,
                'total_amount' => $total,
                'payment_method' => $request->payment_method,
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Create order items and decrease product quantities
            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_name' => $cartItem->product->name,
                    'product_sku' => $cartItem->product->sku,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $cartItem->product->price,
                    'total_price' => $cartItem->product->price * $cartItem->quantity,
                ]);

                // Decrease product quantity
                $cartItem->product->decreaseQuantity($cartItem->quantity);
            }

            // Clear cart
            CartItem::where('session_id', $this->getSessionId())->delete();

            DB::commit();

            // Send order confirmation email to customer
            Mail::to($order->customer_email)->send(new OrderConfirmation($order));

            // Send new order notification to admin
            $adminEmail = config('mail.from.address');
            Mail::to($adminEmail)->send(new NewOrderNotification($order));

            // TODO: Process payment based on payment method

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Your order has been placed successfully!');

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to process order. Please try again.');
        }
    }

    /**
     * Show order success page
     */
    public function success($orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items.product')
            ->firstOrFail();

        return view('marketing.checkout.success', compact('order'));
    }

    /**
     * Get session ID
     */
    protected function getSessionId(): string
    {
        return Session::get('cart_session_id', '');
    }

    /**
     * Get cart items
     */
    protected function getCartItems()
    {
        return CartItem::where('session_id', $this->getSessionId())
            ->with('product')
            ->get();
    }
}
