<section x-data="ProductTabsTwo({{ $productTabsTwo['tabs'] }})" class="landscape-tab-products-wrap">
    <div class="container">
        <div class="landscape-right-tab-products-inner">
            <div class="tab-products-header">
                <h3 class="section-title">{{ $productTabsTwo['title'] }}</h3>
    
                <div class="tab-products-header-overflow">
                    <ul class="tabs">
                        @foreach ($productTabsTwo['tabs'] as $key => $tab)
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
                <div class="landscape-right-tab-products products-slider swiper">
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
    
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>
</section>
