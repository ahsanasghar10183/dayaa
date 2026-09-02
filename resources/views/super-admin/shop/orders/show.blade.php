<x-super-admin-sidebar-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 min-w-0 truncate">{{ __('admin.shop.order_details_prefix') }} {{ $order->order_number }}</h2>
            <a href="{{ route('super-admin.shop.orders.index') }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-200 border border-transparent rounded-lg font-semibold text-gray-700 hover:bg-gray-300 transition flex-shrink-0">
                <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                {{ __('admin.shop.back_to_orders') }}
            </a>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">{{ __('admin.shop.order_items') }}</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.shop.product_col') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.shop.sku_col') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.shop.price_col') }}</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.shop.quantity_col') }}</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('admin.shop.total_col') }}</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($order->items as $item)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->product_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ $item->product_sku }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">€{{ number_format($item->unit_price, 2) }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ $item->quantity }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <div class="text-sm font-semibold text-gray-900">€{{ number_format($item->total_price, 2) }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Order Totals -->
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('admin.shop.subtotal') }}</span>
                                <span class="font-medium text-gray-900">€{{ number_format($order->subtotal_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('admin.shop.tax') }}</span>
                                <span class="font-medium text-gray-900">€{{ number_format($order->tax_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ __('admin.shop.shipping') }}</span>
                                <span class="font-medium text-gray-900">€{{ number_format($order->shipping_amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-base font-semibold pt-2 border-t border-gray-200">
                                <span class="text-gray-900">{{ __('admin.shop.total_col') }}</span>
                                <span class="text-gray-900">€{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.shop.customer_information') }}</h3>
                    <div class="space-y-6">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">{{ __('admin.shop.contact_details') }}</h4>
                            <div class="space-y-1">
                                <p class="text-sm font-semibold text-gray-900">{{ $order->customer_name }}</p>
                                <p class="text-sm text-gray-600"><i class="fa-solid fa-envelope text-gray-400 mr-2"></i>{{ $order->customer_email }}</p>
                                @if($order->customer_phone)
                                <p class="text-sm text-gray-600"><i class="fa-solid fa-phone text-gray-400 mr-2"></i>{{ $order->customer_phone }}</p>
                                @endif
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">{{ __('admin.shop.billing_address') }}</h4>
                                <div class="text-sm text-gray-700 whitespace-pre-line break-words bg-gray-50 p-3 rounded-lg">{{ $order->billing_address }}</div>
                            </div>
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">{{ __('admin.shop.shipping_address') }}</h4>
                                <div class="text-sm text-gray-700 whitespace-pre-line break-words bg-gray-50 p-3 rounded-lg">{{ $order->shipping_address }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Notes -->
                @if($order->notes)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.shop.order_notes') }}</h3>
                    <p class="text-sm text-gray-700 whitespace-pre-line break-words">{{ $order->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Order Status Management -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.shop.order_status_label') }}</h3>
                    <form action="{{ route('super-admin.shop.orders.update-status', $order) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="order_status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.shop.order_status_label') }}</label>
                                <select name="order_status" id="order_status" class="select">
                                    <option value="pending" {{ $order->order_status === 'pending' ? 'selected' : '' }}>{{ __('admin.super_admin.status_pending') }}</option>
                                    <option value="processing" {{ $order->order_status === 'processing' ? 'selected' : '' }}>{{ __('admin.shop.status_processing') }}</option>
                                    <option value="shipped" {{ $order->order_status === 'shipped' ? 'selected' : '' }}>{{ __('admin.shop.status_shipped') }}</option>
                                    <option value="completed" {{ $order->order_status === 'completed' ? 'selected' : '' }}>{{ __('admin.shop.status_completed') }}</option>
                                    <option value="cancelled" {{ $order->order_status === 'cancelled' ? 'selected' : '' }}>{{ __('admin.shop.status_cancelled') }}</option>
                                </select>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                {{ __('admin.super_admin.update_status') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Payment Status Management -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.shop.payment_status_label') }}</h3>
                    <form action="{{ route('super-admin.shop.orders.update-payment', $order) }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">{{ __('admin.shop.payment_status_label') }}</label>
                                <select name="payment_status" id="payment_status" class="select">
                                    <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>{{ __('admin.super_admin.status_pending') }}</option>
                                    <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>{{ __('admin.shop.status_paid') }}</option>
                                    <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>{{ __('admin.shop.status_failed') }}</option>
                                    <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>{{ __('admin.shop.status_refunded') }}</option>
                                </select>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg">
                                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">{{ __('admin.shop.payment_method_label') }}</p>
                                <p class="text-sm font-medium text-gray-900">{{ ucfirst($order->payment_method) }}</p>
                            </div>
                            <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                {{ __('admin.shop.update_payment') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Order Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.shop.order_information') }}</h3>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">{{ __('admin.shop.order_number') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ $order->order_number }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">{{ __('admin.shop.order_date') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ $order->created_at->translatedFormat('M d, Y \a\t g:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 uppercase tracking-wide">{{ __('admin.shop.last_updated') }}</p>
                            <p class="text-sm font-medium text-gray-900">{{ $order->updated_at->translatedFormat('M d, Y \a\t g:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Delete Order -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ __('admin.shop.danger_zone') }}</h3>
                    <form action="{{ route('super-admin.shop.orders.destroy', $order) }}" method="POST" onsubmit="return confirm('{{ __('admin.shop.confirm_delete_order_permanent') }}');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-2 border border-red-300 text-red-700 rounded-lg hover:bg-red-50 transition">
                            {{ __('admin.shop.delete_order') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-super-admin-sidebar-layout>
