@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Checkout - Buy Now</h4>
                </div>
                <div class="card-body">
                    <!-- Product Summary -->
                    <div class="row mb-4">
                        <div class="col-md-4">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $product->name }}">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <i class="fas fa-image fa-2x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h5>{{ $product->name }}</h5>
                            <p class="mb-1"><strong>Brand:</strong> {{ $product->brand->name ?? 'No Brand' }}</p>
                            <p class="mb-1"><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
                            <p class="mb-1"><strong>Quantity:</strong> {{ $quantity ?? 1 }}</p>
                            <p class="mb-1"><strong>Subtotal:</strong> ${{ number_format(($product->price * ($quantity ?? 1)), 2) }}</p>
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="mb-4">
                        <h5>Shipping Information</h5>
                        <form id="shippingForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Full Name</label>
                                    <input type="text" class="form-control" id="name" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" required>
                                </div>
                                <div class="col-12">
                                    <label for="address" class="form-label">Address</label>
                                    <input type="text" class="form-control" id="address" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="city" class="form-label">City</label>
                                    <input type="text" class="form-control" id="city" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="state" class="form-label">State/Province</label>
                                    <input type="text" class="form-control" id="state" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="zip" class="form-label">Zip/Postal Code</label>
                                    <input type="text" class="form-control" id="zip" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="country" class="form-label">Country</label>
                                    <select class="form-select" id="country" required>
                                        <option value="">Choose...</option>
                                        <option>United States</option>
                                        <option>Canada</option>
                                        <option>United Kingdom</option>
                                        <!-- Add more countries as needed -->
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone" required>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-4">
                        <h5>Payment Method</h5>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="creditCard" checked>
                            <label class="form-check-label" for="creditCard">
                                Credit Card
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="paypal">
                            <label class="form-check-label" for="paypal">
                                PayPal
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="paymentMethod" id="bankTransfer">
                            <label class="form-check-label" for="bankTransfer">
                                Bank Transfer
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Order Summary</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Subtotal
                            <span>${{ number_format($product->price * ($quantity ?? 1), 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Shipping
                            <span>$5.00</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Tax
                            <span>${{ number_format(($product->price * ($quantity ?? 1) * 0.1), 2) }}</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center fw-bold">
                            Total
                            <span>${{ number_format(($product->price * ($quantity ?? 1) * 1.1 + 5, 2) }}</span>
                        </li>
                    </ul>
                </div>
                <div class="card-footer">
                    <form action="{{ route('checkout.process-buy-now') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" value="{{ $quantity ?? 1 }}">
                        <button type="submit" class="btn btn-success w-100 btn-lg">
                            Complete Purchase
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Return Policy</h5>
                    <p class="card-text small">You may return most items within 30 days of delivery for a full refund.</p>
                    <h5 class="card-title mt-3">Need Help?</h5>
                    <p class="card-text small">Contact our customer service for any questions about your order.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Client-side validation
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('shippingForm');
        const submitBtn = document.querySelector('button[type="submit"]');
        
        submitBtn.addEventListener('click', function(e) {
            let isValid = true;
            
            // Simple validation - in a real app you'd want more robust validation
            form.querySelectorAll('[required]').forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('is-invalid');
                    isValid = false;
                } else {
                    input.classList.remove('is-invalid');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Please fill in all required fields');
            }
        });
    });
</script>
@endsection