@extends('website.layout.app')

@push('styles')
<link rel="stylesheet" href="{{ asset('assets/css/global.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/navigation.css') }}">
<style>
    /* ==================== CHECKOUT PAGE STYLES ==================== */
    .checkout-section {
        padding: 80px 0;
        background: #f8f9fa;
        min-height: 70vh;
    }

    .checkout-container {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 2rem;
        align-items: start;
    }

    .checkout-header {
        margin-bottom: 2rem;
    }

    .checkout-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #1a1a1a;
        margin-bottom: 0.5rem;
    }

    .checkout-subtitle {
        color: #666;
        font-size: 1.1rem;
    }

    /* Checkout Steps */
    .checkout-steps {
        display: flex;
        align-items: center;
        margin-bottom: 3rem;
        background: white;
        padding: 1.5rem 2rem;
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
    }

    .step {
        display: flex;
        align-items: center;
        flex: 1;
    }

    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f8f9fa;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        margin-right: 1rem;
        border: 2px solid #e0e0e0;
    }

    .step.active .step-number {
        background: #4361ee;
        color: white;
        border-color: #4361ee;
    }

    .step.completed .step-number {
        background: #38b000;
        color: white;
        border-color: #38b000;
    }

    .step-info {
        flex: 1;
    }

    .step-title {
        font-weight: 600;
        color: #1a1a1a;
        margin-bottom: 0.2rem;
    }

    .step-description {
        font-size: 0.85rem;
        color: #666;
    }

    .step-divider {
        width: 40px;
        height: 2px;
        background: #e0e0e0;
        margin: 0 1rem;
    }

    /* Checkout Forms */
    .checkout-forms {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        margin-bottom: 2rem;
    }

    .form-section {
        margin-bottom: 2.5rem;
    }

    .form-section:last-child {
        margin-bottom: 0;
    }

    .form-section-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 1.5rem;
        color: #1a1a1a;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #4361ee;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #333;
    }

    .form-input,
    .form-select,
    .form-textarea {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 100px;
    }

    /* Payment Methods */
    .payment-methods {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .payment-method {
        display: flex;
        align-items: center;
        padding: 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .payment-method:hover {
        border-color: #4361ee;
    }

    .payment-method.selected {
        border-color: #4361ee;
        background: #f8f9ff;
    }

    .payment-method input {
        margin-right: 1rem;
    }

    .payment-method-icon {
        font-size: 1.5rem;
        margin-right: 1rem;
        color: #4361ee;
    }

    .payment-method-info {
        flex: 1;
    }

    .payment-method-name {
        font-weight: 600;
        margin-bottom: 0.2rem;
    }

    .payment-method-description {
        font-size: 0.85rem;
        color: #666;
    }

    /* Order Summary */
    .order-summary {
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

    .order-items {
        margin-bottom: 1.5rem;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #e0e0e0;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .order-item-info {
        flex: 1;
    }

    .order-item-name {
        font-weight: 600;
        margin-bottom: 0.2rem;
    }

    .order-item-details {
        font-size: 0.85rem;
        color: #666;
    }

    .order-item-price {
        font-weight: 600;
        color: #4361ee;
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

    .checkout-actions {
        margin-top: 2rem;
    }

    .place-order-btn {
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

    .place-order-btn:hover {
        background: #2f9a00;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(56, 176, 0, 0.3);
    }

    .back-to-cart {
        display: block;
        text-align: center;
        margin-top: 1rem;
        color: #4361ee;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .back-to-cart:hover {
        color: #3a56d4;
    }

    /* Responsive Design */
    @media (max-width: 968px) {
        .checkout-container {
            grid-template-columns: 1fr;
            gap: 1.5rem;
        }

        .order-summary {
            position: static;
        }
    }

    @media (max-width: 768px) {
        .checkout-steps {
            flex-direction: column;
            gap: 1rem;
            text-align: center;
        }

        .step-divider {
            display: none;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .step {
            justify-content: center;
        }
    }

    @media (max-width: 480px) {
        .checkout-section {
            padding: 60px 0;
        }

        .checkout-forms,
        .order-summary {
            padding: 1.5rem;
        }

        .checkout-title {
            font-size: 2rem;
        }
    }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">
@endpush

@section('content')

<!-- ==================== CHECKOUT SECTION ==================== -->
<section class="checkout-section">
    <div class="container">
        <div class="checkout-header">
            <h1 class="checkout-title">Checkout</h1>
            <p class="checkout-subtitle">Complete your order with secure payment</p>
        </div>

        <!-- Checkout Steps -->
        <div class="checkout-steps">
            <div class="step completed">
                <div class="step-number">1</div>
                <div class="step-info">
                    <div class="step-title">Shopping Cart</div>
                    <div class="step-description">Review your items</div>
                </div>
            </div>
            <div class="step-divider"></div>
            <div class="step active">
                <div class="step-number">2</div>
                <div class="step-info">
                    <div class="step-title">Checkout</div>
                    <div class="step-description">Shipping & Payment</div>
                </div>
            </div>
            <div class="step-divider"></div>
            <div class="step">
                <div class="step-number">3</div>
                <div class="step-info">
                    <div class="step-title">Confirmation</div>
                    <div class="step-description">Order complete</div>
                </div>
            </div>
        </div>

        <div class="checkout-container">
            <!-- Checkout Forms -->
            <div class="checkout-forms">
                <form id="checkoutForm">
                    <!-- Shipping Information -->
                    <div class="form-section">
                        <h3 class="form-section-title">Shipping Information</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label class="form-label">First Name *</label>
                                <input type="text" class="form-input" name="first_name" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Last Name *</label>
                                <input type="text" class="form-input" name="last_name" required>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Email Address *</label>
                                <input type="email" class="form-input" name="email" required>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Phone Number *</label>
                                <input type="tel" class="form-input" name="phone" required>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Address *</label>
                                <input type="text" class="form-input" name="address" placeholder="Street address" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">City *</label>
                                <input type="text" class="form-input" name="city" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">State *</label>
                                <input type="text" class="form-input" name="state" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">ZIP Code *</label>
                                <input type="text" class="form-input" name="zip_code" required>
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Delivery Instructions (Optional)</label>
                                <textarea class="form-textarea" name="delivery_instructions" placeholder="Any special delivery instructions..."></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="form-section">
                        <h3 class="form-section-title">Payment Method</h3>
                        <div class="payment-methods">
                            <label class="payment-method selected">
                                <input type="radio" name="payment_method" value="credit_card" checked>
                                <div class="payment-method-icon">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="payment-method-info">
                                    <div class="payment-method-name">Credit/Debit Card</div>
                                    <div class="payment-method-description">Pay securely with your card</div>
                                </div>
                            </label>
                            
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="cash_on_delivery">
                                <div class="payment-method-icon">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="payment-method-info">
                                    <div class="payment-method-name">Cash on Delivery</div>
                                    <div class="payment-method-description">Pay when you receive your order</div>
                                </div>
                            </label>
                            
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="digital_wallet">
                                <div class="payment-method-icon">
                                    <i class="fas fa-wallet"></i>
                                </div>
                                <div class="payment-method-info">
                                    <div class="payment-method-name">Digital Wallet</div>
                                    <div class="payment-method-description">Pay with eSewa, Khalti, etc.</div>
                                </div>
                            </label>
                        </div>

                        <!-- Credit Card Form (shown when credit card is selected) -->
                        <div id="creditCardForm" class="form-grid">
                            <div class="form-group full-width">
                                <label class="form-label">Card Number *</label>
                                <input type="text" class="form-input" name="card_number" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Expiry Date *</label>
                                <input type="text" class="form-input" name="expiry_date" placeholder="MM/YY">
                            </div>
                            <div class="form-group">
                                <label class="form-label">CVV *</label>
                                <input type="text" class="form-input" name="cvv" placeholder="123">
                            </div>
                            <div class="form-group full-width">
                                <label class="form-label">Cardholder Name *</label>
                                <input type="text" class="form-input" name="cardholder_name" placeholder="John Doe">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="order-summary">
                <h3 class="summary-title">Order Summary</h3>
                
                <div class="order-items">
                    @foreach($orderItems as $item)
                        <div class="order-item">
                            <div class="order-item-info">
                                <div class="order-item-name">{{ $item['name'] }}</div>
                                <div class="order-item-details">Qty: {{ $item['quantity'] }} • {{ $item['shop_name'] }}</div>
                            </div>
                            <div class="order-item-price">Rs. {{ number_format($item['price'] * $item['quantity']) }}</div>
                        </div>
                    @endforeach
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Subtotal</span>
                    <span class="summary-value">Rs. {{ number_format($subtotal) }}</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Shipping</span>
                    <span class="summary-value">Rs. {{ number_format($shipping) }}</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Tax</span>
                    <span class="summary-value">Rs. {{ number_format($tax) }}</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Discount</span>
                    <span class="summary-value">- Rs. {{ number_format($discount) }}</span>
                </div>
                
                <div class="summary-row">
                    <span class="summary-label">Total</span>
                    <span class="summary-value summary-total">Rs. {{ number_format($total) }}</span>
                </div>

                <div class="checkout-actions">
                    <button type="submit" form="checkoutForm" class="place-order-btn">
                        <i class="fas fa-lock me-2"></i>
                        Place Order
                    </button>
                    <a href="{{ route('website.cart') }}" class="back-to-cart">
                        <i class="fas fa-arrow-left me-2"></i>
                        Back to Cart
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Payment method selection
    const paymentMethods = document.querySelectorAll('.payment-method');
    const creditCardForm = document.getElementById('creditCardForm');

    paymentMethods.forEach(method => {
        method.addEventListener('click', function() {
            // Remove selected class from all methods
            paymentMethods.forEach(m => m.classList.remove('selected'));
            // Add selected class to clicked method
            this.classList.add('selected');
            // Check the radio button
            const radio = this.querySelector('input[type="radio"]');
            radio.checked = true;

            // Show/hide credit card form
            if (radio.value === 'credit_card') {
                creditCardForm.style.display = 'grid';
            } else {
                creditCardForm.style.display = 'none';
            }
        });
    });

    // Form submission
    const checkoutForm = document.getElementById('checkoutForm');
    checkoutForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate form
        if (validateForm()) {
            // Show loading state
            const submitBtn = document.querySelector('.place-order-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
            submitBtn.disabled = true;

            // Here you would typically make an AJAX call to process the order
            setTimeout(() => {
                // Simulate successful order processing
                showToast('Order placed successfully!', 'success');
                
                // Redirect to order confirmation page
                setTimeout(() => {
                    window.location.href = "{{ route('website.order.confirmation') }}";
                }, 2000);
            }, 2000);
        }
    });

    // Form validation
    function validateForm() {
        const requiredFields = checkoutForm.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.style.borderColor = '#dc3545';
                isValid = false;
            } else {
                field.style.borderColor = '#e0e0e0';
            }
        });

        // Validate email
        const emailField = checkoutForm.querySelector('input[type="email"]');
        if (emailField.value && !isValidEmail(emailField.value)) {
            emailField.style.borderColor = '#dc3545';
            showToast('Please enter a valid email address', 'error');
            isValid = false;
        }

        // Validate phone
        const phoneField = checkoutForm.querySelector('input[type="tel"]');
        if (phoneField.value && !isValidPhone(phoneField.value)) {
            phoneField.style.borderColor = '#dc3545';
            showToast('Please enter a valid phone number', 'error');
            isValid = false;
        }

        return isValid;
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidPhone(phone) {
        const phoneRegex = /^[0-9]{10}$/;
        return phoneRegex.test(phone.replace(/\D/g, ''));
    }

    // Real-time validation
    const formInputs = checkoutForm.querySelectorAll('input, textarea');
    formInputs.forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim()) {
                this.style.borderColor = '#e0e0e0';
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