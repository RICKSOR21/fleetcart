<div x-show="hasAnyProduct" class="col-xl-6 col-lg-18">
    <template x-if="hasAnyProduct">
        <div class="daily-deals-wrap">
            <div class="daily-deals-header clearfix">
                <h3 class="section-title">
                    {!! $flashSale['title'] !!}
                </h3>
            </div>

            <div class="daily-deals swiper">
                <div class="swiper-wrapper">
                    <template
                        x-for="product in products"
                        :key="product.id"
                    >
                        <div class="swiper-slide">
                            <div x-data="FlashSaleProductCard(product)" class="daily-deals-inner">
                                <div class="daily-deals-top">
                                    <a :href="productUrl" class="product-image">
                                        <img
                                            :src="baseImage"
                                            :class="{ 'image-placeholder': !hasBaseImage }"
                                            :alt="productName"
                                            loading="lazy"
                                        />
                                    </a>
                                </div>

                                @include('storefront::public.partials.product_rating')

                                <a :href="productUrl" class="product-name">
                                    <span x-text="productName"></span>
                                </a>

                                <div class="product-info">
                                    <div class="product-price">
                                        <span class="special-price" x-text="formatCurrency(specialPrice)"></span>
                            
                                        <span class="previous-price" x-text="formatCurrency(regularPrice)"></span>
                                    </div>
                                </div>

                                @include('storefront::public.home.sections.flash_sale.product_countdown')

                                <div class="deal-progress">
                                    <div class="deal-stock">
                                        <div class="stock-available">
                                            {{ trans("storefront::product_card.available") }}

                                            <span x-text="product.pivot.qty"></span>
                                        </div>

                                        <div class="stock-sold">
                                            {{ trans("storefront::product_card.sold") }}

                                            <span x-text="product.pivot.sold"></span>
                                        </div>
                                    </div>

                                    <div class="progress">
                                        <div class="progress-bar" :style="{ width: progress }"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <div class="swiper-button-next">
                    {{ trans("storefront::layouts.next") }}
                </div>
                
                <div class="swiper-button-prev">
                    {{ trans("storefront::layouts.prev") }}
                </div>
            </div>
        </div>
    </template>
</div>