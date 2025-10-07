<section x-data="TopBrands" class="top-brands-wrap clearfix">
    <div class="container">
        <div x-ref="topBrands" class="top-brands swiper clearfix">
            <div class="top-brand-list swiper-wrapper">
                @foreach ($topBrands as $topBrand)
                    <a
                        href="{{ $topBrand['url'] }}"
                        class="swiper-slide top-brand-item d-inline-flex align-items-center justify-content-center overflow-hidden"
                    >
                        <img src="{{ $topBrand['logo']['path'] }}" alt="Brand logo" loading="lazy" />
                    </a>
                @endforeach
            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section> 