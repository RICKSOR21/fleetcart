<section
    x-data="FlashSale"
    class="
        vertical-products-wrap
        {{ $flashSaleEnabled ? 'flash-sale-enabled' : '' }}
    "
>
    <div class="container">
        <div class="row">
            @if ($flashSaleEnabled)
                @include('storefront::public.home.sections.flash_sale.products')
            @endif
            
            @include('storefront::public.home.sections.flash_sale.vertical_products', [
                'title' => $flashSale['vertical_products_1_title'],
                'columnNumber' => 1
            ])

            @include('storefront::public.home.sections.flash_sale.vertical_products', [
                'title' => $flashSale['vertical_products_2_title'],
                'columnNumber' => 2
            ])

            @include('storefront::public.home.sections.flash_sale.vertical_products', [
                'title' => $flashSale['vertical_products_3_title'],
                'columnNumber' => 3
            ])
        </div>
    </div>
</section>
