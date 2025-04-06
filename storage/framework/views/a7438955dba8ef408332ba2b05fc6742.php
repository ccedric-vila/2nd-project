<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-lg border-0 rounded-lg overflow-hidden">
                <div class="card-header bg-gradient-primary p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-light btn-sm px-3 shadow-sm">
                                <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                            </a>
                            <a href="<?php echo e(route('admin.products.import')); ?>" class="btn btn-info btn-sm px-3 shadow-sm ms-2">
                                <i class="fas fa-file-import me-2"></i> Import Products
                            </a>
                        </div>
                        <h2 class="text-white m-0 fw-bold">Product Management</h2>
                        <a href="<?php echo e(route('admin.product.create')); ?>" class="btn btn-success btn-sm px-3 shadow-sm">
                            <i class="fas fa-plus me-2"></i> Add New Product
                        </a>
                    </div>
                </div>

                <div class="card-body p-0">
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show m-3 border-left border-success border-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle me-2 text-success fa-lg"></i>
                                <span><?php echo e(session('success')); ?></span>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="px-4 py-3">Image</th>
                                    <th class="px-4 py-3">Name</th>
                                    <th class="px-4 py-3">Supplier</th>
                                    <th class="px-4 py-3">Details</th>
                                    <th class="px-4 py-3">Pricing</th>
                                    <th class="px-4 py-3">Stock</th>
                                    <th class="px-4 py-3 text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $productItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="border-top">
                                        <td class="px-4 py-3">
                                            <?php if($productItem->images->isNotEmpty()): ?>
                                                <div class="product-img-container">
                                                    <img src="<?php echo e(asset('storage/' . $productItem->images->first()->image_path)); ?>" 
                                                        alt="<?php echo e($productItem->product_name); ?>" 
                                                        class="img-thumbnail rounded shadow-sm" 
                                                        width="80"
                                                        style="object-fit: cover; height: 80px;">
                                                </div>
                                            <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" 
                                                    style="width: 80px; height: 80px;">
                                                    <i class="fas fa-image text-muted fa-2x"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold"><?php echo e($productItem->product_name); ?></span>
                                                <span class="badge bg-light text-muted rounded-pill">ID: <?php echo e($productItem->product_id); ?></span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <?php if($productItem->supplier): ?>
                                                <span class="d-inline-block text-truncate" style="max-width: 150px;" title="<?php echo e($productItem->supplier->brand_name); ?>">
                                                    <i class="fas fa-building me-1 text-muted"></i> <?php echo e($productItem->supplier->brand_name); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-danger text-white rounded-pill">
                                                    <i class="fas fa-exclamation-circle me-1"></i> No Supplier
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex flex-wrap gap-1">
                                                <span class="badge rounded-pill bg-light text-dark border shadow-sm">
                                                    <i class="fas fa-ruler me-1 text-primary"></i> <?php echo e($productItem->size); ?>

                                                </span>
                                                <span class="badge rounded-pill bg-light text-dark border shadow-sm">
                                                    <i class="fas fa-tag me-1 text-info"></i> <?php echo e($productItem->category); ?>

                                                </span>
                                                <span class="badge rounded-pill bg-light text-dark border shadow-sm">
                                                    <i class="fas fa-layer-group me-1 text-secondary"></i> <?php echo e($productItem->types); ?>

                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="pricing-details">
                                                <div class="mb-1">Cost: <span class="fw-bold text-muted">₱<?php echo e(number_format($productItem->cost_price, 2)); ?></span></div>
                                                <div class="mb-1">Sell: <span class="fw-bold">₱<?php echo e(number_format($productItem->sell_price, 2)); ?></span></div>
                                                <div class="small px-2 py-1 rounded <?php echo e($productItem->sell_price > $productItem->cost_price ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'); ?>">
                                                    <i class="fas <?php echo e($productItem->sell_price > $productItem->cost_price ? 'fa-arrow-up' : 'fa-arrow-down'); ?> me-1"></i>
                                                    Margin: ₱<?php echo e(number_format($productItem->sell_price - $productItem->cost_price, 2)); ?>

                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="stock-indicator">
                                                <span class="badge rounded-pill bg-<?php echo e($productItem->stock > 10 ? 'success' : ($productItem->stock > 0 ? 'warning' : 'danger')); ?> text-white shadow-sm">
                                                    <i class="fas <?php echo e($productItem->stock > 10 ? 'fa-box-full' : ($productItem->stock > 0 ? 'fa-box-open' : 'fa-box')); ?> me-1"></i>
                                                    <?php echo e($productItem->stock); ?> in stock
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <div class="d-flex justify-content-center gap-2">
                                                <a href="<?php echo e(route('admin.product.edit', $productItem->product_id)); ?>" 
                                                class="btn btn-primary btn-sm rounded-circle shadow-sm" title="Edit Product">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="<?php echo e(route('admin.product.destroy', $productItem->product_id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-circle shadow-sm" 
                                                            onclick="return confirm('Are you sure you want to delete this product?')"
                                                            title="Delete Product">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5">
                                            <div class="empty-state">
                                                <i class="fas fa-box-open text-muted fa-4x mb-3"></i>
                                                <h5 class="text-muted">No products found.</h5>
                                                <p class="text-muted">Get started by adding your first product to inventory.</p>
                                                <a href="<?php echo e(route('admin.product.create')); ?>" class="btn btn-primary mt-2 shadow-sm">
                                                    <i class="fas fa-plus me-2"></i> Add Your First Product
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if($product->hasPages()): ?>
                        <div class="d-flex justify-content-center py-4">
                            <?php echo e($product->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    /* Custom styling */
    body {
        background-color: #f8f9fe;
        font-family: 'Poppins', sans-serif;
    }
    
    /* Card styling */
    .card {
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    /* Enhanced header gradient */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
    }
    
    /* Table styling */
    .table {
        font-size: 0.95rem;
        margin-bottom: 0;
    }
    
    .table > :not(caption) > * > * {
        padding: 1rem 1rem;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: rgba(94, 114, 228, 0.05);
    }
    
    /* Product image styling */
    .product-img-container {
        position: relative;
        overflow: hidden;
        border-radius: 8px;
    }
    
    .img-thumbnail {
        transition: transform 0.3s ease;
        border: none;
    }
    
    .img-thumbnail:hover {
        transform: scale(1.8);
        z-index: 10;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
    }
    
    /* Badge styling */
    .badge {
        font-weight: 500;
        padding: 0.5em 0.85em;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
    }
    
    .badge.rounded-pill {
        padding-right: 0.85em;
        padding-left: 0.85em;
    }
    
    .badge:hover {
        box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        transform: translateY(-2px);
    }
    
    /* Pricing details styling */
    .pricing-details {
        line-height: 1.5;
    }
    
    .bg-success-subtle {
        background-color: rgba(45, 206, 137, 0.15);
    }
    
    .bg-danger-subtle {
        background-color: rgba(245, 54, 92, 0.15);
    }
    
    /* Stock indicator styling */
    .stock-indicator .badge {
        padding: 0.6em 1em;
        font-size: 0.75rem;
    }
    
    /* Button styling */
    .btn {
        font-weight: 500;
        letter-spacing: 0.3px;
        transition: all 0.3s ease;
    }
    
    .btn-sm {
        padding: 0.4rem 0.8rem;
    }
    
    .btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.15);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
        border: none;
    }
    
    .btn-success {
        background: linear-gradient(135deg, #2dce89 0%, #26a69a 100%);
        border: none;
    }
    
    .btn-info {
        background: linear-gradient(135deg, #11cdef 0%, #1171ef 100%);
        border: none;
        color: white;
    }
    
    .btn-danger {
        background: linear-gradient(135deg, #f5365c 0%, #f56036 100%);
        border: none;
    }
    
    /* Empty state styling */
    .empty-state {
        padding: 2rem;
        color: #8898aa;
    }
    
    /* Action buttons */
    .btn.rounded-circle {
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    /* Alert styling */
    .alert {
        border-radius: 10px;
    }
    
    .border-left {
        border-left-width: 4px !important;
    }
    
    /* Pagination styling */
    .pagination {
        gap: 5px;
    }
    
    .page-item .page-link {
        border-radius: 50%;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        color: #5e72e4;
        border: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .page-item.active .page-link {
        background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
    }
    
    /* Font Awesome icons */
    .fa-box-full:before {
        content: "\f49f"; /* This is for the full box icon */
    }
</style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/admin/product/index.blade.php ENDPATH**/ ?>