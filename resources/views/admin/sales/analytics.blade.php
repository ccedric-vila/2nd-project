@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Sales Analytics</h1>
                <div class="badge bg-primary fs-6">
                    All Sales Data
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
                            <h3 class="mb-0">$<span id="total-sales">0.00</span></h3>
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
                            <h6 class="text-muted mb-2">All Time Data</h6>
                            <h5 class="mb-0">ALL TIME</h5>
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
                                return '$' + context.raw.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return '$' + value;
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
                                return `${context.label}: $${context.raw.toFixed(2)}`;
                            }
                        }
                    }
                }
            }
        });
    }

    // Load chart data
    function loadChartData() {
        fetch(`{{ route('admin.sales.analytics.data') }}`)
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                // Update charts with all data
                salesBarChart.data.labels = data.sales_chart.labels;
                salesBarChart.data.datasets[0].data = data.sales_chart.data;
                salesBarChart.update();

                productPieChart.data.labels = data.product_chart.labels;
                productPieChart.data.datasets[0].data = data.product_chart.data;
                productPieChart.data.datasets[0].backgroundColor = data.product_chart.colors;
                productPieChart.update();

                // Update summary cards
                document.getElementById('total-sales').textContent = data.summary.total_sales.toFixed(2);
                document.getElementById('total-products').textContent = data.summary.total_products;
            })
            .catch(error => {
                console.error('Error loading chart data:', error);
                alert('Error loading sales data. Please try again.');
            });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        initCharts();
        loadChartData();
    });

    function formatDate(date) {
        return date.toISOString().split('T')[0];
    }
</script>
@endpush