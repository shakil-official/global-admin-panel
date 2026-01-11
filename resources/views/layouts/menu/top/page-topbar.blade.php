<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <div class="d-flex">
                <!-- LOGO -->

                @include('layouts.menu.top.navbar-brand-box')


                <button type="button"
                        class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger"
                        id="topnav-hamburger-icon">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <!-- App Search-->
                {{--                @include('layouts.menu.top.search')--}}

            </div>

            <div class="d-flex align-items-center">

                {{--                @include('layouts.menu.top.search-in-md')--}}
                {{--                @include('layouts.menu.top.language')--}}
                @include('layouts.menu.top.app')
                {{--                @include('layouts.menu.top.cart')--}}


                <div class="ms-1 header-item d-none d-sm-flex">
                    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
                            data-toggle="fullscreen">
                        <i class='bx bx-fullscreen fs-22'></i>
                    </button>
                </div>

{{--                <div class="ms-1 header-item d-none d-sm-flex">--}}
{{--                    <button type="button"--}}
{{--                            class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle light-dark-mode">--}}
{{--                        <i class='bx bx-moon fs-22'></i>--}}
{{--                    </button>--}}
{{--                </div>--}}

{{--                @include('layouts.menu.top.notification')--}}
                @include('layouts.menu.top.header-profile')


            </div>
        </div>
    </div>
</header>
