<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ getenv('APP_NAME') }}</title>

{{--    <link rel="stylesheet" href="{{ asset('/theme/assets/css/custom_import.css') }}">--}}

    @viteReactRefresh
    @vite(['resources/js/app.jsx', 'resources/css/app.css'])

    <!-- Open Graph Metadata for Social Media -->
    <meta property="og:title" content="">
    <meta property="og:description" content="">
    <meta property="og:image" content="{{ asset('/images/brand/rr_bag_house.png') }}">
    <meta property="og:url" content="">
    <meta property="og:type" content="website">

    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

</head>

<body class="overflow-x-hidden">
<div id="app"></div>
</body>

</html>
