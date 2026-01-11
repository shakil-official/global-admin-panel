<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable" data-bs-theme="light" data-layout-width="fluid" data-layout-position="fixed" data-layout-style="default">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

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
    {{--        @vite(['resources/css/app.css'])--}}


</head>
<body style="font-family: sans-serif; color: #1a202c;">
<div
    style="min-height: 100vh; display: flex; flex-direction: column; justify-content: center;padding-top: 24px; background-color: #dddddd;">
    {{ $slot }}

</div>
</body>


<!-- JAVASCRIPT -->
<script src="{{ asset('theme/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('theme/assets/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ asset('theme/assets/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ asset('theme/assets/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ asset('theme/assets/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ asset('theme/assets/js/plugins.js') }}"></script>


<!-- validation init -->
<script src="{{ asset('theme/assets/js/pages/form-validation.init.js') }}"></script>
<!-- password create init -->
<script src="{{ asset('theme/assets/js/pages/passowrd-create.init.js') }}"></script>

</html>
