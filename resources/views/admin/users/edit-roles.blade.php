@extends('admin.layouts.app')

@section('title', 'Manage User Roles')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-cog me-2"></i>
                        Manage Roles for: {{ $user->name }}
                    </h5>
                    <a href="{{ route('admin.users.roles.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Users
                    </a>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="user-info-card">
                                <div class="user-avatar-large">
                                    <i class="fas fa-user-circle fa-4x text-primary"></i>
                                </div>
                                <div class="user-details">
                                    <h6 class="mb-1">{{ $user->name }}</h6>
                                    <p class="text-muted mb-0">{{ $user->email }}</p>
                                    <small class="text-muted">User ID: {{ $user->id }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="current-roles-card">
                                <h6 class="mb-3">
                                    <i class="fas fa-shield-alt me-2"></i>
                                    Current Roles
                                </h6>
                                @if($user->roles->isNotEmpty())
                                    <div class="current-roles">
                                        @foreach($user->roles as $role)
                                            <span class="badge bg-primary me-2 mb-2 current-role">
                                                <i class="fas fa-user-tag me-1"></i>
                                                {{ Str::title($role->name) }}
                                            </span>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted">
                                        <i class="fas fa-info-circle me-2"></i>
                                        This user currently has no assigned roles.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.users.roles.update', $user->id) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3">
                                    <i class="fas fa-tasks me-2"></i>
                                    Available Roles
                                </h6>

                                <div class="roles-grid">
                                    @foreach($roles as $role)
                                        <div class="role-card @if(in_array($role->id, $userRoles)) selected @endif">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       id="role_{{ $role->id }}"
                                                       name="roles[]"
                                                       value="{{ $role->id }}"
                                                       @if(in_array($role->id, $userRoles)) checked @endif>
                                                <label class="form-check-label" for="role_{{ $role->id }}">
                                                    <div class="role-info">
                                                        <div class="role-name">
                                                            <i class="fas fa-user-tag me-2"></i>
                                                            {{ Str::title($role->name) }}
                                                        </div>
                                                        <div class="role-meta">
                                                            <span class="badge bg-info">
                                                                {{ $role->permissions->count() }} permissions
                                                            </span>
                                                            <small class="text-muted d-block">
                                                                Created {{ $role->created_at->diffForHumans() }}
                                                            </small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>
                                    Update User Roles
                                </button>
                                <a href="{{ route('admin.users.roles.index') }}" class="btn btn-secondary ms-2">
                                    <i class="fas fa-times me-1"></i>
                                    Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.user-info-card {
    display: flex;
    align-items: center;
    padding: 1.5rem;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
}

.user-avatar-large {
    margin-right: 1.5rem;
}

.user-details h6 {
    margin-bottom: 0.25rem;
}

.current-roles-card {
    padding: 1.5rem;
    background-color: #f8f9fa;
    border-radius: 0.5rem;
    border: 1px solid #e9ecef;
}

.current-roles {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.current-role {
    font-size: 0.8rem;
}

.roles-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 1rem;
}

.role-card {
    border: 2px solid #e9ecef;
    border-radius: 0.5rem;
    padding: 1rem;
    background-color: white;
    transition: all 0.3s ease;
}

.role-card:hover {
    border-color: #86b7fe;
    box-shadow: 0 0.125rem 0.25rem rgba(13, 110, 253, 0.15);
}

.role-card.selected {
    border-color: #0d6efd;
    background-color: #f0f8ff;
}

.form-check {
    margin-bottom: 0;
}

.form-check-input {
    margin-top: 0.25rem;
}

.form-check-label {
    cursor: pointer;
    margin-left: 1.5rem;
    width: 100%;
}

.role-info {
    margin-left: 1.5rem;
}

.role-name {
    font-weight: 600;
    color: #495057;
    margin-bottom: 0.5rem;
}

.role-meta {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.btn {
    border-radius: 0.375rem;
    padding: 0.5rem 1rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}
</style>
@endsection
