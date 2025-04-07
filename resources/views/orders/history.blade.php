@extends('layouts.app')

@section('content')
<div class="container py-4">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <a href="{{ route('home') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-1"></i> Back to Dashboard
            </a>
            <h1 class="fw-bold text-primary d-inline-block">Order History</h1>
        </div>
        <div class="bg-primary p-2 rounded">
            <span class="text-white fw-bold">Total Orders: {{ $orders->total() }}</span>
        </div>
    </div>

    @if($orders->isEmpty())
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-5">
                <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">No Orders Yet</h3>
                <p class="text-muted">Your order history will appear here once you make purchases</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-shopping-bag me-2"></i>Start Shopping
                </a>
            </div>
        </div>
    @else
        <div class="card border-0 shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4">Order #</th>
                                <th>Date</th>
                                <th>Product Name</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th class="pe-4 text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr class="align-middle">
                                <td class="ps-4 fw-bold">#{{ $order->id }}</td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="fw-medium">{{ $order->created_at->format('M d, Y') }}</span>
                                        <small class="text-muted">{{ $order->created_at->format('h:i A') }}</small>
                                    </div>
                                </td>
                                <td>
                                    @if($order->orderLines->isNotEmpty())
                                        {{ $order->orderLines->first()->product->product_name ?? 'N/A' }}
                                        @if($order->orderLines->count() > 1)
                                            + {{ $order->orderLines->count() - 1 }} more
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-secondary rounded-pill me-2">{{ $order->orderLines->sum('quantity') }}</span>
                                        <span>items</span>
                                    </div>
                                </td>
                                <td class="fw-bold text-success">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge rounded-pill py-2 px-3 
                                        @if($order->status === 'pending') bg-warning text-dark
                                        @elseif($order->status === 'accepted') bg-success
                                        @elseif($order->status === 'delivered') bg-info
                                        @else bg-danger
                                        @endif">
                                        <i class="fas 
                                            @if($order->status === 'pending') fa-clock
                                            @elseif($order->status === 'accepted') fa-check-circle
                                            @elseif($order->status === 'delivered') fa-truck
                                            @else fa-times-circle
                                            @endif me-1"></i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                            <i class="fas fa-eye me-1"></i> Details
                                        </a>
                                        @if($order->status === 'delivered')
                                            @php
                                                $unreviewedProduct = $order->orderLines->first(function($line) use ($order) {
                                                    $product = $line->product;
                                                    return $product && !$product->reviews()
                                                        ->where('user_id', auth()->id())
                                                        ->where('order_id', $order->id)
                                                        ->exists();
                                                });
                                                
                                                $reviewedProduct = $order->orderLines->first(function($line) use ($order) {
                                                    $product = $line->product;
                                                    return $product && $product->reviews()
                                                        ->where('user_id', auth()->id())
                                                        ->where('order_id', $order->id)
                                                        ->exists();
                                                });
                                            @endphp
                                            
                                            @if($reviewedProduct && $reviewedProduct->product)
                                                <button class="btn btn-sm btn-warning rounded-pill px-3 edit-review-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#reviewModal"
                                                        data-order-id="{{ $order->id }}"
                                                        data-product-id="{{ $reviewedProduct->product->product_id }}"
                                                        data-product-name="{{ $reviewedProduct->product->product_name }}"
                                                        data-review-rating="{{ optional($reviewedProduct->product->reviews()->where('user_id', auth()->id())->where('order_id', $order->id)->first())->rating }}"
                                                        data-review-comment="{{ optional($reviewedProduct->product->reviews()->where('user_id', auth()->id())->where('order_id', $order->id)->first())->comment ?? '' }}">
                                                    <i class="fas fa-edit me-1"></i> Edit Review
                                                </button>
                                            @elseif($unreviewedProduct && $unreviewedProduct->product)
                                                <button class="btn btn-sm btn-success rounded-pill px-3 review-btn" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#reviewModal"
                                                        data-order-id="{{ $order->id }}"
                                                        data-product-id="{{ $unreviewedProduct->product->product_id }}"
                                                        data-product-name="{{ $unreviewedProduct->product->product_name }}">
                                                    <i class="fas fa-star me-1"></i> Review
                                                </button>
                                            @else
                                                <button class="btn btn-sm btn-info rounded-pill px-3" disabled>
                                                    <i class="fas fa-check me-1"></i> Reviewed
                                                </button>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
            </div>
            <div>
                {{ $orders->links('pagination::bootstrap-5') }}
            </div>
        </div>
    @endif
</div>

