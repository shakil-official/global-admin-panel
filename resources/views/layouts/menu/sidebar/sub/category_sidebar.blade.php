<li class="nav-item">
    <a class="nav-link menu-link {{ request()->routeIs('category.*', 'sub-category.*', 'blog.*') ? 'active' : '' }}"
       href="#categorySetting"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->routeIs('category.*', 'sub-category.*', 'blog.*') ? 'true' : 'false' }}"
       aria-controls="categorySetting">
        <i data-feather="list" class="icon-dual"></i>


        <span>Blog</span>
    </a>

    <div class="collapse menu-dropdown  {{ request()->routeIs('category.*', 'sub-category.*', 'blog.*') ? 'show' : '' }}"
         id="categorySetting">
        <ul class="nav nav-sm flex-column">

            <li class="nav-item">
                <a href="{{ route('category.index') }}"
                   class="nav-link {{ request()->routeIs('category.index') ? 'active' : '' }}">
                    Category
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('blog.index') }}"
                   class="nav-link {{ request()->routeIs('blog.*') ? 'active' : '' }}"
                   data-key="t-analytics">
                    Blog
                </a>
            </li>


            {{--            <li class="nav-item">--}}
            {{--                <a href="{{ route('sub-category.index') }}"--}}
            {{--                   class="nav-link {{ request()->routeIs('sub-category.index') ? 'active' : '' }}">--}}
            {{--                    Sub Category--}}
            {{--                </a>--}}
            {{--            </li>--}}

        </ul>
    </div>
</li>

<li class="nav-item">
    <a class="nav-link menu-link {{ request()->routeIs('faq-category.*', 'faq.*') ? 'active' : '' }}"
       href="#FaqSetting"
       data-bs-toggle="collapse"
       role="button"
       aria-expanded="{{ request()->routeIs('faq-category.*', 'faq.*') ? 'true' : 'false' }}"
       aria-controls="FaqSetting">
        <i data-feather="list" class="icon-dual"></i>
        <span>Faq Settings</span>
    </a>

    <div class="collapse menu-dropdown  {{ request()->routeIs('faq-category.*', 'faq.*') ? 'show' : '' }}"
         id="FaqSetting">
        <ul class="nav nav-sm flex-column">

            <li class="nav-item">
                <a href="{{ route('faq-category.index') }}"
                   class="nav-link {{ request()->routeIs('faq-category.index') ? 'active' : '' }}">
                    Faq Category
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('faq.index') }}"
                   class="nav-link {{ request()->routeIs('faq.index') ? 'active' : '' }}">
                    Faq
                </a>
            </li>
        </ul>
    </div>
</li>
