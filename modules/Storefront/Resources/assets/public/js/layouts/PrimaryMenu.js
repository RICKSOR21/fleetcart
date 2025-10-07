import Swiper from "swiper";
import { Navigation, Pagination } from "swiper/modules";

Alpine.data("PrimaryMenu", () => ({
    init() {
        new Swiper(".primary-menu", {
            modules: [Navigation, Pagination],
            slidesPerView: "auto",
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    },
}));
