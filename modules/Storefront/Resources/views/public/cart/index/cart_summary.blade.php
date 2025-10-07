<template x-if="!cartIsEmpty">
    <aside class="order-summary-wrap">
        <div class="order-summary">
            <div class="order-summary-top">
                <h3 class="section-title">{{ trans('storefront::cart.cart_summary') }}</h3>
            </div>

            <div class="order-summary-middle">
                <ul class="list-inline order-summary-list">
                    <li>
                        <label>{{ trans('storefront::cart.total') }}</label>

                        <span x-text="formatCurrency($store.cart.subTotal)"></span>
                    </li>
                </ul>
            </div>

            <div class="order-summary-bottom">
                <a
                    href="{{ route('checkout.create') }}"
                    class="btn btn-primary btn-proceed-to-checkout"
                >
                    {{ trans('storefront::cart.proceed_to_checkout') }}
                </a>
            </div>
        </div>
    </aside>
</template>
