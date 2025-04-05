<?php $__env->startSection('content'); ?>
<div class="bg-gray-50">
    <div class="mx-auto max-w-2xl px-4 py-12 sm:px-6 sm:py-16 lg:max-w-7xl lg:px-8">
        <!-- Hero Section with enhanced styling -->
        <div class="text-center mb-16">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">
                <span class="text-indigo-600">Style</span>Sphere
            </h1>
            <p class="mx-auto mt-6 max-w-2xl text-xl text-gray-500">
                Discover our premium collection of fashion essentials.
            </p>
            <div class="mt-8">
                <a href="<?php echo e(route('login')); ?>" class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-5 py-3 text-base font-medium text-white hover:bg-indigo-700 transition-all">
                    Sign in to start shopping
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        <!-- Category Navigation (New) -->
        <div class="flex justify-center space-x-4 mb-12 overflow-x-auto pb-4">
            <a href="#" class="px-4 py-2 text-sm font-medium text-gray-900 bg-white rounded-full border border-gray-200 hover:bg-gray-100 transition-all">All Products</a>
            <a href="#" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-900 rounded-full border border-transparent hover:border-gray-200 transition-all">Clothing</a>
            <a href="#" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-900 rounded-full border border-transparent hover:border-gray-200 transition-all">Accessories</a>
            <a href="#" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-900 rounded-full border border-transparent hover:border-gray-200 transition-all">Footwear</a>
            <a href="#" class="px-4 py-2 text-sm font-medium text-gray-500 hover:text-gray-900 rounded-full border border-transparent hover:border-gray-200 transition-all">Sale</a>
        </div>

        <!-- Section Title (New) -->
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900">Featured Products</h2>
            <div class="flex items-center space-x-2">
                <button class="p-2 rounded-full hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                    </svg>
                </button>
                <button class="p-2 rounded-full hover:bg-gray-100">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h9m5-4v12m0 0l-4-4m4 4l4-4" />
                    </svg>
                </button>
            </div>
        </div>

        <!-- Improved Product Grid -->
        <div class="grid grid-cols-1 gap-y-10 gap-x-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
            <?php $__currentLoopData = $product; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="group relative">
                <!-- Product Badge (New) -->
                <?php if($product->created_at->gt(now()->subDays(7))): ?>
                <div class="absolute top-2 left-2 z-10">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                        New
                    </span>
                </div>
                <?php endif; ?>

                <!-- Improved Product Image -->
                <div class="aspect-h-1 aspect-w-1 w-full overflow-hidden rounded-lg bg-gray-100 transition-all duration-300 group-hover:shadow-lg">
                    <?php if($product->images->count() > 0): ?>
                    <img src="<?php echo e(asset('storage/' . $product->images[0]->image_path)); ?>" 
                         alt="<?php echo e($product->product_name); ?>" 
                         class="h-full w-full object-cover object-center group-hover:scale-105 transition-all duration-300">
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300"></div>
                    <?php else: ?>
                    <div class="h-full w-full flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Wishlist Button (New) -->
                <button class="absolute top-2 right-2 p-1.5 rounded-full bg-white bg-opacity-70 hover:bg-opacity-100 shadow-sm transition-all duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 hover:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </button>

                <!-- Improved Product Info -->
                <div class="mt-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-base font-medium text-gray-900 group-hover:text-indigo-600 transition-colors">
                                <?php echo e($product->product_name); ?>

                            </h3>
                            <p class="mt-1 text-sm text-gray-500"><?php echo e($product->supplier->brand_name ?? 'No Brand'); ?></p>
                        </div>
                        <p class="text-base font-semibold text-indigo-600">$<?php echo e(number_format($product->sell_price, 2)); ?></p>
                    </div>

                    <!-- Improved Reviews -->
                    <?php if($product->reviews->count() > 0): ?>
                    <div class="mt-2">
                        <div class="flex items-center">
                            <?php $avgRating = $product->average_rating; ?>
                            <?php for($i = 1; $i <= 5; $i++): ?>
                                <?php if($i <= floor($avgRating)): ?>
                                    <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php elseif($i == ceil($avgRating) && ($avgRating - floor($avgRating)) >= 0.5): ?>
                                    <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php else: ?>
                                    <svg class="h-4 w-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <span class="ml-1 text-xs font-medium text-gray-500">(<?php echo e($product->reviews->count()); ?>)</span>
                        </div>
                        
                        <!-- Improved Review Preview -->
                        <?php if($product->reviews->count() > 0): ?>
                        <?php $latestReview = $product->reviews->sortByDesc('created_at')->first(); ?>
                        <div class="mt-2 text-xs text-gray-500 bg-gray-50 p-2 rounded-md">
                            <p class="italic">"<?php echo e(\Illuminate\Support\Str::limit($latestReview->comment, 60)); ?>"</p>
                            <p class="mt-1 font-medium">- <?php echo e($latestReview->user->name); ?></p>
                        </div>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Improved Action Buttons -->
                <div class="mt-4 grid grid-cols-2 gap-3">
                    <form action="<?php echo e(route('login')); ?>" method="GET">
                        <input type="hidden" name="redirect_to" value="<?php echo e(url()->current()); ?>">
                        <button type="submit" class="flex w-full items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2.5 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Add to Cart
                        </button>
                    </form>
                    <form action="<?php echo e(route('login')); ?>" method="GET">
                        <input type="hidden" name="redirect_to" value="<?php echo e(url()->current()); ?>">
                        <input type="hidden" name="buy_now" value="<?php echo e($product->id); ?>">
                        <button type="submit" class="flex w-full items-center justify-center rounded-md border border-transparent bg-indigo-600 px-3 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                            Buy Now
                        </button>
                    </form>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <!-- Pagination (New) -->
        <div class="mt-12 flex justify-center">
            <nav class="flex items-center justify-between">
                <div class="flex flex-1 justify-between sm:hidden">
                    <a href="#" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Previous</a>
                    <a href="#" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">Next</a>
                </div>
                <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-center">
                    <div>
                        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <a href="#" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="#" aria-current="page" class="relative z-10 inline-flex items-center bg-indigo-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">1</a>
                            <a href="#" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">2</a>
                            <a href="#" class="relative hidden items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0 md:inline-flex">3</a>
                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 ring-1 ring-inset ring-gray-300 focus:outline-offset-0">...</span>
                            <a href="#" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </nav>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Newsletter Section (New) -->
        <div class="mt-16 bg-indigo-50 rounded-xl p-8">
            <div class="text-center">
                <h3 class="text-lg font-medium text-indigo-900">Subscribe to our newsletter</h3>
                <p class="mt-2 text-sm text-indigo-700">Get notified about new arrivals and exclusive offers</p>
                <div class="mt-4 flex max-w-md mx-auto">
                    <input type="email" class="block w-full rounded-l-md border-0 py-3 px-4 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600" placeholder="Enter your email">
                    <button type="submit" class="flex items-center justify-center rounded-r-md border border-transparent bg-indigo-600 px-5 py-3 text-base font-medium text-white hover:bg-indigo-700">Subscribe</button>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/landing.blade.php ENDPATH**/ ?>