

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Sales Records</h1>
                <div>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                    <a href="<?php echo e(route('admin.sales.analytics')); ?>" class="btn btn-primary me-2">
                        <i class="fas fa-chart-pie"></i> Charts
                    </a>
                </div>
            </div>
        </div>
    </div>

    

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3" id="filter-form">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="<?php echo e(request('search')); ?>" placeholder="Product, Customer, or Order #">
                </div>
                
                <div class="col-md-3">
                    <label for="month" class="form-label">Month</label>
                    <select class="form-select" id="month" name="month">
                        <option value="0">All Months</option>
                        <?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $month): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($month); ?>" <?php echo e(request('month') == $month ? 'selected' : ''); ?>>
                                <?php echo e(DateTime::createFromFormat('!m', $month)->format('F')); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="year" class="form-label">Year</label>
                    <select class="form-select" id="year" name="year">
                        <option value="">All Years</option>
                        <?php $__currentLoopData = $years; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $year): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($year); ?>" <?php echo e(request('year') == $year ? 'selected' : ''); ?>>
                                <?php echo e($year); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="<?php echo e(route('admin.sales.index')); ?>" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Order #</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($sale->sale_date->format('M d, Y')); ?></td>
                            <td>#<?php echo e($sale->order->order_number ?? 'N/A'); ?></td>
                            <td><?php echo e($sale->product->product_name ?? 'Product deleted'); ?></td>
                            <td><?php echo e($sale->user->name ?? 'User deleted'); ?></td>
                            <td class="text-end"><?php echo e(number_format($sale->quantity)); ?></td>
                            <td class="text-end">$<?php echo e(number_format($sale->unit_price, 2)); ?></td>
                            <td class="text-end fw-bold">$<?php echo e(number_format($sale->total_price, 2)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4">No sales records found</td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if($sales->count()): ?>
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-end">Total:</th>
                            <th class="text-end">$<?php echo e(number_format($sales->sum('total_price'), 2)); ?></th>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                <?php echo e($sales->withQueryString()->links()); ?>

            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit when filters change
    document.getElementById('month').addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });
    
    document.getElementById('year').addEventListener('change', function() {
        document.getElementById('filter-form').submit();
    });

    // Loading indicator
    const form = document.getElementById('filter-form');
    if (form) {
        form.addEventListener('submit', function() {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Filtering...';
                submitBtn.disabled = true;
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/admin/sales/index.blade.php ENDPATH**/ ?>