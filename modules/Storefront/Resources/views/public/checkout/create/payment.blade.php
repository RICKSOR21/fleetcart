<div class="payment-method">
    <h4 class="title">{{ trans('storefront::checkout.payment_method') }}</h4>

    <div class="payment-method-form">
        <div class="form-group">
            <template x-for="(gateway, name) in gateways">
                <div class="form-radio">
                    <input
                        type="radio"
                        name="payment_method"
                        :value="name"
                        :id="name"
                        x-model="form.payment_method"
                    >

                    <label :for="name" x-text="gateway.label"></label>

                    <span class="helper-text" x-text="gateway.description"></span>
                </div>
            </template>

            <template x-if="hasNoPaymentMethod">
                <span class="error-message">
                    {{ trans('storefront::checkout.no_payment_method') }}
                </span>
            </template>
        </div>
    </div>
</div>

@if (setting('stripe_enabled') && setting('stripe_integration_type') === 'embedded_form')
    <div x-cloak id="stripe-element" x-show="form.payment_method === 'stripe'">
        {{-- A Stripe Element will be mounted here dynamically. --}}
    </div>
@endif

<template x-if="shouldShowPaymentInstructions">
    <div class="payment-instructions">
        <h4 class="title">{{ trans('storefront::checkout.payment_instructions') }}</h4>

        <p x-html="paymentInstructions"></p>
    </div>
</template>
