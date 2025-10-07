import Swiper from "swiper";
import { Navigation } from "swiper/modules";
import { chunk } from "lodash";
import "../../../../components/ProductCard";

Alpine.data("VerticalProducts", (columnNumber) => ({
    chunk,
    products: [],

    get hasAnyProduct() {
        return this.products.length !== 0;
    },

    init() {
        this.fetchProducts();
    },

    async fetchProducts() {
        const response = await axios.get(
            `/storefront/vertical-products/${columnNumber}`
        );

        this.products = response.data;

        setTimeout(() => {
            new Swiper(this.$refs.verticalProducts, this.swiperOptions());
        }, 0);
    },

    swiperOptions() {
        return {
            modules: [Navigation],
            slidesPerView: 1,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        };
    },
}));
