<ul class="list-inline">
    @foreach ($subCategories as $subCategory)
        <li :class="{ active: queryParams.category === '{{ $subCategory->slug }}' }">
            @if ($subCategory->items->isNotEmpty())
                <i
                    class="las la-angle-right"
                    @click="
                        $($el).toggleClass('open');
                        $($el).siblings('ul').slideToggle(200);
                    "
                >
                </i>
            @endif
            
            <a
                href="{{ route('categories.products.index', ['category' => $subCategory->slug]) }}"
                @click.prevent='
                    changeCategory({
                        name: "{{ $subCategory->name }}",
                        banner: {{ $subCategory->banner }},
                        slug: "{{ $subCategory->slug }}"
                    })
                '
            >
                {{ $subCategory->name }}
            </a>

            @if ($subCategory->items->isNotEmpty())
                @include('storefront::public.products.index.browse_sub_categories', ['subCategories' => $subCategory->items])
            @endif
        </li>
    @endforeach
</ul>
