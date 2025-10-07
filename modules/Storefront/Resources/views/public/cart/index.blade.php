@extends('storefront::public.layout')

@section('title', trans('storefront::cart.cart'))

@section('content')
    <div x-data="Cart">
        <section class="cart-wrap">
            <div class="container">
                @if (!$isCartEmpty)
                    @include('storefront::public.cart.index.skeleton')

                    <template x-if="!cartIsEmpty">
                        <div>
                            @include('storefront::public.cart.index.steps')

                            <div class="cart">
                                <div class="cart-inner">
                                    @include('storefront::public.cart.index.cart_table')
                                </div>

                                @include('storefront::public.cart.index.cart_summary')
                            </div>
                        </div>
                    </template>

                    <template x-cloak x-if="$store.cart.fetched && cartIsEmpty">
                        @include('storefront::public.cart.index.empty_cart')
                    </template>
                @else
                    @include('storefront::public.cart.index.empty_cart')
                @endif
            </div>
        </section>
        
        @if ($crossSellProducts->isNotEmpty())
            @include('storefront::public.partials.landscape_products', [
                'title' => trans('storefront::product.you_might_also_like'),
                'url' => '/cart/cross-sell-products',
                'watchState' => '$store.cart.isEmpty'
            ])
        @endif
    </div>
@endsection

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/cart/main.scss',  
        'modules/Storefront/Resources/assets/public/js/pages/cart/main.js',
    ])
@endpush
