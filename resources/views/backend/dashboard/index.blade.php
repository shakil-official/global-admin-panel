@extends('layouts.main')  <!-- Extends the base layout -->

@section('title', 'Dashboard')  <!-- Dynamic page title -->
@section('breadcrumb-main', 'Dashboard')  <!-- Dynamic page main -->
@section('breadcrumb-title', 'Dashboard')  <!-- Dynamic page title -->
@section('breadcrumb-sub-title', 'main')  <!-- Dynamic breadcrumb sub title -->

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body">
                    <b><p class="card-title-desc text-center">Welcome to the Group Resilience Dashboard.</p></b>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body d-flex justify-content-center">
                    <img src="{{ asset('images/brand/' . getenv('BRAND_IMAGE_NAME')) }}"
                         alt=""
                         class="img-fluid">
                </div>
            </div>
        </div>
    </div>


@endsection

@push('styles')
    <!-- Optional: Additional styles specific to this view -->
    {{--    <link rel="stylesheet" href="{{ asset('theme/assets/css/home.css') }}">--}}
@endpush

@push('scripts')
    <!-- Optional: Additional scripts specific to this view -->
    {{--    <script src="{{ asset('theme/assets/js/home.js') }}"></script>--}}
@endpush
