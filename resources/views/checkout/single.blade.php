@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h3 class="mb-0"><i class="fas fa-bolt me-2"></i> Express Checkout</h3>
                </div>
                
                <div class="card-body p-4">
                    <!-- Progress Steps -->
                    <div class="steps mb-5">
                        <div class="step active">
                            <div class="step-number">1</div>
                            <div class="step-label">Order Details</div>
                        </div>
                        <div class="step">
                            <div class="step-number">2</div>
                            <div class="step-label">Payment</div>
                        </div>
                        <div class="step">
                            <div class="step-number">3</div>
                            <div class="step-label">Confirmation</div>
                        </div>
                    </div>

                    <h4 class="mb-4 text-primary">Order Summary</h4>
                    
                    <!-- Product Details Card -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-4">
                                @if($product->images->count() > 0)
                                    <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                        class="img-fluid rounded-start" 
                                        style="height: 220px; object-fit: cover;"
                                        alt="{{ $product->product_name }}">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $product->product_name }}</h5>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-info text-dark">{{ $product->category }}</span>
                                        <span class="badge bg-secondary">{{ $product->types }}</span>
                                    </div>
                                    <p class="card-text text-muted">{{ $product->description }}</p>
                                    
                                    <div class="d-flex justify-content-between align-items-end mt-3">
                                        <div>
                                            <p class="mb-1"><strong>Size:</strong> 
                                                <span class="text-primary">{{ $product->size }}</span>
                                            </p>
                                            <div class="rating">
                                                @php $avgRating = $product->average_rating; @endphp
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($avgRating))
                                                        <i class="fas fa-star text-warning"></i>
                                                    @elseif($i == floor($avgRating) + 1 && ($avgRating - floor($avgRating)) >= 0.5)
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    @else
                                                        <i class="far fa-star text-warning"></i>
                                                    @endif
                                                @endfor
                                                <small class="ms-2">{{ number_format($avgRating, 1) }} ({{ $product->reviews->count() }} reviews)</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h4 class="text-primary mb-0">${{ number_format($product->sell_price, 2) }}</h4>
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> In Stock: {{ $product->stock }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checkout Form -->
                    <form action="{{ route('checkout.process') }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->product_id }}">
                        <input type="hidden" name="max_stock" value="{{ $product->stock }}">

                        <div class="row g-3">
                            <!-- Shipping Information -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Shipping Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="fullName" class="form-label">Full Name</label>
                                            <input type="text" class="form-control" id="fullName" 
                                                   value="{{ Auth::user()->name }}" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="contactNumber" class="form-label">Contact Number *</label>
                                            <input type="text" class="form-control" id="contactNumber" 
                                                   name="contact_number" value="{{ Auth::user()->contact_number }}" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="shippingAddress" class="form-label">Shipping Address *</label>
                                            <textarea class="form-control" id="shippingAddress" 
                                                      name="address" rows="3" required>{{ Auth::user()->address }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Order Details -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-shopping-bag me-2"></i> Order Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Quantity *</label>
                                            <input type="number" class="form-control" id="quantity" 
                                                   name="quantity" min="1" max="{{ $product->stock }}" value="1" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="size" class="form-label">Size *</label>
                                            <select class="form-select" id="size" name="size" required>
                                                <option value="">Select Size</option>
                                                <option value="XS" {{ $product->size == 'XS' ? 'selected' : '' }}>XS</option>
                                                <option value="S" {{ $product->size == 'S' ? 'selected' : '' }}>S</option>
                                                <option value="M" {{ $product->size == 'M' ? 'selected' : '' }}>M</option>
                                                <option value="L" {{ $product->size == 'L' ? 'selected' : '' }}>L</option>
                                                <option value="XL" {{ $product->size == 'XL' ? 'selected' : '' }}>XL</option>
                                                <option value="XXL" {{ $product->size == 'XXL' ? 'selected' : '' }}>XXL</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Special Instructions</label>
                                            <textarea class="form-control" rows="2" placeholder="Any special delivery instructions?"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Summary -->
                                <div class="card border-0 shadow-sm">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-credit-card me-2"></i> Payment Summary</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Subtotal:</span>
                                            <span>${{ number_format($product->sell_price, 2) }}</span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Shipping:</span>
                                            <span class="text-success">FREE</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Total:</span>
                                            <span>${{ number_format($product->sell_price, 2) }}</span>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 mt-3 py-2">
                                            <i class="fas fa-lock me-2"></i> Complete Secure Payment
                                        </button>
                                        <div class="text-center mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-shield-alt me-1"></i> Secure SSL Encryption
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .steps {
        display: flex;
        justify-content: space-between;
        position: relative;
    }
    .steps::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background-color: #e9ecef;
        z-index: 1;
    }
    .step {
        display: flex;
        flex-direction: column;
        align-items: center;
        position: relative;
        z-index: 2;
    }
    .step-number {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background-color: #e9ecef;
        color: #6c757d;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-bottom: 8px;
    }
    .step.active .step-number {
        background-color: #667eea;
        color: white;
    }
    .step-label {
        font-size: 0.875rem;
        color: #6c757d;
    }
    .step.active .step-label {
        color: #667eea;
        font-weight: 500;
    }
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .card-header {
        border-bottom: none;
    }
    .rating {
        color: #ffc107;
    }
    .form-control:focus, .form-select:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
    }
</style>
@endsection

@section('scripts')
<script>
    // Form validation
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
    
    // Update quantity validation
    document.getElementById('quantity').addEventListener('change', function() {
        const max = parseInt(this.getAttribute('max'));
        let value = parseInt(this.value);
        
        if (isNaN(value) || value < 1) value = 1;
        if (value > max) value = max;
        
        this.value = value;
    });
</script>
@endsection