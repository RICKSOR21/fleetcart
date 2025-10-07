@extends('storefront::public.layout')

@section('title', trans('storefront::compare.compare'))

@section('content')
    <div x-data="Compare">
        <section class="compare-wrap">
            <div class="container">
                @if (!$compare->isEmpty())
                    @include('storefront::public.compare.partials.skeleton')
                    @include('storefront::public.compare.partials.compare_table')

                    <template x-if="$store.compare.fetchedCompareProducts && !hasAnyProduct">
                        @include('storefront::public.compare.partials.empty_compare_table')
                    </template>
                @else
                    @include('storefront::public.compare.partials.empty_compare_table')
                @endif
            </div>
        </section>

        @if ($compare->relatedProducts()->isNotEmpty())
            @include('storefront::public.partials.landscape_products', [
                'title' => trans('storefront::product.related_products'),
                'url' => '/compare/related-products',
                'watchState' => '$store.compare.isEmptyCompareList'
            ])
        @endif
    </div>
@endsection

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/compare/main.scss',
        'modules/Storefront/Resources/assets/public/js/pages/compare/main.js',
    ])
@endpush
