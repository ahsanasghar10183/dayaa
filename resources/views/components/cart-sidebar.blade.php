<!-- Cart Sidebar -->
<div id="cartSidebar" class="cart-sidebar">
    <div class="cart-sidebar-overlay" onclick="closeCartSidebar()"></div>
    <div class="cart-sidebar-content">
        <!-- Header -->
        <div class="cart-sidebar-header">
            <h3>{{ __('marketing.cart.title') }}</h3>
            <button type="button" class="cart-sidebar-close" onclick="closeCartSidebar()">
                <i class="fa-solid fa-times"></i>
            </button>
        </div>

        <!-- Cart Items -->
        <div class="cart-sidebar-body">
            <div id="cartItemsContainer">
                <!-- Items will be loaded via JavaScript -->
                <div class="text-center py-5">
                    <i class="fa-solid fa-spinner fa-spin fa-2x text-primary"></i>
                </div>
            </div>

            <!-- Related Products Section -->
            <div class="related-products-section mt-4" id="relatedProductsSection" style="display: none;">
                <h4 class="mb-3">{{ __('marketing.shop.related_products') }}</h4>
                <div class="related-products-carousel-wrapper position-relative">
                    <button class="carousel-nav-btn prev-btn" onclick="scrollRelatedProducts('prev')">
                        <i class="fa-solid fa-chevron-left"></i>
                    </button>
                    <div class="related-products-carousel" id="relatedProductsCarousel">
                        <!-- Related products will be loaded via JavaScript -->
                    </div>
                    <button class="carousel-nav-btn next-btn" onclick="scrollRelatedProducts('next')">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Footer with Totals and Checkout -->
        <div class="cart-sidebar-footer">
            <div class="cart-totals" id="cartTotals">
                <!-- Totals will be loaded via JavaScript -->
            </div>
            <a href="{{ route('marketing.checkout.index') }}" class="pp-theme-btn w-100 text-center">
                {{ __('marketing.cart.proceed_checkout') }}
            </a>
            <button onclick="closeCartSidebar()" class="pp-theme-btn-bordered w-100 text-center mt-2" style="cursor: pointer; background: white; border: 2px solid #0F69F3; color: #0F69F3; border-radius: 100px; padding: 19px 24px; font-weight: 600; transition: all 0.3s ease;">
                <i class="fa-solid fa-shopping-bag"></i> {{ __('marketing.cart.continue_shopping') }}
            </button>
        </div>
    </div>
</div>

<style>
.cart-sidebar {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 99999;
    display: none;
    pointer-events: none;
}

.cart-sidebar.active {
    display: block;
    pointer-events: all;
}

.cart-sidebar-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.cart-sidebar.active .cart-sidebar-overlay {
    opacity: 1;
}

.cart-sidebar-content {
    position: absolute;
    top: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    max-width: 450px;
    background: #ffffff;
    box-shadow: -5px 0 25px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
    transform: translateX(100%);
    transition: transform 0.3s ease;
}

.cart-sidebar.active .cart-sidebar-content {
    transform: translateX(0);
}

.cart-sidebar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #e5e5e5;
    background: linear-gradient(135deg, #0F69F3 0%, #170AB5 100%);
    color: white;
}

.cart-sidebar-header h3 {
    margin: 0;
    font-size: 20px;
    font-weight: 600;
    color: white;
}

.cart-sidebar-close {
    background: transparent;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: white;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: background 0.2s ease;
}

.cart-sidebar-close:hover {
    background: rgba(255, 255, 255, 0.2);
}

.cart-sidebar-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px;
}

.cart-item {
    display: flex;
    gap: 15px;
    padding: 15px 0;
    border-bottom: 1px solid #e5e5e5;
}

.cart-item:last-child {
    border-bottom: none;
}

.cart-item-image {
    width: 80px;
    height: 80px;
    flex-shrink: 0;
    border-radius: 8px;
    overflow: hidden;
    background: #f5f5f5;
}

.cart-item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.cart-item-details {
    flex: 1;
    min-width: 0;
}

