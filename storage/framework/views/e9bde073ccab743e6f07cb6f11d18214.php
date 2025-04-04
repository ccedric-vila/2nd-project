

<?php $__env->startSection('content'); ?>
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
                            <?php if($product->images->count() > 0): ?>
                                <img src="<?php echo e(asset('storage/' . $product->images->first()->image_path)); ?>" 
                                     class="img-fluid rounded" 
                                     alt="<?php echo e($product->name); ?>">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Product Details -->
                        <div class="col-md-7">
                            <h3><?php echo e($product->name); ?></h3>
                            <p class="text-muted"><?php echo e($product->category); ?> - <?php echo e($product->types); ?></p>
                            
                            <div class="mb-3">
                                <span class="fw-bold">Size:</span> <?php echo e($product->size); ?>

                            </div>
                            
                            <div class="mb-3">
                                <span class="fw-bold">Price:</span> $<?php echo e(number_format($product->sell_price, 2)); ?>

                            </div>
                            
                            <div class="mb-3">
                                <span class="fw-bold">Description:</span>
                                <p><?php echo e($product->description); ?></p>
                            </div>
                            
                            <!-- Checkout Form -->
                            <form action="<?php echo e(route('checkout.process')); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                                
                                <div class="mb-3">
                                    <label for="quantity" class="form-label">Quantity</label>
                                    <input type="number" class="form-control" id="quantity" 
                                           name="quantity" min="1" max="<?php echo e($product->stock); ?>" value="1">
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/checkout/index.blade.php ENDPATH**/ ?>