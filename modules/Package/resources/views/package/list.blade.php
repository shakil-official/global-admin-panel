@extends('layouts.main')

@section('title', 'Package')
@section('breadcrumb-main', 'Package Request')
@section('breadcrumb-title', 'Package')
@section('breadcrumb-sub-title', 'Request')

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


            new DataTable("#" + tableId, {
                lengthChange: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('package.list-request') }}",
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

        </script>


@endpush
