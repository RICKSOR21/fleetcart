<aside
    x-data="SidebarCart"
    class="sidebar-cart-wrap"
    :class="{ active: $store.layout.isOpenSidebarCart }"
>
    <div class="sidebar-cart-top">
        <div class="title">
            {{ trans('storefront::layouts.my_cart') }}

            <div class="count skeleton" :class="{ skeleton: $store.cart.fetching }" x-text="$store.cart.quantity"></div>
        </div>

        <div class="sidebar-cart-close" @click="$store.layout.closeSidebarCart()">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                <path d="M15.8338 4.16663L4.16705 15.8333M4.16705 4.16663L15.8338 15.8333" stroke="#0E1E3E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg> 
        </div>
    </div>
        
    <div
        class="sidebar-cart-middle"
        :class="cartIsEmpty ? 'empty' : 'custom-scrollbar'"
    >
        <template x-if="!cartIsEmpty">
            <div class="sidebar-cart-items-wrap">
                @include('storefront::public.layouts.sidebar_cart.sidebar_cart_items')
            </div>
        </template>

        <template x-if="cartIsEmpty">
            <div class="empty-message">
                @include('storefront::public.layouts.sidebar_cart.empty_logo')

                <h4>{{ trans('storefront::cart.your_cart_is_empty') }}</h4>
            </div>
        </template>
    </div>

    <template x-if="!cartIsEmpty">
        <div class="sidebar-cart-bottom">
            <h5 class="sidebar-cart-subtotal">
                {{ trans('storefront::layouts.subtotal') }}

                <span x-text="formatCurrency($store.cart.subTotal)"></span>
            </h5>

            <div class="sidebar-cart-actions">
                <button type="button" @click="clearCart" class="btn btn-clear-cart">
                    {{ trans('storefront::layouts.clear_cart') }}
                </button>

                @if (! request()->routeIs('cart.index'))
                    <a href="{{ route('cart.index') }}" class="btn btn-default btn-view-cart">
                        {{ trans('storefront::layouts.view_cart') }}
                    </a>
                @endif

                <a href="{{ route('checkout.create') }}" class="btn btn-primary btn-checkout">
                    {{ trans('storefront::layouts.checkout') }}
                </a>
            </div>
        </div>
    </template>
</aside>
