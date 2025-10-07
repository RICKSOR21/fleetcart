<section x-data="ProductTabsOne({{ $productTabsOne }})" class="landscape-tab-products-wrap">
    <div class="container">
        <div class="landscape-left-tab-products-inner">
            <div class="tab-products-header">
                <div class="tab-products-header-overflow">
                    <ul class="tabs">
                        @foreach($productTabsOne as $key => $tab)
                            <li
                                class="tab-item"
                                :class="classes({{ $key }})"
                                @click="changeTab({{ $key }})"
                            >
                                {{ $tab }}
                            </li>
                        @endforeach
                    </ul>
    
                    <hr>
                </div>
            </div>
    
            <div class="tab-content">
                <div class="landscape-left-tab-products products-slider swiper"> 
                    <div class="swiper-wrapper">
                        @foreach (range(0, 7) as $skeleton)
                            <div class="swiper-slide swiper-slide-skeleton">
                                @include('storefront::public.partials.product_card_skeleton')
                            </div>
                        @endforeach
                        
                        <template x-for="product in products" :key="product.id">
                            <div class="swiper-slide">
                                @include('storefront::public.partials.product_card')
                            </div>
                        </template>
                    </div>
    
                    <div class="swiper-button-next">
                        {{ trans("storefront::layouts.next") }}
                    </div>
                    
                    <div class="swiper-button-prev">
                        {{ trans("storefront::layouts.prev") }}
                    </div>

                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>
