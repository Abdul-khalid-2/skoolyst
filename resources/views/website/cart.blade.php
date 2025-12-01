@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== CART PAGE STYLES ==================== */
    .cart-section {
        padding: 80px 0;
        background: #f8f9fa;
        min-height: 70vh;
    }

    .cart-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        align-items: start;
    }

    .cart-header {
        margin-bottom: 2rem;
    }

    .cart-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .cart-subtitle {
        color: #666;
        font-size: 1.1rem;
    }

    /* Cart Items */
    .cart-items {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto auto;
        gap: 1.5rem;
        padding: 1.5rem 0;
        border-bottom: 1px solid #e0e0e0;
        align-items: center;
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image {
        width: 100px;
        height: 100px;
        border-radius: 10px;
        overflow: hidden;
        background: #f8f9fa;
    }

    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .cart-item-info {
        flex: 1;
    }

    .cart-item-name {
        font-size: 1.2rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .cart-item-shop {
        color: #666;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .cart-item-category {
        color: #4361ee;
        font-size: 0.85rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .cart-item-price {
        text-align: right;
    }

    .cart-item-price-current {
        font-size: 1.3rem;
        font-weight: 700;
        color: #4361ee;
        margin-bottom: 0.5rem;
    }

    .cart-item-price-original {
        font-size: 1rem;
        color: #999;
        text-decoration: line-through;
    }

    .cart-item-quantity {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .quantity-btn {
        width: 35px;
        height: 35px;
        border: 1px solid #e0e0e0;
        background: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1.1rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .quantity-btn:hover {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    .quantity-input {
        width: 50px;
        height: 35px;
        border: 1px solid #e0e0e0;
        border-radius: 6px;
        text-align: center;
        font-size: 1rem;
        font-weight: 600;
    }

    .cart-item-remove {
        background: none;
        border: none;
        color: #dc3545;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 6px;
        transition: all 0.2s ease;
    }

    .cart-item-remove:hover {
        background: #ffe8e8;
    }

    /* Cart Summary */
    .cart-summary {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 2rem;
    }

    .summary-title {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1a1a1a;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.8rem 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .summary-row:last-child {
        border-bottom: none;
    }

    .summary-label {
        color: #666;
        font-size: 1rem;
    }

    .summary-value {
        font-weight: 600;
        color: #1a1a1a;
    }

    .summary-total {
        font-size: 1.3rem;
        font-weight: 700;
        color: #4361ee;
    }

    .summary-actions {
        margin-top: 2rem;
    }

    .checkout-btn {
        width: 100%;
        padding: 1rem 2rem;
        background: #38b000;
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .checkout-btn:hover {
        background: #2f9a00;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(56, 176, 0, 0.3);
    }
    .lock-checkout-btn {

        width: 100%;
        padding: 1rem 2rem;
        background: #a3a3a3;
        color: rgb(0, 0, 0);
        border: none;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .continue-shopping {
        display: block;
        text-align: center;
        margin-top: 1rem;
        color: #4361ee;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .continue-shopping:hover {
        color: #3a56d4;
    }

    /* Empty Cart */
    .empty-cart {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        grid-column: 1 / -1;
    }

    .empty-cart-icon {
        font-size: 4rem;
        color: #e0e0e0;
        margin-bottom: 1.5rem;
    }

    .empty-cart-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: #666;
        margin-bottom: 1rem;
    }

    .empty-cart-description {
        color: #999;
        margin-bottom: 2rem;
        font-size: 1.1rem;
    }

    /* Loading States */
    .loading {
        opacity: 0.6;
        pointer-events: none;
    }

    .quantity-btn:disabled,
    .quantity-input:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Responsive Design */
    @media (max-width: 968px) {
        .cart-container {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .cart-summary {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .cart-item {
            grid-template-columns: 80px 1fr;
            gap: 1rem;
        }

        .cart-item-price,
        .cart-item-quantity {
            grid-column: 1 / -1;
            justify-self: start;
            margin-top: 1rem;
        }

        .cart-item-price {
            text-align: left;
        }

        .cart-item-quantity {
            display: flex;
            align-items: center;
        }
    }

    @media (max-width: 480px) {
        .cart-section {
            padding: 60px 0;
        }

        .cart-items,
        .cart-summary {
            padding: 1.5rem;
        }

        .cart-title {
            font-size: 2rem;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== CART SECTION ==================== -->
<section class="cart-section">
    <div class="container">
        <div class="cart-header">
            <h1 class="cart-title">Shopping Cart</h1>
            <p class="cart-subtitle">Review your items and proceed to checkout</p>
        </div>

        @if($cartItems && count($cartItems) > 0)
            <div class="cart-container">
                <!-- Cart Items -->
                <div class="cart-items">
                    @foreach($cartItems as $item)
                        <div class="cart-item" data-cart-item-id="{{ $item['id'] }}">
                            <div class="cart-item-image">
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                            </div>
                            
                            <div class="cart-item-info">
                                <h3 class="cart-item-name">{{ $item['name'] }}</h3>
                                <p class="cart-item-shop">{{ $item['shop_name'] }}</p>
                                <span class="cart-item-category">{{ $item['category'] }}</span>
                            </div>
                            
                            <div class="cart-item-price">
                                {{-- <div class="cart-item-price-current">Rs. {{ number_format($item['price'] * $item['quantity']) }}</div> --}}
                                <div class="cart-item-price-current" data-item-total="{{ $item['id'] }}">Rs. {{ number_format($item['price'] * $item['quantity']) }}</div>
                                <div class="cart-item-price-original">Rs. {{ number_format($item['price']) }} each</div>
                            </div>
                            
                            <div class="cart-item-quantity">
                                <button class="quantity-btn decrease-quantity" data-item-id="{{ $item['id'] }}">-</button>
                                <input type="number" class="quantity-input" value="{{ $item['quantity'] }}" min="1" max="{{ $item['max_quantity'] ?? 10 }}" data-item-id="{{ $item['id'] }}">
                                <button class="quantity-btn increase-quantity" data-item-id="{{ $item['id'] }}">+</button>
                                <button class="cart-item-remove" data-item-id="{{ $item['id'] }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h3 class="summary-title">Order Summary</h3>
                    
                    <div class="summary-row">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value" id="subtotal">Rs. {{ number_format($subtotal) }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Shipping</span>
                        <span class="summary-value" id="shipping">Rs. {{ number_format($shipping) }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Tax</span>
                        <span class="summary-value" id="tax">Rs. {{ number_format($tax) }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Discount</span>
                        <span class="summary-value" id="discount">- Rs. {{ number_format($discount) }}</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Total</span>
                        <span class="summary-value summary-total" id="total">Rs. {{ number_format($total) }}</span>
                    </div>

                    <div class="summary-actions">
                         @if ($total > 750)
                            <a href="{{ route('website.checkout') }}" class="checkout-btn">
                                <i class="fas fa-lock me-2"></i>
                                Proceed to Checkout
                            </a>
                        @else
                            <button disabled  class="btn  lock-checkout-btn">
                                <i class="fas fa-unlock me-2"></i>
                                Buy items worth more than Rs 750 to proceed to checkout
                            </button>
                        @endif
                        <a href="{{ route('website.stationary.index') }}" class="continue-shopping">
                            <i class="fas fa-arrow-left me-2"></i>
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="empty-cart">
                <div class="empty-cart-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <h3 class="empty-cart-title">Your cart is empty</h3>
                <p class="empty-cart-description">
                    Looks like you haven't added any items to your cart yet.
                </p>
                <a href="{{ route('website.stationary.index') }}" class="btn btn-primary">
                    <i class="fas fa-shopping-bag me-2"></i>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</section>

@endsection

@push('scripts')
<script>


document.addEventListener('DOMContentLoaded', function() {
    // Quantity controls
    const decreaseButtons = document.querySelectorAll('.decrease-quantity');
    const increaseButtons = document.querySelectorAll('.increase-quantity');
    const quantityInputs = document.querySelectorAll('.quantity-input');
    const removeButtons = document.querySelectorAll('.cart-item-remove');

    // Function to disable all buttons for a specific item
    function disableItemButtons(itemId) {
        const buttons = document.querySelectorAll(`
            .decrease-quantity[data-item-id="${itemId}"],
            .increase-quantity[data-item-id="${itemId}"],
            .cart-item-remove[data-item-id="${itemId}"],
            .quantity-input[data-item-id="${itemId}"]
        `);
        
        buttons.forEach(button => {
            button.disabled = true;
            button.style.opacity = '0.6';
            button.style.cursor = 'not-allowed';
        });
    }

    // Function to enable all buttons for a specific item
    function enableItemButtons(itemId) {
        const buttons = document.querySelectorAll(`
            .decrease-quantity[data-item-id="${itemId}"],
            .increase-quantity[data-item-id="${itemId}"],
            .cart-item-remove[data-item-id="${itemId}"],
            .quantity-input[data-item-id="${itemId}"]
        `);
        
        buttons.forEach(button => {
            button.disabled = false;
            button.style.opacity = '1';
            button.style.cursor = 'pointer';
        });
    }

    // Function to disable all cart buttons
    function disableAllCartButtons() {
        const allButtons = document.querySelectorAll(`
            .decrease-quantity,
            .increase-quantity,
            .cart-item-remove,
            .quantity-input
        `);
        
        allButtons.forEach(button => {
            button.disabled = true;
            button.style.opacity = '0.6';
            button.style.cursor = 'not-allowed';
        });
    }

    // Function to enable all cart buttons
    function enableAllCartButtons() {
        const allButtons = document.querySelectorAll(`
            .decrease-quantity,
            .increase-quantity,
            .cart-item-remove,
            .quantity-input
        `);
        
        allButtons.forEach(button => {
            button.disabled = false;
            button.style.opacity = '1';
            button.style.cursor = 'pointer';
        });
    }

    // Update quantity
    function updateQuantity(itemId, newQuantity) {
        if (newQuantity < 1) newQuantity = 1;

        // Disable buttons for this specific item
        disableItemButtons(itemId);

        fetch('/cart/update', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: itemId,
                quantity: newQuantity
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the quantity input value
                const quantityInput = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
                if (quantityInput) {
                    quantityInput.value = newQuantity;
                }

                // Update item total price if it exists
                const itemTotalElement = document.querySelector(`[data-item-total="${itemId}"]`);
                if (itemTotalElement && data.item_total) {
                    itemTotalElement.textContent = `Rs. ${Math.round(data.item_total).toLocaleString()}`;
                }

                // Update UI with new cart data
                updateCartUI(data.cart_data);
                updateCartCount(data.cart_count);
                
                // Show appropriate success message
                showToast('Cart updated successfully', 'success');
                
                // Remove item from DOM if quantity becomes 0
                if (newQuantity === 0) {
                    const item = document.querySelector(`[data-cart-item-id="${itemId}"]`);
                    if (item) {
                        item.remove();
                    }
                    
                    // Show empty cart if no items left
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        showEmptyCart();
                    }
                }
            } else {
                showToast(data.message || 'Failed to update cart', 'error');
                // Reload to get correct state
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while updating cart', 'error');
        })
        .finally(() => {
            // Re-enable buttons regardless of success/error
            enableItemButtons(itemId);
        });
    }

    // Remove item from cart
    function removeItem(itemId) {
        if (confirm('Are you sure you want to remove this item from your cart?')) {
            // Disable buttons for this specific item
            disableItemButtons(itemId);

            fetch('/cart/remove', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    product_id: itemId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const item = document.querySelector(`[data-cart-item-id="${itemId}"]`);
                    if (item) {
                        item.remove();
                    }
                    
                    updateCartUI(data.cart_data);
                    updateCartCount(data.cart_count);
                    showToast('Item removed from cart', 'success');
                    
                    // Show empty cart if no items left
                    if (document.querySelectorAll('.cart-item').length === 0) {
                        showEmptyCart();
                    }
                } else {
                    showToast(data.message || 'Failed to remove item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('An error occurred while removing item', 'error');
            })
            .finally(() => {
                // Re-enable buttons for remaining items
                enableAllCartButtons();
            });
        }
    }

    // Update cart UI with new totals
    function updateCartUI(cartData) {
        if (document.getElementById('subtotal')) {
            document.getElementById('subtotal').textContent = `Rs. ${Math.round(cartData.subtotal).toLocaleString()}`;
        }
        if (document.getElementById('shipping')) {
            document.getElementById('shipping').textContent = `Rs. ${Math.round(cartData.shipping).toLocaleString()}`;
        }
        if (document.getElementById('tax')) {
            document.getElementById('tax').textContent = `Rs. ${Math.round(cartData.tax).toLocaleString()}`;
        }
        if (document.getElementById('discount')) {
            document.getElementById('discount').textContent = `- Rs. ${Math.round(cartData.discount).toLocaleString()}`;
        }
        if (document.getElementById('total')) {
            document.getElementById('total').textContent = `Rs. ${Math.round(cartData.total).toLocaleString()}`;
        }
    }

    // Update cart count in header
    function updateCartCount(count) {
        const cartBadge = document.querySelector('.cart-badge, .cart-count');
        const cartCountElement = document.getElementById('cart-count');

        if (cartBadge) {
            if (count > 0) {
                cartBadge.textContent = count > 99 ? '99+' : count;
                cartBadge.style.display = 'flex';
            } else {
                cartBadge.style.display = 'none';
            }
        }

        if (cartCountElement) {
            cartCountElement.textContent = count;
        }

        // Update global cart count variable
        if (typeof window.updateCartCount === 'function') {
            window.updateCartCount(count);
        }
    }

    // Show empty cart state
    function showEmptyCart() {
        const cartContainer = document.querySelector('.cart-container');
        if (cartContainer) {
            cartContainer.innerHTML = `
                <div class="empty-cart">
                    <div class="empty-cart-icon">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <h3 class="empty-cart-title">Your cart is empty</h3>
                    <p class="empty-cart-description">
                        Looks like you haven't added any items to your cart yet.
                    </p>
                    <a href="/stationary" class="btn btn-primary">
                        <i class="fas fa-shopping-bag me-2"></i>
                        Start Shopping
                    </a>
                </div>
            `;
        }
    }

    // Event listeners
    decreaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
            const currentValue = parseInt(input.value);
            
            // Prevent multiple rapid clicks
            if (!this.disabled) {
                updateQuantity(itemId, currentValue - 1);
            }
        });
    });

    increaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
            const currentValue = parseInt(input.value);
            const maxQuantity = parseInt(input.getAttribute('max')) || 10;
            
            // Prevent multiple rapid clicks
            if (!this.disabled) {
                if (currentValue < maxQuantity) {
                    updateQuantity(itemId, currentValue + 1);
                } else {
                    showToast(`Maximum quantity limit reached (${maxQuantity} items)`, 'warning');
                }
            }
        });
    });

    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const itemId = this.getAttribute('data-item-id');
            const newQuantity = parseInt(this.value);
            const maxQuantity = parseInt(this.getAttribute('max')) || 10;
            
            // Prevent multiple rapid changes
            if (!this.disabled) {
                if (newQuantity > maxQuantity) {
                    this.value = maxQuantity;
                    showToast(`Maximum quantity limit is ${maxQuantity} items`, 'warning');
                    return;
                }
                
                if (newQuantity >= 1) {
                    updateQuantity(itemId, newQuantity);
                } else {
                    this.value = 1;
                }
            }
        });
    });

    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            
            // Prevent multiple rapid clicks
            if (!this.disabled) {
                removeItem(itemId);
            }
        });
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        const existingToasts = document.querySelectorAll('.toast');
        existingToasts.forEach(toast => toast.remove());

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#38b000' : type === 'error' ? '#dc3545' : type === 'warning' ? '#ff6b35' : '#4361ee'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 10000;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);

        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (toast.parentNode) {
                    document.body.removeChild(toast);
                }
            }, 300);
        }, 3000);
    }
});



</script>

@endpush
