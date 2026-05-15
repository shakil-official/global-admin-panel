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


        const tableId = "{{ $table }}"; // Table ID passed from the controller
        const table_url = "{{ $table_url }}"; // Table ID passed from the controller
        const delete_url = "{{ $delete_url }}"; // Table ID passed from the controller
        const columns = @json($columns); // Columns passed from the controller

        // Build the DataTables columns array
        const dataTableColumns = columns.map((column) => {

            return {
                data: column.toLowerCase(), // Use column name in lowercase for data key
                name: column.toLowerCase(),
                orderable: column.toLowerCase() !== 'action', // Make 'action' column not orderable
                searchable: column.toLowerCase() !== 'action', // Make 'action' column not searchable
            };
        });

        console.log(tableId)


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
            pagingType: "simple_numbers", // Options: "simple", "simple_numbers", "full", "full_numbers"
            buttons: [],
            initComplete: function () {
            },
            drawCallback: function () {
            }
        });

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
                        url: delete_url, // Your Laravel route
                        type: 'delete',
                        data: {
                            _token: '{{ csrf_token() }}', // CSRF token
                            id: $(this).data('id') // Pass necessary data
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

        // Function to handle delete confirmation
        function confirmDelete(url) {
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
                        url: url,
                        type: 'delete',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            Swal.fire({
                                title: "Deleted!",
                                text: "User has been deleted.",
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
        }


    </script>

@endpush
