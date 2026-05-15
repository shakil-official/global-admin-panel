@extends('admin.layouts.main')

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
                <h5 class="card-title mb-0">Add New Role</h5>
                <p class="text-muted mb-0">Create a new role for the system</p>
            </div>
            <div class="card-body">
                <form action="{{ route('role.role-create') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="role-name" class="form-label">Role Name <span class="text-danger">*</span></label>
                                <input type="text" name="role-name" id="role-name" class="form-control"
                                       placeholder="Enter role name" required>
                                <small class="text-muted">e.g., Content Manager, Editor, Viewer</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label d-block">&nbsp;</label>
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="ri-save-line me-1"></i> Create Role
                                </button>
                                <a href="{{ route('roles.view') }}" class="btn btn-light">
                                    <i class="ri-arrow-left-line me-1"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="ri-information-line me-2"></i>
                                <strong>Note:</strong> After creating the role, you can assign permissions to it from the Permission Management page.
                            </div>
                        </div>
                    </div>
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
                        text: "Role created successfully!",
                        icon: "success"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '{{ route("roles.view") }}';
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
