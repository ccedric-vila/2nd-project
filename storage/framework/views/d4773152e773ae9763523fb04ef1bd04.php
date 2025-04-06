<?php $__env->startSection('content'); ?>
    <!-- StyleSphere Header with Enhanced Design -->
    <div class="header bg-gradient-primary py-7 py-lg-8" style="background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);">
        <div class="container">
            <div class="header-body text-center mb-7">
                <div class="row justify-content-center">
                    <div class="col-lg-8 col-md-10">
                        <!-- Animated StyleSphere Text -->
                        <h1 class="text-white display-2 animate__animated animate__fadeInDown" style="font-family: 'Poppins', sans-serif; font-weight: 800; letter-spacing: 1px; text-shadow: 3px 3px 10px rgba(0, 0, 0, 0.4);">
                            StyleSphere
                        </h1>
                        <!-- Subtitle with Animation -->
                        <p class="text-lead text-light animate__animated animate__fadeInUp" style="font-size: 1.6rem; font-family: 'Poppins', sans-serif; font-weight: 300; letter-spacing: 0.5px; text-shadow: 1px 1px 5px rgba(0, 0, 0, 0.3);">
                            Welcome to the Admin Dashboard
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Enhanced Animated Waves Background -->
        <div class="wave-container">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
                <path fill="#ffffff" fill-opacity="0.8" d="M0,160L48,149.3C96,139,192,117,288,128C384,139,480,181,576,192C672,203,768,181,864,160C960,139,1056,117,1152,112C1248,107,1344,117,1392,122.7L1440,128L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
            </svg>
        </div>
    </div>

    <!-- Admin Dashboard Links -->
    <div class="container mt--9 pb-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-transparent pb-4">
                        <h3 class="mb-0 text-center" style="font-family: 'Poppins', sans-serif; font-weight: 700; color: #344767; position: relative;">
                            <span class="badge badge-pill badge-primary px-3 py-2 shadow-sm" style="position: absolute; top: -30px; left: 50%; transform: translateX(-50%); background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);">
                                <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                            </span>
                            Quick Actions
                        </h3>
                    </div>
                    <div class="card-body px-lg-5 py-lg-5">
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <a href="<?php echo e(route('admin.supplier.index')); ?>" class="admin-card-link">
                                    <div class="card admin-card bg-gradient-info animate__animated animate__fadeInLeft">
                                        <div class="card-body text-center py-4">
                                            <div class="icon-circle bg-white shadow">
                                                <i class="fas fa-truck text-info"></i>
                                            </div>
                                            <h5 class="mt-4 mb-2 text-white">Manage Suppliers</h5>
                                            <p class="text-white-50 mb-0">View and manage your product suppliers</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 mb-4">
                                <a href="<?php echo e(route('admin.product.index')); ?>" class="admin-card-link">
                                    <div class="card admin-card bg-gradient-success animate__animated animate__fadeInUp">
                                        <div class="card-body text-center py-4">
                                            <div class="icon-circle bg-white shadow">
                                                <i class="fas fa-box-open text-success"></i>
                                            </div>
                                            <h5 class="mt-4 mb-2 text-white">Manage Products</h5>
                                            <p class="text-white-50 mb-0">Add or update your product catalog</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 mb-4">
                                <a href="<?php echo e(route('admin.users.index')); ?>" class="admin-card-link">
                                    <div class="card admin-card bg-gradient-warning animate__animated animate__fadeInRight">
                                        <div class="card-body text-center py-4">
                                            <div class="icon-circle bg-white shadow">
                                                <i class="fas fa-users text-warning"></i>
                                            </div>
                                            <h5 class="mt-4 mb-2 text-white">Manage Users</h5>
                                            <p class="text-white-50 mb-0">View and manage customer accounts</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <a href="<?php echo e(route('admin.orders.index')); ?>" class="admin-card-link">
                                    <div class="card admin-card bg-gradient-danger animate__animated animate__fadeInLeft" data-wow-delay="0.2s">
                                        <div class="card-body text-center py-4">
                                            <div class="icon-circle bg-white shadow">
                                                <i class="fas fa-shopping-cart text-danger"></i>
                                            </div>
                                            <h5 class="mt-4 mb-2 text-white">Manage Orders</h5>
                                            <p class="text-white-50 mb-0">Track and process customer orders</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 mb-4">
                                <a href="<?php echo e(route('admin.sales.index')); ?>" class="admin-card-link">
                                    <div class="card admin-card bg-gradient-secondary animate__animated animate__fadeInUp" data-wow-delay="0.2s">
                                        <div class="card-body text-center py-4">
                                            <div class="icon-circle bg-white shadow">
                                                <i class="fas fa-receipt text-secondary"></i>
                                            </div>
                                            <h5 class="mt-4 mb-2 text-white">Sales Records</h5>
                                            <p class="text-white-50 mb-0">Monitor your store performance</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                            <div class="col-md-4 mb-4">
                                <a href="<?php echo e(route('admin.reviews.index')); ?>" class="admin-card-link">
                                    <div class="card admin-card bg-gradient-primary animate__animated animate__fadeInRight" data-wow-delay="0.2s">
                                        <div class="card-body text-center py-4">
                                            <div class="icon-circle bg-white shadow">
                                                <i class="fas fa-star text-primary"></i>
                                            </div>
                                            <h5 class="mt-4 mb-2 text-white">Product Reviews</h5>
                                            <p class="text-white-50 mb-0">Manage customer feedback</p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted animate__animated animate__fadeIn" style="font-family: 'Poppins', sans-serif; font-size: 0.9rem;">
                        StyleSphere Admin Panel © <?php echo e(date('Y')); ?> | <span class="badge badge-pill badge-light shadow-sm">v2.0</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS for Animations and Styling -->
    <style>
        /* Animate.css for animations */
        @import url('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css');

        /* Google Fonts for Typography */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700;800&display=swap');

        body {
            background-color: #f8f9fe;
            font-family: 'Poppins', sans-serif;
        }

        /* Wave Animation with subtle movement */
        .wave-container {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            z-index: 1;
        }

        .wave-container svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 120px;
            animation: wave-flow 15s ease-in-out infinite alternate;
        }

        @keyframes wave-flow {
            0% { transform: translateX(-2%); }
            50% { transform: translateX(0%); }
            100% { transform: translateX(2%); }
        }

        /* Card Styling */
        .card {
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.12);
            transform: translateY(-10px);
        }

        /* Admin Dashboard Cards */
        .admin-card {
            height: 100%;
            border: none;
            transition: transform 0.4s ease, box-shadow 0.4s ease;
        }

        .admin-card-link {
            text-decoration: none;
            display: block;
            height: 100%;
        }

        .admin-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 15px 35px rgba(50, 50, 93, 0.2), 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        /* Icon Circle */
        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            transition: transform 0.3s ease;
        }

        .icon-circle i {
            font-size: 32px;
        }

        .admin-card:hover .icon-circle {
            transform: scale(1.15);
        }

        /* Gradient Backgrounds */
        .bg-gradient-primary {
            background: linear-gradient(135deg, #5e72e4 0%, #825ee4 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #2dce89 0%, #26a69a 100%);
        }

        .bg-gradient-info {
            background: linear-gradient(135deg, #11cdef 0%, #1171ef 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #fb6340 0%, #fbb140 100%);
        }

        .bg-gradient-danger {
            background: linear-gradient(135deg, #f5365c 0%, #f56036 100%);
        }

        .bg-gradient-secondary {
            background: linear-gradient(135deg, #5a6268 0%, #4e555b 100%);
        }

        /* Card Text */
        .admin-card h5 {
            font-weight: 600;
            font-size: 1.25rem;
        }

        .admin-card p {
            font-size: 0.9rem;
            line-height: 1.4;
        }

        /* Fix for overlapping content */
        .container.mt--9 {
            position: relative;
            z-index: 2;
        }

        /* Ensure consistent height on cards */
        .admin-card .card-body {
            height: 100%;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            min-height: 220px;
        }

        /* Animation delays */
        .animate__fadeInUp, .animate__fadeInDown {
            animation-duration: 0.8s;
        }

        .animate__fadeInLeft, .animate__fadeInRight {
            animation-duration: 0.9s;
        }

        /* Responsive adjustments */
        @media (max-width: 767px) {
            .icon-circle {
                width: 60px;
                height: 60px;
            }
            
            .icon-circle i {
                font-size: 24px;
            }
            
            .admin-card .card-body {
                min-height: 180px;
                padding: 1.5rem 1rem;
            }
            
            .admin-card h5 {
                font-size: 1.1rem;
            }
            
            .admin-card p {
                font-size: 0.8rem;
            }
        }
    </style>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp5\htdocs\stylesphere\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>