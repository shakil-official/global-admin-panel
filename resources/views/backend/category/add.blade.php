@extends('layouts.main')  <!-- Extends the base layout -->

@section('title', 'Category Page')  <!-- Dynamic page title -->
@section('breadcrumb-main', 'Category Page')  <!-- Dynamic page main -->
@section('breadcrumb-title', 'Category')  <!-- Dynamic page title -->
@section('breadcrumb-sub-title', 'add')  <!-- Dynamic breadcrumb sub title -->

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                @if(count($buttons) > 0)
                    <div class="card-header d-flex align-items-center">

                        <div class="d-flex gap-1 flex-wrap">
                            @foreach($buttons as $addButton)
                                <a href="{{ $addButton['url'] }}" type="button" class="btn  create-btn  {{ $addButton['classes'] }} }}">
                                    <i class="{{ $addButton['icon'] }} align-bottom me-1"></i> {{ $addButton['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="card-body pt-6">
                    <div style="margin-top: 20px;">
                        {!! \App\Helpers\Builder\FormHelper::renderForm($formConfig) !!}
                    </div>
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
    <script src="{{ asset('theme/assets/js/pages/form-validation.init.js') }}"></script>
@endpush



