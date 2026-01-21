@auth
    <div id="scrollbar">
        <div class="container-fluid">
            <div id="two-column-menu"></div>

            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span data-key="t-menu">Menu</span></li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i data-feather="home" class="icon-dual"></i>
                        <span data-key="t-widgets">Dashboards</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('contact.index') ? 'active' : '' }}" href="{{ route('contact.index') }}">
                        <i data-feather="mail" class="icon-dual"></i>
                        <span data-key="t-widgets">Contact</span>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->is(['category*', 'global_category*', 'global_sub_category*', 'services*']) ? 'active' : '' }}"
                       href="#sidebarProductsSetting"
                       data-bs-toggle="collapse"
                       role="button"
                       aria-expanded="{{ request()->is('category*') ? 'true' : 'false' }}"
                       aria-controls="sidebarProductsSetting">
                        <i data-feather="settings" class="icon-dual"></i>
                        <span data-key="t-dashboards">Setting</span>
                    </a>
                    <div class="collapse menu-dropdown {{ request()->is(['global_category*', 'service*','category*', 'global_sub_category*', 'slider*', 'feedback*', 'services*', 'springered*']) ? 'show' : '' }}" id="sidebarProductsSetting">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="{{ route('global_category.index') }}"
                                   class="nav-link {{ request()->routeIs('global_category.*') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    Service Type
                                </a>
                            </li>



                            <li class="nav-item">
                                <a href="{{ route('global_sub_category.index') }}"
                                   class="nav-link {{ request()->routeIs('global_sub_category.*') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    Services
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('slider_image.index') }}"
                                   class="nav-link {{ request()->routeIs('slider_image.*') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    Hero Slider
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('feedback.index') }}"
                                   class="nav-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}"
                                   data-key="t-analytics">
                                    Client's feedback
                                </a>
                            </li>

                        </ul>
                    </div>
                </li> <!-- end Product Settings Menu -->

                <li class="nav-item">
                    <a class="nav-link menu-link {{ request()->routeIs('section_term.*', 'about.*') ? 'active' : '' }}"
                       href="#sidebarBasicSetting"
                       data-bs-toggle="collapse"
                       role="button"
                       aria-expanded="{{ request()->routeIs('section_term.*', 'about.*') ? 'true' : 'false' }}"
                       aria-controls="sidebarBasicSetting">
                        <i data-feather="settings" class="icon-dual"></i>
                        <span>Basic Setting</span>
                    </a>

                    <div class="collapse menu-dropdown  {{ request()->routeIs('section_term.*', 'about.*') ? 'show' : '' }}"
                         id="sidebarBasicSetting">
                        <ul class="nav nav-sm flex-column">

                            <li class="nav-item">
                                <a href="{{ route('section_term.edit') }}"
                                   class="nav-link {{ request()->routeIs('section_term.edit') ? 'active' : '' }}">
                                    Term & Condition
                                </a>
                            </li>

                            <li class="nav-item">
                                <a href="{{ route('about.edit') }}"
                                   class="nav-link {{ request()->routeIs('about.edit') ? 'active' : '' }}">
                                    About
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
