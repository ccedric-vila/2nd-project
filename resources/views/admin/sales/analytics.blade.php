@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Sales Analytics</h1>
                <div class="d-flex align-items-center gap-3">
                    <div id="date-range-badge" class="badge bg-primary fs-6">
                        All Sales Data
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" id="refresh-btn">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Row -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form id="analytics-filters">
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label for="year-filter" class="form-label">Year</label>
                                <select class="form-select" id="year-filter" name="year">
                                    <option value="">All Years</option>
                                    @foreach($years as $year)
                                        <option value="{{ $year }}">{{ $year }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="month-filter" class="form-label">Month</label>
                                <select class="form-select" id="month-filter" name="month" disabled>
                                    <option value="">All Months</option>
                                    @foreach($months as $key => $month)
                                        <option value="{{ $key }}">{{ $month }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="group-by-filter" class="form-label">Group By</label>
                                <select class="form-select" id="group-by-filter" name="group_by">
                                    <option value="daily">Daily</option>
                                    <option value="monthly" selected>Monthly</option>
                                    <option value="yearly">Yearly</option>
                                </select>
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-filter"></i> Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-start-primary h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Sales</h6>
                            <h3 class="mb-0">₱<span id="total-sales">0.00</span></h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="fas fa-dollar-sign text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start-success h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Products Sold</h6>
                            <h3 class="mb-0"><span id="total-products">0</span></h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="fas fa-boxes text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-start-info h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Filtered Data</h6>
                            <h5 class="mb-0" id="filter-indicator">ALL TIME</h5>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="fas fa-calendar-alt text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Sales Bar Chart -->
        <div class="col-lg-8 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Sales Overview</h5>
                </div>
                <div class="card-body">
                    <canvas id="sales-bar-chart" height="300"></canvas>
                </div>
            </div>
        </div>

        <!-- Product Pie Chart -->
        <div class="col-lg-4 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">Sales by Product</h5>
                </div>
                <div class="card-body">
                    <canvas id="product-pie-chart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Initialize charts
    let salesBarChart, productPieChart;

    function initCharts() {
        // Sales Bar Chart
        const salesBarCtx = document.getElementById('sales-bar-chart').getContext('2d');
        salesBarChart = new Chart(salesBarCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Sales Amount',
                    data: [],
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '₱' + value;
                            }
                        }
                    }
                }
            }
        });

        // Product Pie Chart
        const productPieCtx = document.getElementById('product-pie-chart').getContext('2d');
        productPieChart = new Chart(productPieCtx, {
            type: 'pie',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ₱${value.toFixed(2)} (${percentage}%)`;
                            }
                        }
                    },
                    legend: {
                        position: 'right',
                    }
                }
            }
        });
    }

    // Load chart data
    function loadChartData(params = {}) {
        // Show loading state
        document.getElementById('total-sales').textContent = 'Loading...';
        document.getElementById('total-products').textContent = 'Loading...';
        
        // Build query string from params
        const queryString = new URLSearchParams(params).toString();
        
        fetch(`{{ route('admin.sales.analytics.data') }}?${queryString}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                // Update sales chart
                salesBarChart.data.labels = data.sales_chart.labels;
                salesBarChart.data.datasets[0].data = data.sales_chart.data;
                salesBarChart.update();

                // Update product chart
                productPieChart.data.labels = data.product_chart.labels;
                productPieChart.data.datasets[0].data = data.product_chart.data;
                productPieChart.data.datasets[0].backgroundColor = data.product_chart.colors;
                productPieChart.update();

                // Update summary cards
                document.getElementById('total-sales').textContent = data.summary.total_sales.toFixed(2);
                document.getElementById('total-products').textContent = data.summary.total_products;
                
                // Update date range badge
                document.getElementById('date-range-badge').textContent = data.summary.date_range || 'All Sales Data';
                
                // Update filter indicator
                updateFilterIndicator(params);
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
                alert('Error loading sales data. Please try again.');
                document.getElementById('total-sales').textContent = '0.00';
                document.getElementById('total-products').textContent = '0';
            });
    }

    function updateFilterIndicator(params) {
        const indicator = document.getElementById('filter-indicator');
        let filterText = 'ALL TIME';
        
        if (params.year || params.month) {
            filterText = '';
            
            if (params.year) {
                filterText += params.year;
                if (params.month) {
                    const monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 
                                        'July', 'August', 'September', 'October', 'November', 'December'];
                    filterText += ' ' + monthNames[parseInt(params.month) - 1];
                }
            }
            
            // Add group by info
            const groupBy = params.group_by || 'monthly';
            filterText += ` (Grouped by ${groupBy})`;
        }
        
        indicator.textContent = filterText || 'ALL TIME';
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
        loadChartData();
        
        // Setup filter form
        document.getElementById('analytics-filters').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = Object.fromEntries(formData.entries());
            loadChartData(params);
        });
        
        // Enable month filter when year is selected
        document.getElementById('year-filter').addEventListener('change', function() {
            document.getElementById('month-filter').disabled = !this.value;
            if (!this.value) {
                document.getElementById('month-filter').value = '';
            }
        });
        
        // Refresh button
        document.getElementById('refresh-btn').addEventListener('click', function() {
            const formData = new FormData(document.getElementById('analytics-filters'));
            const params = Object.fromEntries(formData.entries());
            loadChartData(params);
        });
        
        // Auto-submit when month is selected if year is already selected
        document.getElementById('month-filter').addEventListener('change', function() {
            if (document.getElementById('year-filter').value) {
                const formData = new FormData(document.getElementById('analytics-filters'));
                const params = Object.fromEntries(formData.entries());
                loadChartData(params);
            }
        });
    });
</script>
@endpush