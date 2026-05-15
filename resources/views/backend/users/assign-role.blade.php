@extends('layouts.main')

@section('title', $title)
@section('breadcrumb-main', $title_main)
@section('breadcrumb-title', $title)
@section('breadcrumb-sub-title', $title_sub)

@push('styles')
    <link rel="stylesheet" href="{{ asset('theme/assets/libs/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Assign Role to User: {{ $data->name }}</h5>
                <p class="text-muted mb-0">Manage role assignments for user: {{ $data->email }}</p>
            </div>
            <div class="card-body">
                <form action="{{ route('user.assign-role-update') }}" method="POST">
                    @csrf
                    <input type="hidden" name="user_id" value="{{ encrypt($data->id) }}">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role" class="form-label">Select Role</label>
                                <select name="role" id="role" class="form-select" required>
                                    <option value="">Choose a role...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}" 
                                            @if(in_array($role->name, $assignedRoles)) selected @endif>
                                            {{ $role->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="ri-check-line me-1"></i> Assign Role
                                </button>
                                <a href="{{ route('super.user.view') }}" class="btn btn-light">
                                    <i class="ri-arrow-left-line me-1"></i> Back
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($data->roles->count() > 0)
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3">Current Role Permissions</h6>
                            <div class="table-responsive">
                                <table class="table table-bordered table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Permission Name</th>
                                            <th>Guard</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data->getPermissionsViaRoles() as $permission)
                                        <tr>
                                            <td>
                                                <span class="badge bg-primary-subtle text-primary">
                                                    <i class="ri-key-line me-1"></i>{{ $permission->name }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-info-subtle text-info">{{ $permission->guard_name }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('theme/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/pages/sweetalerts.init.js') }}"></script>

    <script>
    $(document).ready(function() {
        // Handle form submission with AJAX
        $('form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var method = form.attr('method');
            
            $.ajax({
                url: url,
                method: method,
                data: form.serialize(),
                success: function(response) {
                    Swal.fire({
                        title: "Success!",
                        text: "Role assigned successfully!",
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route("super.user.view") }}';
                        }
                    });
                },
                error: function(xhr) {
                    var errors = xhr.responseJSON.errors;
                    var errorMessages = [];
                    
                    for (var key in errors) {
                        errorMessages.push(errors[key][0]);
                    }
                    
                    Swal.fire({
                        title: "Error!",
                        text: errorMessages.join(', '),
                        icon: "error"
                    });
                }
            });
        });
    });
    </script>
@endpush
