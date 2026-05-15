@extends('admin.layouts.app')

@section('title', 'User Role Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users-cog me-2"></i>
                        User Role Management
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-shield-alt me-1"></i> Manage Roles
                        </a>
                        @can('user.create')
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user-plus me-1"></i> Add User
                        </a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>User</th>
                                    <th>Email</th>
                                    <th>Current Roles</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">
                                                    <i class="fas fa-user-circle fa-2x text-primary"></i>
                                                </div>
                                                <div>
                                                    <div class="fw-bold">{{ $user->name }}</div>
                                                    <small class="text-muted">ID: {{ $user->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-muted">{{ $user->email }}</span>
                                        </td>
                                        <td>
                                            <div class="role-badges">
                                                @foreach($user->roles as $role)
                                                    <span class="badge bg-primary me-1">
                                                        <i class="fas fa-user-tag me-1"></i>
                                                        {{ Str::title($role->name) }}
                                                    </span>
                                                @endforeach
                                                @if($user->roles->isEmpty())
                                                    <span class="badge bg-secondary">
                                                        <i class="fas fa-question me-1"></i>
                                                        No Role
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            @if($user->status)
                                                <span class="badge bg-success">
                                                    <i class="fas fa-check me-1"></i>
                                                    Active
                                                </span>
                                            @else
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-times me-1"></i>
                                                    Inactive
                                                </span>
                                            @endif
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $user->created_at->format('M j, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('user.update')
                                                <a href="{{ route('admin.users.roles.edit', $user->id) }}"
                                                   class="btn btn-outline-primary btn-sm"
                                                   title="Manage User Roles">
                                                    <i class="fas fa-user-cog"></i>
                                                </a>
                                                @endcan

                                                @if(auth()->id() !== $user->id)
                                                    @can('user.delete')
                                                    <form action="{{ route('users.destroy', $user->id) }}"
                                                          method="POST"
                                                          onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-outline-danger btn-sm"
                                                                title="Delete User">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                    @endcan
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">No users found</p>
                                            @can('user.create')
                                            <a href="{{ route('register') }}" class="btn btn-primary">
                                                <i class="fas fa-user-plus me-1"></i> Create First User
                                            </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users->hasPages())
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="text-muted">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} users
                        </div>
                        {{ $users->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.user-avatar {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.role-badges {
    max-width: 200px;
}

.table th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.btn-group .btn {
    margin: 0 2px;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.5rem;
}

.pagination {
    margin-bottom: 0;
}

.pagination .page-link {
    border-radius: 0.375rem;
    margin: 0 0.125rem;
}
</style>
@endsection
