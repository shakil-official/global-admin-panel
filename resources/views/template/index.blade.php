@extends('layouts.app')

@section('title', 'Roles Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Roles Management</h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addRoleModal">
                            <i class="fas fa-plus"></i> Add Role
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="rolesTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Guard</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Data will be loaded via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Role</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('role.role-create') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="role-name">Role Name</label>
                        <input type="text" name="role-name" id="role-name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Role</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize DataTable
    var rolesTable = $('#rolesTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: '{{ route("role.role-list") }}',
            type: 'GET',
            dataSrc: function(json) {
                return json.data;
            }
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'guard_name' },
            { data: 'created_at' },
            { data: 'action', orderable: false, searchable: false }
        ],
        order: [[0, 'desc']]
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
                rolesTable.ajax.reload();
                
                // Show success message
                if (response.success) {
                    alert('Role created successfully!');
                }
            },
            error: function(xhr) {
                var errors = xhr.responseJSON.errors;
                var errorMessages = [];
                
                for (var key in errors) {
                    errorMessages.push(errors[key][0]);
                }
                
                alert('Error: ' + errorMessages.join(', '));
            }
        });
    });

    // Handle delete action
    $(document).on('submit', 'form[action*="role-delete"]', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr('action');
        
        if (confirm('Are you sure you want to delete this role?')) {
            $.ajax({
                url: url,
                method: 'POST',
                data: form.serialize(),
                success: function(response) {
                    rolesTable.ajax.reload();
                    alert('Role deleted successfully!');
                },
                error: function(xhr) {
                    alert('Error deleting role: ' + xhr.responseJSON.message);
                }
            });
        }
    });
});
</script>
@endsection
