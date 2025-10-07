<section x-data="FeaturedCategories({{ $featuredCategories['categories'] }})" class="featured-categories-wrap">
    <div class="container">
        <div class="featured-categories-header">
            <div class="featured-categories-text">
                <h2 class="title">{{ $featuredCategories['title'] }}</h2>
                
                <span class="excerpt">{{ $featuredCategories['subtitle'] }}</span>
            </div>

            <ul class="tabs featured-categories-tabs">
                @foreach ($featuredCategories['categories'] as $key => $tab)
                    <li
                        class="tab-item"
                        :class="classes({{ $key }})"
                        @click="changeTab({{ $key }})"
                    >
                        <div class="featured-category-image">
                            @if ($tab['logo']->path)
                                <img
                                    src="{{ $tab['logo']->path }}"
                                    alt="Category logo"
                                    loading="lazy"
                                />
                            @else
                                <img
                                    src="{{ asset('build/assets/image-placeholder.png') }}"
                                    class="image-placeholder"
                                    alt="Category logo"
                                    loading="lazy"
                                />
                            @endif
                        </div>
                        
                        <span class="featured-category-name">
                            {{ $tab['name'] }}
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="tab-content">
            <div class="featured-category-products products-slider swiper"> 
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
</section>
