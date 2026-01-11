@extends('layouts.main')  <!-- Extends the base layout -->

@section('title', 'Test Page Page')  <!-- Dynamic page title -->
@section('breadcrumb-main', 'Start page')  <!-- Dynamic page main -->
@section('breadcrumb-title', 'Start')  <!-- Dynamic page title -->
@section('breadcrumb-sub-title', 'main')  <!-- Dynamic breadcrumb sub title -->

@section('content')



    {!! \App\Helpers\Builder\FormHelper::renderForm($formConfig) !!}




@endsection
@push('styles')
    <!-- Optional: Additional styles specific to this view -->
    {{--    <link rel="stylesheet" href="{{ asset('theme/assets/css/home.css') }}">--}}
@endpush

@push('scripts')
    <!-- Optional: Additional scripts specific to this view -->
    {{--    <script src="{{ asset('theme/assets/js/home.js') }}"></script>--}}
        <script src="{{ asset('theme/assets/js/pages/form-validation.init.js') }}"></script>
@endpush



