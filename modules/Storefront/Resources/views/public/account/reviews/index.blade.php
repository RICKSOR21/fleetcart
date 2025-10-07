@extends('storefront::public.account.layout')

@section('title', trans('storefront::account.pages.my_reviews'))

@section('account_breadcrumb')
    <li class="active">{{ trans('storefront::account.pages.my_reviews') }}</li>
@endsection

@section('panel')
    <div x-data="Reviews" class="panel">
        <div class="panel-header">
            <h4>{{ trans('storefront::account.pages.my_reviews') }}</h4>
        </div>

        <div x-cloak class="panel-body" :class="{ loading: fetchingReviews }">
            <template x-if="reviewIsEmpty">
                <div class="empty-message">
                    <template x-if="!fetchingReviews">
                        <h3>
                            {{ trans('storefront::account.reviews.no_reviews') }}
                        </h3>
                    </template>
                </div>
            </template>

            <template x-if="!reviewIsEmpty">
                <div class="table-responsive">
                    <table class="table table-borderless my-reviews-table">
                        <thead>
                            <tr>
                                <th>{{ trans('storefront::account.image') }}</th>
                                <th>{{ trans('storefront::account.product_name') }}</th>
                                <th>{{ trans('storefront::account.status') }}</th>
                                <th>{{ trans('storefront::account.date') }}</th>
                                <th>{{ trans('storefront::account.reviews.rating') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            <template x-for="review in reviews.data" :key="review.id">
                                <tr x-data="ReviewItem(review.product)">
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
                                        <span
                                            class="badge"
                                            :class="review.is_approved ? 'badge-success' : 'badge-warning'"
                                            x-text="review.status"
                                        >
                                        </span>
                                    </td>

                                    <td x-text="review.created_at_formatted"></td>

                                    <td>
                                        @include('storefront::public.partials.product_rating', [
                                            'data' => 'review'
                                        ])
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>
            </template>
        </div>

        <div class="panel-footer">
            <template x-if="reviews.total > 10">
                @include('storefront::public.partials.pagination')
            </template>
        </div>
    </div>
@endsection

@push('globals')
    @vite([
        'modules/Storefront/Resources/assets/public/sass/pages/account/reviews/main.scss', 
        'modules/Storefront/Resources/assets/public/js/pages/account/reviews/main.js',
    ])
@endpush
