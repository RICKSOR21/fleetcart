import Swiper from "swiper";
import { Navigation, Autoplay } from "swiper/modules";

Alpine.data("TopBrands", () => ({
    init() {
        this.initTopBrandsSlider();
    },

    initTopBrandsSlider() {
        new Swiper(".top-brands", {
            modules: [Navigation, Autoplay],
            slidesPerView: 2,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                450: {
                    slidesPerView: 3,
                },
                750: {
                    slidesPerView: 4,
                },
                900: {
                    slidesPerView: 5,
                },
                1050: {
                    slidesPerView: 6,
                },
                1200: {
                    slidesPerView: 7,
                },
            },
        });
    },
}));
