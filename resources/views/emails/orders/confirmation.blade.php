<x-mail::message>
# Order Confirmation

Thank you for your order, {{ $order->customer_name }}!

We have received your order **{{ $order->order_number }}** and it is now being processed.

## Order Details

<x-mail::table>
| Product | Quantity | Price |
|:--------|:--------:| -----:|
@foreach($order->items as $item)
| {{ $item->product_name }} | {{ $item->quantity }} | €{{ number_format($item->total_price, 2) }} |
@endforeach
</x-mail::table>

### Order Summary

- **Subtotal:** €{{ number_format($order->subtotal_amount, 2) }}
- **Tax (19%):** €{{ number_format($order->tax_amount, 2) }}
- **Shipping:** €{{ number_format($order->shipping_amount, 2) }}
- **Total:** **€{{ number_format($order->total_amount, 2) }}**

### Shipping Address

{{ $order->shipping_address }}<br>
{{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_zip }}<br>
{{ $order->shipping_country }}

### Payment Method

{{ ucfirst($order->payment_method) }}<br>
**Status:** {{ ucfirst($order->payment_status) }}

<x-mail::button :url="$orderUrl" color="primary">
View Order Details
</x-mail::button>

If you have any questions about your order, please don't hesitate to contact us.

Thanks,<br>
{{ config('app.name') }} Team
</x-mail::message>
