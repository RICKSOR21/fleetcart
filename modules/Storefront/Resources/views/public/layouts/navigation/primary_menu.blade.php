<nav x-data="PrimaryMenu" class="primary-menu position-relative navbar navbar-expand-sm swiper">
    <ul class="navbar-nav mega-menu horizontal-megamenu swiper-wrapper"> 
        @foreach ($primaryMenu->menus() as $menu)
            @include('storefront::public.layouts.navigation.menu', ['type' => 'primary_menu'])
        @endforeach
    </ul>

    <div class="swiper-button-next"></div>
    <div class="swiper-button-prev"></div>
</nav>
