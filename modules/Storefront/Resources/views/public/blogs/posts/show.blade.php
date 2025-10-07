@extends('storefront::public.layout')

@section('title', trans('storefront::blog.blog_posts.blog_post'))

 @push('meta')
    <meta name="title" content="{{ $blogPost->meta->meta_title }}">
    <meta name="description" content="{{ $blogPost->meta->meta_description }}">
    <meta name="twitter:card" content="summary">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="{{ $blogPost->meta->meta_title }}">
    <meta property="og:description" content="{{ $blogPost->meta->meta_description }}">
    <meta property="og:image" content="{{ $blogPost->featuredImage->path }}">
    <meta property="og:locale" content="{{ locale() }}">

    @foreach (supported_locale_keys() as $code)
        <meta property="og:locale:alternate" content="{{ $code }}">
    @endforeach
@endpush

@section('content')
    <section class="blog-post-wrap">
        <div class="container">
            <div class="row">
                <div class="col-xl-14 col-lg-13 order-1 order-lg-0">
                    <div class="blog-post-content">
                        <div class="blog-post-featured-image overflow-hidden">
                            @if (!$blogPost->featured_image->path)
                                <div class="image-holder image-placeholder position-relative">
                                    <img src="{{ asset('build/assets/image-placeholder.png') }}" alt="Featured image" />
                                </div>
                            @else
                                <div class="image-holder position-relative">
                                    <img src="{{ $blogPost->featured_image->path }}" alt="Featured image" />
                                </div>
                            @endif
                        </div>

                        <ul class="list-inline blog-post-meta d-flex">
                            <li class="d-flex align-items-center">
                                <i class="las la-user"></i>

                                {{ $blogPost->user_name }}
                            </li>

                            <li class="d-flex align-items-center">
                                <i class="las la-calendar"></i>

                                {{ $blogPost->created_at->format('d M, Y') }}
                            </li>
                        </ul>

                        <h1 class="blog-post-title">{{ $blogPost->title }}</h1>

                        <div class="custom-page-content">
                            {!! $blogPost->description !!}
                        </div>
                    </div>

                    <div class="blog-post-social-share">
                        <div class="row">
                            <div class="col-md-3">
                                <h6>{{ trans('storefront::blog.blog_posts.social_share') }}</h5>
                            </div>

                            <div class="col-md-15">
                                <ul class="list-inline social-share-links">
                                    <li>
                                        <a href="https://www.facebook.com/sharer.php?u={{ route('blog_posts.show', ['slug' => $blogPost->slug]) }}" title="{{ trans('storefront::blog.blog_posts.facebook') }}" target="_blank">
                                            <i class="lab la-facebook"></i>
                                        </a>
                                    </li>
                            
                                    <li>
                                        <a href="https://twitter.com/share?url={{ route('blog_posts.show', ['slug' => $blogPost->slug]) }}&text={{ $blogPost->title }}" title="{{ trans('storefront::blog.blog_posts.twitter') }}" target="_blank">
                                            <svg class="twitter-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 30 30" width="30px" height="30px">
                                                <path d="M26.37,26l-8.795-12.822l0.015,0.012L25.52,4h-2.65l-6.46,7.48L11.28,4H4.33l8.211,11.971L12.54,15.97L3.88,26h2.65 l7.182-8.322L19.42,26H26.37z M10.23,6l12.34,18h-2.1L8.12,6H10.23z"/>
                                            </svg>
                                        </a>
                                    </li>
                            
                                    <li>
                                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ route('blog_posts.show', ['slug' => $blogPost->slug]) }}" title="{{ trans('storefront::blog.blog_posts.linkedin') }}" target="_blank">
                                            <i class="lab la-linkedin"></i>
                                        </a>
                                    </li>
                            
                                    <li>
                                        <a href="https://www.tumblr.com/share?v=3&u={{ route('blog_posts.show', ['slug' => $blogPost->slug]) }}" title="{{ trans('storefront::blog.blog_posts.tumblr') }}" target="_blank">
                                            <i class="lab la-tumblr"></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    @if (!$blogPost->tags->isEmpty())
                        <div class="blog-post-tags">
                            <div class="row">
                                <div class="col-md-3">
                                    <h6>{{ trans('storefront::blog.blog_posts.tags') }}</h5>
                                </div>

                                <div class="col-md-15">
                                    <ul class="list-inline">
                                        @foreach($blogPost->tags as $tag)
                                            <li>
                                                <a href="{{ route('blog_tag.blog_posts.index', ['tag'=> $tag->id])}}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                                        <path d="M4.16989 15.3L8.69989 19.83C10.5599 21.69 13.5799 21.69 15.4499 19.83L19.8399 15.44C21.6999 13.58 21.6999 10.56 19.8399 8.69005L15.2999 4.17005C14.3499 3.22005 13.0399 2.71005 11.6999 2.78005L6.69989 3.02005C4.69989 3.11005 3.10989 4.70005 3.00989 6.69005L2.76989 11.69C2.70989 13.04 3.21989 14.35 4.16989 15.3Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M9.5 12C10.8807 12 12 10.8807 12 9.5C12 8.11929 10.8807 7 9.5 7C8.11929 7 7 8.11929 7 9.5C7 10.8807 8.11929 12 9.5 12Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round"/>
                                                    </svg>

                                                    {{ $tag->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div class="col-xl-4 col-lg-5 order-0 order-lg-1 mb-4 mb-lg-0">
                    @include('storefront::public.blogs.posts.layouts.sidebar')
                </div>
            </div>
        </div>
    </section>
@endsection

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/blogs/show/main.scss',
    ])
@endPush
