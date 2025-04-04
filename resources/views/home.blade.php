@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-center mb-4">Welcome to StyleSphere</h1>

    <div class="row">
        @if($products->total() > 0)
            @foreach ($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <!-- Product Image Carousel -->
                        @if($product->images->count() > 0)
                        <div id="productCarousel-{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @foreach($product->images as $key => $image)
                                <div class="carousel-item {{ $key === 0 ? 'active' : '' }}">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                        class="d-block w-100 card-img-top" 
                                        style="height: 300px; object-fit: cover;" 
                                        alt="{{ $product->name }}">
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
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text"><strong>Brand:</strong> {{ $product->brand->name ?? 'No Brand' }}</p>
                            <p class="card-text"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                            <p class="card-text"><strong>Stock:</strong> {{ $product->stock }}</p>
                            
                            <!-- Enhanced Product Rating Display -->
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
                                    
                                    <!-- Display top 2 reviews -->
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
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                        </button>
                                    </form>

                                    <!-- Updated Buy Now Button -->
                                    <<form action="{{ route('checkout.single') }}" method="POST" class="mb-2">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-bolt me-1"></i> Buy Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- View All Reviews Button -->
                            @if($product->reviews->count() > 2)
                                <button class="btn btn-outline-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#reviewsModal-{{ $product->id }}">
                                    <i class="fas fa-comment me-1"></i> View All Reviews ({{ $product->reviews->count() }})
                                </button>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Reviews Modal for each product -->
                <div class="modal fade" id="reviewsModal-{{ $product->id }}" tabindex="-1" aria-labelledby="reviewsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewsModalLabel">Reviews for {{ $product->name }}</h5>
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

    <!-- Pagination -->
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
    // Quantity input validation
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