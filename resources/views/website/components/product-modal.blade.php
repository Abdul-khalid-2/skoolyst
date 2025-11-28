<!-- ==================== PRODUCT DETAILS MODAL ==================== -->
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
                        <!-- Category -->
                        <div id="detailsProductCategory" class="modal-product-category"></div>
                        
                        <h4 id="detailsProductName" class="modal-product-name"></h4>
                        
                        <!-- Shop Name -->
                        <div id="detailsProductShop" class="modal-product-shop"></div>
                        
                        <div id="detailsProductPrice" class="modal-product-price"></div>
                        
                    </div>
                    <div class="col">
                        <div class="modal-info-left">
                            <!-- Stock Status -->
                            <div id="detailsStockStatus" class="modal-stock-status"></div>
                        </div>
                        
                        <div class="modal-info-right">
                            <!-- Product Attributes -->
                            <div id="detailsProductAttributes" class="modal-attributes"></div>
                        </div>
                        
                        <!-- Description -->
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
                <button type="button" class="btn btn-secondary" id="continueShopping">
                    Continue Shopping
                </button>
                <button type="button" class="btn btn-success" id="addToCartFromModal">
                    <i class="fas fa-shopping-cart me-2"></i> Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>