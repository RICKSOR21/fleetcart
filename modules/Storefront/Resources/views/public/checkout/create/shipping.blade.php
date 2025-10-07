<template x-if="hasShippingMethod">
    <div class="shipping-method">
        <h4 class="title">{{ trans('storefront::checkout.shipping_method') }}</h4>

        <div class="shipping-method-form">
            <div class="form-group">
                <template x-for="(shippingMethod, key) in cart.availableShippingMethods">
                    <div class="form-radio">
                        <input
                            type="radio"
                            name="shipping_method"
                            :value="shippingMethod.name"
                            :id="shippingMethod.name"
                            @change="updateShippingMethod(shippingMethod.name)"
                            x-model="form.shipping_method"
                        >

                        <label :for="shippingMethod.name" x-text="shippingMethod.label"></label>

                        <span
                            :class="{ 'text-line-through': hasFreeShipping }"
                            x-text="formatCurrency(shippingMethod.cost.inCurrentCurrency.amount)"
                        >
                        </span>
                    </div>
                </template>
            </div>
        </div>
    </div>
</template>
