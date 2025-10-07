Alpine.data("CartItem", (cartItem) => ({
    controller: null,
    product: cartItem.product,
    item: cartItem.variant || cartItem.product,
    qty: cartItem.qty,

    get productName() {
        return this.product.name;
    },

    get productUrl() {
        let url = `/products/${this.product.slug}`;

        if (this.hasAnyVariant) {
            url += `?variant=${this.item.uid}`;
        }

        return url;
    },

    get unitPrice() {
        return cartItem.unitPrice.inCurrentCurrency.amount;
    },

    get hasAnyVariation() {
        return Object.keys(cartItem.variations).length !== 0;
    },

    get variationsLength() {
        return Object.keys(cartItem.variations).length;
    },

    get hasAnyOption() {
        return Object.keys(cartItem.options).length !== 0;
    },

    get optionsLength() {
        return Object.keys(cartItem.options).length;
    },

    get hasAnyVariant() {
        return this.product.variant !== null;
    },

    get hasAnyMedia() {
        return this.item.media.length !== 0;
    },

    get hasBaseImage() {
        if (this.hasAnyVariant) {
            return this.item.base_image.length !== 0 ||
                this.product.base_image.length !== 0
                ? true
                : false;
        }

        return this.item.base_image.length !== 0;
    },

    get baseImage() {
        return this.hasBaseImage
            ? this.item.base_image.path || this.product.base_image.path
            : `${window.location.origin}/build/assets/image-placeholder.png`;
    },

    isQtyIncreaseDisabled(cartItem) {
        return (
            this.maxQuantity(cartItem) !== null &&
            cartItem.qty >= cartItem.item.qty
        );
    },

    lineTotal(qty) {
        return qty * cartItem.unitPrice.inCurrentCurrency.amount;
    },

    optionValues(option) {
        let values = [];

        for (let value of option.values) {
            values.push(value.label);
        }

        return values.join(", ");
    },

    maxQuantity({ item }) {
        return item.is_in_stock && item.does_manage_stock ? item.qty : null;
    },

    exceedsMaxStock({ item, qty }) {
        return item.does_manage_stock && item.qty < qty;
    },

    changeQuantity(cartItem, qty) {
        if (isNaN(qty) || qty < 1) {
            qty = 1;

            this.updateCart(cartItem, qty);

            return;
        }

        cartItem.qty = qty;

        if (this.exceedsMaxStock(cartItem)) {
            qty = cartItem.item.qty;

            this.updateCart(cartItem, qty);

            return;
        }

        this.updateCart(cartItem, qty);
    },

    updateQuantity(cartItem, qty) {
        if (isNaN(qty) || qty < 1) {
            qty = 1;
            cartItem.qty = 1;

            return;
        }

        cartItem.qty = qty;

        if (this.exceedsMaxStock(cartItem)) {
            cartItem.qty = cartItem.item.qty;

            this.updateCart(cartItem, cartItem.qty);

            return;
        }

        this.updateCart(cartItem, qty);
    },

    async updateCart(cartItem, qty) {
        if (this.controller) {
            this.controller.abort();
        }

        this.controller = new AbortController();

        try {
            const { data } = await axios.put(
                `/cart/items/${cartItem.id}`,
                {
                    qty: qty || 1,
                },
                {
                    signal: this.controller.signal,
                }
            );

            this.qty = data.items[cartItem.id].qty;
            this.$store.cart.updateCart(data);
        } catch (error) {
            if (error.code !== "ERR_CANCELED") {
                // revert cart item quantity on error
                this.$store.cart.updateCartItemQty({
                    id: cartItem.id,
                    qty: this.qty,
                });

                notify(trans("storefront::storefront.something_went_wrong"));
            }
        }
    },

    removeCartItem() {
        this.$store.cart.removeCartItem(cartItem.id);

        axios.delete(`/cart/items/${cartItem.id}`).then((response) => {
            this.$store.cart.updateCart(response.data);
        });
    },
}));
