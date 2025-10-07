import Swiper from "swiper";
import { Navigation, Autoplay } from "swiper/modules";

Alpine.data("HomeFeatures", () => ({
    init() {
        this.initFeaturesSlider();
    },

    initFeaturesSlider() {
        new Swiper(".features", {
            modules: [Navigation, Autoplay],
            slidesPerView: 1,
            autoplay: {
                delay: 2500,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                576: {
                    slidesPerView: 2,
                },
                780: {
                    slidesPerView: 3,
                },
                1180: {
                    slidesPerView: 4,
                },
                1400: {
                    slidesPerView: 5,
                },
            },
        });
    },
}));
