<div
    class="filter-section-wrap"
    :class="{ active: $store.layout.isOpenSidebarFilter }"
>
    <div class="filter-section-header">
        <h4 class="section-title">
            {{ trans('storefront::products.filters') }}
        </h4>
        
        <svg @click="$store.layout.closeSidebarFilter()" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
            <path d="M15.8338 4.16663L4.16705 15.8333M4.16705 4.16663L15.8338 15.8333" stroke="#0E1E3E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
    </div>

    <div class="filter-section-inner custom-scrollbar">
        <div class="filter-section">
            <h6>{{ trans('storefront::products.price') }}</h6>

            <div class="filter-price">
                <form @submit.prevent="fetchProducts">
                    <div class="price-input">
                        <div class="form-group">
                            <input
                                type="number"
                                id="price-from"
                                class="form-control price-from"
                                :value="queryParams.fromPrice"
                                @change="updatePriceRange($event.target.value, null)"
                                x-ref="fromPrice"
                            >
                        </div>

                        <div class="form-group">
                            <input
                                type="number"
                                id="price-to"
                                class="form-control price-to"
                                :value="queryParams.toPrice"
                                @change="updatePriceRange(null, $event.target.value)"
                                x-ref="toPrice"
                            >
                        </div>
                    </div>

                    <div x-ref="priceRange" @change="fetchProducts"></div>
                </form>
            </div>
        </div>
        
        <template x-for="attribute in attributeFilters" :key="attribute.id">
            <div class="filter-section">
                <h6 x-text="attribute.name"></h6>

                <div class="filter-checkbox custom-scrollbar">
                    <template x-for="value in attribute.values" :key="value.id">
                        <div class="form-check">
                            <input
                                type="checkbox"
                                :name="attribute.slug"
                                :id="'attribute-' + value.id"
                                :checked="isFilteredByAttribute(attribute.slug, value.value)"
                                @click="toggleAttributeFilter(attribute.slug, value.value)"
                            >

                            <label :for="'attribute-' + value.id" x-text="value.value"></label>
                        </div>
                    </template>
                </div>
            </div>
        </template>
    </div>
</div>
