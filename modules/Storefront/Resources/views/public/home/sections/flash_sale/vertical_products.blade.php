<div
    x-data="VerticalProducts({{ $columnNumber }})"
    x-show="hasAnyProduct"
    class="{{ $flashSaleEnabled ? 'col-xl-4 col-lg-6' : 'col-xl-6 col-lg-6' }}"
>
    <template x-if="hasAnyProduct">
        <div class="vertical-products">
            <div class="vertical-products-header">
                <h3 class="section-title">{{ $title }}</h3>
            </div>

            <div class="vertical-products-slider swiper" x-ref="verticalProducts">
                <div class="swiper-wrapper"> 
                    <template
                        x-for="(productChunks, index) in chunk(products, 5)"
                        :key="index"
                    >
                        <div class="swiper-slide">
                            <template
                                x-for="product in productChunks"
                                :key="product.id"
                            >
                                @include('storefront::public.partials.vertical_products')
                            </template>
                        </div>
                    </template>
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    </template>
</div>