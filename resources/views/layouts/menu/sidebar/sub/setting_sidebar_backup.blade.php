<li class="nav-item">
    <a class="nav-link menu-link {{ request()->is([ 'coverage*', 'services*', 'network*', 'offer*']) ? 'active' : '' }}"
       href="#sidebarSetting"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->is('coverage*') ? 'true' : 'false' }}"
       aria-controls="sidebarSetting">
        <i data-feather="settings" class="icon-dual"></i>
        <span data-key="t-dashboards">Setting</span>
    </a>
    <div
        class="collapse menu-dropdown {{ request()->is(['coverage*', 'service*', 'network*', 'slider*', 'feedback*', 'services*', 'offer*', 'package*']) ? 'show' : '' }}"
        id="sidebarSetting">
        <ul class="nav nav-sm flex-column">

            <li class="nav-item">
                <a href="{{ route('coverage.index') }}"
                   class="nav-link {{ request()->routeIs('coverage.*') ? 'active' : '' }}"
                   data-key="t-analytics">
                    Coverage
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('offer.index') }}"
                   class="nav-link {{ request()->routeIs('offer.*') ? 'active' : '' }}"
                   data-key="t-analytics">
                    Offer
                </a>
            </li>


            <li class="nav-item">
                <a href="{{ route('service.index') }}"
                   class="nav-link {{ request()->routeIs('service.*') ? 'active' : '' }}"
                   data-key="t-analytics">
                    Services
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('network-partner.index') }}"
                   class="nav-link {{ request()->routeIs('network-partner.*') ? 'active' : '' }}"
                   data-key="t-analytics">
                    Network Partner
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('slider.index') }}"
                   class="nav-link {{ request()->routeIs('slider.*') ? 'active' : '' }}"
                   data-key="t-analytics">
                    Slider
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('package.index') }}"
                   class="nav-link {{ request()->routeIs('package.*') ? 'active' : '' }}"
                   data-key="t-analytics">
                    Package
                </a>
            </li>

{{--            <li class="nav-item">--}}
{{--                <a href="{{ route('feedback.index') }}"--}}
{{--                   class="nav-link {{ request()->routeIs('feedback.*') ? 'active' : '' }}"--}}
{{--                   data-key="t-analytics">--}}
{{--                    Client's feedback--}}
{{--                </a>--}}
{{--            </li>--}}
        </ul>
    </div>
</li>
