@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Checkout</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Product Image -->
                        <div class="col-md-5">
                            @if($product->images->count() > 0)
                                <img src="{{ asset('storage/' . $product->images->first()->image_path) }}" 
                                     class="img-fluid rounded" 
                                     alt="{{ $product->name }}">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Product Details -->
                        <div class="col-md-7">
                            <h3>{{ $product->name }}</h3>
                            <p class="text-muted">{{ $product->category }} - {{ $product->types }}</p>
                            
                            <div class="mb-3">
                                <span class="fw-bold">Size:</span> {{ $product->size }}
                            </div>
                            
                            <div class="mb-3">
                                <span class="fw-bold">Price:</span> ${{ number_format($product->sell_price, 2) }}
                            </div>
                            
                            <div class="mb-3">
                                <span class="fw-bold">Description:</span>
                                <p>{{ $product->description }}</p>
                            </div>
                            
                            <!-- Checkout Form -->
                            <form action="{{ route('checkout.process') }}" method="POST">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" 
                                           name="quantity" min="1" max="{{ $product->stock }}" value="1">
                                </div>
                                
                                <button type="submit" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-credit-card me-2"></i> Proceed to Payment
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection