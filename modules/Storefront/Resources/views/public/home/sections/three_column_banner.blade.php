<section class="banner-wrap three-column-banner">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <a
                    href="{{ $threeColumnBanners['banner_1']->call_to_action_url }}"
                    class="banner"
                    target="{{ $threeColumnBanners['banner_1']->open_in_new_window ? '_blank' : '_self' }}"
                >
                    <img src="{{ $threeColumnBanners['banner_1']->image->path }}" alt="Banner" loading="lazy" />
                </a>
            </div>

            <div class="col-md-6">
                <a
                    href="{{ $threeColumnBanners['banner_2']->call_to_action_url }}"
                    class="banner"
                    target="{{ $threeColumnBanners['banner_2']->open_in_new_window ? '_blank' : '_self' }}"
                >
                    <img src="{{ $threeColumnBanners['banner_2']->image->path }}" alt="Banner" loading="lazy" />
                </a>
            </div>

            <div class="col-md-6">
                <a
                    href="{{ $threeColumnBanners['banner_3']->call_to_action_url }}"
                    class="banner"
                    target="{{ $threeColumnBanners['banner_3']->open_in_new_window ? '_blank' : '_self' }}"
                >
                    <img src="{{ $threeColumnBanners['banner_3']->image->path }}" alt="Banner" loading="lazy" />
                </a>
            </div>
        </div>
    </div>
</section>