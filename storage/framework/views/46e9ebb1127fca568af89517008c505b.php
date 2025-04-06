<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>StyleSphere</title>

    <!-- Favicon -->
    <link rel="icon" href="https://i.imgur.com/JVZRkhB.png" type="image/png">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Montserrat:300,400,500,600,700" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Scripts -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/sass/app.scss', 'resources/js/app.js']); ?>
    
    <style>
        :root {
            --primary-color: #2a2a2a;
            --secondary-color: #f8f9fa;
            --accent-color: #e83e8c;
            --light-color: #ffffff;
            --dark-color: #1a1a1a;
            --text-color: #333333;
            --light-text: #f8f9fa;
            --border-radius: 4px;
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            color: var(--text-color);
            background-color: var(--secondary-color);
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        
        #app {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        .navbar {
            background-color: var(--light-color) !important;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            padding: 0.8rem 0;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
            color: var(--primary-color) !important;
            display: flex;
            align-items: center;
        }
        
        .navbar-brand img {
            margin-right: 10px;
            height: 30px;
        }
        
        .nav-link {
            font-weight: 500;
            color: var(--primary-color) !important;
            margin: 0 0.5rem;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover {
            color: var(--accent-color) !important;
        }
        
        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }
        
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-radius: var(--border-radius);
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
            transition: all 0.3s ease;
        }
        
        .dropdown-item:hover {
            background-color: var(--accent-color);
            color: var(--light-color);
        }
        
        .cart-badge {
            font-size: 0.6rem;
            top: -8px;
            right: -8px;
        }
        
        main {
            flex: 1;
            padding: 2rem 0;
        }

        /* Chart-specific styles */
        .chart-container {
            background-color: var(--light-color);
            border-radius: var(--border-radius);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .chart-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
        }

        .chart-controls {
            display: flex;
            gap: 1rem;
        }

        .chart-card {
            background-color: var(--light-color);
            border-radius: var(--border-radius);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        canvas {
            width: 100% !important;
            height: auto !important;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="<?php echo e(auth()->check() ? (auth()->user()->role === 'admin' ? route('admin.dashboard') : route('home')) : url('/')); ?>">
                <img src="<?php echo e(asset('storage/logo/logo.png')); ?>" alt="StyleSphere Logo">
                StyleSphere
                </a>

                <!-- Only show toggle button if user is logged in -->
                <?php if(auth()->guard()->check()): ?>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <?php endif; ?>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ms-auto align-items-center">
                        <?php if(auth()->guard()->guest()): ?>
                            <!-- Guest Links -->
                            <?php if(Route::has('login')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('login')); ?>"><?php echo e(__('Login')); ?></a>
                                </li>
                            <?php endif; ?>

                            <?php if(Route::has('register')): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('register')); ?>"><?php echo e(__('Register')); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- ADMIN: Show dashboard link -->
                            <?php if(Auth::user()->role === 'admin'): ?>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('admin.dashboard')); ?>">
                                        <i class="fas fa-chart-line me-1"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="<?php echo e(route('logout')); ?>"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-1"></i> Logout
                                    </a>
                                </li>
                            
                            <!-- USER: Show full navigation -->
                            <?php else: ?>
                                <!-- Shopping Cart -->
                                <li class="nav-item me-3">
                                    <a href="<?php echo e(route('cart.index')); ?>" class="nav-link position-relative">
                                        <i class="fas fa-shopping-bag"></i>
                                        <?php if(auth()->user()->cart && auth()->user()->cart->count() > 0): ?>
                                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger cart-badge">
                                            <?php echo e(auth()->user()->cart->count()); ?>

                                        </span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                                
                                <!-- User Dropdown -->
                                <li class="nav-item dropdown">
                                    <a id="userDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        <i class="fas fa-user-circle me-1"></i> <?php echo e(Auth::user()->name); ?>

                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                        <a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">
                                            <i class="fas fa-user-edit me-2"></i> Edit Profile
                                        </a>
                                        <a class="dropdown-item" href="<?php echo e(route('orders.history')); ?>">
                                            <i class="fas fa-history me-2"></i> Order History
                                        </a>
                                        <div class="dropdown-divider"></div>
                                        <a class="dropdown-item" href="<?php echo e(route('logout')); ?>"
                                           onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt me-2"></i> <?php echo e(__('Logout')); ?>

                                        </a>
                                    </div>
                                </li>
                            <?php endif; ?>
                            
                            <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST" class="d-none">
                                <?php echo csrf_field(); ?>
                            </form>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                <?php echo $__env->yieldContent('content'); ?>
            </div>
        </main>
    </div>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/layouts/app.blade.php ENDPATH**/ ?>