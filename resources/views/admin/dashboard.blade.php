@extends('layouts.main')  <!-- Extends the base layout -->

@section('title', 'Super Dashboard')  <!-- Dynamic page title -->
@section('breadcrumb-main', 'Dashboard')  <!-- Dynamic page main -->
@section('breadcrumb-title', 'Dashboard')  <!-- Dynamic page title -->
@section('breadcrumb-sub-title', 'main')  <!-- Dynamic breadcrumb sub title -->

@push('styles')
    <style>

    </style>
@endpush

@section('content')
    <div class="card text-center">
        <div class="card-body text-center">
            <h3 class="text-danger">Welcome to</h3>
            <h1>Super Admin </h1>
        </div>
    </div>
@endsection

@push('scripts')
    <script>

    </script>
@endpush
