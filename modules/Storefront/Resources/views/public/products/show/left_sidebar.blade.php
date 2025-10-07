<aside class="left-sidebar">
    @if ($upSellProducts->isNotEmpty())
        <div class="vertical-products">
            <div class="vertical-products-header">
                <div class="section-title">{{ trans('storefront::product.you_might_also_like') }}</div>
            </div>

            <div class="vertical-products-slider swiper" x-ref="upSellProducts">
                <div x-cloak class="swiper-wrapper">
                    @foreach ($upSellProducts->chunk(5) as $upSellProductChunks)
                        <div class="swiper-slide">
                            <div class="vertical-products-slide">
                                @foreach ($upSellProductChunks as $upSellProduct)
                                    <div x-data="ProductCard({{ $upSellProduct }})" class="vertical-product-card">
                                        <a :href="productUrl" class="product-image">
                                            <img
                                                :src="baseImage"
                                                :class="{ 'image-placeholder': !hasBaseImage }"
                                                :alt="productName"
                                                loading="lazy"
                                            />

                                            <div class="product-image-layer"></div>
                                        </a>

                                        <div class="product-info">
                                            <a :href="productUrl" class="product-name">
                                                <span x-text="productName"></span>
                                            </a>

                                            @include('storefront::public.partials.product_rating')
                                        
                                            <div class="product-price" x-html="productPrice"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </div>
    @endif

    @if ($banner->image->exists)
        <a
            href="{{ $banner->call_to_action_url }}"
            class="banner d-none d-lg-block"
            target="{{ $banner->open_in_new_window ? '_blank' : '_self' }}"
        >
            <img src="{{ $banner->image->path }}" alt="Banner" loading="lazy" />
        </a>
    @endif
</aside>
