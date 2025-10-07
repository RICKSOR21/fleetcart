<div class="col-lg-6 col-sm-18">
    <div class="order-information">
        <h4>{{ trans('storefront::account.view_order.order_information') }}</h4>

        <ul class="list-inline order-information-list">
            <li>
                <label>{{ trans('storefront::account.view_order.id') }}</label>
                <span>{{ $order->id }}</span>
            </li>
            <li>
                <label>{{ trans('storefront::account.view_order.phone') }}</label>
                <span>{{ $order->customer_phone }}</span>
            </li>

            <li>
                <label>{{ trans('storefront::account.view_order.email') }}</label>
                <span>{{ $order->customer_email }}</span>
            </li>

            <li>
                <label>{{ trans('storefront::account.view_order.date') }}</label>
                <span>{{ $order->created_at->toFormattedDateString() }}</span>
            </li>

            <li>
                <label>{{ trans('storefront::account.view_order.shipping_method') }}</label>
                <span>{{ $order->shipping_method }}</span>
            </li>

            <li>
                <label>{{ trans('storefront::account.view_order.payment_method') }}</label>
                <span>
                    {{ $order->payment_method }}

                    @if ($order->payment_method === 'Bank Transfer')
                        <br>
                        <span style="color: #999; font-size: 13px;">{!! setting('bank_transfer_instructions') !!}</span>
                    @endif
                </span>
            </li>

            @if ($order->note)
                <li>
                    <label>{{ trans('storefront::account.view_order.order_note') }}</label>
                    <span>{{ $order->note }}</span>
                </li>
            @endif

            @if ($order->tracking_reference)
                <li x-data="{ tracking: '{{ $order->tracking_reference }}' }" class="d-flex align-items-center">
                    <label>{{ trans('storefront::account.view_order.tracking_reference') }}:</label>

                    <div class="d-flex align-items-center flex-wrap">
                        <span class="m-r-5" x-text="tracking.length > 30 ? tracking.slice(0, 30) + '...' : tracking"></span>

                        <div class="d-flex">
                            <button
                                type="button"
                                @click="
                                    navigator.clipboard.writeText(tracking).then(() => {
                                        notify('Copied to clipboard');
                                    });
                                "
                                class="btn-track-order m-r-5"
                                title="{{ trans('storefront::account.view_order.copy') }}"
                            >
                                <i class="lar la-copy"></i>
                            </button>

                            @if (filter_var($order->tracking_reference, FILTER_VALIDATE_URL))
                                <a href="{{ $order->tracking_reference }}" class="btn-track-order" target="_blank" title="{{ trans('storefront::account.view_order.open_link') }}">
                                    <i class="las la-external-link-alt"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </li>
            @endif
        </ul>
    </div>
</div>
