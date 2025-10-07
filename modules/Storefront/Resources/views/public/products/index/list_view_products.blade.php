<div class="list-view-products">
    <template
        x-for="product in products.data"
        :key="uid()"
    >
        <div class="list-view-products-item">
            <div x-data="ProductCard(product)" class="list-view-product-card">
                <div class="product-card-left position-relative">
                    <a :href="productUrl" class="product-image"> 
                        <img
                            :src="baseImage"
                            :class="{ 'image-placeholder': !hasBaseImage }"
                            :alt="productName"
                            loading="lazy"
                        />

                    </a>
                    
                    <div class="product-card-actions">
                        <button
                            class="btn btn-wishlist"
                            :class="{ added: inWishlist }"
                            @click="syncWishlist"
                        >
                            <template x-if="inWishlist">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    width="24"
                                    height="24"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                >
                                    <path
                                        d="M16.44 3.1001C14.63 3.1001 13.01 3.9801 12 5.3301C10.99 3.9801 9.37 3.1001 7.56 3.1001C4.49 3.1001 2 5.6001 2 8.6901C2 9.8801 2.19 10.9801 2.52 12.0001C4.1 17.0001 8.97 19.9901 11.38 20.8101C11.72 20.9301 12.28 20.9301 12.62 20.8101C15.03 19.9901 19.9 17.0001 21.48 12.0001C21.81 10.9801 22 9.8801 22 8.6901C22 5.6001 19.51 3.1001 16.44 3.1001Z"
                                        fill="#292D32"
                                    />
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
                            @click="syncCompareList"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M13.6667 3.66675H6.33333C3.85781 3.66675 2 5.45677 2 8.00008" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M2.33301 12.3333H9.66634C12.1419 12.3333 13.9997 10.5433 13.9997 8" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M12.333 2C12.333 2 13.9997 3.22748 13.9997 3.66668C13.9997 4.10588 12.333 5.33333 12.333 5.33333" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
                                <path d="M3.66665 10.6667C3.66665 10.6667 2.00001 11.8942 2 12.3334C1.99999 12.7726 3.66667 14.0001 3.66667 14.0001" stroke="white" stroke-width="1.25" stroke-linecap="round" stroke-linejoin="round"></path>
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

                <div class="product-card-right">
                    <div class="product-name-and-rating">
                        <a :href="productUrl" class="product-name">
                            <span x-text="productName"></span>
                        </a>
    
                        @include('storefront::public.partials.product_rating')
                    </div>

                    <div class="product-price" x-html="productPrice"></div>

                    <div class="product-card-actions-parent">
                        <template x-if="hasNoOption || isOutOfStock">
                            <button
                                class="btn btn-default btn-add-to-cart"
                                :class="{ 'btn-loading': addingToCart }"
                                :disabled="isOutOfStock"
                                @click="addToCart"
                            >
                                {{ trans("storefront::product_card.add_to_cart") }}
                            </button>
                        </template>
    
                        <template x-if="!(hasNoOption || isOutOfStock)">
                            <a
                                :href="productUrl"
                                class="btn btn-default btn-add-to-cart"
                            >
                                {{ trans("storefront::product_card.view_options") }}
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>
