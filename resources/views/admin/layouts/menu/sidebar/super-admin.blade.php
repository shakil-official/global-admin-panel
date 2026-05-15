@auth('admin')
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('super.admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('super.admin.dashboard') }}">
                        <i data-feather="home" class="icon-dual"></i>
                        <span data-key="t-widgets">Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('super.user.view') ? 'active' : '' }}"
                       href="{{ route('super.user.view') }}">
                        <i data-feather="users" class="icon-dual"></i>
                        <span data-key="t-widgets">User Management</span>
                    </a>
                </li>

                <!-- Roles & Permissions Management -->
                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is(['super/roles*', 'super/permission*']) ? 'active' : '' }}"
                       href="#sidebarRolesPermissions"
                       data-bs-toggle="collapse"
                       role="button"
                       aria-expanded="{{ request()->is(['super/roles*', 'super/permission*']) ? 'true' : 'false' }}"
                       aria-controls="sidebarRolesPermissions">
                        <i data-feather="shield" class="icon-dual"></i>
                        <span data-key="t-dashboards">Roles & Permissions</span>
                    </a>
                    <div
                        class="collapse menu-dropdown {{ request()->is(['super/roles*', 'super/permission*']) ? 'show' : '' }}"
                        id="sidebarRolesPermissions">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="/super/roles/view"
                                   class="nav-link {{ request()->is('super/roles/view') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    <i data-feather="users" class="icon-dual"></i> All Roles
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="/super/roles/add"
                                   class="nav-link {{ request()->is('super/roles/add') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    <i data-feather="user-plus" class="icon-dual"></i> Add Role
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="/super/roles/permission/list"
                                   class="nav-link {{ request()->is('super/roles/permission/list') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    <i data-feather="key" class="icon-dual"></i> Permission List
                                </a>
                            </li>

                            <li class="nav-item d-none" >
                                <a href="/super/roles/permission/list"
                                   class="nav-link {{ request()->is('super/roles/permission/list') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    <i data-feather="shield-check" class="icon-dual"></i> Assign Permissions
                                </a>
                            </li>

                            <li class="nav-item d-none">
                                <a href="/super/permissions/settings"
                                   class="nav-link {{ request()->is('super/permissions/settings') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    <i data-feather="settings" class="icon-dual"></i> Permission Settings
                                </a>
                            </li>

                            <li class="nav-item d-none">
                                <a href="/super/permissions/audit"
                                   class="nav-link {{ request()->is('super/permissions/audit') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    <i data-feather="activity" class="icon-dual"></i> Permission Audit
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
@endauth

