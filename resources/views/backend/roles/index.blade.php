@extends('admin.layouts.main')

@section('title', $title)
@section('breadcrumb-main', $title_main)
@section('breadcrumb-title', $title)
@section('breadcrumb-sub-title', $title_sub)

@push('styles')
    <link rel="stylesheet" href="{{ asset('theme/assets/libs/sweetalert2/sweetalert2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/assets/dataTables/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/assets/dataTables/css/responsive.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('theme/assets/dataTables/css/buttons.dataTables.min.css') }}">
@endpush

@section('content')
    <x-dynamic-table
        :columns="$columns"
        :title="$title"
        :table="$table"
        :addButtons="$buttons"
    />
@endsection

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title mb-0">Add New Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('role.role-create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="role-name" class="form-label">Role Name</label>
                        <input type="text" name="role-name" id="role-name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('theme/assets/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ asset('theme/assets/dataTables/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('theme/assets/dataTables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('theme/assets/dataTables/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('theme/assets/dataTables/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('theme/assets/dataTables/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('theme/assets/dataTables/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('theme/assets/dataTables/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('theme/assets/dataTables/js/vfs_fonts.js') }}"></script>
    <script src="{{ asset('theme/assets/dataTables/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('theme/assets/dataTables/js/jszip.min.js') }}"></script>
    <script src="{{ asset('theme/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/pages/sweetalerts.init.js') }}"></script>

    <script>
        const tableId = "{{ $table }}";
        const table_url = "{{ $table_url }}";
        const delete_url = "{{ $delete_url }}";
        const columns = @json($columns);

        // Build the DataTables columns array
        const dataTableColumns = columns.map((column) => {
            return {
                data: column.toLowerCase().replace(/\s+/g, '_'),
                name: column.toLowerCase().replace(/\s+/g, '_'),
                orderable: column.toLowerCase() !== 'action',
                searchable: column.toLowerCase() !== 'action',
            };
        });

        new DataTable("#" + tableId, {
            lengthChange: true,
            processing: true,
            serverSide: true,
            ajax: table_url,
            columns: dataTableColumns,
            language: {
                lengthMenu: "_MENU_ ",
                paginate: {},
            },
            pagingType: "simple_numbers",
            buttons: [],
            initComplete: function () {
            },
            drawCallback: function () {
            }
        });

        // Handle delete action
        $(document).on('click', '#deleteButton', function () {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: delete_url,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: $(this).data('id')
                        },
                        success: function (response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "Your data has been deleted.",
                                icon: "success"
                            });
                            $('#' + tableId).DataTable().ajax.reload();
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: "Error!",
                                text: "Something went wrong. Please try again.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });

        // Handle form submission with AJAX
        $('#addRoleModal form').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.attr('action');
            var method = form.attr('method');

            $.ajax({
                url: url,
                method: method,
                data: form.serialize(),
                success: function(response) {
                    $('#addRoleModal').modal('hide');
                    form[0].reset();
                    $('#' + tableId).DataTable().ajax.reload();

                    Swal.fire({
                        title: "Success!",
                        text: "Role created successfully!",
                        icon: "success"
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
    </script>
@endpush
