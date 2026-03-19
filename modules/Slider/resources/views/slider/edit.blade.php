@extends('layouts.main')

@section('title', 'Slider')
@section('breadcrumb-main', 'Slider Page')
@section('breadcrumb-title', 'Slider')
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

    <script>
        document.addEventListener("DOMContentLoaded",()=>{
            const featureInput=document.querySelector('input[name="feature_pills"]');
            const editor=document.querySelector('.feature-pills-editor');
            if(editor){
                let pills=JSON.parse(featureInput.value||'[]');
                function render(){editor.innerHTML='';pills.forEach((p,i)=>{
                    const div=document.createElement('div');div.className='pill';
                    div.innerHTML=`<span>${p}</span><button type="button" data-index="${i}">x</button>`;
                    editor.appendChild(div);
                });featureInput.value=JSON.stringify(pills);}
                editor.addEventListener('click',e=>{
                    if(e.target.tagName==='BUTTON'){
                        const i=parseInt(e.target.dataset.index);
                        pills.splice(i,1);render();
                    }
                });
                // example: add new pill dynamically
                editor.addEventListener('dblclick',()=>{
                    const val=prompt("Enter feature pill");if(val){pills.push(val);render();}
                });
                render();
            }
        });
    </script>
@endpush
