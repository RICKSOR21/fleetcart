import Swiper from "swiper";
import { Navigation } from "swiper/modules";
import "./flash-sale/FlashSaleProductCard";
import "./flash-sale/VerticalProducts";

Alpine.data("FlashSale", () => ({
    products: [],

    get hasAnyProduct() {
        return this.products.length !== 0;
    },

    init() {
        this.fetchProducts();
    },

    async fetchProducts() {
        const response = await axios.get("storefront/flash-sale-products");

        this.products = response.data;

        this.$nextTick(() => {
            new Swiper(".daily-deals", this.swiperOptions());
        });
    },

    swiperOptions() {
        return {
            modules: [Navigation],
            slidesPerView: 1,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                768: {
                    slidesPerView: 2,
                },
                1200: {
                    slidesPerView: 1,
                },
            },
        };
    },
}));
