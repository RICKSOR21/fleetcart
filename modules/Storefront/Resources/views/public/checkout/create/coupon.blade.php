<div class="coupon-wrap">
    <div class="d-flex">
        <input
            type="text"
            placeholder="{{ trans('storefront::checkout.enter_coupon_code') }}"
            class="form-control"
            @keyup.enter="applyCoupon"
            @input="couponError = null"
            x-model="couponCode"
        >

        <button
            type="button"
            class="btn btn-default btn-apply-coupon"
            @click.prevent="applyCoupon"
        >
            {{ trans('storefront::checkout.apply') }}
        </button>
    </div>

    <template x-if="couponError">
        <span class="error-message" x-text="couponError"></span>
    </template>
</div>
