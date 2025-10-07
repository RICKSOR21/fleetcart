<section class="banner-wrap two-column-banner">
    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <a
                    href="{{ $twoColumnBanners['banner_1']->call_to_action_url }}"
                    class="banner"
                    target="{{ $twoColumnBanners['banner_1']->open_in_new_window ? '_blank' : '_self' }}"
                >
                    <img src="{{ $twoColumnBanners['banner_1']->image->path }}" alt="Banner" loading="lazy" />
                </a>
            </div>

            <div class="col-md-9">
                <a
                    href="{{ $twoColumnBanners['banner_2']->call_to_action_url }}"
                    class="banner"
                    target="{{ $twoColumnBanners['banner_2']->open_in_new_window ? '_blank' : '_self' }}"
                >
                    <img src="{{ $twoColumnBanners['banner_2']->image->path }}" alt="Banner" loading="lazy" />
                </a>
            </div>
        </div>
    </div>
</section>