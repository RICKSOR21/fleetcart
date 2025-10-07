@extends('storefront::public.layout')

@section('title', setting('store_tagline'))

@section('content')
    @includeUnless(is_null($slider), 'storefront::public.home.sections.hero')

    @if (setting('storefront_features_section_enabled'))
        @include('storefront::public.home.sections.home_features')
    @endif

    @if (setting('storefront_featured_categories_section_enabled'))
        @include('storefront::public.home.sections.featured_categories')
    @endif

    @if (setting('storefront_three_column_full_width_banners_enabled'))
        @include('storefront::public.home.sections.three_column_full_width_banner')
    @endif

    @if (setting('storefront_product_tabs_1_section_enabled'))
        @include('storefront::public.home.sections.product_tabs_one')
    @endif

    @if (setting('storefront_top_brands_section_enabled') && $topBrands->isNotEmpty())
        @include('storefront::public.home.sections.top_brands')
    @endif

    @if (setting('storefront_flash_sale_and_vertical_products_section_enabled'))
        @include('storefront::public.home.sections.flash_sale', [
            'flashSaleEnabled' => setting('storefront_active_flash_sale_campaign')
        ])
    @endif

    @if (setting('storefront_two_column_banners_enabled'))
        @include('storefront::public.home.sections.two_column_banner')
    @endif

    @if (setting('storefront_product_grid_section_enabled'))
        @include('storefront::public.home.sections.grid_products')
    @endif

    @if (setting('storefront_three_column_banners_enabled'))
        @include('storefront::public.home.sections.three_column_banner')
    @endif

    @if (setting('storefront_product_tabs_2_section_enabled'))
        @include('storefront::public.home.sections.product_tabs_two')
    @endif

    @if (setting('storefront_one_column_banner_enabled'))
        @include('storefront::public.home.sections.one_column_banner')
    @endif

    @if (setting('storefront_blogs_section_enabled'))
        @include('storefront::public.home.sections.blog')
    @endif
@endsection

@push('meta')
    <meta name="description" content="{{ setting('store_description') }}">
@endpush

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/home/main.scss',
        'modules/Storefront/Resources/assets/public/js/pages/home/main.js',
    ])
@endpush
