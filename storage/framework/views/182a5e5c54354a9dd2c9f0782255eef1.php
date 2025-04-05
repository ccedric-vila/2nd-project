

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h3 class="mb-0"><i class="fas fa-bolt me-2"></i> Express Checkout</h3>
                </div>
                
                <div class="card-body p-4">
                    <h4 class="mb-4 text-primary">Order Summary</h4>
                    
                    <!-- Product Details Card -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-4">
                                <?php if($product->images->count() > 0): ?>
                                    <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" 
                                        class="img-fluid rounded-start" 
                                        style="height: 220px; object-fit: cover;"
                                        alt="<?php echo e($product->product_name); ?>">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center h-100">
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo e($product->product_name); ?></h5>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-info text-dark"><?php echo e($product->category); ?></span>
                                        <span class="badge bg-secondary"><?php echo e($product->types); ?></span>
                                    </div>
                                    <p class="card-text text-muted"><?php echo e($product->description); ?></p>
                                    
                                    <div class="d-flex justify-content-between align-items-end mt-3">
                                        <div>
                                            <p class="mb-1"><strong>Size:</strong> 
                                                <span class="text-primary"><?php echo e($product->size); ?></span>
                                            </p>
                                            <div class="rating">
                                                <?php $avgRating = $product->average_rating; ?>
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <?php if($i <= floor($avgRating)): ?>
                                                        <i class="fas fa-star text-warning"></i>
                                                    <?php elseif($i == floor($avgRating) + 1 && ($avgRating - floor($avgRating)) >= 0.5): ?>
                                                        <i class="fas fa-star-half-alt text-warning"></i>
                                                    <?php else: ?>
                                                        <i class="far fa-star text-warning"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                                <small class="ms-2"><?php echo e(number_format($avgRating, 1)); ?> (<?php echo e($product->reviews->count()); ?> reviews)</small>
                                            </div>
                                        </div>
                                        <div class="text-end">
                                            <h4 class="text-primary mb-0">$<span class="unit-price"><?php echo e(number_format($product->sell_price, 2)); ?></span></h4>
                                            <small class="text-success">
                                                <i class="fas fa-check-circle"></i> In Stock: <span class="available-stock"><?php echo e($product->stock); ?></span>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Checkout Form -->
                    <form action="<?php echo e(route('checkout.process')); ?>" method="POST" class="needs-validation" novalidate>
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="product_id" value="<?php echo e($product->product_id); ?>">
                        <input type="hidden" id="unit-price" value="<?php echo e($product->sell_price); ?>">

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
                                                value="<?php echo e(Auth::user()->name); ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label for="contactNumber" class="form-label">Contact Number *</label>
                                            <input type="text" class="form-control" id="contactNumber" 
                                                name="contact_number" value="<?php echo e(Auth::user()->contact_number ?? ''); ?>" required>
                                            <div class="invalid-feedback">Please provide a valid contact number</div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="shippingAddress" class="form-label">Shipping Address *</label>
                                            <textarea class="form-control" id="shippingAddress" 
                                                    name="address" rows="3" required><?php echo e(Auth::user()->address ?? ''); ?></textarea>
                                            <div class="invalid-feedback">Please provide your shipping address</div>
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
                                                name="quantity" min="1" max="<?php echo e($product->stock); ?>" value="1" required>
                                            <div class="invalid-feedback">Please select a valid quantity (1-<?php echo e($product->stock); ?>)</div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Selected Size</label>
                                            <select class="form-select" name="size" required>
                                                <option value="XS" <?php echo e($product->size == 'XS' ? 'selected' : ''); ?>>XS</option>
                                                <option value="S" <?php echo e($product->size == 'S' ? 'selected' : ''); ?>>S</option>
                                                <option value="M" <?php echo e($product->size == 'M' ? 'selected' : ''); ?>>M</option>
                                                <option value="L" <?php echo e($product->size == 'L' ? 'selected' : ''); ?>>L</option>
                                                <option value="XL" <?php echo e($product->size == 'XL' ? 'selected' : ''); ?>>XL</option>
                                                <option value="XXL" <?php echo e($product->size == 'XXL' ? 'selected' : ''); ?>>XXL</option>
                                            </select>
                                            <div class="invalid-feedback">Please select a size</div>
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
                                            <span>Price per item:</span>
                                            <span>$<span id="display-price"><?php echo e(number_format($product->sell_price, 2)); ?></span></span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Quantity:</span>
                                            <span id="quantity-display">1</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Total:</span>
                                            <span>$<span id="total-price"><?php echo e(number_format($product->sell_price, 2)); ?></span></span>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 mt-3 py-2">
                                            <i class="fas fa-lock me-2"></i> Complete Secure Payment
                                        </button>
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
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
    .invalid-feedback {
        display: none;
        color: #dc3545;
    }
    .was-validated .form-control:invalid ~ .invalid-feedback {
        display: block;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const quantityInput = document.getElementById('quantity');
        const quantityDisplay = document.getElementById('quantity-display');
        const totalPriceElement = document.getElementById('total-price');
        const displayPriceElement = document.getElementById('display-price');
        const unitPrice = parseFloat(document.getElementById('unit-price').value);
        const maxStock = parseInt("<?php echo e($product->stock); ?>");

        function updateTotals() {
            let quantity = parseInt(quantityInput.value);
            
            // Validate quantity
            if (isNaN(quantity)) quantity = 1;
            if (quantity < 1) quantity = 1;
            if (quantity > maxStock) quantity = maxStock;
            
            // Update the input if it was corrected
            quantityInput.value = quantity;
            
            // Update displays
            quantityDisplay.textContent = quantity;
            const totalPrice = (quantity * unitPrice).toFixed(2);
            totalPriceElement.textContent = totalPrice;
            displayPriceElement.textContent = unitPrice.toFixed(2);
        }

        // Initial update
        updateTotals();

        // Update when quantity changes
        quantityInput.addEventListener('input', updateTotals);
        quantityInput.addEventListener('change', updateTotals);
        
        // Form validation
        const form = document.querySelector('.needs-validation');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/checkout/single.blade.php ENDPATH**/ ?>