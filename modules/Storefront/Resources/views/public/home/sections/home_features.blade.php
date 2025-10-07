<section x-data="HomeFeatures" class="features-wrap">
    <div class="container">
        <div class="features swiper overflow-hidden">
            <div class="feature-list swiper-wrapper" x-ref="featureList">
                @foreach ($features as $feature)
                    <div class="single-feature swiper-slide">
                        <div class="feature-icon">
                            <i class="{{ $feature->icon }}"></i>
                        </div>

                        <div class="feature-details">
                            <h6>{{ $feature->title }}</h6>
                            
                            <span>{{ $feature->subtitle }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</section>
