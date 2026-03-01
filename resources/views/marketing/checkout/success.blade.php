@extends('marketing.layouts.master')

@section('title', 'Order Confirmed - Dayaa Shop')
@section('meta_description', 'Your order has been confirmed. Thank you for your purchase.')

@section('content')

<!-- Success Section -->
<section class="section-padding fix">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="success-icon mb-4">
                    <i class="fa-solid fa-check-circle" style="font-size: 100px; color: #28a745;"></i>
                </div>
                <h1 class="mb-3">Order Confirmed!</h1>
                <p class="lead mb-4">Thank you for your purchase. Your order has been successfully placed.</p>

                <div class="card mb-4 text-start">
                    <div class="card-header">
                        <h4 class="mb-0">Order Details</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>Order Number:</strong><br>
                                {{ $order->order_number }}
                            </div>
                            <div class="col-sm-6">
                                <strong>Order Date:</strong><br>
                                {{ $order->created_at->format('F d, Y H:i') }}
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6">
                                <strong>Customer Name:</strong><br>
                                {{ $order->customer_name }}
                            </div>
                            <div class="col-sm-6">
                                <strong>Email:</strong><br>
                                {{ $order->customer_email }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <strong>Billing Address:</strong><br>
                                {{ $order->billing_address }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4 text-start">
                    <div class="card-header">
                        <h4 class="mb-0">Order Items</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Quantity</th>
                                        <th>Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ $item->formatted_unit_price }}</td>
                                        <td>{{ $item->formatted_total_price }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                        <td>€{{ number_format($order->subtotal, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Tax:</strong></td>
                                        <td>€{{ number_format($order->tax_amount, 2) }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end"><strong>Shipping:</strong></td>
                                        <td>
                                            @if($order->shipping_amount == 0)
                                            <span class="text-success">FREE</span>
                                            @else
                                            €{{ number_format($order->shipping_amount, 2) }}
                                            @endif
                                        </td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td colspan="3" class="text-end"><h5 class="mb-0">Total:</h5></td>
                                        <td><h5 class="mb-0">{{ $order->formatted_total }}</h5></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="fa-solid fa-info-circle"></i>
                    <strong>What's Next?</strong><br>
                    You will receive an order confirmation email at <strong>{{ $order->customer_email }}</strong> with tracking information once your order ships.
                    @if($order->payment_method == 'bank_transfer')
                    <br><br>
                    Since you selected bank transfer, please complete the payment using the details provided in your confirmation email.
                    @endif
                </div>

                <div class="mt-4">
                    <a href="{{ route('marketing.shop.index') }}" class="pp-theme-btn me-2">
                        Continue Shopping <i class="fa-solid fa-arrow-right"></i>
                    </a>
                    <a href="{{ route('marketing.contact') }}" class="pp-theme-btn pp-style-2">
                        Contact Support
                    </a>
                </div>

                <div class="mt-5 text-muted">
                    <p class="mb-2"><i class="fa-solid fa-headset"></i> Need help? Contact us at <a href="mailto:support@dayaatech.de">support@dayaatech.de</a></p>
                    <p class="mb-0"><i class="fa-solid fa-phone"></i> Or call us at <a href="tel:+491234567890">+49 123 456 7890</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
