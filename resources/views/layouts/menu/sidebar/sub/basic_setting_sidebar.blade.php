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
                    Privacy Policy
                </a>
            </li>

        </ul>
    </div>
</li>
