@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header with Back Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left mr-2"></i>Dashboard
        </a>
        <h1 class="mb-0">Order Management</h1>
        <div></div> <!-- Empty div for balance -->
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
        {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert">
            <span>&times;</span>
        </button>
    </div>
    @endif

    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>#{{ $order->id }}</td>
                    <td>
                        <div>{{ $order->user->name }}</div>
                        <small class="text-muted">{{ $order->user->email }}</small>
                    </td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td>${{ number_format($order->total_amount, 2) }}</td>
                    <td>
                        <span class="badge 
                            @switch($order->status)
                                @case('pending') badge-warning @break
                                @case('accepted') badge-primary @break
                                @case('delivered') badge-success @break
                                @case('cancelled') badge-danger @break
                            @endswitch">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="text-nowrap">
                        <a href="{{ route('orders.show', $order->id) }}" 
                           class="btn btn-sm btn-outline-info" 
                           title="View Details">
                            <i class="fas fa-eye"></i>
                        </a>

                        @if($order->status === 'pending')
                        <form action="{{ route('admin.orders.accept', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success" title="Accept Order">
                                <i class="fas fa-check"></i>
                            </button>
                        </form>
                        <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-danger" title="Cancel Order">
                                <i class="fas fa-times"></i>
                            </button>
                        </form>
                        @endif

                        @if($order->status === 'accepted')
                        <form action="{{ route('admin.orders.deliver', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-truck"></i> Deliver
                            </button>
                        </form>

                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">No orders found</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .badge {
        font-size: 0.85em;
        font-weight: 500;
        padding: 0.35em 0.65em;
    }
    .table td, .table th {
        vertical-align: middle;
    }
</style>
@endpush