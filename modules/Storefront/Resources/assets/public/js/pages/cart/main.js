import "../../components/CartItem";
import "../../components/LandscapeProducts";

Alpine.data("Cart", () => ({
    shippingMethodName: null,

    get cartFetched() {
        return this.$store.cart.fetched;
    },

    get cartIsEmpty() {
        return this.$store.cart.isEmpty;
    },

    init() {
        Alpine.effect(() => {
            if (this.cartFetched) {
                this.hideSkeleton();
            }
        });
    },

    hideSkeleton() {
        document.querySelector(".cart-skeleton").remove();
    },

    clearCart() {
        this.$store.cart.clearCart();

        axios
            .delete("/cart/clear")
            .then(({ data }) => {
                this.$store.cart.updateCart(data);
            })
            .catch((error) => {
                notify(error.response.data.message);
            });
    },
}));
