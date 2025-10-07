import "./components/WishlistItem";
import "../../../components/Pagination";

Alpine.data("Wishlist", () => ({
    fetchingWishlist: false,
    products: { data: [] },
    currentPage: 1,

    get wishlistIsEmpty() {
        return this.products.data.length === 0;
    },

    get totalPage() {
        return Math.ceil(this.products.total / 10);
    },

    init() {
        this.fetchWishlist();
    },

    changePage(page) {
        this.currentPage = page;

        this.fetchWishlist();
    },

    async fetchWishlist() {
        this.fetchingWishlist = true;

        try {
            const response = await axios.get(
                `/account/wishlist/products?page=${this.currentPage}`
            );

            this.products = response.data;
        } catch (error) {
            notify(error.response.data.message);
        } finally {
            this.fetchingWishlist = false;
        }
    },

    removeItem(product) {
        this.products.data.splice(this.products.data.indexOf(product), 1);
        this.products.total--;

        this.$store.wishlist.removeFromWishlist(product.id);
    },
}));
