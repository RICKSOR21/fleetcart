@if (setting('newsletter_enabled') && json_decode(Cookie::get('show_newsletter_popup', true)))
    <div x-data="NewsletterPopup" class="modal newsletter-wrap fade" id="newsletterPopup" tabindex="-1" aria-labelledby="newsletterPopup" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body"> 
                    <div class="newsletter-inner">
                        <div class="newsletter-left" style="background-image: url({{ $newsletterBgImage }})"></div>

                        <div class="newsletter-right flex-grow-1">
                            <h1 class="title">
                                {{ trans('storefront::layouts.subscribe_to_our_newsletter') }}
                            </h1>

                            <p class="sub-title">
                                {{ trans('storefront::layouts.subscribe_to_our_newsletter_subtitle') }}
                            </p>

                            <form x-ref="form" @submit.prevent="subscribe" class="newsletter-form">
                                <div class="form-group">
                                    <input
                                    type="text"
                                    class="form-control"
                                    placeholder="{{ trans('storefront::layouts.email_address') }}"
                                    @input="subscribed = false"
                                    x-model="email"
                                    >

                                    <svg class="input-icon" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path d="M14.167 17.0832H5.83366C3.33366 17.0832 1.66699 15.8332 1.66699 12.9165V7.08317C1.66699 4.1665 3.33366 2.9165 5.83366 2.9165H14.167C16.667 2.9165 18.3337 4.1665 18.3337 7.08317V12.9165C18.3337 15.8332 16.667 17.0832 14.167 17.0832Z" stroke="#A0AEC0" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M14.1663 7.5L11.558 9.58333C10.6997 10.2667 9.29134 10.2667 8.433 9.58333L5.83301 7.5" stroke="#A0AEC0" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </div>

                                <template x-if="error">
                                    <span class="error-message" x-text="error"></span>
                                </template>

                                <div class="d-flex flex-column">
                                    <button
                                        type="submit"
                                        class="btn btn-primary btn-subscribe"
                                        :class="{ 'btn-loading': subscribing }"
                                        :disabled="subscribing"
                                    >
                                        <template x-if="subscribed">
                                            <i class="las la-check"></i>
                                        </template>

                                        <span
                                            x-text="
                                                subscribed ?
                                                '{{ trans('storefront::layouts.subscribed') }}' :
                                                '{{ trans('storefront::layouts.subscribe') }}'
                                            "
                                        >
                                        </span>
                                    </button>
                                    
                                    <button
                                        type="button"
                                        class="btn btn-link btn-no-thanks"
                                        @click="disableNewsletterPopup"
                                    >
                                        {{ trans('storefront::layouts.no_thanks') }}
                                    </button>
                                </div>
                            </form>

                            <p class="info-text">
                                {{ trans('storefront::layouts.by_subscribing') }} <a href="{{ $privacyPageUrl }}">{{ trans('storefront::layouts.privacy_policy') }}</a>
                            </p>

                            <button type="button" aria-label="Button Close" class="close" data-bs-dismiss="modal">
                                <i class="las la-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
