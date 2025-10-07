<div x-data="ProductCard(product)" class="vertical-product-card">
    <a :href="productUrl" class="product-image">
        <img
            :src="baseImage"
            :class="{ 'image-placeholder': !hasBaseImage }"
            :alt="productName"
            loading="lazy"
        />

        <div class="product-image-layer"></div>
    </a>

    <div class="product-info">
        <a :href="productUrl" class="product-name">
            <span x-text="productName"></span>
        </a>

        @include('storefront::public.partials.product_rating')
        
        <div class="product-price" x-html="productPrice"></div>
    </div>
</div>
