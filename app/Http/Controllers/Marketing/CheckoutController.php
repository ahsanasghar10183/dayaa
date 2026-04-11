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

        $subtotal = $cartItems->sum('subtotal');

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
        // Validation rules for Shopify-style checkout
        $rules = [
            'customer_email' => 'required|email|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'apartment' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'country' => 'required|string|max:2',
            'postal_code' => 'required|string|max:20',
            'customer_phone' => 'nullable|string|max:50',
        ];

        // Add shipping address validation if not same as billing
        if (!$request->has('same_as_billing')) {
            $rules['shipping_first_name'] = 'required|string|max:255';
            $rules['shipping_last_name'] = 'required|string|max:255';
            $rules['shipping_company'] = 'nullable|string|max:255';
            $rules['shipping_address'] = 'required|string|max:500';
            $rules['shipping_apartment'] = 'nullable|string|max:255';
            $rules['shipping_city'] = 'required|string|max:255';
            $rules['shipping_country'] = 'required|string|max:2';
            $rules['shipping_postal_code'] = 'required|string|max:20';
        }

        $request->validate($rules);

        $cartItems = $this->getCartItems();

        if ($cartItems->isEmpty()) {
            return redirect()->route('marketing.cart.index')->with('error', 'Your cart is empty');
        }

        // Calculate totals and validate stock
        $subtotal = 0;
        foreach ($cartItems as $item) {
            // Check if variation or simple product
            if ($item->variation) {
                // Validate variation stock
                if (!$item->variation->isInStock() || $item->variation->quantity < $item->quantity) {
                    return back()->with('error', "Product variation '{$item->product->name} - {$item->variation_name}' is no longer available in requested quantity");
                }
                $subtotal += $item->variation->effective_price * $item->quantity;
            } else {
                // Validate simple product stock
                if (!$item->product->isInStock() || $item->product->quantity < $item->quantity) {
                    return back()->with('error', "Product '{$item->product->name}' is no longer available in requested quantity");
                }
                $subtotal += $item->product->price * $item->quantity;
            }
        }

        $tax = $subtotal * 0.19;
        $shipping = $subtotal > 100 ? 0 : 9.99;
        $total = $subtotal + $tax + $shipping;

        try {
            DB::beginTransaction();

            // Format billing address
            $billingAddress = $this->formatAddress(
                $request->first_name,
                $request->last_name,
                $request->company,
                $request->address,
                $request->apartment,
                $request->city,
                $request->country,
                $request->postal_code
            );

            // Format shipping address
            if ($request->has('same_as_billing')) {
                $shippingAddress = $billingAddress;
            } else {
                $shippingAddress = $this->formatAddress(
                    $request->shipping_first_name,
                    $request->shipping_last_name,
                    $request->shipping_company,
                    $request->shipping_address,
                    $request->shipping_apartment,
                    $request->shipping_city,
                    $request->shipping_country,
                    $request->shipping_postal_code
                );
            }

            // Create order (always use Stripe as payment method)
            $order = Order::create([
                'order_number' => Order::generateOrderNumber(),
                'customer_name' => $request->first_name . ' ' . $request->last_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'billing_address' => $billingAddress,
                'shipping_address' => $shippingAddress,
                'subtotal' => $subtotal,
                'tax_amount' => $tax,
                'shipping_amount' => $shipping,
                'discount_amount' => 0,
                'total_amount' => $total,
                'payment_method' => 'stripe', // Always Stripe
                'payment_status' => 'pending',
                'order_status' => 'pending',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            // Create order items and decrease product/variation quantities
            foreach ($cartItems as $cartItem) {
                // Determine product name, SKU, and price
                $productName = $cartItem->product->name;
                if ($cartItem->variation_name) {
                    $productName .= ' - ' . $cartItem->variation_name;
                }

                $productSku = $cartItem->variation
                    ? ($cartItem->variation->sku ?? $cartItem->product->sku)
                    : $cartItem->product->sku;

                $unitPrice = $cartItem->variation
                    ? $cartItem->variation->effective_price
                    : $cartItem->product->price;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'product_variation_id' => $cartItem->product_variation_id,
                    'variation_name' => $cartItem->variation_name,
                    'product_name' => $productName,
                    'product_sku' => $productSku,
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $unitPrice,
                    'total_price' => $unitPrice * $cartItem->quantity,
                ]);

                // Decrease quantity (variation or product)
                if ($cartItem->variation) {
                    $cartItem->variation->decreaseQuantity($cartItem->quantity);
                } else {
                    $cartItem->product->decreaseQuantity($cartItem->quantity);
                }
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

            // Always redirect to Stripe Checkout
            return $this->createStripeCheckoutSession($order);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()
                ->withInput()
                ->with('error', 'Failed to process order. Please try again.');
        }
    }

    /**
     * Format address from individual fields
     */
    protected function formatAddress($firstName, $lastName, $company, $address, $apartment, $city, $country, $postalCode)
    {
        $addressParts = [
            $firstName . ' ' . $lastName,
        ];

        if ($company) {
            $addressParts[] = $company;
        }

        $addressParts[] = $address;

        if ($apartment) {
            $addressParts[] = $apartment;
        }

        $addressParts[] = $postalCode . ' ' . $city;
        $addressParts[] = $country;

        return implode("\n", $addressParts);
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
            ->with('product', 'variation')
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
