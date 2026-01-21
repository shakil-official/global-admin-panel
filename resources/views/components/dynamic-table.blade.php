

<div class="row">
    <div class="col-lg-12">
        <div class="card border card-border-light" id="dynamicTable">
            <div class="card-header d-flex align-items-center">
                <span class="ribbon-three ribbon-three-primary"><span>{{ $title }}</span></span>
                <h5 class="card-title flex-grow-1 mb-0"></h5>
                <div class="d-flex gap-1 flex-wrap">
                    @foreach($addButtons as $addButton)
                        @if(!empty($addButton['url']))
                            <a href="{{ $addButton['url'] }}" type="button"
                               class="btn btn-primary create-btn {{  $addButton['classes'] }}">
                                <i class="{{ $addButton['icon'] }} align-bottom me-1"></i> {{ $addButton['label'] }}
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="card-body">
                <div>
                    <table class="table align-middle mb-0
                                      table-bordered dt-responsive
                                      table-striped table-nowrap"
                           id="{{ $table }}" style="width:100%">
                        <thead class="table-light">
                        <tr>
                            @foreach ($columns as $column)
                                <th class="sort" data-sort="{{ strtolower(str_replace(' ', '_', $column)) }}"
                                    scope="col">{{ $column }}</th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


