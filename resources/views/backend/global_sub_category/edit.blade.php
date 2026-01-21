@extends('layouts.main')

@section('title', 'Global Sub Category')
@section('breadcrumb-main', 'Global Sub Category Page')
@section('breadcrumb-title', 'Global Sub Category')
@section('breadcrumb-sub-title', 'edit')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card border border-light">
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
    <!-- quill css -->
    <link href="{{ asset('theme/assets/libs/quill/quill.core.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/assets/libs/quill/quill.bubble.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('theme/assets/libs/quill/quill.snow.css') }}" rel="stylesheet" type="text/css"/>
@endpush

@push('scripts')
    <script src="{{ asset('theme/assets/js/pages/form-validation.init.js') }}"></script>
    <script src="{{ asset('theme/assets/js/pages/form-validation.init.js') }}"></script>
    <!-- quill js -->
    <script src="{{ asset('theme/assets/libs/quill/quill.min.js') }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let snowEditorElements = document.querySelectorAll(".snow-editor");

            if (snowEditorElements) {
                Array.from(snowEditorElements).forEach(function (editorElement, index) {
                    let options = {
                        theme: "snow",
                        modules: {
                            toolbar: [
                                [{font: []}, {size: []}],
                                ["bold", "italic", "underline", "strike"],
                                [{color: []}, {background: []}],
                                [{script: "super"}, {script: "sub"}],
                                [{header: [false, 1, 2, 3, 4, 5, 6]}, "blockquote", "code-block"],
                                [{list: "ordered"}, {list: "bullet"}, {indent: "-1"}, {indent: "+1"}],
                                ["direction", {align: []}],
                                ["link", "image", "video"],
                                ["clean"]
                            ]
                        }
                    };

                    // Initialize Quill for this editor
                    let quill = new Quill(editorElement, options);

                    // Create or find the hidden input field for form submission
                    let inputName = editorElement.getAttribute("data-name") || `snowEditor_${index}`;
                    let hiddenInput = document.querySelector(`input[name="${inputName}"]`);

                    if (!hiddenInput) {
                        hiddenInput = document.createElement("input");
                        hiddenInput.setAttribute("type", "hidden");
                        hiddenInput.setAttribute("name", inputName);
                        editorElement.parentNode.appendChild(hiddenInput);
                    }

                    // Prepopulate editor if hidden input already has a value (from old form data)
                    if (hiddenInput.value) {
                        quill.root.innerHTML = hiddenInput.value;
                    }

                    // Sync Quill content with the hidden input field
                    quill.on("text-change", function () {
                        hiddenInput.value = quill.root.innerHTML;
                    });
                });
            }
        });
    </script>

@endpush
