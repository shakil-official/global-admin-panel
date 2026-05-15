<div class="dropdown ms-sm-3 header-item topbar-user">
    <button type="button" class="btn" id="page-header-user-dropdown" data-bs-toggle="dropdown"
            aria-haspopup="true" aria-expanded="false">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user"
                                 src="{{ asset('theme/assets/images/users/user-dummy-img.jpg') }}"
                                 alt="Header Admin">
                            <span class="text-start ms-xl-2">
                                <span
                                    class="d-none d-xl-inline-block ms-1 fw-medium user-name-text">{{ Auth::user() ? Auth::user()->name  : 'No User'  }}</span>
                                <span class="d-none d-xl-block ms-1 fs-12 user-name-sub-text">Admin</span>
                            </span>
                        </span>
    </button>


    @php
        $dropdownItems = [
//            ['label' => 'Profile', 'icon' => 'mdi-account-circle', 'link' => route('main'), 'badge' => false],
//            ['label' => 'Messages', 'icon' => 'mdi-message-text-outline', 'link' => route('main'), 'badge' => false],
//            ['label' => 'Task board', 'icon' => 'mdi-calendar-check-outline', 'link' => route('main'), 'badge' => false],
//            ['label' => 'Help', 'icon' => 'mdi-lifebuoy', 'link' => route('main'), 'badge' => false],
//            ['label' => 'Balance', 'icon' => 'mdi-wallet', 'link' => route('main'), 'badge' => false, 'extra' => 'Balance : <b>$5971.67</b>'],
//            ['label' => 'Settings', 'icon' => 'mdi-cog-outline', 'link' => route('main'), 'badge' => true, 'badgeClass' => 'bg-success-subtle text-success', 'badgeText' => 'New'],
//            ['label' => 'Lock screen', 'icon' => 'mdi-lock', 'link' => route('main'), 'badge' => false],
            ['label' => 'Log Out', 'icon' => 'mdi-logout', 'link' => route('logout'), 'badge' => false, 'logout' => true], // Added logout entry

        ];
    @endphp

    {{--    <div class="dropdown-menu dropdown-menu-end">--}}
    {{--        <h6 class="dropdown-header">{{ Auth::user() ? Auth::user()->name  : 'No User'  }}</h6>--}}

    {{--        @foreach($dropdownItems as $item)--}}
    {{--            <a class="dropdown-item" href="{{ $item['link'] }}">--}}
    {{--                <i class="mdi {{ $item['icon'] }} text-muted fs-16 align-middle me-1"></i>--}}
    {{--                <span class="align-middle">{{ $item['label'] }}</span>--}}

    {{--                @if(isset($item['extra']))--}}
    {{--                    <span class="align-middle">{!! $item['extra'] !!}</span>--}}
    {{--                @endif--}}

    {{--                @if($item['badge'])--}}
    {{--                    <span class="badge {{ $item['badgeClass'] }} mt-1 float-end">{{ $item['badgeText'] }}</span>--}}
    {{--                @endif--}}
    {{--            </a>--}}
    {{--        @endforeach--}}


    {{--        <!-- Authentication -->--}}
    {{--        <form method="POST" action="{{ route('logout') }}">--}}
    {{--            @csrf--}}

    {{--            <a class="dropdown-item" href="{{route('logout')}}" onclick="event.preventDefault();--}}
    {{--                                                this.closest('form').submit();">--}}
    {{--                <i class="mdi mdi-logout text-muted fs-16 align-middle me-1"></i>--}}
    {{--                <span class="align-middle"> {{ __('Log Out') }}</span>--}}
    {{--            </a>--}}
    {{--        </form>--}}
    {{--    </div>--}}

    <div class="dropdown-menu dropdown-menu-end">
        <h6 class="dropdown-header">{{ Auth::user() ? Auth::user()->name  : 'No User'  }}</h6>

        @foreach($dropdownItems as $item)
            <a class="dropdown-item" href="{{ $item['logout'] ?? $item['link'] }}"
               onclick="{{ isset($item['logout']) ? 'event.preventDefault(); document.getElementById(\'logout-form\').submit();' : '' }}">
                <i class="mdi {{ $item['icon'] }} text-muted fs-16 align-middle me-1"></i>
                <span class="align-middle">{{ $item['label'] }}</span>

                @if(isset($item['extra']))
                    <span class="align-middle">{!! $item['extra'] !!}</span>
                @endif

                @if($item['badge'])
                    <span class="badge {{ $item['badgeClass'] }} mt-1 float-end">{{ $item['badgeText'] }}</span>
                @endif
            </a>
        @endforeach

        @auth('admin')
            <!-- Logout Form -->
            <form id="logout-form" method="POST" action="{{ route('super.admin.logout') }}" style="display: none;">
                @csrf
            </form>
        @endauth

        <!-- Logout Form -->
        <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
            @csrf
        </form>
    </div>


</div>
