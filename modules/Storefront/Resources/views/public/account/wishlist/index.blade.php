@extends('storefront::public.account.layout')

@section('title', trans('storefront::account.pages.my_wishlist'))

@section('account_breadcrumb')
    <li class="active">{{ trans('storefront::account.pages.my_wishlist') }}</li>
@endsection

@section('panel')
    <div x-data="Wishlist" class="panel">
        <div class="panel-header">
            <h4>{{ trans('storefront::account.pages.my_wishlist') }}</h4>
        </div>

        <div x-cloak class="panel-body" :class="{ loading: fetchingWishlist }">
            <template x-if="wishlistIsEmpty">
                <div class="empty-message">
                    <template x-if="!fetchingWishlist">
                        <h3>
                            {{ trans('storefront::account.wishlist.empty_wishlist') }}
                        </h3>
                    </template>
                </div>
            </template>

            <template x-if="!wishlistIsEmpty">
                <div class="table-responsive">
                    <table class="table table-borderless my-wishlist-table">
                        <thead>
                            <tr>
                                <th>{{ trans('storefront::account.image') }}</th>
                                <th>{{ trans('storefront::account.product_name') }}</th>
                                <th>{{ trans('storefront::account.wishlist.price') }}</th>
                                <th>{{ trans('storefront::account.wishlist.availability') }}</th>
                                <th>{{ trans('storefront::account.actions') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <template x-for="product in products.data" :key="product.id">
                                <tr x-data="WishlistItem(product)">
                                    <td>
                                        <div class="product-image">
                                            <img
                                                :src="baseImage"
                                                :class="{ 'image-placeholder': !hasBaseImage }"
                                                :alt="productName"
                                            >
                                        </div>
                                    </td>

                                    <td>
                                        <a :href="productUrl" class="product-name" x-text="productName"></a>
                                    </td>

                                    <td>
                                        <span class="product-price" x-html="productPrice"></span>
                                    </td>

                                    <td>
                                        <span
                                            class="badge"
                                            :class="isOutOfStock ? 'badge-danger' : 'badge-success'"
                                            x-text="isOutOfStock ?
                                                '{{ trans('storefront::account.wishlist.out_of_stock') }}' :
                                                '{{ trans('storefront::account.wishlist.in_stock') }}'
                                            "
                                        >
                                        </span>
                                    </td>

                                    <td>
                                        <div class="d-flex">
                                            <template x-if="hasNoOption || isOutOfStock">
                                                <button
                                                    class="btn btn-primary btn-add-to-cart"
                                                    :class="{ 'btn-loading': addingToCart }"
                                                    :disabled="isOutOfStock"
                                                    title="{{ trans('storefront::product_card.add_to_cart') }}"
                                                    @click="addToCart"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <g clip-path="url(#clip0_2055_61)">
                                                            <path d="M1.3335 1.33325H2.20427C2.36828 1.33325 2.45029 1.33325 2.51628 1.36341C2.57444 1.38999 2.62373 1.43274 2.65826 1.48655C2.69745 1.54761 2.70905 1.6288 2.73225 1.79116L3.04778 3.99992M3.04778 3.99992L3.74904 9.15419C3.83803 9.80827 3.88253 10.1353 4.0389 10.3815C4.17668 10.5984 4.37422 10.7709 4.60773 10.8782C4.87274 10.9999 5.20279 10.9999 5.8629 10.9999H11.5682C12.1965 10.9999 12.5107 10.9999 12.7675 10.8869C12.9939 10.7872 13.1881 10.6265 13.3283 10.4227C13.4875 10.1917 13.5462 9.88303 13.6638 9.26576L14.5462 4.63305C14.5876 4.41579 14.6083 4.30716 14.5783 4.22225C14.552 4.14777 14.5001 4.08504 14.4319 4.04526C14.3541 3.99992 14.2435 3.99992 14.0223 3.99992H3.04778ZM6.66683 13.9999C6.66683 14.3681 6.36835 14.6666 6.00016 14.6666C5.63197 14.6666 5.3335 14.3681 5.3335 13.9999C5.3335 13.6317 5.63197 13.3333 6.00016 13.3333C6.36835 13.3333 6.66683 13.6317 6.66683 13.9999ZM12.0002 13.9999C12.0002 14.3681 11.7017 14.6666 11.3335 14.6666C10.9653 14.6666 10.6668 14.3681 10.6668 13.9999C10.6668 13.6317 10.9653 13.3333 11.3335 13.3333C11.7017 13.3333 12.0002 13.6317 12.0002 13.9999Z" stroke="white" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip0_2055_61">
                                                                <rect width="16" height="16" fill="white"/>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                </button>
                                            </template>
                                            
                                            <template x-if="!(hasNoOption || isOutOfStock)">
                                                <a
                                                    :href="productUrl"
                                                    title="{{ trans('storefront::product_card.view_options') }}"
                                                    class="btn btn-primary btn-add-to-cart"
                                                >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                        <path d="M14.3623 7.3635C14.565 7.6477 14.6663 7.78983 14.6663 8.00016C14.6663 8.2105 14.565 8.35263 14.3623 8.63683C13.4516 9.9139 11.1258 12.6668 7.99967 12.6668C4.87353 12.6668 2.54774 9.9139 1.63703 8.63683C1.43435 8.35263 1.33301 8.2105 1.33301 8.00016C1.33301 7.78983 1.43435 7.6477 1.63703 7.3635C2.54774 6.08646 4.87353 3.3335 7.99967 3.3335C11.1258 3.3335 13.4516 6.08646 14.3623 7.3635Z" stroke="white" stroke-width="1.2"/>
                                                        <path d="M10 8C10 6.8954 9.1046 6 8 6C6.8954 6 6 6.8954 6 8C6 9.1046 6.8954 10 8 10C9.1046 10 10 9.1046 10 8Z" stroke="white" stroke-width="1"/>
                                                    </svg>
                                                </a>
                                            </template>

                                            <button class="btn btn-delete" title="{{ trans('storefront::account.wishlist.delete') }}" @click="removeItem(product)">
                                                <i class="las la-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>

        <template x-if="products.total > 10">
            <div class="panel-footer">
                @include('storefront::public.partials.pagination')
            </div>
        </template>
    </div>
@endsection

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/account/wishlist/main.scss', 
        'modules/Storefront/Resources/assets/public/js/pages/account/wishlist/main.js',
    ])
@endpush
