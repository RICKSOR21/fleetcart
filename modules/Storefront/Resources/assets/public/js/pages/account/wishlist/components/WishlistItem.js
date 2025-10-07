import ProductMixin from "../../../../mixins/ProductMixin";

Alpine.data("WishlistItem", (product) => ({
    ...ProductMixin(product),
}));
