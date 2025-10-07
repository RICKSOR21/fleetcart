<template x-if="hasAnyProduct">
    <div class="table-responsive">
        <table class="table table-bordered compare-table"> 
            <tbody>
                <tr>
                    <td>{{ trans('storefront::compare.product_overview') }}</td>

                    <template x-for="(product, index) in $store.compare.products" :key="index">
                        <td x-data="ProductCard(product)">
                            <a :href="productUrl" class="product-image">
                                <img
                                    :src="baseImage"
                                    :class="{ 'image-placeholder': !hasBaseImage }"
                                    :alt="product.name"
                                >
                            </a>

                            <a
                                :href="productUrl"
                                class="product-name"
                                x-text="product.name"
                            >
                            </a>

                            <button class="btn btn-remove" @click="removeItem">
                                <i class="las la-times"></i>
                            </button>
                        </td>
                    </template>
                </tr>

                <tr>
                    <td>{{ trans('storefront::compare.description') }}</td>

                    <template x-for="(product, index) in $store.compare.products" :key="index">
                        <td x-text="product.short_description || '-'"></td>
                    </template>
                </tr>

                <tr>
                    <td>{{ trans('storefront::compare.rating') }}</td>

                    <template x-for="(product, index) in $store.compare.products" :key="index">
                        <td>
                            @include('storefront::public.partials.product_rating')
                        </td>
                    </template>
                </tr>

                <tr>
                    <td>{{ trans('storefront::compare.price') }}</td>

                    <template x-for="(product, index) in $store.compare.products" :key="index">
                        <td x-data="ProductCard(product)">
                            <span class="product-price" x-html="productPrice"></span>
                        </td>
                    </template>
                </tr>

                <tr>
                    <td>{{ trans('storefront::compare.availability') }}</td>

                    <template x-for="(product, index) in $store.compare.products" :key="index">
                        <td x-data="ProductCard(product)">
                            <template x-if="isInStock">
                                <span class="badge badge-success">
                                    {{ trans('storefront::compare.in_stock') }}
                                </span>
                            </template>

                            <template x-if="!isInStock">
                                <span class="badge badge-warning">
                                    {{ trans('storefront::compare.out_of_stock') }}
                                </span>
                            </template>
                        </td>
                    </template>
                </tr>

                <template x-for="(attribute, index) in $store.compare.attributes" :key="index">
                    <tr>
                        <td x-text="attribute.name"></td>

                        <template x-for="(product, index) in $store.compare.products" :key="index">
                            <td x-text="hasAttribute(product, attribute) ? attributeValues(product, attribute) : '-'"></td>
                        </template>
                    </tr>
                </template>

                <tr>
                    <td>{{ trans('storefront::compare.actions') }}</td>

                    <template x-for="(product, index) in $store.compare.products" :key="index">
                        <td x-data="ProductCard(product)">
                            <button
                                title="{{ trans('storefront::compare.add_to_wishlist') }}"
                                class="btn btn-wishlist"
                                :class="{ 'added': inWishlist }"
                                @click="syncWishlist"
                            >
                                <template x-if="inWishlist">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M16.44 3.1001C14.63 3.1001 13.01 3.9801 12 5.3301C10.99 3.9801 9.37 3.1001 7.56 3.1001C4.49 3.1001 2 5.6001 2 8.6901C2 9.8801 2.19 10.9801 2.52 12.0001C4.1 17.0001 8.97 19.9901 11.38 20.8101C11.72 20.9301 12.28 20.9301 12.62 20.8101C15.03 19.9901 19.9 17.0001 21.48 12.0001C21.81 10.9801 22 9.8801 22 8.6901C22 5.6001 19.51 3.1001 16.44 3.1001Z" fill="#292D32"/>
                                    </svg>
                                </template>

                                <template x-if="!inWishlist">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M12.62 20.81C12.28 20.93 11.72 20.93 11.38 20.81C8.48 19.82 2 15.69 2 8.68998C2 5.59998 4.49 3.09998 7.56 3.09998C9.38 3.09998 10.99 3.97998 12 5.33998C13.01 3.97998 14.63 3.09998 16.44 3.09998C19.51 3.09998 22 5.59998 22 8.68998C22 15.69 15.52 19.82 12.62 20.81Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </template>
                            </button>
                            
                            <template x-if="!hasAnyOption">
                                <button
                                    title="{{ trans('storefront::compare.add_to_cart') }}"
                                    class="btn btn-add-to-cart"
                                    :disabled="isOutOfStock"
                                    @click="addToCart"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <g clip-path="url(#clip0_2055_61)">
                                        <path d="M1.3335 1.33325H2.20427C2.36828 1.33325 2.45029 1.33325 2.51628 1.36341C2.57444 1.38999 2.62373 1.43274 2.65826 1.48655C2.69745 1.54761 2.70905 1.6288 2.73225 1.79116L3.04778 3.99992M3.04778 3.99992L3.74904 9.15419C3.83803 9.80827 3.88253 10.1353 4.0389 10.3815C4.17668 10.5984 4.37422 10.7709 4.60773 10.8782C4.87274 10.9999 5.20279 10.9999 5.8629 10.9999H11.5682C12.1965 10.9999 12.5107 10.9999 12.7675 10.8869C12.9939 10.7872 13.1881 10.6265 13.3283 10.4227C13.4875 10.1917 13.5462 9.88303 13.6638 9.26576L14.5462 4.63305C14.5876 4.41579 14.6083 4.30716 14.5783 4.22225C14.552 4.14777 14.5001 4.08504 14.4319 4.04526C14.3541 3.99992 14.2435 3.99992 14.0223 3.99992H3.04778ZM6.66683 13.9999C6.66683 14.3681 6.36835 14.6666 6.00016 14.6666C5.63197 14.6666 5.3335 14.3681 5.3335 13.9999C5.3335 13.6317 5.63197 13.3333 6.00016 13.3333C6.36835 13.3333 6.66683 13.6317 6.66683 13.9999ZM12.0002 13.9999C12.0002 14.3681 11.7017 14.6666 11.3335 14.6666C10.9653 14.6666 10.6668 14.3681 10.6668 13.9999C10.6668 13.6317 10.9653 13.3333 11.3335 13.3333C11.7017 13.3333 12.0002 13.6317 12.0002 13.9999Z" stroke="white" stroke-linecap="round" stroke-linejoin="round"></path>
                                        </g>
                                        <defs>
                                        <clipPath id="clip0_2055_61">
                                        <rect width="16" height="16" fill="white"></rect>
                                        </clipPath>
                                        </defs>
                                    </svg>
                                </button>
                            </template>

                            <template x-if="hasAnyOption">
                                <a
                                    :href="productUrl"
                                    title="{{ trans('storefront::compare.view_options') }}"
                                    class="btn btn-add-to-cart"
                                    :disabled="isOutOfStock"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                        <path d="M15.58 12C15.58 13.98 13.98 15.58 12 15.58C10.02 15.58 8.42004 13.98 8.42004 12C8.42004 10.02 10.02 8.41998 12 8.41998C13.98 8.41998 15.58 10.02 15.58 12Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                        <path d="M12 20.27C15.53 20.27 18.82 18.19 21.11 14.59C22.01 13.18 22.01 10.81 21.11 9.39997C18.82 5.79997 15.53 3.71997 12 3.71997C8.46997 3.71997 5.17997 5.79997 2.88997 9.39997C1.98997 10.81 1.98997 13.18 2.88997 14.59C5.17997 18.19 8.46997 20.27 12 20.27Z" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                </a>
                            </template>
                        </td>
                    </template>
                </tr>
            </tbody>
        </table>
    </div>
</template>
