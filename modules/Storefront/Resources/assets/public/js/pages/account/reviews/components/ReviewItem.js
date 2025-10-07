import ProductMixin from "../../../../mixins/ProductMixin";
import "../../../../components/ProductRating";

Alpine.data("ReviewItem", (product) => ({
    ...ProductMixin(product),
}));
