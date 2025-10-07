@extends('storefront::public.layout')

@section('title', trans('storefront::checkout.checkout'))

@section('content')
    <section
        x-data="
            Checkout({
                customerEmail: '{{ auth()->user()->email ?? null }}',
                customerPhone: '{{ auth()->user()->phone ?? null }}',
                addresses: {{ $addresses }},
                defaultAddress: {{ $defaultAddress }},
                gateways: {{ $gateways }},
                countries: {{ json_encode($countries) }}
            })
        "
        class="checkout-wrap"
    >
        <div class="container">
            @include('storefront::public.cart.index.steps')

            <form class="checkout-form" @input="errors.clear($event.target.name)">
                <div class="checkout-inner">
                    <div class="checkout-left">
                        @include('storefront::public.checkout.create.form.account_details')
                        @include('storefront::public.checkout.create.form.billing_details')
                        @include('storefront::public.checkout.create.form.shipping_details')
                        @include('storefront::public.checkout.create.form.order_note')
                    </div>

                    <div class="checkout-right">
                        @include('storefront::public.checkout.create.payment')
                        @include('storefront::public.checkout.create.shipping')
                    </div>
                </div>

                @include('storefront::public.checkout.create.order_summary')
            </form>

            @if (setting('authorizenet_enabled'))
                <template x-if="authorizeNetToken">
                    <form
                        x-ref="authorizeNetForm"
                        method="post"
                        action="{{
                            setting('authorizenet_test_mode') ?
                            'https://test.authorize.net/payment/payment' :
                            'https://accept.authorize.net/payment/payment'
                        }}"
                    >
                        <input type="hidden" name="token" :value="authorizeNetToken" />

                        <button type="submit"></button>
                    </form>
                </template>
            @endif

            @if (setting('payfast_enabled'))
                <form
                    x-ref="payFastForm"
                    method="post"
                    action="https://sandbox.payfast.co.za/eng/process"
                >
                    <template x-for="(value, name, index) in payFastFormFields" :key="index">
                        <input :name="name" type="hidden" :value="value" />
                    </template>
                </form>
            @endif
        </div>
    </section>
@endsection

@push('pre-scripts')
    @if (setting('stripe_enabled') && setting('stripe_integration_type') === 'embedded_form')
        <script defer src="https://js.stripe.com/v3/"></script>
    @endif

    @if (setting('paypal_enabled'))
        <script src="https://www.paypal.com/sdk/js?client-id={{ setting('paypal_client_id') }}&currency={{ setting('default_currency') }}&disable-funding=credit,card,venmo,sepa,bancontact,eps,giropay,ideal,mybank,p24,p24"></script>
    @endif

    @if (setting('paytm_enabled'))
        <script async src="https://securegw{{ setting('paytm_test_mode') ? '-stage' : '' }}.paytm.in/merchantpgpui/checkoutjs/merchants/{{ setting('paytm_merchant_id') }}.js"></script>
    @endif

    @if (setting('razorpay_enabled'))
        <script async src="https://checkout.razorpay.com/v1/checkout.js"></script>
    @endif

    @if (setting('mercadopago_enabled'))
        <script async src="https://sdk.mercadopago.com/js/v2"></script>
    @endif

    @if (setting('flutterwave_enabled'))
        <script async src="https://checkout.flutterwave.com/v3.js"></script>
    @endif

    @if (setting('paystack_enabled'))
        <script async src="https://js.paystack.co/v1/inline.js"></script>
    @endif

    @if (setting('payfast_enabled'))
        <script async src="https://www.payfast.co.za/onsite/engine.js"></script>
    @endif
@endpush

@push('globals')
    <script>
        FleetCart.stripePublishableKey = '{{ setting("stripe_publishable_key") }}',
        FleetCart.stripeEnabled = {{ setting("stripe_enabled") ? 'true' : 'false' }},
        FleetCart.stripeIntegrationType = '{{ setting("stripe_integration_type") }}',
        FleetCart.langs['storefront::checkout.payment_for_order'] = '{{ trans("storefront::checkout.payment_for_order") }}';
        FleetCart.langs['storefront::checkout.remember_about_your_order'] = '{{ trans("storefront::checkout.remember_about_your_order") }}';
    </script>

    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/checkout/create/main.scss',
        'modules/Storefront/Resources/assets/public/js/pages/checkout/create/main.js',
    ])
@endpush
