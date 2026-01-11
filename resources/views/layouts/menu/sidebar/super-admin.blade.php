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
                        <span data-key="t-widgets">Dashboards</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('super.user.view') ? 'active' : '' }}"
                       href="{{ route('super.user.view') }}">
                        <i data-feather="home" class="icon-dual"></i>
                        <span data-key="t-widgets">Users</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is(['services*']) ? 'active' : '' }}"
                       href="#sidebarProductsSetting"
                       data-bs-toggle="collapse"
                       role="button"
                       aria-expanded="{{ request()->is('question*') ? 'true' : 'false' }}"
                       aria-controls="sidebarProductsSetting">
                        <i data-feather="settings" class="icon-dual"></i>
                        <span data-key="t-dashboards">Setting</span>
                    </a>
                    <div
                        class="collapse menu-dropdown {{ request()->is(['services*']) ? 'show' : '' }}"
                        id="sidebarProductsSetting">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('global_category.index') }}"
                                   class="nav-link {{ request()->routeIs('global_category.*') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    GR Model
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('global_sub_category.index') }}"
                                   class="nav-link {{ request()->routeIs('global_sub_category.*') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    Services
                                </a>
                            </li>

                        </ul>
                    </div>
                </li> <!-- end Product Settings Menu -->
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
@endauth

