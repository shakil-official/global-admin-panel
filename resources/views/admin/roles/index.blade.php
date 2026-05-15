@extends('admin.layouts.app')

@section('title', 'Roles & Permissions Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-shield-alt me-2"></i>
                        Roles & Permissions
                    </h5>
                    @can('user.create')
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus me-1"></i> Add Role
                    </a>
                    @endcan
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
                                    <th>Role Name</th>
                                    <th>Permissions</th>
                                    <th>Users Count</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    <tr>
                                        <td>
                                            <span class="badge bg-primary role-badge">
                                                <i class="fas fa-user-tag me-1"></i>
                                                {{ Str::title($role->name) }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="permission-tags">
                                                @foreach($role->permissions->take(5) as $permission)
                                                    <span class="badge bg-light text-dark me-1 mb-1 permission-tag">
                                                        {{ Str::title(str_replace('.', ' ', $permission->name)) }}
                                                    </span>
                                                @endforeach
                                                @if($role->permissions->count() > 5)
                                                    <span class="badge bg-secondary">
                                                        +{{ $role->permissions->count() - 5 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">
                                                <i class="fas fa-users me-1"></i>
                                                {{ $role->users()->count() }}
                                            </span>
                                        </td>
                                        <td>
                                            <small class="text-muted">{{ $role->created_at->format('M j, Y') }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('user.update')
                                                <a href="{{ route('admin.roles.edit', $role->id) }}"
                                                   class="btn btn-outline-primary btn-sm"
                                                   title="Edit Role">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @endcan

                                                @can('user.delete')
                                                <form action="{{ route('admin.roles.destroy', $role->id) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this role?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="btn btn-outline-danger btn-sm"
                                                            title="Delete Role">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                            <p class="text-muted mb-0">No roles found</p>
                                            @can('user.create')
                                            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary">
                                                <i class="fas fa-plus me-1"></i> Create First Role
                                            </a>
                                            @endcan
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.role-badge {
    font-size: 0.85rem;
    padding: 0.5rem 0.75rem;
}

.permission-tags {
    max-width: 300px;
}

.permission-tag {
    font-size: 0.75rem;
    font-weight: 500;
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
</style>
@endsection
