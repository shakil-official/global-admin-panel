@auth
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>

            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                       href="{{ route('dashboard') }}">
                        <i data-feather="home" class="icon-dual"></i>
                        <span data-key="t-widgets">Dashboards</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('contact.view') ? 'active' : '' }}"
                       href="{{ route('contact.view') }}">
                        <i data-feather="mail" class="icon-dual"></i>
                        <span data-key="t-widgets">Contact</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('seo.information') ? 'active' : '' }}"
                       href="{{ route('seo.information') }}">
                        <i data-feather="mail" class="icon-dual"></i>
                        <span data-key="t-widgets">SEO</span>
                    </a>
                </li>


                @include('layouts.menu.sidebar.sub.category_sidebar')


                @include('layouts.menu.sidebar.sub.setting_sidebar')
                <!-- end Product Settings Menu -->

                @include('layouts.menu.sidebar.sub.basic_setting_sidebar')

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
@endauth
