@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="fw-bold">Sales Records</h1>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary rounded-pill">
                        <i class="fas fa-arrow-left mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('admin.sales.analytics') }}" class="btn btn-primary rounded-pill">
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
                               value="{{ request('search') }}" placeholder="Product, Customer, or Order #">
                    </div>
                </div>
                
                <div class="col-md-3">
                    <label for="month" class="form-label fw-medium">Month</label>
                    <select class="form-select" id="month" name="month">
                        <option value="0">All Months</option>
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" {{ request('month') == $month ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-3">
                    <label for="year" class="form-label fw-medium">Year</label>
                    <select class="form-select" id="year" name="year">
                        <option value="">All Years</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary rounded-pill">
                        <i class="fas fa-filter me-1"></i> Filter
                    </button>
                    <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-secondary rounded-pill">
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
                        @forelse ($sales as $sale)
                        <tr>
                            <td class="text-nowrap">{{ $sale->sale_date->format('M d, Y') }}</td>
                            <td class="fw-medium">{{ $sale->order->order_number ?? 'N/A' }}</td>
                            <td>{{ $sale->product->product_name ?? 'Product deleted' }}</td>
                            <td>{{ $sale->user->name ?? 'User deleted' }}</td>
                            <td class="text-end">{{ number_format($sale->quantity) }}</td>
                            <td class="text-end">₱{{ number_format($sale->unit_price, 2) }}</td>
                            <td class="text-end fw-bold">₱{{ number_format($sale->total_price, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="fas fa-chart-bar fa-3x mb-3"></i>
                                    <p class="mb-0">No sales records found</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($sales->count())
                    <tfoot class="table-light">
                        <tr>
                            <th colspan="6" class="text-end">Total:</th>
                            <th class="text-end fs-5">₱{{ number_format($sales->sum('total_price'), 2) }}</th>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $sales->withQueryString()->links() }}
            </div>
        </div>
    </div>
</div>

@push('scripts')
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
@endpush
@endsection