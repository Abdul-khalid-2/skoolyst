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
                                <div class="cart-item-price-current">Rs. {{ number_format($item['price'] * $item['quantity']) }}</div>
                                <div class="cart-item-price-original">Rs. {{ number_format($item['price']) }} each</div>
                            </div>
                            
                            <div class="cart-item-quantity">
                                <button class="quantity-btn decrease-quantity" data-item-id="{{ $item['id'] }}">-</button>
                                <input type="number" class="quantity-input" value="{{ $item['quantity'] }}" min="1" max="10" data-item-id="{{ $item['id'] }}">
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
                        <a href="{{ route('website.checkout') }}" class="checkout-btn">
                            <i class="fas fa-lock me-2"></i>
                            Proceed to Checkout
                        </a>
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

    // Update quantity
    function updateQuantity(itemId, newQuantity) {
        if (newQuantity < 1) newQuantity = 1;
        if (newQuantity > 10) newQuantity = 10;

        // Here you would typically make an AJAX call to update the cart
        console.log(`Updating item ${itemId} quantity to ${newQuantity}`);
        
        // For demo purposes, we'll just update the UI
        const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
        if (input) {
            input.value = newQuantity;
        }
        
        updateCartTotals();
    }

    // Remove item from cart
    function removeItem(itemId) {
        // Here you would typically make an AJAX call to remove the item
        console.log(`Removing item ${itemId} from cart`);
        
        // For demo purposes, we'll just remove from UI
        const item = document.querySelector(`[data-cart-item-id="${itemId}"]`);
        if (item) {
            item.remove();
        }
        
        updateCartTotals();
        
        // Show empty cart if no items left
        if (document.querySelectorAll('.cart-item').length === 0) {
            showEmptyCart();
        }
    }

    // Update cart totals (this would typically come from server)
    function updateCartTotals() {
        // In a real application, this would be calculated on the server
        // For demo, we'll just update with some values
        const subtotal = 2500; // This would be calculated from cart items
        const shipping = 100;
        const tax = 250;
        const discount = 150;
        const total = subtotal + shipping + tax - discount;

        document.getElementById('subtotal').textContent = `Rs. ${subtotal.toLocaleString()}`;
        document.getElementById('shipping').textContent = `Rs. ${shipping.toLocaleString()}`;
        document.getElementById('tax').textContent = `Rs. ${tax.toLocaleString()}`;
        document.getElementById('discount').textContent = `- Rs. ${discount.toLocaleString()}`;
        document.getElementById('total').textContent = `Rs. ${total.toLocaleString()}`;
    }

    // Show empty cart state
    function showEmptyCart() {
        const cartContainer = document.querySelector('.cart-container');
        cartContainer.innerHTML = `
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
        `;
    }

    // Event listeners
    decreaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
            const currentValue = parseInt(input.value);
            updateQuantity(itemId, currentValue - 1);
        });
    });

    increaseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            const input = document.querySelector(`.quantity-input[data-item-id="${itemId}"]`);
            const currentValue = parseInt(input.value);
            updateQuantity(itemId, currentValue + 1);
        });
    });

    quantityInputs.forEach(input => {
        input.addEventListener('change', function() {
            const itemId = this.getAttribute('data-item-id');
            const newQuantity = parseInt(this.value);
            updateQuantity(itemId, newQuantity);
        });
    });

    removeButtons.forEach(button => {
        button.addEventListener('click', function() {
            const itemId = this.getAttribute('data-item-id');
            if (confirm('Are you sure you want to remove this item from your cart?')) {
                removeItem(itemId);
            }
        });
    });

    // Toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#38b000' : type === 'error' ? '#dc3545' : '#4361ee'};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            z-index: 10000;
        `;
        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            document.body.removeChild(toast);
        }, 3000);
    }
});
</script>
@endpush