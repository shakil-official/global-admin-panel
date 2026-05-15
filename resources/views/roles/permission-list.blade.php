@extends('layouts.app')

@section('title', 'Permission Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Permission Management</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('permission-create') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="group_name">Select Role</label>
                                    <select name="group_name" id="group_name" class="form-control" required>
                                        <option value="">Choose a role...</option>
                                        @foreach($permissionGroups as $id => $name)
                                            <option value="{{ $id }}">{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="module_select">Select Module</label>
                                    <select name="module_select" id="module_select" class="form-control">
                                        <option value="">All Modules</option>
                                        <option value="all">Load All Modules</option>
                                        <option value="custom">Custom Permissions</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label><br>
                                    <button type="button" id="load_permissions" class="btn btn-info">Load Permissions</button>
                                    <button type="submit" class="btn btn-success">Assign Permissions</button>
                                </div>
                            </div>
                        </div>

                        <div id="permissions_container" class="mt-4">
                            <div class="row">
                                <div class="col-12">
                                    <h5>Available Permissions</h5>
                                    <div class="permissions-grid" id="permissions_grid">
                                        <!-- Permissions will be loaded here dynamically -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="custom_permissions_container" class="mt-4" style="display: none;">
                            <div class="row">
                                <div class="col-12">
                                    <h5>Custom Permissions</h5>
                                    <div class="form-group">
                                        <label for="custom_permission_input">Enter custom permissions (comma separated)</label>
                                        <textarea name="custom_permissions" id="custom_permission_input" class="form-control" rows="3" placeholder="e.g., users.view, users.create, users.edit"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.permissions-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 10px;
    max-height: 400px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    padding: 15px;
    border-radius: 5px;
}

.permission-item {
    display: flex;
    align-items: center;
    padding: 8px;
    border: 1px solid #e9ecef;
    border-radius: 4px;
    background: #f8f9fa;
}

.permission-item:hover {
    background: #e9ecef;
}

.permission-item input[type="checkbox"] {
    margin-right: 8px;
}

.permission-item label {
    margin: 0;
    font-size: 0.9rem;
    cursor: pointer;
}

.module-section {
    margin-bottom: 20px;
}

.module-title {
    font-weight: bold;
    color: #495057;
    margin-bottom: 10px;
    padding: 10px;
    background: #e9ecef;
    border-radius: 4px;
}
</style>

<script>
$(document).ready(function() {
    let allModulesPermissions = {};

    // Load all modules permissions on page load
    loadAllModulesPermissions();

    function loadAllModulesPermissions() {
        $.get('{{ route("api.modules.permissions") }}')
            .done(function(data) {
                allModulesPermissions = data;
                updateModuleSelect();
            })
            .fail(function() {
                console.error('Failed to load modules');
            });
    }

    function updateModuleSelect() {
        const moduleSelect = $('#module_select');
        moduleSelect.find('option:not(:first)').remove();
        
        moduleSelect.append('<option value="all">Load All Modules</option>');
        moduleSelect.append('<option value="custom">Custom Permissions</option>');
        
        Object.keys(allModulesPermissions).forEach(function(moduleName) {
            moduleSelect.append('<option value="' + moduleName + '">' + moduleName + '</option>');
        });
    }

    $('#module_select').on('change', function() {
        const selectedModule = $(this).val();
        
        if (selectedModule === 'custom') {
            $('#permissions_container').hide();
            $('#custom_permissions_container').show();
        } else {
            $('#permissions_container').show();
            $('#custom_permissions_container').hide();
        }
    });

    $('#load_permissions').on('click', function() {
        const selectedModule = $('#module_select').val();
        
        if (!selectedModule) {
            alert('Please select a module first');
            return;
        }

        if (selectedModule === 'custom') {
            return; // Custom permissions are shown directly
        }

        displayPermissions(selectedModule);
    });

    function displayPermissions(selectedModule) {
        const permissionsGrid = $('#permissions_grid');
        permissionsGrid.empty();

        if (selectedModule === 'all') {
            // Display all modules permissions
            Object.keys(allModulesPermissions).forEach(function(moduleName) {
                const moduleData = allModulesPermissions[moduleName];
                const moduleSection = $('<div class="module-section"></div>');
                
                moduleSection.append('<div class="module-title">' + moduleName + '</div>');
                
                const permissionsContainer = $('<div class="permissions-grid"></div>');
                
                moduleData.permissions.forEach(function(permission) {
                    const permissionItem = createPermissionItem(permission);
                    permissionsContainer.append(permissionItem);
                });
                
                moduleSection.append(permissionsContainer);
                permissionsGrid.append(moduleSection);
            });
        } else {
            // Display specific module permissions
            const moduleData = allModulesPermissions[selectedModule];
            if (moduleData) {
                moduleData.permissions.forEach(function(permission) {
                    const permissionItem = createPermissionItem(permission);
                    permissionsGrid.append(permissionItem);
                });
            }
        }
    }

    function createPermissionItem(permission) {
        const permissionId = permission.replace(/\./g, '_');
        return `
            <div class="permission-item">
                <input type="checkbox" name="permissions[]" value="${permission}" id="${permissionId}">
                <label for="${permissionId}">${permission}</label>
            </div>
        `;
    }

    // Select/Deselect all functionality
    $(document).on('change', '.select-all', function() {
        const isChecked = $(this).is(':checked');
        $(this).closest('.module-section').find('input[type="checkbox"]:not(.select-all)').prop('checked', isChecked);
    });
});
</script>
@endsection
