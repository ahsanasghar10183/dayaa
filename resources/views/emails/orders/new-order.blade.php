<x-mail::message>
# New Order Received

A new order has been placed in the Dayaa shop!

## Order Information

- **Order Number:** {{ $order->order_number }}
- **Order Date:** {{ $order->created_at->format('M d, Y \a\t g:i A') }}
- **Order Total:** **€{{ number_format($order->total_amount, 2) }}**
- **Payment Status:** {{ ucfirst($order->payment_status) }}
- **Payment Method:** {{ ucfirst($order->payment_method) }}

## Customer Information

- **Name:** {{ $order->customer_name }}
- **Email:** {{ $order->customer_email }}
- **Phone:** {{ $order->customer_phone }}

## Shipping Address

{{ $order->shipping_address }}<br>
{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
{{ $order->shipping_country }}

## Order Items

<x-mail::table>
| Product | SKU | Quantity | Price |
|:--------|:----|:--------:| -----:|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->product_sku }} | {{ $item->quantity }} | €{{ number_format($item->total_price, 2) }} |
@endforeach
</x-mail::table>

### Order Summary

- **Subtotal:** €{{ number_format($order->subtotal_amount, 2) }}
- **Tax (19%):** €{{ number_format($order->tax_amount, 2) }}
- **Shipping:** €{{ number_format($order->shipping_amount, 2) }}
- **Total:** **€{{ number_format($order->total_amount, 2) }}**

<x-mail::button :url="$adminUrl" color="success">
View Order in Admin Panel
</x-mail::button>

Please process this order as soon as possible.

Thanks,<br>
{{ config('app.name') }} System
</x-mail::message>
