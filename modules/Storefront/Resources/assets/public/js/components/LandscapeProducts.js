import Swiper from "swiper";
import { Navigation, Pagination } from "swiper/modules";
import "../components/ProductCard";

Alpine.data("LandscapeProducts", ({ url, watchState }) => ({
    products: [],

    init() {
        this.fetchProducts();

        if (watchState) {
            this.$watch(watchState, (newValue) => {
                if (newValue) {
                    this.products = [];

                    this.$refs.landscapeProductsWrap.remove();
                }
            });
        }
    },

    hideLandscapeProductsSkeleton() {
        const skeletons = document.querySelectorAll(
            ".landscape-products .swiper-slide-skeleton"
        );

        skeletons.forEach((skeleton) => skeleton.remove());
    },

    async fetchProducts() {
        try {
            const response = await axios.get(url);

            this.products = response.data;

            this.$nextTick(() => {
                new Swiper(".landscape-products", {
                    modules: [Navigation, Pagination],
                    slidesPerView: 2,
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                    breakpoints: {
                        576: {
                            slidesPerView: 3,
                        },
                        830: {
                            slidesPerView: 4,
                        },
                        991: {
                            slidesPerView: 5,
                        },
                        1200: {
                            slidesPerView: 6,
                        },
                        1400: {
                            slidesPerView: 7,
                        },
                        1760: {
                            slidesPerView: 8,
                        },
                    },
                });
            });
        } catch (error) {
            notify(error.response.data.message);
        } finally {
            this.hideLandscapeProductsSkeleton();
        }
    },
}));
