<div class="grid-view-products">
    <template
        x-for="product in products.data"
        :key="uid()"
    >
        <div class="grid-view-products-item">
            @include('storefront::public.partials.product_card')
        </div>
    </template>
</div>
