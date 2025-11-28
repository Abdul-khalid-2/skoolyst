class ProductModal {
    constructor() {
        this.modal = null;
        this.currentProduct = null;
        this.init();
    }

    init() {
        this.createModal();
        this.bindEvents();
    }

    createModal() {
        // Check if modal already exists
        if (document.getElementById('productDetailsModal')) {
            this.modal = document.getElementById('productDetailsModal');
            return;
        }

        // Create modal dynamically
        const modalHTML = `
            <div class="modal-overlay" id="productDetailsModal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Product Details</h3>
                        <button class="modal-close" id="closeDetailsModal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="modal-product">
                            <div class="modal-product-image">
                                <img id="detailsProductImage" src="" alt="Product Image">
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div id="detailsProductCategory" class="modal-product-category"></div>
                                    <h4 id="detailsProductName" class="modal-product-name"></h4>
                                    <div id="detailsProductShop" class="modal-product-shop"></div>
                                    <div id="detailsProductPrice" class="modal-product-price"></div>
                                </div>
                                <div class="col">
                                    <div class="modal-info-left">
                                        <div id="detailsStockStatus" class="modal-stock-status"></div>
                                    </div>
                                    <div class="modal-info-right">
                                        <div id="detailsProductAttributes" class="modal-attributes"></div>
                                    </div>
                                    <div class="modal-description">
                                        <h5>Description</h5>
                                        <p id="detailsProductDescription"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="quantity-selector">
                            <span class="quantity-label">Quantity:</span>
                            <div class="quantity-controls">
                                <button type="button" class="quantity-btn" id="decreaseQuantity">-</button>
                                <input type="number" id="quantity" name="quantity" value="1" min="1" max="10" class="quantity-input">
                                <button type="button" class="quantity-btn" id="increaseQuantity">+</button>
                            </div>
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="btn btn-secondary" id="continueShopping">Continue Shopping</button>
                            <button type="button" class="btn btn-success" id="addToCartFromModal">
                                <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHTML);
        this.modal = document.getElementById('productDetailsModal');
    }

    bindEvents() {
        // Close modal events
        document.getElementById('closeDetailsModal').addEventListener('click', () => this.close());
        document.getElementById('continueShopping').addEventListener('click', () => this.close());

        // Quantity controls
        document.getElementById('decreaseQuantity').addEventListener('click', () => this.decreaseQuantity());
        document.getElementById('increaseQuantity').addEventListener('click', () => this.increaseQuantity());

        // Add to cart
        document.getElementById('addToCartFromModal').addEventListener('click', () => this.addToCart());

        // Close modal when clicking outside
        this.modal.addEventListener('click', (e) => {
            if (e.target === this.modal) {
                this.close();
            }
        });

        // Bind favorite buttons globally
        this.bindFavoriteButtons();
    }

    bindFavoriteButtons() {
        // Delegate favorite button clicks
        document.addEventListener('click', (e) => {
            if (e.target.closest('.favorite-btn')) {
                this.handleFavoriteClick(e.target.closest('.favorite-btn'));
            }
        });
    }

    show(productData, productImage = null) {
        this.currentProduct = productData;
        this.populateModal(productData, productImage);
        this.modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    close() {
        this.modal.classList.remove('active');
        this.currentProduct = null;
        document.body.style.overflow = '';
    }

    populateModal(productData, productImage = null) {
        // Set product image
        if (productImage) {
            document.getElementById('detailsProductImage').src = productImage;
        }

        // Product name
        document.getElementById('detailsProductName').textContent = productData.name;

        // Category
        const categoryElement = document.getElementById('detailsProductCategory');
        if (productData.category && productData.category.name) {
            categoryElement.textContent = productData.category.name;
            categoryElement.style.display = 'block';
        } else {
            categoryElement.style.display = 'none';
        }

        // Shop
        const shopElement = document.getElementById('detailsProductShop');
        if (productData.shop && productData.shop.name) {
            shopElement.innerHTML = `<i class="fas fa-store"></i> ${productData.shop.name}`;
            shopElement.style.display = 'flex';
        } else {
            shopElement.style.display = 'none';
        }

        // Price
        const priceElement = document.getElementById('detailsProductPrice');
        const currentPrice = productData.sale_price || productData.base_price;
        const originalPrice = productData.base_price;

        if (productData.sale_price && productData.sale_price < productData.base_price) {
            priceElement.innerHTML = `
                Rs. ${parseInt(currentPrice).toLocaleString()}
                <span style="font-size: 1rem; color: #999; text-decoration: line-through; margin-left: 0.5rem;">
                    Rs. ${parseInt(originalPrice).toLocaleString()}
                </span>
            `;
        } else {
            priceElement.textContent = `Rs. ${parseInt(currentPrice).toLocaleString()}`;
        }

        // Stock Status
        const stockStatusElement = document.getElementById('detailsStockStatus');
        const isInStock = productData.is_in_stock;
        const isLowStock = productData.stock_quantity <= 10 && productData.stock_quantity > 0;
        const stockStatus = isInStock ? (isLowStock ? 'Low Stock' : 'In Stock') : 'Out of Stock';
        stockStatusElement.textContent = stockStatus;
        stockStatusElement.className = `modal-stock-status ${isInStock ? (isLowStock ? 'low-stock' : 'in-stock') : 'out-of-stock'}`;

        // Attributes
        const attributesElement = document.getElementById('detailsProductAttributes');
        attributesElement.innerHTML = '';

        // Handle different attribute structures
        const attributes = productData.productAttributes || productData.attributes;
        if (attributes) {
            if (attributes.education_board) {
                this.createAttributeTag(attributesElement, attributes.education_board);
            }
            if (attributes.class_level) {
                this.createAttributeTag(attributesElement, attributes.class_level);
            }
            if (attributes.subject) {
                this.createAttributeTag(attributesElement, attributes.subject);
            }
        }

        if (productData.brand) {
            this.createAttributeTag(attributesElement, productData.brand);
        }

        // Description
        const descriptionElement = document.getElementById('detailsProductDescription');
        descriptionElement.textContent = productData.description || productData.short_description || 'No description available.';

        // Reset quantity
        document.getElementById('quantity').value = 1;

        // Enable/disable add to cart button based on stock
        document.getElementById('addToCartFromModal').disabled = !isInStock;
    }

    createAttributeTag(container, text) {
        const tag = document.createElement('span');
        tag.className = 'modal-attribute-tag';
        tag.textContent = typeof text === 'string' ? text.charAt(0).toUpperCase() + text.slice(1) : text;
        container.appendChild(tag);
    }

    decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }

    increaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue < 10) {
            quantityInput.value = currentValue + 1;
        }
    }

    addToCart() {
        if (!this.currentProduct || document.getElementById('addToCartFromModal').disabled) return;

        const quantity = parseInt(document.getElementById('quantity').value);

        // AJAX call to add to cart
        fetch('/cart/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                product_id: this.currentProduct.uuid || this.currentProduct.id,
                quantity: quantity
            })
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.showToast(`${this.currentProduct.name} added to cart successfully!`, 'success');
                    this.close();
                    // Update cart count in header
                    this.updateCartCount(data.cart_count);
                } else {
                    this.showToast(data.message || 'Failed to add product to cart', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.showToast('An error occurred while adding to cart', 'error');
            });
    }

    // Add this method to update cart count
    updateCartCount(count) {
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

    handleFavoriteClick(button) {
        const productCard = button.closest('.product-card');
        const productName = productCard.querySelector('.product-name').textContent;
        const isActive = button.classList.contains('active');

        // Toggle favorite state
        button.classList.toggle('active');

        // AJAX call to update favorites
        // You can implement this based on your backend

        if (!isActive) {
            this.showToast(`${productName} added to favorites!`, 'success');
        } else {
            this.showToast(`${productName} removed from favorites!`, 'info');
        }
    }

    showToast(message, type = 'info') {
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
            transform: translateX(100%);
            transition: transform 0.3s ease;
        `;
        toast.textContent = message;

        document.body.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);

        // Remove after 3 seconds
        setTimeout(() => {
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(toast);
            }, 300);
        }, 3000);
    }
}

// Initialize modal globally
let productModal = null;

document.addEventListener('DOMContentLoaded', function () {
    productModal = new ProductModal();

    // Bind quick view buttons globally
    document.addEventListener('click', function (e) {
        if (e.target.closest('.quick-view-btn') || e.target.closest('.view-details-btn')) {
            e.preventDefault();
            const button = e.target.closest('.quick-view-btn') || e.target.closest('.view-details-btn');
            const productData = JSON.parse(button.getAttribute('data-product-data'));
            const productCard = button.closest('.product-card');
            const productImage = productCard.querySelector('.product-image img').src;

            productModal.show(productData, productImage);
        }
    });
});