.cart-item-name {
    font-size: 14px;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 5px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.cart-item-price {
    font-size: 16px;
    font-weight: 700;
    color: #0F69F3;
    margin-bottom: 8px;
}

.cart-item-quantity {
    display: flex;
    align-items: center;
    gap: 10px;
}

.quantity-btn {
    width: 28px;
    height: 28px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
}

.quantity-btn:hover {
    border-color: #0F69F3;
    color: #0F69F3;
}

.quantity-value {
    font-size: 14px;
    font-weight: 600;
    min-width: 30px;
    text-align: center;
}

.cart-item-remove {
    background: transparent;
    border: none;
    color: #ff4444;
    cursor: pointer;
    padding: 5px;
    font-size: 18px;
    transition: color 0.2s ease;
}

.cart-item-remove:hover {
    color: #cc0000;
}

.cart-empty {
    text-align: center;
    padding: 40px 20px;
    color: #666;
}

.cart-empty i {
    font-size: 64px;
    color: #ddd;
    margin-bottom: 15px;
}

/* Related Products Carousel */
.related-products-section {
    padding-top: 20px;
    border-top: 2px solid #e5e5e5;
}

.related-products-section h4 {
    font-size: 16px;
    font-weight: 600;
    color: #1a1a2e;
}

.related-products-carousel-wrapper {
    position: relative;
}

.related-products-carousel {
    display: flex;
    gap: 15px;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding: 10px 0;
    -ms-overflow-style: none;
    scrollbar-width: none;
}

.related-products-carousel::-webkit-scrollbar {
    display: none;
}

.related-product-card {
    flex: 0 0 160px;
    background: white;
    border: 1px solid #e5e5e5;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.3s ease;
    cursor: pointer;
    text-decoration: none;
    color: inherit;
    display: block;
}

.related-product-card:hover {
    border-color: #0F69F3;
    box-shadow: 0 4px 12px rgba(15, 105, 243, 0.15);
    transform: translateY(-2px);
}

.related-product-image {
    width: 100%;
    height: 120px;
    overflow: hidden;
    background: #f5f5f5;
}

.related-product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.related-product-info {
    padding: 10px;
}

.related-product-name {
    font-size: 13px;
    font-weight: 600;
    color: #1a1a2e;
    margin-bottom: 5px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    line-height: 1.3;
}

.related-product-price {
    font-size: 14px;
    font-weight: 700;
    color: #0F69F3;
}

.carousel-nav-btn {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: white;
    border: 1px solid #ddd;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: all 0.2s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.carousel-nav-btn:hover {
    background: linear-gradient(135deg, #0F69F3 0%, #170AB5 100%);
    color: white;
    border-color: #0F69F3;
}

.carousel-nav-btn.prev-btn {
    left: -10px;
}

.carousel-nav-btn.next-btn {
    right: -10px;
}

/* Cart Footer */
.cart-sidebar-footer {
    padding: 20px;
    border-top: 1px solid #e5e5e5;
    background: #f9f9f9;
}

.cart-totals {
    margin-bottom: 15px;
}

.cart-total-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
    font-size: 14px;
    color: #333 !important;
}

.cart-total-row.grand-total {
    font-size: 18px;
    font-weight: 700;
    color: #1a1a2e !important;
    padding-top: 12px;
    border-top: 2px solid #ddd;
    margin-top: 8px;
}

.cart-total-row .label {
    font-weight: 500;
    color: #1a1a2e !important;
    background: none !important;
    -webkit-background-clip: unset !important;
    -webkit-text-fill-color: #1a1a2e !important;
    background-clip: unset !important;
}

.cart-total-row .value {
    font-weight: 700;
    color: #1a1a2e !important;
    background: none !important;
    -webkit-background-clip: unset !important;
    -webkit-text-fill-color: #1a1a2e !important;
    background-clip: unset !important;
}

.cart-total-row.grand-total .label {
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
}

.cart-total-row.grand-total .value {
    color: #000000 !important;
    -webkit-text-fill-color: #000000 !important;
}

/* Responsive */
@media (max-width: 576px) {
    .cart-sidebar-content {
        max-width: 100%;
    }

    .related-product-card {
        flex: 0 0 140px;
    }
}
</style>

<script>
function openCartSidebar() {
    const sidebar = document.getElementById('cartSidebar');
    sidebar.classList.add('active');
    document.body.style.overflow = 'hidden';
    loadCartData();
}

function closeCartSidebar() {
    const sidebar = document.getElementById('cartSidebar');
    sidebar.classList.remove('active');
    document.body.style.overflow = '';
}

function loadCartData() {
    fetch('{{ route("marketing.cart.data") }}')
        .then(response => response.json())
        .then(data => {
            renderCartItems(data.items);
            renderCartTotals(data.totals);
            if (data.relatedProducts && data.relatedProducts.length > 0) {
                renderRelatedProducts(data.relatedProducts);
            }
        })
        .catch(error => {
            console.error('Error loading cart:', error);
            document.getElementById('cartItemsContainer').innerHTML = `
                <div class="cart-empty">
                    <i class="fa-solid fa-exclamation-triangle"></i>
                    <p>Error loading cart. Please refresh.</p>
                </div>
            `;
        });
}

function renderCartItems(items) {
    const container = document.getElementById('cartItemsContainer');

    if (!items || items.length === 0) {
        container.innerHTML = `
            <div class="cart-empty">
                <i class="fa-solid fa-shopping-cart"></i>
                <p>{{ __('marketing.cart.empty_cart') }}</p>
                <p class="text-muted">{{ __('marketing.cart.empty_cart_text') }}</p>
                <a href="{{ route('marketing.shop.index') }}" class="pp-theme-btn mt-3">
                    {{ __('marketing.cart.browse_products') }}
                </a>
            </div>
        `;
        return;
    }

    container.innerHTML = items.map(item => `
        <div class="cart-item" data-item-id="${item.id}">
            <div class="cart-item-image">
                <img src="${item.image}" alt="${item.name}">
            </div>
            <div class="cart-item-details">
                <div class="cart-item-name">${item.name}</div>
                <div class="cart-item-price">${item.price}</div>
                <div class="cart-item-quantity">
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity - 1})">
                        <i class="fa-solid fa-minus"></i>
                    </button>
                    <span class="quantity-value">${item.quantity}</span>
                    <button class="quantity-btn" onclick="updateQuantity(${item.id}, ${item.quantity + 1})">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                    <button class="cart-item-remove" onclick="removeFromCart(${item.id})" title="Remove">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `).join('');
}

function renderCartTotals(totals) {
    const container = document.getElementById('cartTotals');
    container.innerHTML = `
        <div class="cart-total-row">
            <span class="label">{{ __('marketing.cart.subtotal') }}:</span>
            <span class="value">${totals.subtotal}</span>
        </div>
        <div class="cart-total-row">
            <span class="label">{{ __('marketing.cart.tax_percentage') }}:</span>
            <span class="value">${totals.tax}</span>
        </div>
        <div class="cart-total-row">
            <span class="label">{{ __('marketing.cart.shipping') }}:</span>
            <span class="value">${totals.shipping}</span>
        </div>
        <div class="cart-total-row grand-total">
            <span class="label">{{ __('marketing.cart.grand_total') }}:</span>
            <span class="value">${totals.total}</span>
        </div>
    `;
}

function renderRelatedProducts(products) {
    const section = document.getElementById('relatedProductsSection');
    const carousel = document.getElementById('relatedProductsCarousel');

    if (products.length > 0) {
        section.style.display = 'block';
        carousel.innerHTML = products.map(product => `
            <a href="${product.url}" class="related-product-card">
                <div class="related-product-image">
                    <img src="${product.image}" alt="${product.name}">
                </div>
                <div class="related-product-info">
                    <div class="related-product-name">${product.name}</div>
                    <div class="related-product-price">${product.price}</div>
                </div>
            </a>
        `).join('');
    }
}

function updateQuantity(itemId, newQuantity) {
    if (newQuantity < 1) {
        removeFromCart(itemId);
        return;
    }

    fetch(`{{ route('marketing.cart.update', ':itemId') }}`.replace(':itemId', itemId), {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCartData();
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
        }
    })
    .catch(error => console.error('Error updating quantity:', error));
}

function removeFromCart(itemId) {
    if (!confirm('{{ __('marketing.cart.remove_confirm') }}')) {
        return;
    }

    fetch(`{{ route('marketing.cart.remove', ':itemId') }}`.replace(':itemId', itemId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            loadCartData();
            if (typeof updateCartCount === 'function') {
                updateCartCount();
            }
        }
    })
    .catch(error => console.error('Error removing item:', error));
}

function scrollRelatedProducts(direction) {
    const carousel = document.getElementById('relatedProductsCarousel');
    const scrollAmount = 175; // card width + gap

    if (direction === 'prev') {
        carousel.scrollLeft -= scrollAmount;
    } else {
        carousel.scrollLeft += scrollAmount;
    }
}

// Close sidebar on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCartSidebar();
    }
});
</script>
