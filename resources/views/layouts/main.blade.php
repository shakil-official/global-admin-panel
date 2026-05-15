<!doctype html>
<html lang="en"
      data-layout="vertical"
      data-topbar="light"
      data-sidebar="light"
      data-sidebar-size="lg"
      data-sidebar-image="none"
      data-preloader="disable">

<head>
    <meta charset="utf-8"/>
    <title>@yield('title', '')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="" name="description"/>
    <meta content="HlwSyl" name="author"/>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('theme/assets/images/favicon.ico') }}">

    @stack('before_styles')

    <!-- Layout config Js -->
    <script src="{{ asset('theme/assets/js/layout.js') }}"></script>

    <!-- Bootstrap Css -->
    <link href="{{ asset('theme/assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Icons Css -->
    <link href="{{ asset('theme/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/assets/css/app.min.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Custom Css -->
    <link href="{{ asset('theme/assets/css/custom.min.css') }}" rel="stylesheet" type="text/css"/>

    <!-- Vite CSS -->
    {{--    @vite(['resources/css/app.css'])--}}

    @stack('styles')
</head>

<body>
<!-- Begin page -->
<div id="layout-wrapper">
    @include('layouts.menu.top.page-topbar')
    @include('layouts.menu.top.remove-notification-modal')

    <!-- ========== App Menu ========== -->
    @include('layouts.menu.sidebar.main')
    <!-- Left Sidebar End -->

    <!-- Vertical Overlay-->
    <div class="vertical-overlay"></div>

    <!-- ============================================================== -->
    <!-- Start right Content here -->
    <!-- ============================================================== -->
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                @include('layouts.start-page')
                <!-- end page title -->

                @if (isset($errors) && $errors->any())

                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.<br><br>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
            <!-- container-fluid -->
        </div>
        <!-- End Page-content -->
        @include('layouts.footer')
    </div>
    <!-- end main content-->
</div>
<!-- END layout-wrapper -->


<!--start back-to-top-->
<button onclick="topFunction()" class="btn btn-danger btn-icon" id="back-to-top">
    <i class="ri-arrow-up-line"></i>
</button>
<!--end back-to-top-->

<!--preloader-->
<div id="preloader">
    <div id="status">
        <div class="spinner-border text-primary avatar-sm" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>
</div>

<!-- JAVASCRIPT -->
<script src="{{ asset('theme/assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('theme/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('theme/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('theme/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('theme/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('theme/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('theme/assets/js/plugins.js') }}"></script>
<script src="{{ asset('theme/assets/js/app.js') }}"></script>


{{--<!-- Vite JS -->--}}
{{--@vite(['resources/js/app.js'])--}}

@stack('scripts')
</body>

</html>
