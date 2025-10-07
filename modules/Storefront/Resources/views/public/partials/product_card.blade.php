<div x-data="ProductCard({{ $data ?? 'product' }})" class="product-card">
    <div class="product-card-top">
        <a :href="productUrl" class="product-image">
            <img
                :src="baseImage"
                :class="{ 'image-placeholder': !hasBaseImage }"
                :alt="productName"
                loading="lazy"
            />

            <div class="product-image-layer"></div>
        </a>

        <div class="product-card-actions">
            <button
                class="btn btn-wishlist"
                :class="{ added: inWishlist }"
                title="{{ trans('storefront::product_card.wishlist') }}"
                @click="syncWishlist"
            >
                <template x-if="inWishlist">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M12.9747 2.66277C11.1869 1.56615 9.62661 2.00807 8.68927 2.71201C8.30487 3.00064 8.11274 3.14495 7.99967 3.14495C7.88661 3.14495 7.69447 3.00064 7.31007 2.71201C6.37275 2.00807 4.8124 1.56615 3.02463 2.66277C0.678387 4.10196 0.147487 8.84993 5.55936 12.8556C6.59015 13.6185 7.10554 14 7.99967 14C8.89381 14 9.40921 13.6185 10.44 12.8556C15.8519 8.84993 15.3209 4.10196 12.9747 2.66277Z" stroke="#1B1339" stroke-width="1.25" stroke-linecap="round"/>
                    </svg>
                </template>
                
                <template x-if="!inWishlist">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M12.62 20.81C12.28 20.93 11.72 20.93 11.38 20.81C8.48 19.82 2 15.69 2 8.68998C2 5.59998 4.49 3.09998 7.56 3.09998C9.38 3.09998 10.99 3.97998 12 5.33998C13.01 3.97998 14.63 3.09998 16.44 3.09998C19.51 3.09998 22 5.59998 22 8.68998C22 15.69 15.52 19.82 12.62 20.81Z"
                            stroke="#292D32"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </template>
            </button>

            <button
                class="btn btn-compare"
                :class="{ added: inCompareList }"
                title="{{ trans('storefront::product_card.compare') }}"
                @click="syncCompareList"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M13.6667 3.66675H6.33333C3.85781 3.66675 2 5.45677 2 8.00008" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2.33301 12.3333H9.66634C12.1419 12.3333 13.9997 10.5433 13.9997 8" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12.333 2C12.333 2 13.9997 3.22748 13.9997 3.66668C13.9997 4.10588 12.333 5.33333 12.333 5.33333" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3.66665 10.6667C3.66665 10.6667 2.00001 11.8942 2 12.3334C1.99999 12.7726 3.66667 14.0001 3.66667 14.0001" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>

        <ul class="list-inline product-badge">
            <template x-if="isOutOfStock">
                <li class="badge badge-danger">
                    {{ trans("storefront::product_card.out_of_stock") }}
                </li>
            </template>

            <template x-if="isNew">
                <li class="badge badge-info">
                    {{ trans("storefront::product_card.new") }}
                </li>
            </template>

            <template x-if="hasPercentageSpecialPrice">
                <li
                    class="badge badge-success"
                    x-text="`-${item.special_price_percent}%`"
                >
                </li>
            </template>

            <template x-if="hasSpecialPrice && !hasPercentageSpecialPrice">
                <li
                    class="badge badge-success"
                    x-text="`-${specialPricePercent}%`"
                >
                </li>
            </template>
        </ul>
    </div>

    <div class="product-card-middle">
        <a :href="productUrl" class="product-name">
            <span x-text="productName"></span>
        </a> 
        
        @include('storefront::public.partials.product_rating')
    </div>

    <div class="product-card-bottom">
        <div class="product-price">
            <template x-if="hasSpecialPrice">
                <span class="special-price" x-text="formatCurrency(specialPrice)"></span>
            </template>

            <span class="previous-price" x-text="formatCurrency(regularPrice)"></span>
        </div>

        <template x-if="hasNoOption || isOutOfStock">
            <button
                class="btn btn-primary btn-add-to-cart"
                :class="{ 'btn-loading': addingToCart }"
                :disabled="isOutOfStock"
                title="{{ trans('storefront::product_card.add_to_cart') }}"
                @click="addToCart"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <g clip-path="url(#clip0_2055_61)">
                        <path d="M1.3335 1.33325H2.20427C2.36828 1.33325 2.45029 1.33325 2.51628 1.36341C2.57444 1.38999 2.62373 1.43274 2.65826 1.48655C2.69745 1.54761 2.70905 1.6288 2.73225 1.79116L3.04778 3.99992M3.04778 3.99992L3.74904 9.15419C3.83803 9.80827 3.88253 10.1353 4.0389 10.3815C4.17668 10.5984 4.37422 10.7709 4.60773 10.8782C4.87274 10.9999 5.20279 10.9999 5.8629 10.9999H11.5682C12.1965 10.9999 12.5107 10.9999 12.7675 10.8869C12.9939 10.7872 13.1881 10.6265 13.3283 10.4227C13.4875 10.1917 13.5462 9.88303 13.6638 9.26576L14.5462 4.63305C14.5876 4.41579 14.6083 4.30716 14.5783 4.22225C14.552 4.14777 14.5001 4.08504 14.4319 4.04526C14.3541 3.99992 14.2435 3.99992 14.0223 3.99992H3.04778ZM6.66683 13.9999C6.66683 14.3681 6.36835 14.6666 6.00016 14.6666C5.63197 14.6666 5.3335 14.3681 5.3335 13.9999C5.3335 13.6317 5.63197 13.3333 6.00016 13.3333C6.36835 13.3333 6.66683 13.6317 6.66683 13.9999ZM12.0002 13.9999C12.0002 14.3681 11.7017 14.6666 11.3335 14.6666C10.9653 14.6666 10.6668 14.3681 10.6668 13.9999C10.6668 13.6317 10.9653 13.3333 11.3335 13.3333C11.7017 13.3333 12.0002 13.6317 12.0002 13.9999Z" stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                    <defs>
                        <clipPath id="clip0_2055_61">
                            <rect width="16" height="16" fill="white"/>
                        </clipPath>
                    </defs>
                </svg>
            </button>
        </template>
        
        <template x-if="!(hasNoOption || isOutOfStock)">
            <a
                :href="productUrl"
                title="{{ trans('storefront::product_card.view_options') }}"
                class="btn btn-primary btn-add-to-cart"
            >
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M14.3623 7.3635C14.565 7.6477 14.6663 7.78983 14.6663 8.00016C14.6663 8.2105 14.565 8.35263 14.3623 8.63683C13.4516 9.9139 11.1258 12.6668 7.99967 12.6668C4.87353 12.6668 2.54774 9.9139 1.63703 8.63683C1.43435 8.35263 1.33301 8.2105 1.33301 8.00016C1.33301 7.78983 1.43435 7.6477 1.63703 7.3635C2.54774 6.08646 4.87353 3.3335 7.99967 3.3335C11.1258 3.3335 13.4516 6.08646 14.3623 7.3635Z" stroke="white" stroke-width="1"/>
                    <path d="M10 8C10 6.8954 9.1046 6 8 6C6.8954 6 6 6.8954 6 8C6 9.1046 6.8954 10 8 10C9.1046 10 10 9.1046 10 8Z" stroke="white" stroke-width="1"/>
                </svg>
            </a>
        </template>
    </div>
</div>
