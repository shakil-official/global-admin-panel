@extends('layouts.main')

@section('title', $title)
@section('breadcrumb-main', $title_main)
@section('breadcrumb-title', $title)
@section('breadcrumb-sub-title', $title_sub)

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                @if(count($buttons) > 0)
                    <div class="card-header d-flex align-items-center">
                        <h5 class="card-title flex-grow-1 mb-0">{{ $title_card_header }}</h5>
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
    {{-- Optional: Additional styles specific to this view --}}
@endpush

@push('scripts')
    <script src="{{ asset('theme/assets/js/pages/form-validation.init.js') }}"></script>
@endpush
