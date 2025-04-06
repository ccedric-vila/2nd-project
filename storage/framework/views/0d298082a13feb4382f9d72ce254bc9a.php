

<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card border-0 shadow-lg">
                <!-- Order Header -->
                <div class="card-header bg-success text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fas fa-check-circle me-2"></i> Order Confirmation</h3>
                        <span class="badge bg-light text-dark fs-6">#<?php echo e($order->id); ?></span>
                    </div>
                </div>
                
                <?php if(session('success')): ?>
                <div class="alert alert-success m-3">
                    <i class="fas fa-check-circle me-2"></i> <?php echo e(session('success')); ?>

                </div>
                <?php endif; ?>
                
                <div class="card-body">
                    <!-- Thank You Section -->
                    <div class="text-center mb-4">
                        <i class="fas fa-check-circle fa-5x text-success mb-3"></i>
                        <h2>Thank You For Your Order!</h2>
                        <p class="text-muted">Your order #<?php echo e($order->id); ?> was placed on <?php echo e($order->created_at->format('F j, Y \a\t g:i A')); ?></p>
                    </div>

                    <!-- Order Summary Card -->
                    <div class="card mb-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">Order Summary</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="border-0">Product</th>
                                            <th class="border-0 text-center">    </th>
                                            <th class="border-0 text-center">Qty</th>
                                            <th class="border-0 text-end">Price</th>
                                            <th class="border-0 text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $order->orderLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $line): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="border-0">
                                                <div class="d-flex align-items-center">
                                                    <?php if($line->product->images->first()): ?>
                                                    <img src="<?php echo e(asset('storage/'.$line->product->images->first()->image_path)); ?>" 
                                                         alt="<?php echo e($line->product->product_name); ?>" 
                                                         class="img-thumbnail me-3" 
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                    <?php endif; ?>
                                                    <div>
                                                        <h6 class="mb-0"><?php echo e($line->product->product_name); ?></h6>
                                                        <small class="text-muted"><?php echo e($line->product->supplier->brand_name ?? 'Unknown Brand'); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="border-0 text-center"><?php echo e($line->size); ?></td>
                                            <td class="border-0 text-center"><?php echo e($line->quantity); ?></td>
                                            <td class="border-0 text-end">$<?php echo e(number_format($line->sell_price, 2)); ?></td>
                                            <td class="border-0 text-end">$<?php echo e(number_format($line->sell_price * $line->quantity, 2)); ?></td>
                                        </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <td colspan="4" class="text-end border-0"><strong>Subtotal:</strong></td>
                                            <td class="text-end border-0">$<?php echo e(number_format($order->total_amount, 2)); ?></td>
                                        </tr>
                                        <tr>
                                            <td colspan="4" class="text-end border-0"><strong>Total:</strong></td>
                                            <td class="text-end border-0"><strong>$<?php echo e(number_format($order->total_amount, 2)); ?></strong></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Order Details Grid -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Shipping Information</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Name:</strong> <?php echo e($user->name); ?></p>
                                    <p class="mb-1"><strong>Contact:</strong> <?php echo e($user->contact_number ?? 'Not provided'); ?></p>
                                    <p class="mb-0"><strong>Address:</strong> <?php echo e($user->address ?? 'Not provided'); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Order Status</h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong>Status:</strong> <span class="badge bg-info"><?php echo e(ucfirst($order->status)); ?></span></p>
                                    <p class="mb-1"><strong>Payment:</strong> Pending confirmation</p>
                                    <p class="mb-0"><strong>Estimated Processing:</strong> 1-2 business days</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                        </a>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/checkout/success.blade.php ENDPATH**/ ?>