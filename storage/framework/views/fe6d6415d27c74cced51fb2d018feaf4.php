

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fw-bold">Sales Records</h1>
                <div>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-outline-secondary rounded-pill">
                        <i class="fas fa-arrow-left mr-2"></i>Dashboard
                    </a>
                    <a href="<?php echo e(route('admin.sales.analytics')); ?>" class="btn btn-primary rounded-pill">
                        <i class="fas fa-chart-pie me-1"></i> Charts
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4 border-0 rounded-3">
        <div class="card-header bg-light py-3 border-0">
            <h5 class="mb-0 text-primary"><i class="fas fa-filter me-2"></i>Filter Sales Data</h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3" id="filter-form">
                <div class="col-md-4">
                    <label for="search" class="form-label fw-medium">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="<?php echo e(request('search')); ?>" placeholder="Product, Customer, or Order #">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <label for="month" class="form-label fw-medium">Month</label>
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
                    <label for="year" class="form-label fw-medium">Year</label>
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
                    <button type="submit" class="btn btn-primary rounded-pill">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="<?php echo e(route('admin.sales.index')); ?>" class="btn btn-outline-secondary rounded-pill">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-header bg-light py-3 border-0">
            <h5 class="mb-0 text-primary"><i class="fas fa-file-invoice-dollar me-2"></i>Sales Data</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th class="text-nowrap border-0">Date</th>
                            <th class="text-nowrap border-0">Order</th>
                            <th class="border-0">Product</th>
                            <th class="border-0">Customer</th>
                            <th class="text-end border-0">Qty</th>
                            <th class="text-end border-0">Unit Price</th>
                            <th class="text-end border-0">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $sales; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sale): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-nowrap"><?php echo e($sale->sale_date->format('M d, Y')); ?></td>
                            <td class="fw-medium"><?php echo e($sale->order->order_number ?? 'N/A'); ?></td>
                            <td><?php echo e($sale->product->product_name ?? 'Product deleted'); ?></td>
                            <td><?php echo e($sale->user->name ?? 'User deleted'); ?></td>
                            <td class="text-end"><?php echo e(number_format($sale->quantity)); ?></td>
                            <td class="text-end">₱<?php echo e(number_format($sale->unit_price, 2)); ?></td>
                            <td class="text-end fw-bold">₱<?php echo e(number_format($sale->total_price, 2)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                    <p class="mb-0">No sales records found</p>
                                </div>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                    <?php if($sales->count()): ?>
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="6" class="text-end">Total:</th>
                            <th class="text-end fs-5">₱<?php echo e(number_format($sales->sum('total_price'), 2)); ?></th>
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