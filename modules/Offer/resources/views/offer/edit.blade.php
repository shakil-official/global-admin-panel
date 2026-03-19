@extends('layouts.main')

@section('title', 'Offer')
@section('breadcrumb-main', 'Offer Page')
@section('breadcrumb-title', 'Offer')
@section('breadcrumb-sub-title', 'edit')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card border card-border-light">
                @if(count($buttons) > 0)
                    <div class="card-header d-flex align-items-center">
                         <span class="ribbon-three ribbon-three-primary"><span>{{ $title }}</span></span>
                         <h5 class="card-title flex-grow-1 mb-0"></h5>
                        <div class="d-flex gap-1 flex-wrap">
                            @foreach($buttons as $addButton)
                                <a href="{{ $addButton['url'] }}" type="button" class="btn create-btn {{ $addButton['classes'] }}">
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
    {{-- Optional: Add custom styles for this page --}}
@endpush

@push('scripts')
    <script src="{{ asset('theme/assets/js/pages/form-validation.init.js') }}"></script>
@endpush
