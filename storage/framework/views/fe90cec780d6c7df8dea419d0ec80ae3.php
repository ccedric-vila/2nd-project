<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Back Button and Title Section -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
        <h1 class="mb-0">Your Shopping Cart</h1>
        <div></div> <!-- Empty div for spacing balance -->
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if($cartItems->isEmpty()): ?>
        <div class="text-center py-5">
            <i class="fas fa-shopping-cart fa-4x mb-3 text-muted"></i>
            <h3 class="text-muted">Your cart is empty</h3>
            <a href="<?php echo e(route('home')); ?>" class="btn btn-primary mt-3">Continue Shopping</a>
        </div>
    <?php else: ?>
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
                            <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cartProduct): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if($cartProduct->product->images->isNotEmpty()): ?>
                                                <img src="<?php echo e(asset('storage/' . $cartProduct->product->images->first()->image_path)); ?>" 
                                                     class="img-thumbnail me-3" 
                                                     width="80"
                                                     style="object-fit: cover; height: 80px;">
                                            <?php endif; ?>
                                            <div>
                                                <h6 class="mb-1"><?php echo e($cartProduct->product->product_name); ?></h6>
                                                <small class="text-muted">ID: <?php echo e($cartProduct->product->product_id); ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <form action="<?php echo e(route('cart.update', $cartProduct->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PUT'); ?>
                                            <div class="input-group">
                                                <input type="number" name="quantity" value="<?php echo e($cartProduct->quantity); ?>" min="1" 
                                                       class="form-control form-control-sm" style="width: 60px;">
                                                <button type="submit" class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-sync-alt"></i>
                                                </button>
                                            </div>
                                        </form>
                                    </td>
                                    <td>₱<?php echo e(number_format($cartProduct->product->sell_price, 2)); ?></td>
                                    <td>₱<?php echo e(number_format($cartProduct->quantity * $cartProduct->product->sell_price, 2)); ?></td>
                                    <td>
                                        <form action="<?php echo e(route('cart.remove', $cartProduct->id)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Total: ₱<?php echo e(number_format($cartItems->sum(function($cartProduct) { 
                        return $cartProduct->quantity * $cartProduct->product->sell_price; 
                    }), 2)); ?></h4>
                    <form action="<?php echo e(route('cart.checkout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary btn-lg px-4">
                            <i class="fas fa-credit-card me-2"></i>Checkout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
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
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/cart/index.blade.php ENDPATH**/ ?>