<x-app-layout>
    <main class="main-content">
        <section id="product-details" class="page-section">
            <x-page-header>
                <x-slot name="heading">
                    <h2 class="h4 mb-0">{{ $product->name }}</h2>
                    <p class="mb-0 text-muted">Product details and information</p>
                </x-slot>
                <x-slot name="actions">
                    <div class="d-flex gap-2">
                        <x-button href="{{ route('products.edit', $product) }}" variant="primary">
                            <i class="fas fa-edit me-2"></i>Edit
                        </x-button>
                        <x-button href="{{ route('products.index') }}" variant="secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back
                        </x-button>
                    </div>
                </x-slot>
            </x-page-header>

            <div class="row">
                <!-- Product Images -->
                <div class="col-lg-4">
                    <x-card class="mb-4">
                        <div class="card-body text-center">
                            @if($product->main_image_url)
                                <img src="{{ asset('website/'. $product->main_image_url) }}" alt="{{ $product->name }}" 
                                    class="img-fluid rounded mb-3" style="max-height: 300px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" style="height: 300px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            @if($product->hasGalleryImages())
                                <div class="row g-2">
                                    @foreach($product->image_gallery as $imagePath)
                                        <div class="col-4">
                                            <img src="{{ asset('website/'. $imagePath) }}" 
                                                class="img-thumbnail" style="height: 80px; object-fit: cover;">
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </x-card>

                    <!-- Quick Actions -->
                    <x-card>
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <!-- Stock Update Form -->
                            <form action="{{ route('products.update-stock', $product) }}" method="POST" class="mb-3">
                                @csrf
                                <div class="input-group">
                                    <input type="number" name="stock_quantity" class="form-control" placeholder="Stock quantity" min="0" required>
                                    <select name="action" class="form-control" style="max-width: 120px;">
                                        <option value="set">Set</option>
                                        <option value="add">Add</option>
                                        <option value="subtract">Subtract</option>
                                    </select>
                                    <x-button type="submit" variant="primary">Update</x-button>
                                </div>
                            </form>

                            <!-- Status Badges -->
                            <div class="text-center">
                                @if($product->is_featured)
                                    <x-badge variant="warning" class="mb-2">Featured</x-badge>
                                @endif
                                <x-badge variant="{{ $product->is_active ? 'success' : 'secondary' }}" class="mb-2">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </x-badge>
                                @if(!$product->is_approved)
                                    <x-badge variant="warning" class="mb-2">Pending Approval</x-badge>
                                @endif
                            </div>
                        </div>
                    </x-card>
                </div>

                <!-- Product Details -->
                <div class="col-lg-8">
                    <!-- Basic Information -->
                    <x-card class="mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Basic Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%">SKU:</th>
                                            <td><code>{{ $product->sku }}</code></td>
                                        </tr>
                                        <tr>
                                            <th>Product Type:</th>
                                            <td>
                                                <x-badge variant="info" class="text-capitalize">{{ $product->product_type }}</x-badge>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Shop:</th>
                                            <td>{{ $product->shop->name }}</td>
                                        </tr>
                                        <tr>
                                            <th>Category:</th>
                                            <td>{{ $product->category->name }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="40%">Brand:</th>
                                            <td>{{ $product->brand ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Material:</th>
                                            <td>{{ $product->material ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Color:</th>
                                            <td>{{ $product->color ?? 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <th>Size:</th>
                                            <td>{{ $product->size ?? 'N/A' }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($product->short_description)
                                <div class="mt-3">
                                    <strong>Short Description:</strong>
                                    <p class="mb-0 text-muted">{{ $product->short_description }}</p>
                                </div>
                            @endif

                            @if($product->description)
                                <div class="mt-3">
                                    <strong>Description:</strong>
                                    <p class="mb-0 text-muted">{{ $product->description }}</p>
                                </div>
                            @endif
                        </div>
                    </x-card>

                    <!-- Pricing & Inventory -->
                    <div class="row">
                        <div class="col-md-6">
                            <x-card class="mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Pricing</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="50%">Base Price:</th>
                                            <td class="text-end"><strong>Rs. {{ number_format($product->base_price, 2) }}</strong></td>
                                        </tr>
                                        @if($product->sale_price)
                                            <tr>
                                                <th>Sale Price:</th>
                                                <td class="text-end text-success"><strong>Rs. {{ number_format($product->sale_price, 2) }}</strong></td>
                                            </tr>
                                            <tr>
                                                <th>Discount:</th>
                                                <td class="text-end text-danger"><strong>{{ $product->discount_percentage }}% OFF</strong></td>
                                            </tr>
                                        @endif
                                        @if($product->cost_price)
                                            <tr>
                                                <th>Cost Price:</th>
                                                <td class="text-end">Rs. {{ number_format($product->cost_price, 2) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Profit Margin:</th>
                                                <td class="text-end text-success">{{ number_format($product->profit_margin, 2) }}%</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </x-card>
                        </div>

                        <div class="col-md-6">
                            <x-card class="mb-4">
                                <div class="card-header">
                                    <h5 class="mb-0">Inventory</h5>
                                </div>
                                <div class="card-body">
                                    <table class="table table-sm table-borderless">
                                        <tr>
                                            <th width="60%">Stock Quantity:</th>
                                            <td class="text-end">
                                                <x-badge variant="{{ $product->is_in_stock ? 'success' : 'danger' }}">
                                                    {{ $product->stock_quantity }}
                                                </x-badge>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Stock Management:</th>
                                            <td class="text-end">
                                                {{ $product->manage_stock ? 'Enabled' : 'Disabled' }}
                                            </td>
                                        </tr>
                                        @if($product->manage_stock)
                                            <tr>
                                                <th>Low Stock Threshold:</th>
                                                <td class="text-end">{{ $product->low_stock_threshold }}</td>
                                            </tr>
                                            <tr>
                                                <th>Stock Status:</th>
                                                <td class="text-end">
                                                    @if($product->isLowStock())
                                                        <x-badge variant="warning">Low Stock</x-badge>
                                                    @elseif($product->is_in_stock)
                                                        <x-badge variant="success">In Stock</x-badge>
                                                    @else
                                                        <x-badge variant="danger">Out of Stock</x-badge>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </x-card>
                        </div>
                    </div>

                    <!-- Product Attributes -->
                    @if($product->attributes)
                        <x-card class="mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Product Attributes</h5>
                            </div>
                            <div class="card-body">
                                @if($product->product_type === 'book')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                @if($product->attributes->book_author)
                                                    <tr>
                                                        <th width="40%">Author:</th>
                                                        <td>{{ $product->attributes->book_author }}</td>
                                                    </tr>
                                                @endif
                                                @if($product->attributes->book_publisher)
                                                    <tr>
                                                        <th>Publisher:</th>
                                                        <td>{{ $product->attributes->book_publisher }}</td>
                                                    </tr>
                                                @endif
                                                @if($product->attributes->book_isbn)
                                                    <tr>
                                                        <th>ISBN:</th>
                                                        <td><code>{{ $product->attributes->book_isbn }}</code></td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                @if($product->attributes->education_board)
                                                    <tr>
                                                        <th width="40%">Board:</th>
                                                        <td>{{ ucfirst($product->attributes->education_board) }}</td>
                                                    </tr>
                                                @endif
                                                @if($product->attributes->class_level)
                                                    <tr>
                                                        <th>Class Level:</th>
                                                        <td>{{ $product->attributes->class_level }}</td>
                                                    </tr>
                                                @endif
                                                @if($product->attributes->subject)
                                                    <tr>
                                                        <th>Subject:</th>
                                                        <td>{{ $product->attributes->subject }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                @elseif($product->product_type === 'copy')
                                    <div class="row">
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                @if($product->attributes->copy_pages)
                                                    <tr>
                                                        <th width="40%">Pages:</th>
                                                        <td>{{ $product->attributes->copy_pages }}</td>
                                                    </tr>
                                                @endif
                                                @if($product->attributes->copy_quality)
                                                    <tr>
                                                        <th>Quality:</th>
                                                        <td>{{ ucfirst($product->attributes->copy_quality) }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                        <div class="col-md-6">
                                            <table class="table table-sm table-borderless">
                                                @if($product->attributes->copy_size)
                                                    <tr>
                                                        <th width="40%">Size:</th>
                                                        <td>{{ strtoupper($product->attributes->copy_size) }}</td>
                                                    </tr>
                                                @endif
                                                @if($product->attributes->copy_type)
                                                    <tr>
                                                        <th>Type:</th>
                                                        <td>{{ ucfirst(str_replace('_', ' ', $product->attributes->copy_type)) }}</td>
                                                    </tr>
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </x-card>
                    @endif
                </div>
            </div>
        </section>
    </main>
</x-app-layout>
