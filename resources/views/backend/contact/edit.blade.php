@extends('layouts.main')

@section('title', 'Contact')
@section('breadcrumb-main', 'Contact Page')
@section('breadcrumb-title', 'Contact')
@section('breadcrumb-sub-title', 'edit')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                @if(count($buttons) > 0)
                    <div class="card-header d-flex align-items-center">
                        <div class="d-flex gap-1 flex-wrap">
                            @foreach($buttons as $addButton)
                                <a href="{{ $addButton['url'] }}" type="button"
                                   class="btn create-btn {{ $addButton['classes'] }}">
                                    <i class="{{ $addButton['icon'] }} align-bottom me-1"></i> {{ $addButton['label'] }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="card-body p-6">
                    <div class="row g-1">
                        <div class="col-md-2">
                            <label class="fw-bold">Name:</label>
                            <p class="text-muted">{{ $data->name ?? '-' }}</p>
                        </div>

                        <div class="col-md-2">
                            <label class="fw-bold">Email:</label>
                            <p class="text-muted">{{ $data->email ?? '-' }}</p>
                        </div>

                        <div class="col-md-2">
                            <label class="fw-bold">Phone:</label>
                            <p class="text-muted">{{ $data->phone ?? '-' }}</p>
                        </div>

                        <div class="col-md-2">
                            <label class="fw-bold">Area:</label>
                            <p class="text-muted">{{ $data->area ?? '-' }}</p>
                        </div>



                        <div class="col-md-2">
                            <label class="fw-bold">Created At:</label>
                            <p class="text-muted">{{ $data->created_at->format('d M, Y h:i A') }}</p>
                        </div>



                        <div class="col-md-12">
                            <label class="fw-bold">Message:</label>
                            <p class="text-muted">{{ $data->description ?? '-' }}</p>
                        </div>
                    </div>
                </div>
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
