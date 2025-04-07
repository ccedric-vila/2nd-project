<?php $__env->startSection('content'); ?>
<div class="bg-gradient-to-b from-gray-50 to-white">
    <div class="mx-auto max-w-2xl px-4 py-16 sm:px-6 sm:py-24 lg:max-w-7xl lg:px-8">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Welcome to StyleSphere</h2>
            <p class="mx-auto mt-6 max-w-2xl text-xl text-gray-600">
                Discover our premium collection. Sign in to start shopping.
            </p>
            <div class="mt-8">
                <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 transition duration-150">
                    Sign In to Explore
                </a>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="grid grid-cols-1 gap-x-8 gap-y-16 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 xl:gap-x-10">
            <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group relative bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all duration-300 p-4">
                <!-- Product Image -->
                <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-xl bg-gray-200 xl:aspect-h-8 xl:aspect-w-7">
                    <?php if($product->images->count() > 0): ?>
                    <img src="<?php echo e(asset('storage/' . $product->images[0]->image_path)); ?>" 
                         alt="<?php echo e($product->product_name); ?>" 
                         class="h-full w-full object-cover object-center group-hover:opacity-85 transition-opacity duration-300">
                    <?php else: ?>
                    <div class="h-full w-full flex items-center justify-center py-12">
                        <i class="fas fa-image fa-4x text-gray-400"></i>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Hover Quick View Overlay -->
                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <div class="bg-white p-2 rounded-full shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                </div>

                <!-- Product Info -->
                <div class="mt-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-base font-medium text-gray-800 hover:text-indigo-600 transition">
                                <?php echo e($product->product_name); ?>

                            </h3>
                            <p class="mt-1 text-sm text-gray-500"><?php echo e($product->supplier->brand_name ?? 'No Brand'); ?></p>
                        </div>
                        <p class="text-lg font-semibold text-gray-900">$<?php echo e(number_format($product->sell_price, 2)); ?></p>
                    </div>

                    <!-- Reviews -->
                    <?php if($product->reviews->count() > 0): ?>
                    <div class="mt-3 pb-4 border-b border-gray-100">
                        <div class="flex items-center">
                            <?php $avgRating = $product->average_rating; ?>
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= floor($avgRating)): ?>
                                    <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php elseif($i == ceil($avgRating) && ($avgRating - floor($avgRating)) >= 0.5): ?>
                                    <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 1l1.753 5.472h5.673l-4.59 3.333 1.753 5.472L10 12.944l-4.59 3.333 1.753-5.472-4.59-3.333h5.673L10 1z"/>
                                    </svg>
                                <?php else: ?>
                                    <svg class="h-4 w-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <span class="ml-2 text-sm text-gray-500">(<?php echo e($product->reviews->count()); ?>)</span>
                        </div>
                        
                        <!-- Top Review Preview -->
                        <?php if($product->reviews->count() > 0): ?>
                        <?php $latestReview = $product->reviews->sortByDesc('created_at')->first(); ?>
                        <div class="mt-2 text-sm text-gray-600 italic bg-gray-50 p-2 rounded-md">
                            <p class="truncate">"<?php echo e($latestReview->comment); ?>"</p>
                            <p class="text-xs text-gray-500 mt-1">- <?php echo e($latestReview->user->name); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Action Buttons -->
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <form action="<?php echo e(route('login')); ?>" method="GET">
                        <input type="hidden" name="redirect_to" value="<?php echo e(url()->current()); ?>">
                        <button type="submit" class="flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors duration-150 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Add to Cart
                        </button>
                    </form>
                    <form action="<?php echo e(route('login')); ?>" method="GET">
                        <input type="hidden" name="redirect_to" value="<?php echo e(url()->current()); ?>">
                        <input type="hidden" name="buy_now" value="<?php echo e($product->id); ?>">
                        <button type="submit" class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 shadow-sm transition-colors duration-150">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Buy Now
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/landing.blade.php ENDPATH**/ ?>