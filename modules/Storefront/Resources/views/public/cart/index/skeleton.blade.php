<div class="cart-skeleton">
    @include('storefront::public.cart.index.steps')

    <div class="cart">
        <div class="cart-inner">
            <div class="table-responsive">
                <table class="table table-borderless cart-table cart-table-skeleton">
                    <thead>
                        <tr>
                            <th>{{ trans('storefront::cart.table.image') }}</th>
                            <th>{{ trans('storefront::cart.table.product_name') }}</th>
                            <th>{{ trans('storefront::cart.table.unit_price') }}</th>
                            <th>{{ trans('storefront::cart.table.quantity') }}</th>
                            <th>{{ trans('storefront::cart.table.line_total') }}</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>
                                <div class="product-image skeleton"></div>
                            </td>
                            <td>
                                <div class="product-name product-name-skeleton">
                                    <div class="skeleton"></div>
                                    <div class="skeleton"></div>
                                </div>
                            </td>
                            <td>
                                <span class="product-price skeleton"></span>
                            </td>
                            <td>
                                <div class="number-picker skeleton"></div>
                            </td>
                            <td>
                                <span class="product-price skeleton"></span>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>
                                <div class="product-image skeleton"></div>
                            </td>
                            <td>
                                <div class="product-name product-name-skeleton">
                                    <div class="skeleton"></div>
                                    <div class="skeleton"></div>
                                </div>
                            </td>
                            <td>
                                <span class="product-price skeleton"></span>
                            </td>
                            <td>
                                <div class="number-picker skeleton"></div>
                            </td>
                            <td>
                                <span class="product-price skeleton"></span>
                            </td>
                            <td></td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>

        <aside class="order-summary-wrap">
            <div class="order-summary">
                <div class="order-summary-top">
                    <h3 class="section-title">{{ trans('storefront::cart.cart_summary') }}</h3>
                </div>

                <div class="order-summary-middle">
                    <ul class="list-inline order-summary-list">
                        <li>
                            <label class="skeleton"></label>

                            <span class="skeleton"></span>
                        </li>
                    </ul>
                </div>

                <div class="order-summary-bottom">
                    <div class="btn-proceed-to-checkout skeleton"></div>
                </div>
            </div>
        </aside>
    </div>
</div>
