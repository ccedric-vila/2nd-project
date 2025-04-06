<?php $__env->startSection('content'); ?>
<div class="container">
    <h1 class="text-center mb-4">Welcome to StyleSphere</h1>
    
    <!-- Search Form - Updated with Working Price Range Filter -->
    <div class="container mb-4">
        <form action="<?php echo e(route('home')); ?>" method="GET">
            <!-- Existing Search and Price Range Row -->
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" 
                            name="search" 
                            class="form-control" 
                            placeholder="Search products..."
                            value="<?php echo e(request('search')); ?>">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <?php if(request('search') || request('price_range') || request('size') || request('category') || request('types') || request('brand')): ?>
                            <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-secondary">
                                Clear All
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="input-group">
                    <label class="input-group-text" for="size">Price:</label>
                        <select name="price_range" class="form-select" onchange="this.form.submit()">
                            <option value="">All Prices</option>
                            <option value="0-1000" <?php echo e(request('price_range') == '0-1000' ? 'selected' : ''); ?>>$0 - $1000</option>
                            <option value="1001-2000" <?php echo e(request('price_range') == '1001-2000' ? 'selected' : ''); ?>>$1001 - $2000</option>
                            <option value="2001-3000" <?php echo e(request('price_range') == '2001-3000' ? 'selected' : ''); ?>>$2001 - $3000</option>
                            <option value="3001-4000" <?php echo e(request('price_range') == '3001-4000' ? 'selected' : ''); ?>>$3001 - $4000</option>
                            <option value="4001-5000" <?php echo e(request('price_range') == '4001-5000' ? 'selected' : ''); ?>>$4001 - $5000</option>
                            <option value="5001-6000" <?php echo e(request('price_range') == '5001-6000' ? 'selected' : ''); ?>>$5001 - $6000</option>
                            <option value="6001-7000" <?php echo e(request('price_range') == '6001-7000' ? 'selected' : ''); ?>>$6001 - $7000</option>
                            <option value="7001-8000" <?php echo e(request('price_range') == '7001-8000' ? 'selected' : ''); ?>>$7001 - $8000</option>
                            <option value="8001-9000" <?php echo e(request('price_range') == '8001-9000' ? 'selected' : ''); ?>>$8001 - $9000</option>
                            <option value="9001-10000" <?php echo e(request('price_range') == '9001-10000' ? 'selected' : ''); ?>>$9001 - $10000</option>
                        </select>
                        <?php if(request('price_range')): ?>
                            <a href="<?php echo e(route('home', array_merge(request()->except('price_range'), ['page' => null]))); ?>" class="btn btn-outline-secondary">
                                Clear Price
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- NEW FILTER ROW ADDED HERE -->
            <div class="row g-3 mt-2">
                <!-- Size Filter -->
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="size">Size</label>
                        <select name="size" id="size" class="form-select" onchange="this.form.submit()">
                            <option value="">All Sizes</option>
                            <option value="XS" <?php echo e(request('size') == 'XS' ? 'selected' : ''); ?>>XS</option>
                            <option value="S" <?php echo e(request('size') == 'S' ? 'selected' : ''); ?>>S</option>
                            <option value="M" <?php echo e(request('size') == 'M' ? 'selected' : ''); ?>>M</option>
                            <option value="L" <?php echo e(request('size') == 'L' ? 'selected' : ''); ?>>L</option>
                            <option value="XL" <?php echo e(request('size') == 'XL' ? 'selected' : ''); ?>>XL</option>
                            <option value="XXL" <?php echo e(request('size') == 'XXL' ? 'selected' : ''); ?>>XXL</option>
                        </select>
                        <?php if(request('size')): ?>
                            <a href="<?php echo e(route('home', array_merge(request()->except('size'), ['page' => null]))); ?>" class="btn btn-outline-secondary">
                                Clear
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Category Filter -->
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="category">Category</label>
                        <select name="category" id="category" class="form-select" onchange="this.form.submit()">
                            <option value="">All Categories</option>
                            <option value="Mens" <?php echo e(request('category') == 'Mens' ? 'selected' : ''); ?>>Mens</option>
                            <option value="Womens" <?php echo e(request('category') == 'Womens' ? 'selected' : ''); ?>>Womens</option>
                            <option value="Kids" <?php echo e(request('category') == 'Kids' ? 'selected' : ''); ?>>Kids</option>
                        </select>
                        <?php if(request('category')): ?>
                            <a href="<?php echo e(route('home', array_merge(request()->except('category'), ['page' => null]))); ?>" class="btn btn-outline-secondary">
                                Clear
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Types Filter -->
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="types">Types</label>
                        <select name="types" id="types" class="form-select" onchange="this.form.submit()">
                            <option value="">All Types</option>
                            <option value="T-shirt" <?php echo e(request('types') == 'T-shirt' ? 'selected' : ''); ?>>T-shirt</option>
                            <option value="Polo Shirt" <?php echo e(request('types') == 'Polo Shirt' ? 'selected' : ''); ?>>Polo Shirt</option>
                            <option value="Sweater" <?php echo e(request('types') == 'Sweater' ? 'selected' : ''); ?>>Sweater</option>
                            <option value="Hoodie" <?php echo e(request('types') == 'Hoodie' ? 'selected' : ''); ?>>Hoodie</option>
                            <option value="Jersey" <?php echo e(request('types') == 'Jersey' ? 'selected' : ''); ?>>Jersey</option>
                            <option value="Dress" <?php echo e(request('types') == 'Dress' ? 'selected' : ''); ?>>Dress</option>
                            <option value="Sweatshirt" <?php echo e(request('types') == 'Sweatshirt' ? 'selected' : ''); ?>>Sweatshirt</option>
                            <option value="Pants" <?php echo e(request('types') == 'Pants' ? 'selected' : ''); ?>>Pants</option>
                            <option value="Shorts" <?php echo e(request('types') == 'Shorts' ? 'selected' : ''); ?>>Shorts</option>
                        </select>
                        <?php if(request('types')): ?>
                            <a href="<?php echo e(route('home', array_merge(request()->except('types'), ['page' => null]))); ?>" class="btn btn-outline-secondary">
                                Clear
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Brand Filter - Corrected Version -->
                <div class="col-md-3">
                    <div class="input-group">
                        <label class="input-group-text" for="brand">Brand</label>
                        <select name="brand" id="brand" class="form-select" onchange="this.form.submit()">
                            <option value="">All Brands</option>
                            <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $supplier): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($supplier->supplier_id); ?>" <?php echo e(request('brand') == $supplier->supplier_id ? 'selected' : ''); ?>>
                                    <?php echo e($supplier->brand_name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php if(request('brand')): ?>
                            <a href="<?php echo e(route('home', array_merge(request()->except('brand'), ['page' => null]))); ?>" class="btn btn-outline-secondary">
                                Clear
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </form>

        <!-- Price Sorting Options - Single Toggle Button Version -->
        <?php if($products->isNotEmpty()): ?>
        <div class="mt-3 d-flex justify-content-end">
            <?php
                $currentSort = request('sort');
                $nextSort = ($currentSort == 'price_asc') ? 'price_desc' : 'price_asc';
                $buttonText = ($currentSort == 'price_asc') ? 'Low to High' : 'High to Low';
                $buttonIcon = ($currentSort == 'price_asc') ? 'fa-arrow-down' : 'fa-arrow-up';
                $buttonClass = ($currentSort == 'price_asc' || $currentSort == 'price_desc') ? 'btn-primary' : 'btn-outline-primary';
            ?>
            
            <div class="btn-group" role="group">
                <span class="input-group-text bg-transparent border-0 pe-1 small">Sort by:</span>
                <a href="<?php echo e(request()->fullUrlWithQuery(['sort' => $nextSort, 'page' => null])); ?>" 
                   class="btn <?php echo e($buttonClass); ?>">
                    <i class="fas <?php echo e($buttonIcon); ?>"></i> <?php echo e($buttonText); ?>

                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Product Display -->
    <div class="row">
        <?php if(request('search') && $products->isEmpty()): ?>
            <div class="col-12 text-center py-5">
                <h4 class="text-muted">No products found for "<?php echo e(request('search')); ?>"</h4>
            </div>
        <?php elseif($products->isNotEmpty()): ?>
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 h-100">
                        <?php if($product->images->count() > 0): ?>
                        <div id="productCarousel-<?php echo e($product->id); ?>" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                <?php $__currentLoopData = $product->images; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="carousel-item <?php echo e($key === 0 ? 'active' : ''); ?>">
                                    <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" 
                                        class="d-block w-100 card-img-top" 
                                        style="height: 300px; object-fit: cover;" 
                                        alt="<?php echo e($product->product_name); ?>">
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php if($product->images->count() > 1): ?>
                            <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel-<?php echo e($product->id); ?>" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#productCarousel-<?php echo e($product->id); ?>" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                            <?php endif; ?>
                        </div>
                        <?php else: ?>
                        <div class="bg-light d-flex align-items-center justify-content-center" style="height: 300px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                        <?php endif; ?>

                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo e($product->product_name); ?></h5>
                            <p class="card-text"><strong>Brand:</strong> <?php echo e($product->supplier->brand_name ?? 'No Brand'); ?></p>
                            
                            <div class="mb-2">
                                <?php if($product->category): ?>
                                    <span class="badge bg-primary me-1"><?php echo e($product->category); ?></span>
                                <?php endif; ?>
                                
                                <?php if($product->types): ?>
                                    <span class="badge bg-secondary me-1"><?php echo e($product->types); ?></span>
                                <?php endif; ?>
                                
                                <?php if($product->size): ?>
                                    <span class="badge bg-info"><?php echo e($product->size); ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <p class="card-text"><strong>Description:</strong> <?php echo e(Str::limit($product->description, 100)); ?></p>
                            <p class="card-text"><strong>Price:</strong> $<?php echo e(number_format($product->sell_price, 2)); ?></p>
                            <p class="card-text"><strong>Stock:</strong> <?php echo e($product->stock); ?></p>
                            
                            <div class="mb-3">
                                <?php if($product->reviews->count() > 0): ?>
                                    <div class="d-flex align-items-center mb-1">
                                        <?php
                                            $avgRating = $product->average_rating;
                                            $fullStars = floor($avgRating);
                                            $hasHalfStar = ($avgRating - $fullStars) >= 0.5;
                                        ?>
                                        
                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                            <?php if($i <= $fullStars): ?>
                                                <i class="fas fa-star text-warning"></i>
                                            <?php elseif($i == $fullStars + 1 && $hasHalfStar): ?>
                                                <i class="fas fa-star-half-alt text-warning"></i>
                                            <?php else: ?>
                                                <i class="far fa-star text-warning"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <span class="ms-2"><?php echo e(number_format($avgRating, 1)); ?> (<?php echo e($product->reviews_count); ?> reviews)</span>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <?php $__currentLoopData = $product->reviews->take(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="review-item mb-2 pb-2 border-bottom">
                                                <div class="d-flex justify-content-between">
                                                    <strong><?php echo e($review->user->name); ?></strong>
                                                    <div>
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star<?php echo e($i > $review->rating ? '-empty' : ''); ?> text-warning"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                <?php if($review->comment): ?>
                                                    <p class="mb-0 small">"<?php echo e(Str::limit($review->comment, 80)); ?>"</p>
                                                <?php endif; ?>
                                                <small class="text-muted"><?php echo e($review->created_at->diffForHumans()); ?></small>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-muted">
                                        <i class="far fa-star"></i> No reviews yet
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="mt-auto">
                                <div class="d-flex justify-content-between align-items-center">
                                <form action="<?php echo e(route('cart.add')); ?>" method="POST" class="mb-2">
                                    <?php echo csrf_field(); ?>
                                    <input type="hidden" name="product_id" value="<?php echo e($product->product_id); ?>">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="fas fa-shopping-cart me-1"></i> Add to Cart
                                    </button>
                                </form>

                                    <form action="<?php echo e(route('checkout.handle-single')); ?>" method="POST" class="mb-2">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="product_id" value="<?php echo e($product->product_id); ?>">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-bolt me-1"></i> Buy Now
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <?php if($product->reviews->count() > 2): ?>
                                <button class="btn btn-outline-secondary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#reviewsModal-<?php echo e($product->id); ?>">
                                    <i class="fas fa-comment me-1"></i> View All Reviews (<?php echo e($product->reviews->count()); ?>)
                                </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="reviewsModal-<?php echo e($product->id); ?>" tabindex="-1" aria-labelledby="reviewsModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="reviewsModalLabel">Reviews for <?php echo e($product->product_name); ?></h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <?php if($product->reviews->count() > 0): ?>
                                    <div class="mb-4">
                                        <div class="d-flex align-items-center">
                                            <h4 class="me-3"><?php echo e(number_format($product->average_rating, 1)); ?></h4>
                                            <div>
                                                <div class="d-flex mb-1">
                                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                                        <?php if($i <= $fullStars): ?>
                                                            <i class="fas fa-star text-warning"></i>
                                                        <?php elseif($i == $fullStars + 1 && $hasHalfStar): ?>
                                                            <i class="fas fa-star-half-alt text-warning"></i>
                                                        <?php else: ?>
                                                            <i class="far fa-star text-warning"></i>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </div>
                                                <p class="mb-0"><?php echo e($product->reviews->count()); ?> reviews</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="reviews-list">
                                        <?php $__currentLoopData = $product->reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="review-item mb-3 pb-3 border-bottom">
                                                <div class="d-flex justify-content-between mb-2">
                                                    <strong><?php echo e($review->user->name ?? 'Anonymous'); ?></strong>
                                                    <div>
                                                        <?php for($i = 1; $i <= 5; $i++): ?>
                                                            <i class="fas fa-star<?php echo e($i > $review->rating ? '-empty' : ''); ?> text-warning"></i>
                                                        <?php endfor; ?>
                                                    </div>
                                                </div>
                                                <?php if($review->comment): ?>
                                                    <p class="mb-0">"<?php echo e($review->comment); ?>"</p>
                                                <?php else: ?>
                                                    <p class="text-muted mb-0">No comment provided</p>
                                                <?php endif; ?>
                                                <small class="text-muted d-block mt-2"><?php echo e($review->created_at->format('M d, Y')); ?></small>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                <?php else: ?>
                                    <p class="text-center">No reviews yet for this product.</p>
                                <?php endif; ?>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h3>No products available</h3>
                        <p class="text-muted">Check back later for new arrivals</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if($products->hasPages()): ?>
    <div class="d-flex justify-content-center mt-4">
        <?php echo e($products->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            const max = parseInt(this.getAttribute('max'));
            const min = parseInt(this.getAttribute('min'));
            let value = parseInt(this.value);
            
            if (isNaN(value)) value = min;
            if (value > max) value = max;
            if (value < min) value = min;
            
            this.value = value;
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/home.blade.php ENDPATH**/ ?>