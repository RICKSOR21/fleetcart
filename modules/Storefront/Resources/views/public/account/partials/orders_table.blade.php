<div class="table-responsive">
    <table class="table table-borderless my-orders-table">
        <thead>
        <tr>
            <th>{{ trans('storefront::account.orders.order_id') }}</th>
            <th>{{ trans('storefront::account.date') }}</th>
            <th>{{ trans('storefront::account.status') }}</th>
            <th>{{ trans('storefront::account.orders.total') }}</th>
            <th>{{ trans('storefront::account.orders.tracking') }}</th>
            <th>{{ trans('storefront::account.action') }}</th>
        </tr>
        </thead>

        <tbody>
        @foreach ($orders as $order)
            <tr>
                <td>
                    {{ $order->id }}
                </td>
                <td>
                    {{ $order->created_at->toFormattedDateString() }}
                </td>
                <td>
                    <span class="badge {{ order_status_badge_class($order->status) }}">
                        {{ $order->status() }}
                    </span>
                </td>
                <td>
                    {{ $order->total->convert($order->currency, $order->currency_rate)->format($order->currency) }}
                </td>
                <td class="tracking-reference-wrapper"
                    x-data="{ tracking: '{{ $order->tracking_reference }}', isUrl: {{ filter_var($order->tracking_reference, FILTER_VALIDATE_URL) ? 'true' : 'false' }} }">

                    @if(!empty($order->tracking_reference))
                        <template x-if="isUrl">
                            <a class="btn btn-track-order" :href="tracking" target="_blank" title="{{ trans('storefront::account.orders.open_link') }}">
                                <i class="las la-external-link-alt"></i>
                            </a>
                        </template>

                        <template x-if="!isUrl">
                            <span x-text="tracking.length > 20 ? tracking.slice(0, 20) + '...' : tracking"></span>
                        </template>
                    @else
                        <span>-</span>
                    @endif
                </td>

                <td>
                    <a href="{{ route('account.orders.show', $order) }}"
                       title="{{ trans('storefront::account.orders.view') }}" class="btn btn-view">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path
                                d="M14.3623 7.3635C14.565 7.6477 14.6663 7.78983 14.6663 8.00016C14.6663 8.2105 14.565 8.35263 14.3623 8.63683C13.4516 9.9139 11.1258 12.6668 7.99967 12.6668C4.87353 12.6668 2.54774 9.9139 1.63703 8.63683C1.43435 8.35263 1.33301 8.2105 1.33301 8.00016C1.33301 7.78983 1.43435 7.6477 1.63703 7.3635C2.54774 6.08646 4.87353 3.3335 7.99967 3.3335C11.1258 3.3335 13.4516 6.08646 14.3623 7.3635Z"
                                stroke="white" stroke-width="1"/>
                            <path
                                d="M10 8C10 6.8954 9.1046 6 8 6C6.8954 6 6 6.8954 6 8C6 9.1046 6.8954 10 8 10C9.1046 10 10 9.1046 10 8Z"
                                stroke="white" stroke-width="1"/>
                        </svg>
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
