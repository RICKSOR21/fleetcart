<section class="top-nav-wrap">
    <div class="container">
        <div class="top-nav">
            <div class="d-flex justify-content-between">
                <div class="top-nav-left d-none d-lg-block">
                    <span>{{ setting('storefront_welcome_text') }}</span>
                </div>

                <div class="top-nav-right">
                    <ul class="list-inline top-nav-right-list"> 
                        <li>
                            <a href="{{ route('contact.create') }}">
                                <i class="las la-envelope"></i>

                                {{ trans('storefront::layouts.contact') }}
                            </a>
                        </li>

                        @if (is_multilingual())
                            <li
                                x-data="{
                                    open: false,
                                    selected: '{{ locale_get_display_language(locale()) }}'
                                }"
                                class="dropdown custom-dropdown"
                                :class="{ active: open }"
                                @click.away="open = false"
                            >
                                <div
                                    class="btn btn-secondary dropdown-toggle"
                                    :class="{ active: open }"
                                    @click="open = !open"
                                >
                                    <i class="las la-language"></i>

                                    {{ locale_get_display_language(locale()) }}

                                    <i class="las la-angle-down"></i>
                                </div>

                                <ul
                                    x-cloak
                                    x-show="open"
                                    x-transition
                                    class="dropdown-menu"
                                    :class="{ active: open }"
                                >
                                    <div class="dropdown-menu-scroll">
                                        @foreach (supported_locales() as $locale => $language)
                                            @if (locale_get_display_language(locale()) !== $language['name'])
                                                <li
                                                    class="dropdown-item"
                                                    @click="
                                                        open = false;
                                                        selected = '{{ $locale }}';
                                                        location = '{{ localized_url($locale) }}'
                                                    "
                                                >
                                                    {{ $language['name'] }}
                                                </li>
                                            @endif
                                        @endforeach
                                    </div>
                                </ul>
                            </li>
                        @endif

                        @if (is_multi_currency())
                            <li
                                x-data="{
                                    open: false,
                                    selected: '{{ currency() }}'
                                }"
                                class="dropdown custom-dropdown"
                                :class="{ active: open }"
                                @click.away="open = false"
                            >
                                <div
                                    class="btn btn-secondary dropdown-toggle"
                                    :class="{ active: open }"
                                    @click="open = !open"
                                >
                                    <i class="las la-money-bill"></i>

                                    {{ currency() }}

                                    <i class="las la-angle-down"></i>
                                </div>

                                <ul
                                    x-cloak
                                    x-show="open"
                                    x-transition
                                    class="dropdown-menu"
                                    :class="{ active: open }"
                                >
                                    <div class="dropdown-menu-scroll">
                                        @foreach (setting('supported_currencies') as $currency)
                                            @if (currency() !== $currency)
                                                <li
                                                    class="dropdown-item"
                                                    @click="
                                                        open = false;
                                                        selected = '{{ $currency }}';
                                                        location = '{{ route('current_currency.store', ['code' => $currency]) }}'
                                                    "
                                                >
                                                    {{ $currency }}
                                                </li>
                                            @endif
                                        @endforeach
                                    </div>
                                </ul>
                            </li>
                        @endif

                        @auth
                            <li class="top-nav-account">
                                <a href="{{ route('account.dashboard.index') }}">
                                    <i class="las la-user"></i>

                                    {{ trans('storefront::layouts.account') }}
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="{{ route('login') }}">
                                    <i class="las la-sign-in-alt"></i>

                                    {{ trans('storefront::layouts.login_register') }}
                                </a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
