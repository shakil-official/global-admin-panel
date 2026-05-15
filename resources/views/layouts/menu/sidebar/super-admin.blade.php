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

                            <li class="nav-item">
                                <a href="/super/roles/permission/list"
                                   class="nav-link {{ request()->is('super/roles/permission/list') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    <i data-feather="shield-check" class="icon-dual"></i> Assign Permissions
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="/super/permissions/settings"
                                   class="nav-link {{ request()->is('super/permissions/settings') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    <i data-feather="settings" class="icon-dual"></i> Permission Settings
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="/super/permissions/audit"
                                   class="nav-link {{ request()->is('super/permissions/audit') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    <i data-feather="activity" class="icon-dual"></i> Permission Audit
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

{{--                <!-- Content Management -->--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link menu-link {{ request()->is(['blog*', 'category*', 'subcategory*', 'tag*']) ? 'active' : '' }}"--}}
{{--                       href="#sidebarContentManagement"--}}
{{--                       data-bs-toggle="collapse"--}}
{{--                       role="button"--}}
{{--                       aria-expanded="{{ request()->is(['blog*', 'category*', 'subcategory*', 'tag*']) ? 'true' : 'false' }}"--}}
{{--                       aria-controls="sidebarContentManagement">--}}
{{--                        <i data-feather="file-text" class="icon-dual"></i>--}}
{{--                        <span data-key="t-dashboards">Content Management</span>--}}
{{--                    </a>--}}
{{--                    <div--}}
{{--                        class="collapse menu-dropdown {{ request()->is(['blog*', 'category*', 'subcategory*', 'tag*']) ? 'show' : '' }}"--}}
{{--                        id="sidebarContentManagement">--}}
{{--                        <ul class="nav nav-sm flex-column">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="/blog"--}}
{{--                                   class="nav-link {{ request()->is(['blog*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="edit" class="icon-dual"></i> Blog Management--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="/category"--}}
{{--                                   class="nav-link {{ request()->is(['category*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="folder" class="icon-dual"></i> Categories--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="/subcategory"--}}
{{--                                   class="nav-link {{ request()->is(['subcategory*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="folder-2" class="icon-dual"></i> Subcategories--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="/tag"--}}
{{--                                   class="nav-link {{ request()->is(['tag*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="tag" class="icon-dual"></i> Tags--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}

{{--                <!-- Services & Products -->--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link menu-link {{ request()->is(['service*', 'package*', 'offer*']) ? 'active' : '' }}"--}}
{{--                       href="#sidebarServicesProducts"--}}
{{--                       data-bs-toggle="collapse"--}}
{{--                       role="button"--}}
{{--                       aria-expanded="{{ request()->is(['service*', 'package*', 'offer*']) ? 'true' : 'false' }}"--}}
{{--                       aria-controls="sidebarServicesProducts">--}}
{{--                        <i data-feather="package" class="icon-dual"></i>--}}
{{--                        <span data-key="t-dashboards">Services & Products</span>--}}
{{--                    </a>--}}
{{--                    <div--}}
{{--                        class="collapse menu-dropdown {{ request()->is(['service*', 'package*', 'offer*']) ? 'show' : '' }}"--}}
{{--                        id="sidebarServicesProducts">--}}
{{--                        <ul class="nav nav-sm flex-column">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="/service"--}}
{{--                                   class="nav-link {{ request()->is(['service*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="cpu" class="icon-dual"></i> Services--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="/package"--}}
{{--                                   class="nav-link {{ request()->is(['package*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="briefcase" class="icon-dual"></i> Packages--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="/offer"--}}
{{--                                   class="nav-link {{ request()->is(['offer*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="gift" class="icon-dual"></i> Offers--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}

{{--                <!-- Business Management -->--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link menu-link {{ request()->is(['client*', 'branch*', 'coverage*', 'networkpartner*']) ? 'active' : '' }}"--}}
{{--                       href="#sidebarBusinessManagement"--}}
{{--                       data-bs-toggle="collapse"--}}
{{--                       role="button"--}}
{{--                       aria-expanded="{{ request()->is(['client*', 'branch*', 'coverage*', 'networkpartner*']) ? 'true' : 'false' }}"--}}
{{--                       aria-controls="sidebarBusinessManagement">--}}
{{--                        <i data-feather="briefcase" class="icon-dual"></i>--}}
{{--                        <span data-key="t-dashboards">Business Management</span>--}}
{{--                    </a>--}}
{{--                    <div--}}
{{--                        class="collapse menu-dropdown {{ request()->is(['client*', 'branch*', 'coverage*', 'networkpartner*']) ? 'show' : '' }}"--}}
{{--                        id="sidebarBusinessManagement">--}}
{{--                        <ul class="nav nav-sm flex-column">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="/client"--}}
{{--                                   class="nav-link {{ request()->is(['client*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="user" class="icon-dual"></i> Clients--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="/branch"--}}
{{--                                   class="nav-link {{ request()->is(['branch*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="git-branch" class="icon-dual"></i> Branches--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="/coverage"--}}
{{--                                   class="nav-link {{ request()->is(['coverage*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="map" class="icon-dual"></i> Coverage--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="/networkpartner"--}}
{{--                                   class="nav-link {{ request()->is(['networkpartner*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="users" class="icon-dual"></i> Network Partners--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}

{{--                <!-- Support & Feedback -->--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link menu-link {{ request()->is(['faq*', 'feedback*', 'paybill*']) ? 'active' : '' }}"--}}
{{--                       href="#sidebarSupportFeedback"--}}
{{--                       data-bs-toggle="collapse"--}}
{{--                       role="button"--}}
{{--                       aria-expanded="{{ request()->is(['faq*', 'feedback*', 'paybill*']) ? 'true' : 'false' }}"--}}
{{--                       aria-controls="sidebarSupportFeedback">--}}
{{--                        <i data-feather="headphones" class="icon-dual"></i>--}}
{{--                        <span data-key="t-dashboards">Support & Feedback</span>--}}
{{--                    </a>--}}
{{--                    <div--}}
{{--                        class="collapse menu-dropdown {{ request()->is(['faq*', 'feedback*', 'paybill*']) ? 'show' : '' }}"--}}
{{--                        id="sidebarSupportFeedback">--}}
{{--                        <ul class="nav nav-sm flex-column">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="/faq"--}}
{{--                                   class="nav-link {{ request()->is(['faq*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="help-circle" class="icon-dual"></i> FAQ--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="/feedback"--}}
{{--                                   class="nav-link {{ request()->is(['feedback*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="message-circle" class="icon-dual"></i> Feedback--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="/paybill"--}}
{{--                                   class="nav-link {{ request()->is(['paybill*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="credit-card" class="icon-dual"></i> Pay Bills--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}

{{--                <!-- System Settings -->--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link menu-link {{ request()->is(['setting*', 'slider*']) ? 'active' : '' }}"--}}
{{--                       href="#sidebarSystemSettings"--}}
{{--                       data-bs-toggle="collapse"--}}
{{--                       role="button"--}}
{{--                       aria-expanded="{{ request()->is(['setting*', 'slider*']) ? 'true' : 'false' }}"--}}
{{--                       aria-controls="sidebarSystemSettings">--}}
{{--                        <i data-feather="settings" class="icon-dual"></i>--}}
{{--                        <span data-key="t-dashboards">System Settings</span>--}}
{{--                    </a>--}}
{{--                    <div--}}
{{--                        class="collapse menu-dropdown {{ request()->is(['setting*', 'slider*']) ? 'show' : '' }}"--}}
{{--                        id="sidebarSystemSettings">--}}
{{--                        <ul class="nav nav-sm flex-column">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="/setting"--}}
{{--                                   class="nav-link {{ request()->is(['setting*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="settings" class="icon-dual"></i> Settings--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="/slider"--}}
{{--                                   class="nav-link {{ request()->is(['slider*']) ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    <i data-feather="image" class="icon-dual"></i> Slider--}}
{{--                                </a>--}}
{{--                            </li>--}}
{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li>--}}

{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link menu-link {{ request()->is(['services*']) ? 'active' : '' }}"--}}
{{--                       href="#sidebarProductsSetting"--}}
{{--                       data-bs-toggle="collapse"--}}
{{--                       role="button"--}}
{{--                       aria-expanded="{{ request()->is('question*') ? 'true' : 'false' }}"--}}
{{--                       aria-controls="sidebarProductsSetting">--}}
{{--                        <i data-feather="settings" class="icon-dual"></i>--}}
{{--                        <span data-key="t-dashboards">Setting</span>--}}
{{--                    </a>--}}
{{--                    <div--}}
{{--                        class="collapse menu-dropdown {{ request()->is(['services*']) ? 'show' : '' }}"--}}
{{--                        id="sidebarProductsSetting">--}}
{{--                        <ul class="nav nav-sm flex-column">--}}
{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('global_category.index') }}"--}}
{{--                                   class="nav-link {{ request()->routeIs('global_category.*') ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    GR Model--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                            <li class="nav-item">--}}
{{--                                <a href="{{ route('global_sub_category.index') }}"--}}
{{--                                   class="nav-link {{ request()->routeIs('global_sub_category.*') ? 'active' : '' }}"--}}
{{--                                   data-key="t-analytics">--}}
{{--                                    Services--}}
{{--                                </a>--}}
{{--                            </li>--}}

{{--                        </ul>--}}
{{--                    </div>--}}
{{--                </li> <!-- end Product Settings Menu -->--}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
@endauth

