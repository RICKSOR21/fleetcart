<section x-data="GridProducts({{ $gridProducts }})" class="grid-products-wrap">
    <div class="container">
        <div class="grid-products-wrap-inner">
            <div class="tab-products-header">
                <ul class="tabs">
                    @foreach ($gridProducts as $key => $tab)
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
    
            <div class="tab-content">
                <div class="grid-products products-slider swiper">
                    <div class="swiper-wrapper">
                        @foreach (range(0, 15) as $skeleton)
                            <div class="swiper-slide swiper-slide-skeleton">
                                @include('storefront::public.partials.product_card_skeleton')
                            </div>
                        @endforeach

                        <template
                            x-for="product in products"
                            :key="product.id"
                        >
                            <div class="swiper-slide">
                                <div class="grid-products-item"> 
                                    @include('storefront::public.partials.product_card')
                                </div>
                            </div>
                        </template>
                    </div>
                    
                    <div class="swiper-pagination"></div>

                    <div class="swiper-button-next">
                        {{ trans("storefront::layouts.next") }}
                    </div>
                    
                    <div class="swiper-button-prev">
                        {{ trans("storefront::layouts.prev") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
