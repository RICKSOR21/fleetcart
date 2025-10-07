<section class="banner-wrap one-column-banner">
    <div class="container">
        <a
            href="{{ $oneColumnBanner->call_to_action_url }}"
            class="banner"
            target="{{ $oneColumnBanner->open_in_new_window ? '_blank' : '_self' }}"
        >
            <img src="{{ $oneColumnBanner->image->path }}" alt="Banner" loading="lazy" />
        </a>
    </div>
</section>
