<div class="row">
    <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0">@yield('breadcrumb-main', '')</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="javascript: void(0);">@yield('breadcrumb-title', '')</a></li>

                    @if(View::hasSection('breadcrumb-sub-title'))
                        <li class="breadcrumb-item active">@yield('breadcrumb-sub-title', '')</li>
                    @endif

                </ol>
            </div>

        </div>
    </div>
</div>
