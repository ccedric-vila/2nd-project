@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Header Section with Breadcrumbs -->
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-2 rounded">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Management</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        <i class="fas fa-users me-2"></i>{{ __('User Management') }}
                    </h5>
                </div>

                <div class="card-body">
                    <!-- Display any success messages -->
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Role</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle bg-primary text-white me-2">
                                                    {{ substr($user->name, 0, 1) }}
                                                </div>
                                                <div>{{ $user->name }}</div>
                                            </div>
                                        </td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            <form action="{{ route('admin.users.updateStatus', $user->id) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>Active</option>
                                                    <option value="inactive" {{ $user->status === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                                </select>
                                            </form>
                                        </td>
                                        <td>
                                            <form action="{{ route('admin.users.updateRole', $user->id) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <select name="role" class="form-select form-select-sm" onchange="this.form.submit()">
                                                    <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                                                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-users-slash me-1"></i> No users found
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4">
                        {{-- $users->links() --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.avatar-circle {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}
</style>
@endsection