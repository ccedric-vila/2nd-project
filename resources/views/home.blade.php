@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Welcome to StyleSphere</h1>
    
    <!-- Search Form - Updated with Working Price Range Filter -->
    <div class="container mb-4">
        <form action="{{ route('home') }}" method="GET">
            <!-- Existing Search and Price Range Row -->
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Search products..."
                            value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        @if(request('search') || request('price_range') || request('size') || request('category') || request('types') || request('brand'))
                            <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                                Clear All
                            </a>
                        @endif
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                    <label class="input-group-text" for="size">Price:</label>
                        <select name="price_range" class="form-select" onchange="this.form.submit()">
                            <option value="">All Prices</option>
                            <option value="0-1000" {{ request('price_range') == '0-1000' ? 'selected' : '' }}>$0 - $1000</option>
                            <option value="1001-2000" {{ request('price_range') == '1001-2000' ? 'selected' : '' }}>$1001 - $2000</option>
                            <option value="2001-3000" {{ request('price_range') == '2001-3000' ? 'selected' : '' }}>$2001 - $3000</option>
                            <option value="3001-4000" {{ request('price_range') == '3001-4000' ? 'selected' : '' }}>$3001 - $4000</option>
                            <option value="4001-5000" {{ request('price_range') == '4001-5000' ? 'selected' : '' }}>$4001 - $5000</option>
                            <option value="5001-6000" {{ request('price_range') == '5001-6000' ? 'selected' : '' }}>$5001 - $6000</option>
                            <option value="6001-7000" {{ request('price_range') == '6001-7000' ? 'selected' : '' }}>$6001 - $7000</option>
                            <option value="7001-8000" {{ request('price_range') == '7001-8000' ? 'selected' : '' }}>$7001 - $8000</option>
                            <option value="8001-9000" {{ request('price_range') == '8001-9000' ? 'selected' : '' }}>$8001 - $9000</option>
                            <option value="9001-10000" {{ request('price_range') == '9001-10000' ? 'selected' : '' }}>$9001 - $10000</option>
                        </select>
                        @if(request('price_range'))
                            <a href="{{ route('home', array_merge(request()->except('price_range'), ['page' => null])) }}" class="btn btn-outline-secondary">
                                Clear Price
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- NEW FILTER ROW ADDED HERE -->
            <div class="row g-3 mt-2">
                <!-- Size Filter -->
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="size">Size</label>
                        <select name="size" id="size" class="form-select" onchange="this.form.submit()">
                            <option value="">All Sizes</option>
                            <option value="XS" {{ request('size') == 'XS' ? 'selected' : '' }}>XS</option>
                            <option value="S" {{ request('size') == 'S' ? 'selected' : '' }}>S</option>
                            <option value="M" {{ request('size') == 'M' ? 'selected' : '' }}>M</option>
                            <option value="L" {{ request('size') == 'L' ? 'selected' : '' }}>L</option>
                            <option value="XL" {{ request('size') == 'XL' ? 'selected' : '' }}>XL</option>
                            <option value="XXL" {{ request('size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                        </select>
                        @if(request('size'))
                            <a href="{{ route('home', array_merge(request()->except('size'), ['page' => null])) }}" class="btn btn-outline-secondary">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Category Filter -->
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="category">Category</label>
                        <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <option value="Mens" {{ request('category') == 'Mens' ? 'selected' : '' }}>Mens</option>
                            <option value="Womens" {{ request('category') == 'Womens' ? 'selected' : '' }}>Womens</option>
                            <option value="Kids" {{ request('category') == 'Kids' ? 'selected' : '' }}>Kids</option>
                        </select>
                        @if(request('category'))
                            <a href="{{ route('home', array_merge(request()->except('category'), ['page' => null])) }}" class="btn btn-outline-secondary">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Types Filter -->
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="types">Types</label>
                        <select name="types" id="types" class="form-select" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <option value="T-shirt" {{ request('types') == 'T-shirt' ? 'selected' : '' }}>T-shirt</option>
                            <option value="Polo Shirt" {{ request('types') == 'Polo Shirt' ? 'selected' : '' }}>Polo Shirt</option>
                            <option value="Sweater" {{ request('types') == 'Sweater' ? 'selected' : '' }}>Sweater</option>
                            <option value="Hoodie" {{ request('types') == 'Hoodie' ? 'selected' : '' }}>Hoodie</option>
                            <option value="Jersey" {{ request('types') == 'Jersey' ? 'selected' : '' }}>Jersey</option>
                            <option value="Dress" {{ request('types') == 'Dress' ? 'selected' : '' }}>Dress</option>
                            <option value="Sweatshirt" {{ request('types') == 'Sweatshirt' ? 'selected' : '' }}>Sweatshirt</option>
                            <option value="Pants" {{ request('types') == 'Pants' ? 'selected' : '' }}>Pants</option>
                            <option value="Shorts" {{ request('types') == 'Shorts' ? 'selected' : '' }}>Shorts</option>
                        </select>
                        @if(request('types'))
                            <a href="{{ route('home', array_merge(request()->except('types'), ['page' => null])) }}" class="btn btn-outline-secondary">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
                
                <!-- Brand Filter - Corrected Version -->
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="brand">Brand</label>
                        <select name="brand" id="brand" class="form-select" onchange="this.form.submit()">
                            <option value="">All Brands</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->supplier_id }}" {{ request('brand') == $supplier->supplier_id ? 'selected' : '' }}>
                                    {{ $supplier->brand_name }}
                                </option>
                            @endforeach
                        </select>
                        @if(request('brand'))
                            <a href="{{ route('home', array_merge(request()->except('brand'), ['page' => null])) }}" class="btn btn-outline-secondary">
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </form>

        <!-- Price Sorting Options - Single Toggle Button Version -->
        @if($products->isNotEmpty())
        <div class="mt-3 d-flex justify-content-end">
            @php
                $currentSort = request('sort');
                $nextSort = ($currentSort == 'price_asc') ? 'price_desc' : 'price_asc';
                $buttonText = ($currentSort == 'price_asc') ? 'Low to High' : 'High to Low';
                $buttonIcon = ($currentSort == 'price_asc') ? 'fa-arrow-down' : 'fa-arrow-up';
                $buttonClass = ($currentSort == 'price_asc' || $currentSort == 'price_desc') ? 'btn-primary' : 'btn-outline-primary';
            @endphp
            
            <div class="btn-group" role="group">
                <span class="input-group-text bg-transparent border-0 pe-1 small">Sort by:</span>
                <a href="{{ request()->fullUrlWithQuery(['sort' => $nextSort, 'page' => null]) }}" 
                   class="btn {{ $buttonClass }}">
                    <i class="fas {{ $buttonIcon }}"></i> {{ $buttonText }}
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- Product Display -->
    <div class="row">
        @if(request('search') && $products->isEmpty())
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">No products found for "{{ request('search') }}"</h4>
            </div>
        @elseif($products->isNotEmpty())
            @foreach ($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        @if($product->images->count() > 0)
                        <div id="productCarousel-{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($product->images as $key => $image)
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                        class="d-block w-100 card-img-top" 
                                        style="height: 300px; object-fit: cover;" 
                                        alt="{{ $product->product_name }}">
                                </div>
                                @endforeach
                            </div>
                            @if($product->images->count() > 1)
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel-{{ $product->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel-{{ $product->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            @endif
                        </div>
                        @else
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        @endif

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">{{ $product->product_name }}</h5>
                            <p class="card-text"><strong>Brand:</strong> {{ $product->supplier->brand_name ?? 'No Brand' }}</p>
                            
                            <div class="mb-2">
                                @if($product->category)
                                    <span class="badge bg-primary me-1">{{ $product->category }}</span>
                                @endif
                                
                                @if($product->types)
                                    <span class="badge bg-secondary me-1">{{ $product->types }}</span>
                                @endif
                                
                                @if($product->size)
                                    <span class="badge bg-info">{{ $product->size }}</span>
                                @endif
                            </div>
                            
                            <p class="card-text"><strong>Description:</strong> {{ Str::limit($product->description, 100) }}</p>
                            <p class="card-text"><strong>Price:</strong> ${{ number_format($product->sell_price, 2) }}</p>
                            <p class="card-text"><strong>Stock:</strong> {{ $product->stock }}</p>
                            
                            <div class="mb-3">
                                @if($product->reviews->count() > 0)
                                    <div class="d-flex align-items-center mb-1">
                                        @php
                                            $avgRating = $product->average_rating;
                                            $fullStars = floor($avgRating);
                                            $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                                        @endphp
                                        
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $fullStars)
                                                <i class="fas fa-star text-warning"></i>
                                            @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            @else
                                                <i class="far fa-star text-warning"></i>
                                            @endif
                                        @endfor
                                        <span class="ms-2">{{ number_format($avgRating, 1) }} ({{ $product->reviews_count }} reviews)</span>
                                    </div>
                                    
                                    <div class="mt-2">
                                        @foreach($product->reviews->take(2) as $review)
                                            <div class="review-item mb-2 pb-2 border-bottom">
                                                <div class="d-flex justify-content-between">
                                                    <strong>{{ $review->user->name }}</strong>
                                                    <div>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star{{ $i > $review->rating ? '-empty' : '' }} text-warning"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                @if($review->comment)
                                                    <p class="mb-0 small">"{{ Str::limit($review->comment, 80) }}"</p>
                                                @endif
                                                <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-muted">
                                        <i class="far fa-star"></i> No reviews yet
                                    </div>
                                @endif
                            </div>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                <form action="{{ route('cart.add') }}" method="POST" class="mb-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                    </button>
                                </form>

                                    <form action="{{ route('checkout.handle-single') }}" method="POST" class="mb-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-bolt me-1"></i> Buy Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            @if($product->reviews->count() > 2)
                                <button class="btn btn-outline-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#reviewsModal-{{ $product->id }}">
                                    <i class="fas fa-comment me-1"></i> View All Reviews ({{ $product->reviews->count() }})
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="reviewsModal-{{ $product->id }}" tabindex="-1" aria-labelledby="reviewsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewsModalLabel">Reviews for {{ $product->product_name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                @if($product->reviews->count() > 0)
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center">
                                            <h4 class="me-3">{{ number_format($product->average_rating, 1) }}</h4>
                                            <div>
                                                <div class="d-flex mb-1">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $fullStars)
                                                            <i class="fas fa-star text-warning"></i>
                                                        @elseif($i == $fullStars + 1 && $hasHalfStar)
                                                            <i class="fas fa-star-half-alt text-warning"></i>
                                                        @else
                                                            <i class="far fa-star text-warning"></i>
                                                        @endif
                                                    @endfor
                                                </div>
                                                <p class="mb-0">{{ $product->reviews->count() }} reviews</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="reviews-list">
                                        @foreach($product->reviews as $review)
                                            <div class="review-item mb-3 pb-3 border-bottom">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <strong>{{ $review->user->name ?? 'Anonymous' }}</strong>
                                                    <div>
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star{{ $i > $review->rating ? '-empty' : '' }} text-warning"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                                @if($review->comment)
                                                    <p class="mb-0">"{{ $review->comment }}"</p>
                                                @else
                                                    <p class="text-muted mb-0">No comment provided</p>
                                                @endif
                                                <small class="text-muted d-block mt-2">{{ $review->created_at->format('M d, Y') }}</small>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-center">No reviews yet for this product.</p>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h3>No products available</h3>
                        <p class="text-muted">Check back later for new arrivals</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection

@section('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const max = parseInt(this.getAttribute('max'));
            const min = parseInt(this.getAttribute('min'));
            let value = parseInt(this.value);
            
            if (isNaN(value)) value = min;
            if (value > max) value = max;
            if (value < min) value = min;
            
            this.value = value;
        });
    });
</script>
@endsection