<!-- Review Modal -->
<div class="modal fade" id="reviewModal" tabindex="-1" aria-labelledby="reviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="reviewModalLabel">Rate Your Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="review-loading" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary mb-2" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted">Checking existing review...</p>
                </div>
                
                <div id="review-form-container">
                    <form id="reviewForm">
                        @csrf
                        <input type="hidden" name="order_id" id="order_id">
                        <input type="hidden" name="product_id" id="product_id">
                        <div class="text-center mb-4">
                            <div id="product-info" class="mb-3">
                                <!-- Product info will be inserted here -->
                            </div>
                            <h6 class="mb-3">How was your experience with this product?</h6>
                            <div class="rating-stars mb-2">
                                <i class="far fa-star fa-2x star" data-rating="1"></i>
                                <i class="far fa-star fa-2x star" data-rating="2"></i>
                                <i class="far fa-star fa-2x star" data-rating="3"></i>
                                <i class="far fa-star fa-2x star" data-rating="4"></i>
                                <i class="far fa-star fa-2x star" data-rating="5"></i>
                            </div>
                            <small class="text-muted" id="rating-text">Tap to rate</small>
                            <input type="hidden" name="rating" id="rating" value="0" required>
                        </div>
                        <div class="mb-3">
                            <label for="comment" class="form-label">Your Review (Optional)</label>
                            <textarea class="form-control" id="comment" name="comment" rows="3" placeholder="Share your thoughts about this product..."></textarea>
                            <small class="text-muted">Maximum 1000 characters</small>
                            <div class="invalid-feedback" id="comment-error"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-4">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="submit-review">
                                <span id="submit-text">Submit Review</span>
                                <span id="submit-loading" class="d-none">
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    <span class="ms-1">Submitting...</span>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
                
                <div id="review-success" class="text-center py-4 d-none">
                    <div class="mb-3">
                        <i class="fas fa-check-circle text-success fa-4x"></i>
                    </div>
                    <h4 class="mb-2" id="success-message">Review submitted successfully!</h4>
                    <p class="text-muted">Thank you for sharing your feedback.</p>
                    <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">Close</button>
                </div>
                
                <div id="review-error" class="text-center py-4 d-none">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-circle text-danger fa-4x"></i>
                    </div>
                    <h4 class="mb-2">Oops! Something went wrong</h4>
                    <p class="text-muted" id="error-message">Failed to submit your review. Please try again.</p>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" id="retry-button">Try Again</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .rating-stars {
        font-size: 2rem;
        display: inline-block;
    }
    .rating-stars .star {
        cursor: pointer;
        margin: 0 5px;
        transition: all 0.2s;
        color: #e4e5e9;
    }
    .rating-stars .star.selected {
        color: #ffc107;
    }
    .rating-stars .star.hovered {
        color: #ffc107;
        transform: scale(1.1);
    }
    .rating-stars .fas.fa-star {
        color: #ffc107;
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-5px); }
        40%, 80% { transform: translateX(5px); }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.rating-stars .star');
    const ratingInput = document.getElementById('rating');
    const ratingText = document.getElementById('rating-text');
    const reviewForm = document.getElementById('reviewForm');
    const reviewModal = document.getElementById('reviewModal');
    const commentInput = document.getElementById('comment');
    
    // Get modal instance safely
    const getModalInstance = (el) => {
        try {
            return bootstrap.Modal.getInstance(el) || new bootstrap.Modal(el);
        } catch (e) {
            console.error("Failed to get modal instance:", e);
            return new bootstrap.Modal(el);
        }
    };

    // Reset stars function
    function resetStars() {
        stars.forEach(star => {
            star.className = 'far fa-star fa-2x star';
        });
        ratingInput.value = "0";
        ratingText.textContent = "Tap to rate";
        ratingText.classList.remove('text-danger');
    }
    
    // Set rating function
    function setRating(rating) {
        const messages = ["Tap to rate", "Poor", "Fair", "Good", "Very Good", "Excellent"];
        
        stars.forEach(star => {
            const starRating = parseInt(star.getAttribute('data-rating'));
            if (starRating <= rating) {
                star.className = 'fas fa-star fa-2x star selected';
            } else {
                star.className = 'far fa-star fa-2x star';
            }
        });
        
        ratingInput.value = rating;
        ratingText.textContent = messages[rating];
        ratingText.classList.remove('text-danger');
    }
    
    // Show loading state
    function showLoading() {
        document.getElementById('review-form-container').classList.add('d-none');
        document.getElementById('review-loading').classList.remove('d-none');
        document.getElementById('review-success').classList.add('d-none');
        document.getElementById('review-error').classList.add('d-none');
    }
    
    // Show form
    function showForm() {
        document.getElementById('review-form-container').classList.remove('d-none');
        document.getElementById('review-loading').classList.add('d-none');
        document.getElementById('review-success').classList.add('d-none');
        document.getElementById('review-error').classList.add('d-none');
    }
    
    // Show success
    function showSuccess(message) {
        document.getElementById('review-form-container').classList.add('d-none');
        document.getElementById('review-loading').classList.add('d-none');
        document.getElementById('review-success').classList.remove('d-none');
        document.getElementById('review-error').classList.add('d-none');
        document.getElementById('success-message').textContent = message;
    }
    
    // Show error
    function showError(message) {
        document.getElementById('review-form-container').classList.add('d-none');
        document.getElementById('review-loading').classList.add('d-none');
        document.getElementById('review-success').classList.add('d-none');
        document.getElementById('review-error').classList.remove('d-none');
        document.getElementById('error-message').textContent = message;
    }
    
    // Initialize modal event
    reviewModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const orderId = button.getAttribute('data-order-id');
        const productId = button.getAttribute('data-product-id');
        const productName = button.getAttribute('data-product-name');
        const existingRating = button.getAttribute('data-review-rating');
        const existingComment = button.getAttribute('data-review-comment');
        
        // Set form values
        document.getElementById('order_id').value = orderId;
        document.getElementById('product_id').value = productId;
        
        // Reset form
        resetStars();
        commentInput.value = '';
        showLoading();
        
        // Set product info immediately
        document.getElementById('product-info').innerHTML = `
            <div class="text-start">
                <h6 class="mb-1">${productName || 'Product'}</h6>
            </div>
        `;
        
        // If we have existing review data, use it immediately
        if (existingRating) {
            setRating(parseInt(existingRating));
            if (existingComment) {
                commentInput.value = existingComment;
            }
            document.getElementById('submit-text').textContent = 'Update Review';
            document.getElementById('reviewModalLabel').textContent = 'Edit Your Review';
            showForm();
        } else {
            // Check if review exists via API
            fetch(`/reviews/check?order_id=${orderId}&product_id=${productId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                showForm();
                
                if (data.review) {
                    setRating(data.review.rating);
                    commentInput.value = data.review.comment || '';
                    document.getElementById('submit-text').textContent = 'Update Review';
                    document.getElementById('reviewModalLabel').textContent = 'Edit Your Review';
                } else {
                    document.getElementById('submit-text').textContent = 'Submit Review';
                    document.getElementById('reviewModalLabel').textContent = 'Rate Your Product';
                }
            })
            .catch(error => {
                console.error('Error checking review:', error);
                showError('Failed to load review data. Please try again.');
            });
        }
    });
    
    // Star click event
    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            setRating(rating);
        });
        
        // Hover effects
        star.addEventListener('mouseover', function() {
            const hoverRating = parseInt(this.getAttribute('data-rating'));
            
            stars.forEach(s => {
                const starRating = parseInt(s.getAttribute('data-rating'));
                if (starRating <= hoverRating) {
                    if (!s.classList.contains('selected')) {
                        s.classList.remove('far');
                        s.classList.add('fas', 'hovered');
                    }
                }
            });
        });
        
        star.addEventListener('mouseout', function() {
            stars.forEach(s => {
                if (s.classList.contains('hovered')) {
                    s.classList.remove('fas', 'hovered');
                    s.classList.add('far');
                }
            });
        });
    });
    
    // Form submission
    reviewForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate product_id presence
        const productId = document.getElementById('product_id').value;
        if (!productId) {
            showError('Product ID is missing. Please refresh and try again.');
            return;
        }
        
        // Rating validation
        if (ratingInput.value === "0") {
            ratingText.textContent = "Please select a rating";
            ratingText.classList.add('text-danger');
            
            const starsContainer = document.querySelector('.rating-stars');
            starsContainer.style.animation = 'shake 0.5s';
            
            starsContainer.addEventListener('animationend', () => {
                starsContainer.style.animation = '';
            }, {once: true});
            
            return;
        }
        
        // Show loading state in button
        document.getElementById('submit-text').classList.add('d-none');
        document.getElementById('submit-loading').classList.remove('d-none');
        
        // Submit via fetch API
        fetch("{{ route('reviews.store') }}", {
            method: 'POST',
            body: new FormData(reviewForm),
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showSuccess(data.message);
                
                // Reload the page to update the UI
                setTimeout(() => {
                    window.location.reload();
                }, 1500);
            } else {
                throw new Error(data.message || 'Failed to submit review');
            }
        })
        .catch(error => {
            console.error('Error submitting review:', error);
            showError(error.message || 'Failed to submit review. Please try again.');
        })
        .finally(() => {
            // Reset button state
            document.getElementById('submit-text').classList.remove('d-none');
            document.getElementById('submit-loading').classList.add('d-none');
        });
    });
    
    // Retry button event
    document.getElementById('retry-button')?.addEventListener('click', function() {
        showForm();
    });
    
    // Character count for comment
    commentInput.addEventListener('input', function() {
        if (this.value.length > 1000) {
            this.value = this.value.substring(0, 1000);
        }
    });
});
</script>
@endsection