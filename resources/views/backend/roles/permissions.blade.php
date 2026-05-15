@extends('admin.layouts.main')

@section('title', $title)
@section('breadcrumb-main', $title_main)
@section('breadcrumb-title', $title)
@section('breadcrumb-sub-title', $title_sub)

@push('styles')
    <link rel="stylesheet" href="{{ asset('theme/assets/libs/sweetalert2/sweetalert2.min.css') }}">
    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-hover: #4338ca;
            --secondary-color: #0f172a;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;

            --primary-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            --card-bg: #ffffff;
            --body-bg: #f1f5f9;

            --text-dark: #0f172a;
            --text-muted: #64748b;

            --border-color: #e2e8f0;

            --card-shadow: 0 10px 25px rgba(15, 23, 42, 0.08);
            --hover-shadow: 0 15px 35px rgba(79, 70, 229, 0.15);

            --radius-lg: 20px;
            --radius-md: 14px;
            --transition: all 0.3s ease;
        }

        body {
            background: var(--body-bg);
            font-family: "Inter", sans-serif;
            color: var(--text-dark);
        }

        /* Main Card */
        .main-card {
            border: none;
            border-radius: var(--radius-lg);
            background: var(--card-bg);
            box-shadow: var(--card-shadow);
            overflow: hidden;
        }

        /* Header */
        .card-header-gradient {
            background: var(--primary-gradient);
            color: #fff;
            padding: 2rem;
            border: none;
            position: relative;
        }

        .card-header-gradient h2,
        .card-header-gradient h3,
        .card-header-gradient h4 {
            margin: 0;
            font-weight: 700;
        }

        .card-header-gradient p {
            margin-top: 8px;
            opacity: 0.9;
            font-size: 0.95rem;
        }

        /* Section */
        .action-section {
            margin-bottom: 2rem;
            animation: fadeInUp 0.5s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Action Header */
        .action-header {
            background: #fff;
            border: 1px solid var(--border-color);
            border-left: 5px solid var(--primary-color);
            padding: 1rem 1.5rem;
            border-radius: var(--radius-md);
            margin-bottom: 1rem;

            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;

            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.04);
        }

        .action-header-left {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .action-icon {
            width: 48px;
            height: 48px;
            border-radius: 14px;
            background: rgba(99, 102, 241, 0.12);

            display: flex;
            align-items: center;
            justify-content: center;

            color: var(--primary-color);
            font-size: 1.2rem;
        }

        .action-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 700;
            color: var(--text-dark);
        }

        .action-subtitle {
            margin: 2px 0 0;
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        /* Permissions Grid */
        .permissions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 16px;

            padding: 1.25rem;
            background: #fff;
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);

            max-height: 450px;
            overflow-y: auto;
        }

        .permissions-grid::-webkit-scrollbar {
            width: 6px;
        }

        .permissions-grid::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        /* Permission Item */
        .permission-item {
            position: relative;

            display: flex;
            align-items: center;

            padding: 1rem;
            border: 1.5px solid var(--border-color);
            border-radius: 14px;

            background: #fff;
            cursor: pointer;

            transition: var(--transition);
        }

        .permission-item:hover {
            transform: translateY(-3px);
            border-color: var(--primary-color);
            box-shadow: var(--hover-shadow);
        }

        .permission-item.selected {
            background: rgba(99, 102, 241, 0.06);
            border-color: var(--primary-color);
        }

        .permission-item input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: var(--primary-color);
            margin-right: 12px;
            cursor: pointer;
        }

        .permission-item label {
            margin: 0;
            cursor: pointer;

            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-dark);
        }

        /* Module Badge */
        .module-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 0.35rem 0.8rem;
            border-radius: 999px;

            background: rgba(99, 102, 241, 0.12);
            color: var(--primary-color);

            font-size: 0.75rem;
            font-weight: 700;
        }

        /* Stats Card */
        .stats-card {
            background: #fff;
            border-radius: var(--radius-md);
            padding: 1.5rem;
            text-align: center;

            border: 1px solid var(--border-color);
            box-shadow: 0 4px 12px rgba(15, 23, 42, 0.05);

            transition: var(--transition);
        }

        .stats-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--hover-shadow);
        }

        .stats-number {
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--primary-color);
            line-height: 1;
        }

        .stats-title {
            margin-top: 0.5rem;
            font-size: 0.9rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* Buttons */
        .btn-gradient {
            background: var(--primary-gradient);
            border: none;
            color: #fff;

            border-radius: 12px;
            padding: 0.85rem 1.8rem;

            font-weight: 600;
            transition: var(--transition);
        }

        .btn-gradient:hover {
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.25);
            color: #fff;
        }

        .btn-gradient:focus {
            box-shadow: none;
        }

        /* Search Box */
        .search-box {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .search-box input {
            width: 100%;
            border: 1.5px solid var(--border-color);
            border-radius: 999px;

            padding: 0.95rem 1.25rem 0.95rem 3rem;

            background: #fff;
            transition: var(--transition);

            font-size: 0.95rem;
        }

        .search-box input:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.12);
            outline: none;
        }

        .search-box i {
            position: absolute;
            left: 1.1rem;
            top: 50%;
            transform: translateY(-50%);

            color: var(--text-muted);
            font-size: 1rem;
        }

        /* Loading Overlay */
        .loading-overlay {
            position: fixed;
            inset: 0;

            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(3px);

            display: none;
            align-items: center;
            justify-content: center;

            z-index: 9999;
        }

        /*.loading-spinner {*/
        /*    width: 55px;*/
        /*    height: 55px;*/

        /*    border: 4px solid #e2e8f0;*/
        /*    border-top: 4px solid var(--primary-color);*/

        /*    border-radius: 50%;*/
        /*    animation: spin 0.8s linear infinite;*/
        /*}*/

        /*@keyframes spin {*/
        /*    100% {*/
        /*        transform: rotate(360deg);*/
        /*    }*/
        /*}*/

        .loading-overlay {
            position: fixed;
            inset: 0;
            width: 100%;
            height: 100vh;

            display: flex;
            align-items: center;
            justify-content: center;

            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(4px);

            z-index: 99999;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;

            border: 5px solid #e5e7eb;
            border-top: 5px solid #4f46e5;

            border-radius: 50%;
            animation: spinnerRotate 0.8s linear infinite;
        }

        @keyframes spinnerRotate {
            100% {
                transform: rotate(360deg);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .permissions-grid {
                grid-template-columns: 1fr;
            }

            .card-header-gradient {
                padding: 1.5rem;
            }

            .action-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .stats-number {
                font-size: 1.8rem;
            }
        }
    </style>
@endpush

@section('content')
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-spinner"></div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card main-card">
            <div class="card-header card-header-gradient">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-1 text-white">Permission Management</h4>
                        <p class="mb-0 opacity-75">Assign permissions to roles with automatic module detection</p>
                    </div>
                    <button type="button" class="btn btn-light btn-sm" onclick="refreshPermissions()">
                        <i class="ri-refresh-line me-1"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="card-body p-4">
                <!-- Statistics -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number" id="totalModules">0</div>
                            <div class="text-muted">Action Types</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number" id="totalPermissions">0</div>
                            <div class="text-muted">Total Permissions</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number" id="selectedCount">0</div>
                            <div class="text-muted" id="selectedLabel">Selected</div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="stats-card">
                            <div class="stats-number" id="assignedCount">0</div>
                            <div class="text-muted" id="assignedLabel">Already Assigned</div>
                        </div>
                    </div>
                </div>

                <!-- Search -->
                <div class="search-box">
                    <i class="ri-search-line"></i>
                    <input type="text" id="permissionSearch" class="form-control" placeholder="Search permissions by module or action...">
                </div>

                <!-- Permissions Container -->
                <form action="{{ route('permission-create') }}" method="POST" id="permissionForm">
                    @csrf

                    <!-- Role Selection -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ri-group-line me-1"></i> Select Role
                            </label>
                            <select name="group_name" id="group_name" class="form-select" required>
                                <option value="">Choose a role...</option>
                                @foreach($permissionGroups as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">
                                <i class="ri-settings-3-line me-1"></i> Actions
                            </label>
                            <div class="btn-group w-100">
                                <button type="button" id="select_all" class="btn btn-outline-primary">
                                    <i class="ri-checkbox-multiple-line me-1"></i> Select All
                                </button>
                                <button type="button" id="deselect_all" class="btn btn-outline-secondary">
                                    <i class="ri-checkbox-blank-line me-1"></i> Clear All
                                </button>
                                <button type="button" id="assign_permissions" class="btn btn-gradient">
                                    <i class="ri-check-line me-1"></i> Assign Permissions
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="permissions_container">
                        <div class="text-center py-5">
                            <i class="ri-folder-open-line display-1 text-muted"></i>
                            <h5 class="mt-3 text-muted">Select a Role to Load Permissions</h5>
                            <p class="text-muted">Choose a role from the dropdown above to view and manage permissions</p>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('theme/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('theme/assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('theme/assets/js/pages/sweetalerts.init.js') }}"></script>

    <script>
        let allPermissions = {};
        let selectedPermissions = new Set();
        let assignedPermissions = new Set();

        $(document).ready(function() {
            setupEventListeners();
            loadAllPermissions();
        });

        function setupEventListeners() {
            $('#group_name').change(loadRolePermissions);
            $('#select_all').click(selectAllPermissions);
            $('#deselect_all').click(deselectAllPermissions);
            $('#assign_permissions').click(assignPermissions);
            $('#permissionSearch').on('input', filterPermissions);

            $(document).on('click', '.permission-item', function(e) {
                if (e.target.type !== 'checkbox') {
                    const checkbox = $(this).find('input[type="checkbox"]');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                    updatePermissionSelection(checkbox);
                }
            });

            $(document).on('change', '.permission-checkbox', function() {
                updatePermissionSelection($(this));
            });
        }

        function loadAllPermissions() {
            showLoading();

            $.get('{{ route("api.modules.permissions") }}')
                .done(function(data) {
                    processPermissionsData(data);
                })
                .fail(function() {
                    loadDefaultPermissions();
                })
                .always(function() {
                    hideLoading();
                });
        }

        function loadDefaultPermissions() {
            const defaultModules = {
                'blog': ['blog.view', 'blog.create', 'blog.update', 'blog.delete'],
                'service': ['service.view', 'service.create', 'service.update', 'service.delete'],
                'package': ['package.view', 'package.create', 'package.update', 'package.delete'],
                'category': ['category.view', 'category.create', 'category.update', 'category.delete'],
                'subcategory': ['subcategory.view', 'subcategory.create', 'subcategory.update', 'subcategory.delete'],
                'client': ['client.view', 'client.create', 'client.update', 'client.delete'],
                'branch': ['branch.view', 'branch.create', 'branch.update', 'branch.delete'],
                'coverage': ['coverage.view', 'coverage.create', 'coverage.update', 'coverage.delete'],
                'faq': ['faq.view', 'faq.create', 'faq.update', 'faq.delete'],
                'faqcategory': ['faqcategory.view', 'faqcategory.create', 'faqcategory.update', 'faqcategory.delete'],
                'feedback': ['feedback.view', 'feedback.create', 'feedback.update', 'feedback.delete'],
                'networkpartner': ['networkpartner.view', 'networkpartner.create', 'networkpartner.update', 'networkpartner.delete'],
                'offer': ['offer.view', 'offer.create', 'offer.update', 'offer.delete'],
                'paybill': ['paybill.view', 'paybill.create', 'paybill.update', 'paybill.delete'],
                'setting': ['setting.view', 'setting.create', 'setting.update', 'setting.delete'],
                'slider': ['slider.view', 'slider.create', 'slider.update', 'slider.delete'],
                'tag': ['tag.view', 'tag.create', 'tag.update', 'tag.delete']
            };

            processPermissionsData(defaultModules);
        }

        function processPermissionsData(data) {
            allPermissions = {};

            Object.keys(data).forEach(function(moduleName) {
                const permissions = data[moduleName].permissions || data[moduleName] || [];
                permissions.forEach(function(permission) {
                    const parts = permission.split('.');
                    const action = parts[parts.length - 1];

                    if (!allPermissions[action]) {
                        allPermissions[action] = [];
                    }
                    allPermissions[action].push({
                        name: permission,
                        module: moduleName,
                        action: action
                    });
                });
            });

            updateStatistics();
        }

        function loadRolePermissions() {
            const roleId = $('#group_name').val();
            const roleName = $('#group_name option:selected').text();

            console.log('=== Loading permissions for role ===');
            console.log('Role ID:', roleId, 'Role Name:', roleName);

            if (!roleId) {
                showEmptyState();
                $('#selectedLabel').text('Selected');
                $('#assignedLabel').text('Already Assigned');
                return;
            }

            showLoading();

            // Clear Sets before loading new role
            assignedPermissions.clear();
            selectedPermissions.clear();

            console.log('Sets cleared. assignedPermissions:', assignedPermissions.size, 'selectedPermissions:', selectedPermissions.size);

            $('#selectedLabel').text('Selected for ' + roleName);
            $('#assignedLabel').text('Assigned to ' + roleName);

            $.get('{{ route("get.group.permissions") }}', { group_id: roleId })
                .done(function(data) {
                    const rolePerms = data.permissions || [];
                    console.log('API returned permissions:', rolePerms);
                    console.log('Number of permissions from API:', rolePerms.length);

                    // Add to Sets
                    rolePerms.forEach(function(permission) {
                        console.log('Adding to Sets:', permission);
                        assignedPermissions.add(permission);
                        selectedPermissions.add(permission);
                    });

                    console.log('After adding - assignedPermissions.size:', assignedPermissions.size);
                    console.log('After adding - selectedPermissions.size:', selectedPermissions.size);
                    console.log('assignedPermissions contents:', Array.from(assignedPermissions));
                    console.log('selectedPermissions contents:', Array.from(selectedPermissions));

                    // Now display with correct states
                    displayPermissions();
                })
                .fail(function(xhr, status, error) {
                    console.error('API call failed:', error);
                    displayPermissions();
                })
                .always(function() {
                    hideLoading();
                });
        }

        function updateStatistics() {
            const totalModules = Object.keys(allPermissions).length;
            const totalPerms = Object.values(allPermissions).reduce((sum, perms) => sum + perms.length, 0);

            console.log('Updating statistics:', {
                totalModules: totalModules,
                totalPerms: totalPerms,
                selected: selectedPermissions.size,
                assigned: assignedPermissions.size
            });

            $('#totalModules').text(totalModules);
            $('#totalPermissions').text(totalPerms);
            $('#selectedCount').text(selectedPermissions.size);
            $('#assignedCount').text(assignedPermissions.size);
        }

        function displayPermissions() {
            console.log('=== displayPermissions called ===');
            console.log('allPermissions keys:', Object.keys(allPermissions));
            console.log('selectedPermissions:', Array.from(selectedPermissions));
            console.log('assignedPermissions:', Array.from(assignedPermissions));

            const container = $('#permissions_container');
            let html = '';
            let delay = 0;

            // Check if we have any permissions loaded
            const actionKeys = Object.keys(allPermissions);
            if (actionKeys.length === 0) {
                console.log('No permissions loaded, showing empty state');
                showEmptyState();
                return;
            }

            const actionIcons = {
                'view': 'ri-eye-line',
                'create': 'ri-add-circle-line',
                'update': 'ri-edit-line',
                'delete': 'ri-delete-bin-line',
                'export': 'ri-download-line',
                'access': 'ri-key-line',
                'list': 'ri-list-line'
            };

            const actionTitles = {
                'view': 'View Permissions',
                'create': 'Create Permissions',
                'update': 'Update Permissions',
                'delete': 'Delete Permissions',
                'export': 'Export Permissions',
                'access': 'Access Permissions',
                'list': 'List Permissions'
            };

            Object.keys(allPermissions).forEach(function(action) {
                const permissions = allPermissions[action];
                const icon = actionIcons[action] || 'ri-key-line';
                const title = actionTitles[action] || action.charAt(0).toUpperCase() + action.slice(1) + ' Permissions';

                html += `
                    <div class="action-section" style="animation-delay: ${delay}s">
                        <div class="action-header">
                            <div class="action-icon">
                                <i class="${icon}"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="mb-0">${title}</h5>
                                <small>${permissions.length} permissions</small>
                            </div>
                            <span class="module-badge">${action}</span>
                        </div>
                        <div class="permissions-grid">
                `;

                permissions.forEach(function(permission) {
                    const isSelected = selectedPermissions.has(permission.name);
                    const isAssigned = assignedPermissions.has(permission.name);
                    const permissionId = permission.name.replace(/[^a-zA-Z0-9]/g, '_');
                    const checkedAttr = isSelected ? 'checked' : '';
                    const selectedClass = isSelected ? 'selected' : '';
                    const assignedBadge = isAssigned ? '<small class="text-success">(assigned)</small>' : '';

                    if (isSelected || isAssigned) {
                        console.log('Rendering permission:', permission.name, '| selected:', isSelected, '| assigned:', isAssigned);
                    }

                    html += `
                        <div class="permission-item ${selectedClass}" data-permission="${permission.name}">
                            <input type="checkbox"
                                   id="perm_${permissionId}"
                                   name="permissions[]"
                                   value="${permission.name}"
                                   class="permission-checkbox"
                                   ${checkedAttr}>
                            <label for="perm_${permissionId}">
                                ${permission.name}
                                ${assignedBadge}
                            </label>
                        </div>
                    `;
                });

                html += `
                        </div>
                    </div>
                `;

                delay += 0.1;
            });

            container.html(html);
            console.log('HTML rendered, calling updateStatistics');
            updateStatistics();
        }

        function showEmptyState() {
            $('#permissions_container').html(`
                <div class="text-center py-5">
                    <i class="ri-folder-open-line display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Select a Role to Load Permissions</h5>
                    <p class="text-muted">Choose a role from the dropdown above to view and manage permissions</p>
                </div>
            `);
        }

        function selectAllPermissions() {
            $('.permission-checkbox').prop('checked', true);
            $('.permission-item').addClass('selected');
            $('.permission-checkbox').each(function() {
                selectedPermissions.add($(this).val());
            });
            updateStatistics();
        }

        function deselectAllPermissions() {
            $('.permission-checkbox').prop('checked', false);
            $('.permission-item').removeClass('selected');
            selectedPermissions.clear();
            updateStatistics();
        }

        function updatePermissionSelection(checkbox) {
            const permission = checkbox.val();
            const permissionItem = checkbox.closest('.permission-item');
            const isChecked = checkbox.prop('checked');

            console.log('Checkbox clicked:', permission, '| Checked:', isChecked);
            console.log('Before update - selectedPermissions:', Array.from(selectedPermissions));

            if (isChecked) {
                selectedPermissions.add(permission);
                permissionItem.addClass('selected');
                console.log('Added to selectedPermissions:', permission);
            } else {
                selectedPermissions.delete(permission);
                permissionItem.removeClass('selected');
                console.log('Removed from selectedPermissions:', permission);
            }

            console.log('After update - selectedPermissions:', Array.from(selectedPermissions));
            console.log('selectedPermissions.size:', selectedPermissions.size);

            updateStatistics();
        }

        function filterPermissions() {
            const searchTerm = $(this).val().toLowerCase();

            $('.permission-item').each(function() {
                const permission = $(this).data('permission').toString().toLowerCase();

                if (permission.includes(searchTerm)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });

            $('.action-section').each(function() {
                const visibleItems = $(this).find('.permission-item:visible').length;
                $(this).toggle(visibleItems > 0);
            });
        }

        function assignPermissions() {
            const roleId = $('#group_name').val();
            const roleName = $('#group_name option:selected').text();

            if (!roleId) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Please select a role first.',
                    confirmButtonColor: '#667eea'
                });
                return;
            }

            Swal.fire({
                title: 'Confirm Permission Assignment',
                text: `Assign ${selectedPermissions.size} permission(s) to "${roleName}"?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#667eea',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Assign',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#permissionForm').submit();
                }
            });
        }

        function refreshPermissions() {
            selectedPermissions.clear();
            assignedPermissions.clear();
            loadAllPermissions();
            if ($('#group_name').val()) {
                loadRolePermissions();
            }
        }

        function showLoading() {
            $('#loadingOverlay').show();
        }

        function hideLoading() {
            $('#loadingOverlay').hide();
        }
    </script>
@endpush
