<aside class="order-summary-wrap">
    <div class="order-summary">
        <div class="order-summary-top">
            <h3 class="section-title">{{ trans('storefront::checkout.order_summary') }}</h3>
            
            @include('storefront::public.checkout.create.cart_items_skeleton')
            
            <template x-if="cartFetched">
                <ul class="cart-items list-inline">
                    <template x-for="cartItem in cart.items" :key="cartItem.id">
                        <li x-data="CartItem(cartItem)" class="cart-item">
                            <a :href="productUrl" class="product-image">
                                <img
                                    :src="baseImage"
                                    :class="{
                                        'image-placeholder': !hasBaseImage,
                                    }"
                                    :alt="productName"
                                />

                                <span class="qty-count" x-text="cartItem.qty"></span>
                            </a>

                            <div class="product-info">
                                <a
                                    :href="productUrl"
                                    class="product-name"
                                    :title="productName"
                                    x-text="productName"
                                >
                                </a>
                                
                                <template x-if="hasAnyVariation">
                                    <ul class="list-inline product-options">
                                        <template
                                            x-for="(variation, key) in cartItem.variations"
                                            :key="variation.id"
                                        >
                                            <li>
                                                <label x-text="`${variation.name}:`"></label>
                                                
                                                <span x-text="`${variation.values[0].label}${variationsLength === Number(key) ? '' : ','}`"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </template>

                                <template x-if="hasAnyOption">
                                    <ul class="list-inline product-options">
                                        <template
                                            x-for="(option, key) in cartItem.options"
                                            :key="option.id"
                                        >
                                            <li>
                                                <label x-text="`${option.name}:`"></label>
                                                
                                                <span x-text="`${optionValues(option)}${optionsLength === Number(key) ? '' : ','}`"></span>
                                            </li>
                                        </template>
                                    </ul>
                                </template>
                            </div>
                            
                            <div class="product-price" x-text="formatCurrency(unitPrice)"></div>
                        </li>
                    </template>
                </ul>
            </template>

            @include('storefront::public.checkout.create.coupon')
        </div>

        <div class="order-summary-middle">
            <ul class="list-inline order-summary-list order-summary-list-skeleton">
                <li>
                    <label class="skeleton"></label>

                    <span class="skeleton"></span>
                </li>

                <li>
                    <label class="skeleton"></label>

                    <span class="skeleton"></span>
                </li>
            </ul>

            <template x-if="cartFetched">
                <ul class="list-inline order-summary-list">
                    <li>
                        <label>{{ trans('storefront::checkout.subtotal') }}</label>

                        <span x-text="formatCurrency($store.cart.subTotal)"></span>
                    </li>

                    <template x-for="(tax, index) in cart.taxes" :key="index">
                        <li>
                            <label x-text="tax.name"></label>

                            <span x-text="formatCurrency(tax.amount.inCurrentCurrency.amount)"></span>
                        </li>
                    </template>

                    <template x-if="$store.cart.hasCoupon">
                        <li>
                            <label>
                                {{ trans('storefront::checkout.coupon') }}

                                <span class="coupon-code">
                                    (<span x-text="cart.coupon.code"></span>)
                                    
                                    <span class="btn-remove-coupon" @click="removeCoupon">
                                        <i class="las la-times"></i>
                                    </span>
                                </span>
                            </label>

                            <span class="color-primary" x-text="`-${formatCurrency($store.cart.couponValue)}`"></span>
                        </li>
                    </template>

                    <template x-if="hasShippingMethod">
                        <li>
                            <label>
                                {{ trans('storefront::checkout.shipping_cost') }}
                            </label>

                            <span
                                :class="{ 'color-primary': hasFreeShipping }"
                                x-text="
                                    hasFreeShipping ?
                                    '{{ trans('storefront::checkout.free') }}' :
                                    formatCurrency($store.cart.shippingCost)
                                "
                            >
                            </span>
                        </li>
                    </template>
                </ul>
            </template>

            @include('storefront::public.checkout.create.order_summary_total_skeleton')

            <template x-if="cartFetched">
                <div class="order-summary-total">
                    <label>{{ trans('storefront::checkout.total') }}</label>

                    <span x-text="formatCurrency($store.cart.total)"></span>
                </div>
            </template>
        </div>

        <div class="order-summary-bottom">
            <div class="form-group checkout-terms-and-conditions">
                <div class="form-check">
                    <input type="checkbox" x-model="form.terms_and_conditions" id="terms-and-conditions">

                    <label for="terms-and-conditions" class="form-check-label">
                        {{ trans('storefront::checkout.i_agree_to_the') }}

                        <a href="{{ $termsPageURL }}">
                            {{ trans('storefront::checkout.terms_&_conditions') }}
                        </a>
                    </label>

                    <template x-if="errors.has('terms_and_conditions')">
                        <span class="error-message" x-text="errors.get('terms_and_conditions')"></span>
                    </template>
                </div>
            </div>

            <template x-if="form.payment_method === 'paypal'">
                <div id="paypal-button-container"></div>
            </template>

            <template x-if="form.payment_method !== 'paypal'">
                <button
                    x-cloak
                    type="button"
                    class="btn btn-primary btn-place-order"
                    :class="{ 'btn-loading': placingOrder }"
                    :disabled="!form.terms_and_conditions"
                    @click="placeOrder"
                >
                    {{ trans('storefront::checkout.place_order') }}
                </button>
            </template>
        </div>
    </div>
</aside>
