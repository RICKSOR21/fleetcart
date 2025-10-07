<div class="blog-post-card">
    <div class="blog-post">
        <a
            href="{{ route('blog_posts.show', $blogPost->slug) }}"
            class="blog-post-featured-image overflow-hidden"
        >
            @if ($blogPost->featured_image->path)
                <img src="{{ $blogPost->featured_image->path }}" alt="Featured image" loading="lazy" />
            @else
                <div class="image-placeholder">
                    <img src="{{ asset('build/assets/image-placeholder.png') }}" alt="Featured image" loading="lazy" />
                </div>
            @endif
        </a>

        <div class="blog-post-body">
            <ul class="list-inline blog-post-meta">
                <li class="d-flex align-items-center">
                    <i class="las la-user"></i>

                    {{ $blogPost->username }}
                </li>

                <li class="d-flex align-items-center">
                    <i class="las la-calendar"></i>

                    {{ (new \DateTime())->format('d M, Y') }}
                </li>
            </ul>

            <h4 class="blog-post-title">
                <a href="{{ route('blog_posts.show', $blogPost->slug) }}">
                    {{ $blogPost->title }}
                </a>
            </h4>

            <a
                href="{{ route('blog_posts.show', $blogPost->slug) }}"
                class="blog-post-read-more"
            >
                {{ trans("storefront::blog.blog_posts.read_post") }}

                <i class="las la-arrow-right"></i>
            </a>
        </div>
    </div>
</div>