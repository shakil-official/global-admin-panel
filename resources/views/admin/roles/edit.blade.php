@extends('admin.layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>
                        Edit Role: {{ Str::title($role->name) }}
                    </h5>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Back to Roles
                    </a>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">
                                    <i class="fas fa-tag me-1"></i>Role Name
                                </label>
                                <input type="text"
                                       class="form-control @error('name') is-invalid @enderror"
                                       id="name"
                                       name="name"
                                       value="{{ old('name', $role->name) }}"
                                       placeholder="e.g., Content Manager"
                                       required>
                                @error('name')
                                <div class="invalid-feedback d-block">
                                    <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                </div>
                                @enderror
                                <div class="form-text">
                                    Choose a descriptive name for this role (e.g., Content Manager, Sales Representative)
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-3">
                                    <i class="fas fa-key me-2"></i>
                                    Assign Permissions
                                </h6>

                                <div class="permissions-container">
                                    @foreach(config('permissions.list', []) as $permission => $description)
                                        <div class="permission-item">
                                            <div class="form-check">
                                                <input class="form-check-input"
                                                       type="checkbox"
                                                       id="{{ $permission }}"
                                                       name="permissions[]"
                                                       value="{{ $permission }}"
                                                       @if(in_array($permission, $rolePermissions)) checked @endif>
                                                <label class="form-check-label" for="{{ $permission }}">
                                                    <span class="permission-name">{{ $description }}</span>
                                                    <span class="permission-key text-muted">{{ $permission }}</span>
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
                                    Update Role
                                </button>
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary ms-2">
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
.permissions-container {
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #f8f9fa;
}

.permission-item {
    margin-bottom: 0.75rem;
    padding: 0.5rem;
    background-color: white;
    border-radius: 0.25rem;
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
}

.permission-item:hover {
    background-color: #f1f3f5;
    border-color: #dee2e6;
}

.form-check {
    margin-bottom: 0;
}

.form-check-input {
    margin-top: 0.25rem;
}

.form-check-input:checked + .form-check-label .permission-item {
    background-color: #e7f3ff;
    border-color: #b3d1ff;
}

.form-check-label {
    display: flex;
    justify-content: space-between;
    align-items: center;
    cursor: pointer;
    margin-left: 1.5rem;
}

.permission-name {
    font-weight: 500;
    color: #495057;
}

.permission-key {
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
    background-color: #e9ecef;
    padding: 0.125rem 0.25rem;
    border-radius: 0.125rem;
}

.card {
    box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    border: 1px solid rgba(0, 0, 0, 0.125);
}

.card-header {
    background-color: #f8f9fa;
    border-bottom: 1px solid rgba(0, 0, 0, 0.125);
}

.form-label {
    color: #495057;
    font-weight: 600;
}

.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn {
    border-radius: 0.375rem;
    padding: 0.5rem 1rem;
}
</style>
@endsection
