<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Header with Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Dashboard
        </a>
        <h1 class="mb-0">Order Management</h1>
        <div></div> <!-- Empty div for balance -->
    </div>

    <!-- Flash Messages -->
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <?php echo e(session('success')); ?>

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <?php echo e(session('error')); ?>

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>#<?php echo e($order->id); ?></td>
                    <td>
                        <div><?php echo e($order->user->name); ?></div>
                        <small class="text-muted"><?php echo e($order->user->email); ?></small>
                    </td>
                    <td><?php echo e($order->created_at->format('M d, Y')); ?></td>
                    <td>$<?php echo e(number_format($order->total_amount, 2)); ?></td>
                    <td>
                        <span class="badge 
                            <?php switch($order->status):
                                case ('pending'): ?> badge-warning <?php break; ?>
                                <?php case ('accepted'): ?> badge-primary <?php break; ?>
                                <?php case ('delivered'): ?> badge-success <?php break; ?>
                                <?php case ('cancelled'): ?> badge-danger <?php break; ?>
                            <?php endswitch; ?>">
                            <?php echo e(ucfirst($order->status)); ?>

                        </span>
                    </td>
                    <td class="text-nowrap">
                        <a href="<?php echo e(route('orders.show', $order->id)); ?>" 
                           class="btn btn-sm btn-outline-info" 
                           title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>

                        <?php if($order->status === 'pending'): ?>
                        <form action="<?php echo e(route('admin.orders.accept', $order->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-success" title="Accept Order">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="<?php echo e(route('admin.orders.cancel', $order->id)); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-danger" title="Cancel Order">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        <?php endif; ?>

                        <?php if($order->status === 'accepted'): ?>
                        <form action="<?php echo e(route('admin.orders.deliver', $order->id)); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-truck"></i> Deliver
                            </button>
                        </form>

                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" class="text-center py-4">No orders found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <?php if($orders->hasPages()): ?>
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($orders->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    .badge {
        font-size: 0.85em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .table td, .table th {
        vertical-align: middle;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>