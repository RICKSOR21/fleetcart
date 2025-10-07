<div x-data="ProductRating({{ $data ?? 'product' }})" class="product-rating">
    <div class="back-stars">
        <i class="las la-star"></i>
        <i class="las la-star"></i>
        <i class="las la-star"></i>
        <i class="las la-star"></i>
        <i class="las la-star"></i>

        <div x-cloak class="front-stars" :style="{ width: ratingPercent + '%' }">
            <i class="las la-star"></i>
            <i class="las la-star"></i>
            <i class="las la-star"></i>
            <i class="las la-star"></i>
            <i class="las la-star"></i>
        </div>
    </div>

    <template x-if="hasReviewCount">
        <span class="rating-count" x-text="reviewCount"></span>
    </template>

    <template x-if="hasReviewCount">
        <div
            class="reviews"
            x-text="
                reviewCount > 1 ?
                '{{ trans('storefront::product_card.reviews') }}' :
                '{{ trans('storefront::product_card.review') }}'
            "
        >
        </div>
    </template>
</div>
