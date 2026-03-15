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
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    /**
     * Show checkout page
     */
    public function index()
    {
        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('marketing.cart.index')->with('error', 'Your cart is empty');
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
            return redirect()->route('marketing.cart.index')->with('error', 'Your cart is empty');
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

            // Commit the database transaction
            DB::commit();

            // Send order confirmation email to customer
            Mail::to($order->customer_email)->send(new OrderConfirmation($order));

            // Send new order notification to admin
            $adminEmail = config('mail.from.address');
            Mail::to($adminEmail)->send(new NewOrderNotification($order));

            // Process payment based on payment method
            if ($request->payment_method === 'stripe') {
                // For Stripe, create checkout session and redirect
                return $this->createStripeCheckoutSession($order);
            } else {
                // For other payment methods (PayPal, Bank Transfer), just show success page
                return redirect()->route('marketing.checkout.success', $order->order_number)
                    ->with('success', 'Your order has been placed successfully!');
            }

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

    /**
     * Create Stripe Checkout Session
     */
    protected function createStripeCheckoutSession(Order $order)
    {
        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            // Build line items for Stripe
            $lineItems = [];
            foreach ($order->items as $item) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => $item->product_name,
                            'description' => $item->product_sku ? "SKU: {$item->product_sku}" : null,
                        ],
                        'unit_amount' => (int)($item->unit_price * 100), // Convert to cents
                    ],
                    'quantity' => $item->quantity,
                ];
            }

            // Add shipping as a line item if not free
            if ($order->shipping_amount > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Shipping',
                        ],
                        'unit_amount' => (int)($order->shipping_amount * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            // Add tax as a line item
            if ($order->tax_amount > 0) {
                $lineItems[] = [
                    'price_data' => [
                        'currency' => 'eur',
                        'product_data' => [
                            'name' => 'Tax (19%)',
                        ],
                        'unit_amount' => (int)($order->tax_amount * 100),
                    ],
                    'quantity' => 1,
                ];
            }

            // Create Stripe Checkout Session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items' => $lineItems,
                'mode' => 'payment',
                'success_url' => route('marketing.checkout.stripe.success') . '?session_id={CHECKOUT_SESSION_ID}&order=' . $order->order_number,
                'cancel_url' => route('marketing.checkout.stripe.cancel') . '?order=' . $order->order_number,
                'customer_email' => $order->customer_email,
                'metadata' => [
                    'project' => 'Dayaa',
                    'project_type' => 'eCommerce Shop',
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'customer_name' => $order->customer_name,
                    'items_count' => $order->items->count(),
                ],
            ]);

            // Store Stripe session ID in order
            $order->update([
                'stripe_session_id' => $session->id,
            ]);

            // Redirect to Stripe Checkout
            return redirect($session->url);

        } catch (\Exception $e) {
            return redirect()->route('marketing.cart.index')
                ->with('error', 'Payment processing failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle Stripe payment success
     */
    public function stripeSuccess(Request $request)
    {
        $sessionId = $request->get('session_id');
        $orderNumber = $request->get('order');

        if (!$sessionId || !$orderNumber) {
            return redirect()->route('marketing.shop.index')
                ->with('error', 'Invalid payment session');
        }

        try {
            Stripe::setApiKey(config('services.stripe.secret'));

            // Retrieve the Stripe session
            $session = StripeSession::retrieve($sessionId);

            // Find the order
            $order = Order::where('order_number', $orderNumber)->firstOrFail();

            // Update order payment status
            $order->update([
                'payment_status' => 'completed',
                'stripe_payment_intent' => $session->payment_intent,
            ]);

            // Redirect to success page
            return redirect()->route('marketing.checkout.success', $order->order_number)
                ->with('success', 'Payment successful! Your order has been confirmed.');

        } catch (\Exception $e) {
            return redirect()->route('marketing.shop.index')
                ->with('error', 'Payment verification failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle Stripe payment cancellation
     */
    public function stripeCancel(Request $request)
    {
        $orderNumber = $request->get('order');

        if ($orderNumber) {
            $order = Order::where('order_number', $orderNumber)->first();
            if ($order) {
                $order->update([
                    'payment_status' => 'cancelled',
                    'order_status' => 'cancelled',
                ]);
            }
        }

        return redirect()->route('marketing.cart.index')
            ->with('error', 'Payment was cancelled. Your order has been cancelled.');
    }
}
