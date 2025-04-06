<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Header with Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary rounded-pill">
            <i class="fas fa-arrow-left mr-2"></i>Dashboard
        </a>
        <h1 class="mb-0 font-weight-bold">Order Management</h1>
        <div></div> <!-- Empty div for balance -->
    </div>

    <!-- Flash Messages -->
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show shadow-sm border-left border-success" style="border-left-width: 4px !important;">
        <i class="fas fa-check-circle mr-2"></i><?php echo e(session('success')); ?>

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show shadow-sm border-left border-danger" style="border-left-width: 4px !important;">
        <i class="fas fa-exclamation-circle mr-2"></i><?php echo e(session('error')); ?>

        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    <?php endif; ?>

    <!-- Orders Table Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="bg-light">
                        <tr>
                            <th class="border-top-0">Order ID</th>
                            <th class="border-top-0">Customer</th>
                            <th class="border-top-0">Date</th>
                            <th class="border-top-0">Amount</th>
                            <th class="border-top-0">Status</th>
                            <th class="border-top-0">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="font-weight-bold"><?php echo e($order->id); ?></td>
                            <td>
                                <div class="font-weight-medium"><?php echo e($order->user->name); ?></div>
                                <small class="text-muted"><?php echo e($order->user->email); ?></small>
                            </td>
                            <td><?php echo e($order->created_at->format('M d, Y')); ?></td>
                            <td class="font-weight-bold">₱<?php echo e(number_format($order->total_amount, 2)); ?></td>
                            <td>
                                <span class="badge badge-pill 
                                    <?php switch($order->status):
                                        case ('pending'): ?> badge-warning text-dark <?php break; ?>
                                        <?php case ('accepted'): ?> badge-primary <?php break; ?>
                                        <?php case ('delivered'): ?> badge-success <?php break; ?>
                                        <?php case ('cancelled'): ?> badge-danger <?php break; ?>
                                    <?php endswitch; ?>">
                                    <?php echo e(ucfirst($order->status)); ?>

                                </span>
                            </td>
                            <td class="text-nowrap">
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('orders.show', $order->id)); ?>" 
                                       class="btn btn-sm btn-outline-info rounded-pill mr-1" 
                                       title="View Details">
                                        <i class="fas fa-eye"></i> View
                                    </a>

                                    <?php if($order->status === 'pending'): ?>
                                    <form action="<?php echo e(route('admin.orders.accept', $order->id)); ?>" method="POST" class="d-inline mr-1">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill" title="Accept Order">
                                            <i class="fas fa-check mr-1"></i> Accept
                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('admin.orders.cancel', $order->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-danger rounded-pill" title="Cancel Order">
                                            <i class="fas fa-times mr-1"></i> Cancel
                                        </button>
                                    </form>
                                    <?php endif; ?>

                                    <?php if($order->status === 'accepted'): ?>
                                    <form action="<?php echo e(route('admin.orders.deliver', $order->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill">
                                            <i class="fas fa-truck mr-1"></i> Deliver
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No orders found</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
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
        padding: 0.4em 0.8em;
    }
    .table td, .table th {
        vertical-align: middle;
        padding: 1rem 0.75rem;
    }
    .font-weight-medium {
        font-weight: 500;
    }
    .border-left {
        border-left: 1px solid;
    }
    .btn-outline-info:hover, 
    .btn-outline-secondary:hover {
        color: #fff;
    }
    .card {
        border-radius: 0.5rem;
        border: none;
    }
    .btn-group .btn {
        box-shadow: none;
    }
    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }
    .empty-state {
        padding: 2rem;
    }
</style>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/admin/orders/index.blade.php ENDPATH**/ ?>