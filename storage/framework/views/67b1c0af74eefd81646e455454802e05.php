<?php $__env->startSection('content'); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Order Confirmation #<?php echo e($order->id); ?></h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Your order is <strong><?php echo e($order->status); ?></strong>. Admin will contact you soon.
                    </div>

                    <h5>Order Details</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $order->orderLines; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($item->product->name); ?></td>
                                    <td>$<?php echo e(number_format($item->sell_price, 2)); ?></td>
                                    <td><?php echo e($item->quantity); ?></td>
                                    <td>$<?php echo e(number_format($item->sell_price * $item->quantity, 2)); ?></td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3">Total Amount</th>
                                    <th>$<?php echo e(number_format($order->total_amount, 2)); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="mt-4">
                        <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i> Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/orders/show.blade.php ENDPATH**/ ?>