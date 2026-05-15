<div class="dropdown topbar-head-dropdown ms-1 header-item d-none">
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle"
            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class='bx bx-category-alt fs-22'></i>
    </button>
    <div class="dropdown-menu dropdown-menu-lg p-0 dropdown-menu-end">
        <div class="p-3 border-top-0 border-start-0 border-end-0 border-dashed border">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0 fw-semibold fs-15"> Web Apps </h6>
                </div>
                <div class="col-auto">
                    <a href="#!" class="btn btn-sm btn-soft-info"> View All Apps
                        <i class="ri-arrow-right-s-line align-middle"></i></a>
                </div>
            </div>
        </div>

        @php
            $brands = [
                ['name' => 'GitHub', 'image' => 'github.png', 'alt' => 'Github', 'link' => 'https://github.com'],
                ['name' => 'Bitbucket', 'image' => 'bitbucket.png', 'alt' => 'Bitbucket', 'link' => 'https://bitbucket.org'],
                ['name' => 'Dribbble', 'image' => 'dribbble.png', 'alt' => 'Dribbble', 'link' => 'https://dribbble.com'],
                ['name' => 'Dropbox', 'image' => 'dropbox.png', 'alt' => 'Dropbox', 'link' => 'https://dropbox.com'],
                ['name' => 'Mail Chimp', 'image' => 'mail_chimp.png', 'alt' => 'Mail Chimp', 'link' => 'https://mailchimp.com'],
                ['name' => 'Slack', 'image' => 'slack.png', 'alt' => 'Slack', 'link' => 'https://slack.com'],
                ['name' => 'Slack', 'image' => 'slack.png', 'alt' => 'Slack', 'link' => 'https://slack.com'],
                ['name' => 'Slack', 'image' => 'slack.png', 'alt' => 'Slack', 'link' => 'https://slack.com'],
                ['name' => 'Slack', 'image' => 'slack.png', 'alt' => 'Slack', 'link' => 'https://slack.com'],
                ['name' => 'Slack', 'image' => 'slack.png', 'alt' => 'Slack', 'link' => 'https://slack.com'],

            ];
        @endphp

        <div class="p-2">
            <div class="row g-0">
                @foreach($brands as $index => $brand)
                    <div class="col">
                        <a class="dropdown-icon-item" href="{{ $brand['link'] }}" target="_blank">
                            <img src="{{ asset('theme/assets/images/brands/' . $brand['image']) }}" alt="{{ $brand['alt'] }}">
                            <span>{{ $brand['name'] }}</span>
                        </a>
                    </div>
                    @if(($index + 1) % 3 == 0 && !$loop->last)
            </div><div class="row g-0">
                @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
