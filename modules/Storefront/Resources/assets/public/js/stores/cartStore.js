Alpine.store("cart", {
    cart: {
        items: {},
        availableShippingMethods: {},
        coupon: {},
        quantity: 0,
        shippingCost: {},
        shippingMethodName: null,
        subTotal: {},
        taxes: [],
        total: [],
    },
    loading: false,
    fetching: false,
    fetched: false,

    get items() {
        return this.cart.items;
    },

    get quantity() {
        return this.fetched
            ? Object.values(this.items).reduce(
                  (total, item) => total + item.qty,
                  0
              )
            : FleetCart.cartQuantity;
    },

    get isEmpty() {
        return Object.keys(this.cart.items).length === 0;
    },

    get shippingCost() {
        return this.cart.shippingCost?.inCurrentCurrency?.amount || 0;
    },

    get taxTotal() {
        return Object.values(this.cart.taxes).reduce((accumulator, tax) => {
            return accumulator + tax.amount.inCurrentCurrency.amount;
        }, 0);
    },

    get subTotal() {
        return Object.values(this.items).reduce((accumulator, cartItem) => {
            return (
                accumulator +
                cartItem.qty * cartItem.unitPrice.inCurrentCurrency.amount
            );
        }, 0);
    },

    get total() {
        return (
            this.subTotal - this.couponValue + this.taxTotal + this.shippingCost
        );
    },

    get hasCoupon() {
        return Boolean(this.cart.coupon.code);
    },

    get couponValue() {
        return this.cart.coupon?.value?.inCurrentCurrency?.amount ?? 0;
    },

    init() {
        this.fetchingCart();
    },

    async fetchingCart() {
        try {
            this.fetching = true;

            const { data } = await axios.get("/cart/get");

            this.cart = data;
        } catch (error) {
            // Handle error
        } finally {
            this.fetching = false;
            this.fetched = true;
        }
    },

    updateCart(cart) {
        this.cart = { ...cart };

        this.setCoupon(cart);
    },

    updateCartItemQty({ id, qty }) {
        this.cart.items[id].qty = qty;
    },

    removeCartItem(id) {
        delete this.cart.items[id];
    },

    clearCart() {
        this.cart.items = {};
    },

    setCoupon(cart) {
        if (cart.coupon.code) {
            this.cart.coupon = cart.coupon;
        }
    },
});
