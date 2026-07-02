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
                            <div class="modal-product-gallery">
                                <div class="modal-product-image">
                                    <img id="detailsProductImage" src="" alt="Product Image">
                                </div>
                                <div class="modal-product-thumbnails" id="detailsProductThumbnails"></div>
                            </div>
                            <div class="product-info-simple">
                                <div id="detailsProductCategory" class="modal-product-category"></div>
                                <h4 id="detailsProductName" class="modal-product-name"></h4>
                                <div id="detailsProductPrice" class="modal-product-price"></div>
                                <div class="modal-description">
                                    <span class="desc-text" id="detailsProductDescription"></span>
                                    <span class="desc-toggle" id="descToggleBtn" style="display:none;">See more</span>
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

        // Thumbnail clicks (event delegation for dynamic content)
        this.modal.addEventListener('click', (e) => {
            const thumbnailItem = e.target.closest('.thumbnail-item');
            if (thumbnailItem) {
                const imageSrc = thumbnailItem.querySelector('img').src;
                this.changeMainImage(imageSrc, thumbnailItem);
            }
        });

        // Description toggle (event delegation for dynamic content)
        this.modal.addEventListener('click', (e) => {
            if (e.target.id === 'descToggleBtn') {
                this.toggleDescription();
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

    getFavorites() {
        try {
            return JSON.parse(localStorage.getItem('skoolyst_favorites')) || [];
        } catch (e) {
            return [];
        }
    }

    saveFavorites(favorites) {
        localStorage.setItem('skoolyst_favorites', JSON.stringify(favorites));
    }

    isFavorite(productId) {
        return this.getFavorites().includes(productId);
    }

    toggleFavorite(productId) {
        let favorites = this.getFavorites();
        const index = favorites.indexOf(productId);
        let isNowFavorite;

        if (index > -1) {
            favorites.splice(index, 1);
            isNowFavorite = false;
        } else {
            favorites.push(productId);
            isNowFavorite = true;
        }

        this.saveFavorites(favorites);
        return isNowFavorite;
    }

    restoreFavoritesUI() {
        const favorites = this.getFavorites();
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            const productId = btn.getAttribute('data-product-id');
            if (favorites.includes(productId)) {
                btn.classList.add('active');
            } else {
                btn.classList.remove('active');
            }
        });
    }

    buildImageUrl(path) {
        if (!path) return '';
        // If it's already a full URL (starts with http), return as-is
        if (path.startsWith('http://') || path.startsWith('https://')) {
            return path;
        }
        const base = window.assetBaseUrl || '';
        // Avoid double slashes
        return `${base.replace(/\/$/, '')}/${path.replace(/^\//, '')}`;
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
        // Build combined image list: [main_image_url, ...image_gallery], removing duplicates and empty values
        let imageList = [];
        const mainImage = productData.main_image_url || productImage || '';
        const gallery = productData.image_gallery || [];

        // Add main image first (with base URL)
        if (mainImage) {
            imageList.push(this.buildImageUrl(mainImage));
        }

        // Add gallery images (removing duplicates and applying base URL)
        gallery.forEach(img => {
            const fullUrl = this.buildImageUrl(img);
            if (fullUrl && fullUrl !== (imageList[0] || '') && !imageList.includes(fullUrl)) {
                imageList.push(fullUrl);
            }
        });

        // Set main image
        const mainImageUrl = imageList.length > 0 ? imageList[0] : '';
        document.getElementById('detailsProductImage').src = mainImageUrl;

        // Render thumbnails (max 3, excluding the main image)
        const thumbnailsContainer = document.getElementById('detailsProductThumbnails');
        thumbnailsContainer.innerHTML = '';

        const remainingImages = imageList.slice(1, 4); // Get max 3 images after main
        
        if (remainingImages.length > 0) {
            remainingImages.forEach(imageSrc => {
                const thumbnailDiv = document.createElement('div');
                thumbnailDiv.className = 'thumbnail-item';
                thumbnailDiv.innerHTML = `<img src="${imageSrc}" alt="Thumbnail">`;
                thumbnailsContainer.appendChild(thumbnailDiv);
            });
            thumbnailsContainer.style.display = 'flex';
        } else {
            thumbnailsContainer.style.display = 'none';
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

        // Price
        const priceElement = document.getElementById('detailsProductPrice');
        const currentPrice = productData.sale_price || productData.base_price;
        const originalPrice = productData.base_price;

        if (productData.sale_price && productData.sale_price < productData.base_price) {
            priceElement.innerHTML = `
                <span class="current-price">Rs. ${parseInt(currentPrice).toLocaleString()}</span>
                <span class="original-price">Rs. ${parseInt(originalPrice).toLocaleString()}</span>
            `;
        } else {
            priceElement.textContent = `Rs. ${parseInt(currentPrice).toLocaleString()}`;
        }

        // Description
        const descriptionElement = document.getElementById('detailsProductDescription');
        const descriptionText = productData.short_description || productData.description || 'No description available.';
        descriptionElement.textContent = descriptionText;
        descriptionElement.classList.remove('expanded');

        // Check if description needs "See more" toggle (roughly > 120 characters)
        const toggleBtn = document.getElementById('descToggleBtn');
        if (descriptionText.length > 120) {
            toggleBtn.style.display = 'inline-block';
            toggleBtn.textContent = 'See more';
        } else {
            toggleBtn.style.display = 'none';
        }

        // Reset quantity
        document.getElementById('quantity').value = 1;

        // Enable/disable add to cart button based on stock
        const isInStock = productData.is_in_stock;
        document.getElementById('addToCartFromModal').disabled = !isInStock;
    }

    decreaseQuantity() {
        const quantityInput = document.getElementById('quantity');
        const currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    }

    changeMainImage(newSrc, clickedThumbnail) {
        const mainImage = document.getElementById('detailsProductImage');
        
        // Fade out animation
        mainImage.style.opacity = '0';
        
        // Change image after fade out
        setTimeout(() => {
            mainImage.src = newSrc;
            mainImage.style.opacity = '1';
        }, 200);

        // Update active class on thumbnails
        document.querySelectorAll('.thumbnail-item').forEach(thumb => {
            thumb.classList.remove('active');
        });
        clickedThumbnail.classList.add('active');
    }

    toggleDescription() {
        const descText = document.getElementById('detailsProductDescription');
        const toggleBtn = document.getElementById('descToggleBtn');

        descText.classList.toggle('expanded');

        if (descText.classList.contains('expanded')) {
            toggleBtn.textContent = 'See less';
        } else {
            toggleBtn.textContent = 'See more';
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

    updateCartCount(count) {
        // Update every cart badge in the page (mobile + desktop navbar both have .cart-badge)
        document.querySelectorAll('.cart-badge').forEach(function (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.removeProperty('display');
            } else {
                badge.style.display = 'none';
            }
        });

        const cartCountEl = document.getElementById('cart-count');
        if (cartCountEl) {
            cartCountEl.textContent = count;
        }
    }

    handleFavoriteClick(button) {
        const productId = button.getAttribute('data-product-id');
        const productCard = button.closest('.product-card');
        const productName = productCard ? productCard.querySelector('.product-name').textContent : 'Product';

        const isNowFavorite = this.toggleFavorite(productId);

        button.classList.toggle('active', isNowFavorite);

        if (isNowFavorite) {
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
    productModal.restoreFavoritesUI();

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