import "../components/CartItem";

Alpine.data("SidebarCart", () => ({
    get cartIsEmpty() {
        return this.$store.cart.isEmpty;
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
