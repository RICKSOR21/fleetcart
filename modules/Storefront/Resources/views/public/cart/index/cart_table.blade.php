<div class="table-responsive">
    <table class="table table-borderless cart-table">
        <thead>
            <tr>
                <th>{{ trans('storefront::cart.table.image') }}</th>
                <th>{{ trans('storefront::cart.table.product_name') }}</th>
                <th>{{ trans('storefront::cart.table.unit_price') }}</th>
                <th>{{ trans('storefront::cart.table.quantity') }}</th>
                <th>{{ trans('storefront::cart.table.line_total') }}</th>
                <th>
                    <button class="btn-remove" @click="clearCart">
                        <i class="las la-times"></i>
                    </button>
                </th>
            </tr>
        </thead>

        <tbody>
            <template x-for="cartItem in $store.cart.items" :key="cartItem.id">
                <tr x-data="CartItem(cartItem)">
                    <td>
                        <a :href="productUrl" class="product-image">
                            <img
                                :src="baseImage"
                                :class="{ 'image-placeholder': !hasBaseImage }"
                                :alt="productName"
                            />
                        </a>
                    </td>
                    <td>
                        <a
                            :href="productUrl"
                            class="product-name"
                            x-text="productName"
                        >
                        </a>

                        <template x-cloak x-if="hasAnyVariation">
                            <ul class="list-inline product-options">
                                <template
                                    x-for="(variation, key) in cartItem.variations"
                                    :key="variation.id"
                                >
                                    <li>
                                        <label x-text="`${variation.name}:`"></label>
                                        
                                        <span x-text="`${variation.values[0].label}${variationsLength === Number(key) ? '' : ','}`"></span>
                                    </li>
                                </template>
                            </ul>
                        </template>

                        <template x-cloak x-if="hasAnyOption">
                            <ul class="list-inline product-options">
                                <template
                                    x-for="(option, key) in cartItem.options"
                                    :key="option.id"
                                >
                                    <li>
                                        <label x-text="`${option.name}:`"></label>

                                        <span x-text="`${optionValues(option)}${optionsLength === Number(key) ? '' : ','}`"></span>
                                    </li>
                                </template>
                            </ul>
                        </template>
                    </td>
                    <td>
                        <label>{{ trans('storefront::cart.table.unit_price') }}:</label>

                        <span class="product-price" x-text="formatCurrency(unitPrice)"></span>
                    </td>
                    <td>
                        <label>{{ trans('storefront::cart.table.quantity') }}:</label>

                        <div class="number-picker">
                            <div class="input-group-quantity">
                                <button
                                    type="button"
                                    class="btn btn-number btn-minus"
                                    :disabled="cartItem.qty <= 1"
                                    @click="updateQuantity(cartItem, cartItem.qty - 1)"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M13.3333 8H2.66663" stroke="#0E1E3E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>

                                <input
                                    type="text"
                                    :value="cartItem.qty"
                                    autocomplete="off"
                                    min="1"
                                    :max="maxQuantity(cartItem)"
                                    class="form-control input-number input-quantity"
                                    :id="`cart-input-quantity-${cartItem.id}`"
                                    @focus="$event.target.select()"
                                    @input="changeQuantity(cartItem, Number($event.target.value))"
                                    @keydown.up="updateQuantity(cartItem, cartItem.qty + 1)"
                                    @keydown.down="updateQuantity(cartItem, cartItem.qty - 1)"
                                >

                                <button
                                    type="button"
                                    class="btn btn-number btn-plus"
                                    :disabled="isQtyIncreaseDisabled(cartItem)"
                                    @click="updateQuantity(cartItem, cartItem.qty + 1)"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                        <path d="M7.99996 2.66669V13.3334M13.3333 8.00002H2.66663" stroke="#0E1E3E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </td>
                    <td>
                        <label>{{ trans('storefront::cart.table.line_total') }}:</label>

                        <span class="product-price" x-text="formatCurrency(lineTotal(cartItem.qty))"></span>
                    </td>
                    <td>
                        <button class="btn-remove" @click="removeCartItem">
                            <i class="las la-times"></i>
                        </button>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</div>
