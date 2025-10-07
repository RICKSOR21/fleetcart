<div
    x-data="HeaderSearch({
        categories: {{ $categories }},
        initialQuery: '{{ addslashes(request('query')) }}',
        initialCategory: '{{ addslashes(request('category')) }}'
    })"
    class="header-search-wrap-parent"
>
    <div
        class="header-search-wrap-overlay"
        :class="{ active: showSuggestions }"
    >
    </div>

    <div
        class="header-search-wrap"
        :class="{ 'has-suggestion': hasAnySuggestion }"
        @click.away="hideSuggestions"
    >
        <div class="header-search">
            <form autocomplete="on" class="search-form" @submit.prevent="search">
                <div
                    class="header-search-lg"
                    :class="{
                        'header-search-lg-background': showSuggestions
                    }"
                >
                    <input
                        type="text"
                        name="query"
                        class="form-control search-input"
                        :class="{ focused: showSuggestions }"
                        autocomplete="on"
                        placeholder="{{ trans('storefront::layouts.search_for_products') }}"
                        @focus="showExistingSuggestions"
                        @keydown.escape="hideSuggestions"
                        @keydown.down="nextSuggestion"
                        @keydown.up="prevSuggestion"
                        x-model="form.query"
                    />

                    <div
                        class="header-search-right"
                        :class="{
                            'header-search-right-background': showSuggestions
                        }"
                    >
                        <div
                            x-data="{
                                open: false,
                                selected: getCategoryNameBySlug(initialCategory)
                            }"
                            class="dropdown custom-dropdown"
                            @click.away="open = false"
                        >
                            <div
                                class="btn btn-secondary dropdown-toggle skeleton"
                                :class="{ active: open, skeleton }"
                                @click="open = !open"
                            >
                                <span x-text="selected || '{{ trans("storefront::layouts.all_categories") }}'"></span>

                                <i class="las la-angle-down"></i>
                            </div>

                            <ul
                                x-cloak
                                x-transition
                                x-show="open"
                                class="dropdown-menu"
                                :class="{ active: open }"
                            >
                                <div class="dropdown-menu-scroll">
                                    <li
                                        class="dropdown-item"
                                        :class="{
                                            active: selected === '' 
                                        }"
                                        @click="
                                            open = false;
                                            
                                            if (selected !== '') {
                                                changeCategory();
                                            }
    
                                            selected = '';
                                        "
                                    >
                                        {{ trans("storefront::layouts.all_categories") }}
                                    </li>
    
                                    <template x-for="(category, index) in categories" :key="index">
                                        <li
                                            class="dropdown-item"
                                            :class="{ active: category.name === selected }"
                                            @click="
                                                open = false;
                                                
                                                if (selected !== category.name) {
                                                    changeCategory(category.slug);
                                                }
    
                                                selected = category.name;
                                            "
                                            x-text="category.name"
                                        >
                                        </li>
                                    </template>
                                </div>
                            </ul>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary btn-search"
                            aria-label="Search Button"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="24"
                                height="24"
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <path
                                    d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                    stroke="#292D32"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                                <path
                                    d="M22 22L20 20"
                                    stroke="#292D32"
                                    stroke-width="1.5"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="header-search-sm" @click="showMiniSearch = true">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                            stroke="#292D32"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        <path
                            d="M22 22L20 20"
                            stroke="#292D32"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>
            </form>
        </div>

        <div class="header-search-sm-form" :class="{ active: showMiniSearch }">
            <form autocomplete="on" class="search-form" @submit.prevent="search">
                <div class="btn-close" @click="showMiniSearch = false">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M9.57 5.93005L3.5 12.0001L9.57 18.0701"
                            stroke="#292D32"
                            stroke-width="1.5"
                            stroke-miterlimit="10"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        <path
                            d="M20.4999 12H3.66992"
                            stroke="#292D32"
                            stroke-width="1.5"
                            stroke-miterlimit="10"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </div>

                <input
                    x-ref="miniSearchInput"
                    type="text"
                    name="query"
                    class="form-control search-input-sm"
                    autocomplete="on"
                    placeholder="{{ trans('storefront::layouts.search_for_products') }}"
                    :value="form.query"
                    @input="form.query = $event.target.value"
                    @focus="showExistingSuggestions"
                    @keydown.escape="hideSuggestions"
                    @keydown.down="nextSuggestion"
                    @keydown.up="prevSuggestion"
                />

                <button type="submit" class="btn btn-search" aria-label="Search Button">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                    >
                        <path
                            d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                            stroke="#292D32"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                        <path
                            d="M22 22L20 20"
                            stroke="#292D32"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                        />
                    </svg>
                </button>
            </form>
        </div>

        <div
            x-cloak
            x-show="shouldShowSuggestions"
            class="search-suggestions overflow-hidden"
        >
            <div
                class="search-suggestions-inner"
                x-ref="searchSuggestionsInner"
            >
                <template x-if="hasAnyCategorySuggestion">
                    <div class="category-suggestion">
                        <h6 class="title">
                            {{ trans("storefront::layouts.category_suggestions") }}
                        </h6>

                        <ul class="list-inline category-suggestion-list">
                            <template
                                x-for="category in suggestions.categories"
                                :key="category.slug"
                            >
                                <li
                                    class="list-item"
                                    :class="{
                                        active: isActiveSuggestion(category),
                                    }"
                                    :data-slug="category.slug"
                                    @mouseover="changeActiveSuggestion(category)"
                                    @mouseleave="clearActiveSuggestion"
                                >
                                    <a
                                        :href="category.url"
                                        class="single-item"
                                        x-text="category.name"
                                        @click="hideSuggestions"
                                    >
                                    </a>
                                </li>
                            </template>
                        </ul>
                    </div>
                </template>

                <div class="product-suggestion">
                    <h6 class="title">
                        {{ trans("storefront::layouts.product_suggestions") }}
                    </h6>

                    <ul class="list-inline product-suggestion-list">
                        <template
                            x-for="product in suggestions.products"
                            :key="product.slug"
                        >
                            <li
                                class="list-item"
                                :class="{
                                    active: isActiveSuggestion(product),
                                }"
                                :data-slug="product.slug"
                                @mouseover="changeActiveSuggestion(product)"
                                @mouseleave="clearActiveSuggestion"
                            >
                                <a
                                    :href="product.url"
                                    class="single-item"
                                    @click="hideSuggestions"
                                >
                                    <div class="product-image">
                                        <img
                                            :src="baseImage(product)"
                                            :class="{
                                                'image-placeholder': !hasBaseImage(product),
                                            }"
                                            :alt="product.name"
                                        />
                                    </div>

                                    <div class="product-info">
                                        <div class="product-info-top">
                                            <h6 class="product-name" x-html="product.name"></h6>

                                            <template x-if="product.is_out_of_stock">
                                                <ul class="list-inline product-badge">
                                                    <li class="badge badge-danger">
                                                        {{ trans("storefront::product_card.out_of_stock") }}
                                                    </li>
                                                </ul>
                                            </template>
                                        </div>

                                        <div
                                            class="product-price"
                                            x-html="product.formatted_price"
                                        ></div>
                                    </div>
                                </a>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <template x-if="suggestions.remaining !== 0">
                <a
                    :href="moreResultsUrl"
                    class="more-results"
                    x-text="
                        trans('storefront::layouts.more_results', {
                            count: suggestions.remaining
                        })
                    "
                    @click="hideSuggestions"
                >
                </a>
            </template>
        </div>
    </div>
    
    @include('storefront::public.layouts.header.search_suggestions')
</div>