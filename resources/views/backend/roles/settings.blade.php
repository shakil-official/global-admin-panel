@extends('admin.layouts.main')

@section('title', $title)
@section('breadcrumb-main', $title_main)
@section('breadcrumb-title', $title)
@section('breadcrumb-sub-title', $title_sub)

@push('scripts')
<script>
$(document).ready(function() {
    // Load settings from localStorage or defaults
    loadSettings();

    // Auto-assign permissions toggle
    $('#autoAssignPermissions').on('change', function() {
        saveSettings();
        showNotification('Auto-assign permissions ' + ($(this).is(':checked') ? 'enabled' : 'disabled'), 'success');
    });

    // Inherit permissions toggle
    $('#inheritParentPermissions').on('change', function() {
        saveSettings();
        showNotification('Permission inheritance ' + ($(this).is(':checked') ? 'enabled' : 'disabled'), 'success');
    });

    // Strict mode toggle
    $('#strictMode').on('change', function() {
        saveSettings();
        showNotification('Strict permission checking ' + ($(this).is(':checked') ? 'enabled' : 'disabled'), 'success');
    });

    // Clear cache button
    $('#clearCacheBtn').on('click', function() {
        clearCache();
    });

    // Save settings button
    $('#saveSettingsBtn').on('click', function() {
        saveSettings();
        showNotification('Settings saved successfully!', 'success');
    });

    function loadSettings() {
        const settings = JSON.parse(localStorage.getItem('permissionSettings') || '{}');

        $('#autoAssignPermissions').prop('checked', settings.autoAssignPermissions !== false);
        $('#inheritParentPermissions').prop('checked', settings.inheritParentPermissions !== false);
        $('#strictMode').prop('checked', settings.strictMode === true);

        // Update last cache clear time
        const lastCacheClear = localStorage.getItem('lastCacheClear');
        if (lastCacheClear) {
            $('#lastCacheClear').text(new Date(lastCacheClear).toLocaleString());
        }
    }

    function saveSettings() {
        const settings = {
            autoAssignPermissions: $('#autoAssignPermissions').is(':checked'),
            inheritParentPermissions: $('#inheritParentPermissions').is(':checked'),
            strictMode: $('#strictMode').is(':checked'),
            lastUpdated: new Date().toISOString()
        };

        localStorage.setItem('permissionSettings', JSON.stringify(settings));
    }

    function clearCache() {
        // Simulate cache clearing
        $('#clearCacheBtn').prop('disabled', true).html('<i class="ri-loader-4-line spin me-1"></i> Clearing...');

        setTimeout(function() {
            const now = new Date().toISOString();
            localStorage.setItem('lastCacheClear', now);
            $('#lastCacheClear').text(new Date(now).toLocaleString());

            $('#clearCacheBtn').prop('disabled', false).html('<i class="ri-refresh-line me-1"></i> Clear Cache');
            showNotification('Permission cache cleared successfully!', 'success');
        }, 1500);
    }

    function showNotification(message, type) {
        // Create a simple notification (you can replace with SweetAlert2 if available)
        const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
        const notification = $('<div class="alert ' + alertClass + ' alert-dismissible fade show position-fixed" style="top: 20px; right: 20px; z-index: 9999;">' +
            '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
            '<strong>' + (type === 'success' ? 'Success!' : 'Error!') + '</strong> ' + message +
            '</div>');

        $('body').append(notification);

        setTimeout(function() {
            notification.fadeOut(500, function() {
                $(this).remove();
            });
        }, 3000);
    }
});
</script>

<style>
.spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Permission Settings</h5>
                <p class="text-muted mb-0">Configure system-wide permission settings and policies</p>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="mb-3">Default Permissions</h6>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="autoAssignPermissions" checked>
                                <label class="form-check-label" for="autoAssignPermissions">
                                    Auto-assign basic permissions to new roles
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="inheritParentPermissions" checked>
                                <label class="form-check-label" for="inheritParentPermissions">
                                    Inherit permissions from parent roles
                                </label>
                            </div>
                            <div class="form-check form-switch mb-2">
                                <input class="form-check-input" type="checkbox" id="strictMode">
                                <label class="form-check-label" for="strictMode">
                                    Enable strict permission checking
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <h6 class="mb-3">Permission Cache</h6>
                            <div class="d-flex gap-2 mb-3">
                                <button type="button" class="btn btn-outline-primary" id="clearCacheBtn">
                                    <i class="ri-refresh-line me-1"></i> Clear Cache
                                </button>
                                <button type="button" class="btn btn-outline-success" id="saveSettingsBtn">
                                    <i class="ri-save-line me-1"></i> Save Settings
                                </button>
                            </div>
                            <small class="text-muted">
                                Last cache cleared: <span id="lastCacheClear">Never</span>
                            </small>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="mb-3">Permission Groups</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>Group Name</th>
                                        <th>Description</th>
                                        <th>Permissions Count</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Content Management</td>
                                        <td>Blog, Category, Tag permissions</td>
                                        <td><span class="badge bg-primary">15</span></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                                            <button class="btn btn-sm btn-outline-danger">Disable</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>User Management</td>
                                        <td>User, Role, Permission management</td>
                                        <td><span class="badge bg-primary">12</span></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                                            <button class="btn btn-sm btn-outline-danger">Disable</button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>System Settings</td>
                                        <td>System configuration and settings</td>
                                        <td><span class="badge bg-primary">8</span></td>
                                        <td><span class="badge bg-success">Active</span></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary">Edit</button>
                                            <button class="btn btn-sm btn-outline-danger">Disable</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
