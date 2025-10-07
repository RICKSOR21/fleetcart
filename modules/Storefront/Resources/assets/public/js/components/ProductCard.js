import ProductMixin from "../mixins/ProductMixin";
import "./ProductRating";

Alpine.data("ProductCard", (product) => ({
    ...ProductMixin(product),

    get inWishlist() {
        return this.$store.wishlist.inWishlist(this.product.id);
    },

    get inCompareList() {
        return this.$store.compare.inCompareList(this.product.id);
    },
}));
