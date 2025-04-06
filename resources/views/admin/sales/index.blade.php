@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1>Sales Records</h1>
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-chart-line"></i> Dashboard
                    </a>
                    <a href="{{ route('admin.sales.analytics') }}" class="btn btn-primary me-2">
                        <i class="fas fa-chart-pie"></i> Charts
                    </a>
                </div>
            </div>
        </div>
    </div>

    

    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3" id="filter-form">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Product, Customer, or Order #">
                </div>
                
                <div class="col-md-3">
                    <label for="month" class="form-label">Month</label>
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
                    <label for="year" class="form-label">Year</label>
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
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.sales.index') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Order #</th>
                            <th>Product</th>
                            <th>Customer</th>
                            <th class="text-end">Qty</th>
                            <th class="text-end">Unit Price</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                        <tr>
                            <td>{{ $sale->sale_date->format('M d, Y') }}</td>
                            <td>#{{ $sale->order->order_number ?? 'N/A' }}</td>
                            <td>{{ $sale->product->product_name ?? 'Product deleted' }}</td>
                            <td>{{ $sale->user->name ?? 'User deleted' }}</td>
                            <td class="text-end">{{ number_format($sale->quantity) }}</td>
                            <td class="text-end">${{ number_format($sale->unit_price, 2) }}</td>
                            <td class="text-end fw-bold">${{ number_format($sale->total_price, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">No sales records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                    @if($sales->count())
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-end">Total:</th>
                            <th class="text-end">${{ number_format($sales->sum('total_price'), 2) }}</th>
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