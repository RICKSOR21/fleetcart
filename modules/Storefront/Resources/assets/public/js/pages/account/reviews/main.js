import "./components/ReviewItem";
import "../../../components/Pagination";

Alpine.data("Reviews", () => ({
    fetchingReviews: false,
    reviews: { data: [] },
    currentPage: 1,

    get reviewIsEmpty() {
        return this.reviews.data.length === 0;
    },

    get totalPage() {
        return Math.ceil(this.reviews.total / 10);
    },

    init() {
        this.fetchReviews();
    },

    changePage(page) {
        this.currentPage = page;

        this.fetchReviews();
    },

    async fetchReviews() {
        this.fetchingReviews = true;

        try {
            const response = await axios.get(`/reviews/products?page=${this.currentPage}`);

            this.reviews = response.data;
        } catch (error) {
            notify(error.response.data.message);
        } finally {
            this.fetchingReviews = false;
        }
    },
}));
