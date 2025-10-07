<ul class="list-inline" @click.stop>
    @foreach ($subMenus as $subMenu)
        <li
            class="{{ $subMenu->hasItems() ? 'dropdown sub-menu' : '' }}"
            @click="
                $($el).children('ul.list-inline').slideToggle(200);
                $($el).toggleClass('active');
            "
        >
            <a href="{{ $subMenu->url() }}" target="{{ $subMenu->target() }}" @click.stop>
                {{ $subMenu->name() }}
            </a>

            @if ($subMenu->hasItems())
                <i class="las la-angle-right"></i>
            @endif

            @if ($subMenu->hasItems())
                @include('storefront::public.layouts.sidebar_menu.dropdown', ['subMenus' => $subMenu->items()])
            @endif
        </li>
    @endforeach
</ul>
