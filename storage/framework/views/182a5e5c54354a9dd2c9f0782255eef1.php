

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Success Message (hidden by default) -->
            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            
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
                                            <h4 class="text-primary mb-0">₱<span id="unit-price-display"><?php echo e(number_format($product->sell_price, 2)); ?></span></h4>
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
                        <input type="hidden" name="size" value="<?php echo e($product->size); ?>">

                        <div class="row g-3">
                            <!-- Shipping Information -->
                            <div class="col-md-6">
                                <div class="card border-0 shadow-sm mb-4">
                                    <div class="card-header bg-light">
                                        <h5 class="mb-0"><i class="fas fa-truck me-2"></i> Shipping Information</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <label class="form-label">Full Name</label>
                                            <div class="form-control-plaintext"><?php echo e(Auth::user()->name); ?></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Contact Number</label>
                                            <div class="form-control-plaintext"><?php echo e(Auth::user()->contact_number ?? 'Not provided'); ?></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Shipping Address</label>
                                            <div class="form-control-plaintext"><?php echo e(Auth::user()->address ?? 'Not provided'); ?></div>
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
                                            <div class="input-group">
                                                <input type="number" class="form-control" id="quantity" 
                                                    name="quantity" min="1" max="<?php echo e($product->stock); ?>" value="1" required>
                                                <button class="btn btn-outline-primary" type="button" id="update-quantity">
                                                    <i class="fas fa-sync-alt"></i> Update
                                                </button>
                                            </div>
                                            <div class="invalid-feedback">Please select a valid quantity (1-<?php echo e($product->stock); ?>)</div>
                                            <div id="quantity-error" class="text-danger small mt-1 d-none"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Selected Size</label>
                                            <div class="form-control-plaintext"><?php echo e($product->size); ?></div>
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
                                            <span>₱<span id="display-price"><?php echo e(number_format($product->sell_price, 2)); ?></span></span>
                                        </div>
                                        <div class="d-flex justify-content-between mb-2">
                                            <span>Quantity:</span>
                                            <span id="quantity-display">1</span>
                                        </div>
                                        <hr>
                                        <div class="d-flex justify-content-between fw-bold">
                                            <span>Total:</span>
                                            <span>₱<span id="total-price"><?php echo e(number_format($product->sell_price, 2)); ?></span></span>
                                        </div>
                                        <button type="submit" class="btn btn-primary w-100 mt-3 py-2" id="submit-btn">
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
    .form-control:focus {
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
    .form-control-plaintext {
        padding: 0.375rem 0;
        margin-bottom: 0;
        line-height: 1.5;
        background-color: transparent;
        border: solid transparent;
        border-width: 1px 0;
    }
    #update-quantity {
        transition: all 0.3s ease;
    }
    #update-quantity:hover {
        background-color: #667eea;
        color: white;
    }
    #submit-btn {
        transition: all 0.3s ease;
    }
    #submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .alert-success {
        border-left: 4px solid #28a745;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all required elements
        const quantityInput = document.getElementById('quantity');
        const updateButton = document.getElementById('update-quantity');
        const quantityDisplay = document.getElementById('quantity-display');
        const totalPriceElement = document.getElementById('total-price');
        const displayPriceElement = document.getElementById('display-price');
        const unitPriceDisplay = document.getElementById('unit-price-display');
        const productId = document.querySelector('input[name="product_id"]').value;
        const unitPrice = parseFloat("<?php echo e($product->sell_price); ?>");
        const maxStock = parseInt("<?php echo e($product->stock); ?>");
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const quantityError = document.getElementById('quantity-error');
        const submitBtn = document.getElementById('submit-btn');

        // Function to format price
        function formatPrice(price) {
            return price.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }

        // Function to update totals via AJAX
        async function updateTotals() {
            try {
                // Get and validate quantity
                let quantity = parseInt(quantityInput.value);
                if (isNaN(quantity) quantity = 1;
                
                // Validate against max stock
                if (quantity > maxStock) {
                    quantityError.textContent = `Only ${maxStock} items available`;
                    quantityError.classList.remove('d-none');
                    quantityInput.value = maxStock;
                    quantity = maxStock;
                } else if (quantity < 1) {
                    quantityError.textContent = 'Minimum quantity is 1';
                    quantityError.classList.remove('d-none');
                    quantityInput.value = 1;
                    quantity = 1;
                } else {
                    quantityError.classList.add('d-none');
                }

                // Show loading state
                updateButton.disabled = true;
                updateButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating';

                // Make AJAX request
                const response = await fetch("<?php echo e(route('checkout.update-quantity')); ?>", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity
                    })
                });

                const data = await response.json();

                if (!response.ok || !data.success) {
                    throw new Error(data.message || 'Failed to update quantity');
                }

                // Update UI with new data
                quantityDisplay.textContent = data.quantity;
                totalPriceElement.textContent = data.formatted_total.replace('$', '');
                displayPriceElement.textContent = data.formatted_unit_price.replace('$', '');
                unitPriceDisplay.textContent = data.formatted_unit_price.replace('$', '');
                
                // Update stock display if exists
                const stockElement = document.querySelector('.available-stock');
                if (stockElement) {
                    stockElement.textContent = data.available_stock;
                }

                // Update max attribute
                quantityInput.max = data.available_stock;

                // Show success feedback
                updateButton.innerHTML = '<i class="fas fa-check"></i> Updated';
                updateButton.classList.remove('btn-outline-primary');
                updateButton.classList.add('btn-success');

                // Flash the total price to show change
                totalPriceElement.parentElement.style.color = '#28a745';
                setTimeout(() => {
                    totalPriceElement.parentElement.style.color = '';
                }, 1000);

            } catch (error) {
                console.error('Error:', error);
                quantityError.textContent = error.message;
                quantityError.classList.remove('d-none');
            } finally {
                // Reset button after delay
                setTimeout(() => {
                    updateButton.innerHTML = '<i class="fas fa-sync-alt"></i> Update';
                    updateButton.classList.remove('btn-success');
                    updateButton.classList.add('btn-outline-primary');
                    updateButton.disabled = false;
                }, 1500);
            }
        }

        // Initial setup
        updateTotals();

        // Event listeners
        updateButton.addEventListener('click', updateTotals);
        
        quantityInput.addEventListener('change', function() {
            updateTotals();
        });

        quantityInput.addEventListener('input', function() {
            // Live validation while typing
            const quantity = parseInt(quantityInput.value);
            if (quantity > maxStock) {
                quantityError.textContent = `Only ${maxStock} items available`;
                quantityError.classList.remove('d-none');
            } else if (quantity < 1) {
                quantityError.textContent = 'Minimum quantity is 1';
                quantityError.classList.remove('d-none');
            } else {
                quantityError.classList.add('d-none');
            }
        });

        // Form validation
        const form = document.querySelector('.needs-validation');
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            } else {
                // Show loading state on submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';
                
                // Let the form submit normally - the redirect will happen server-side
            }
            form.classList.add('was-validated');
        }, false);

        // If there's a success message, scroll to it
        <?php if(session('success')): ?>
            document.querySelector('.alert-success')?.scrollIntoView({
                behavior: 'smooth'
            });
        <?php endif; ?>
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/checkout/single.blade.php ENDPATH**/ ?>