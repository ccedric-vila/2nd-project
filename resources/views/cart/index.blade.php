@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Back Button and Title Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1 class="mb-0">Your Shopping Cart</h1>
        <div></div> <!-- Empty div for spacing balance -->
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x mb-3 text-muted"></i>
            <h3 class="text-muted">Your cart is empty</h3>
            <a href="{{ route('home') }}" class="btn btn-primary mt-3">Continue Shopping</a>
        </div>
    @else
        <div class="card shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 40%">Product</th>
                                <th style="width: 20%">Quantity</th>
                                <th style="width: 15%">Price</th>
                                <th style="width: 15%">Total</th>
                                <th style="width: 10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cartItems as $cartProduct)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($cartProduct->product->images->isNotEmpty())
                                                <img src="{{ asset('storage/' . $cartProduct->product->images->first()->image_path) }}" 
                                                     class="img-thumbnail me-3" 
                                                     width="80"
                                                     style="object-fit: cover; height: 80px;">
                                            @endif
                                            <div>
                                                <h6 class="mb-1">{{ $cartProduct->product->product_name }}</h6>
                                                <small class="text-muted">ID: {{ $cartProduct->product->product_id }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <form action="{{ route('cart.update', $cartProduct->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="input-group">
                                                <input type="number" name="quantity" value="{{ $cartProduct->quantity }}" min="1" 
                                                       class="form-control form-control-sm" style="width: 60px;">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>₱{{ number_format($cartProduct->product->sell_price, 2) }}</td>
                                    <td>₱{{ number_format($cartProduct->quantity * $cartProduct->product->sell_price, 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.remove', $cartProduct->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Total: ₱{{ number_format($cartItems->sum(function($cartProduct) { 
                        return $cartProduct->quantity * $cartProduct->product->sell_price; 
                    }), 2) }}</h4>
                    <form action="{{ route('cart.checkout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-credit-card me-2"></i>Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<!-- Font Awesome for the icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .table th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .img-thumbnail {
        border-radius: 8px;
    }
    .btn-outline-primary, .btn-outline-danger {
        border-width: 1px;
    }
</style>
@endsection