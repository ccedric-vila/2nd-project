@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Section with Breadcrumbs -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-2 rounded">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Suppliers</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Header with Actions -->
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h1 class="h2 mb-0">Suppliers</h1>
            <p class="text-muted">Manage your supplier relationships</p>
        </div>
        <div class="col-md-6 text-md-end">
            <a href="{{ route('admin.supplier.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-1"></i> Add Supplier
            </a>
            <a href="{{ route('admin.suppliers.import') }}" class="btn btn-outline-primary ms-2">
                <i class="fas fa-file-import me-1"></i> Import
            </a>
        </div>
    </div>

    <!-- Alerts -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

  

    <!-- Suppliers Table -->
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">ID</th>
                            <th>Brand Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th class="text-end pe-3">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($supplier as $supplier)
                            <tr>
                                <td class="ps-3">{{ $supplier->supplier_id }}</td>
                                <td>
                                    <div class="fw-bold">{{ $supplier->brand_name }}</div>
                                </td>
                                <td>
                                    <a href="mailto:{{ $supplier->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1 text-muted small"></i>{{ $supplier->email }}
                                    </a>
                                </td>
                                <td>
                                    <a href="tel:{{ $supplier->phone }}" class="text-decoration-none">
                                        <i class="fas fa-phone me-1 text-muted small"></i>{{ $supplier->phone }}
                                    </a>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="{{ $supplier->address }}">
                                        <i class="fas fa-map-marker-alt me-1 text-muted small"></i>{{ $supplier->address }}
                                    </div>
                                </td>
                                <td class="text-end pe-3">
                                    <a href="{{ route('admin.supplier.edit', $supplier->supplier_id) }}" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.supplier.destroy', $supplier->supplier_id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this supplier?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i> No suppliers found
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination (if you have it) -->
    <div class="mt-4">
        {{-- $suppliers->links() --}}
    </div>
</div>
@endsection