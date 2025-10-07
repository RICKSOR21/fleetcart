import Swiper from "swiper";
import { Autoplay, Navigation, Pagination, Parallax } from "swiper/modules";

Alpine.data("Hero", () => ({
    init() {
        this.initHeroSlider(); 
    },

    initHeroSlider() {
        const { speed, autoplay, autoplaySpeed, dots, arrows } =
            $(".home-slider").data();

        new Swiper(".home-slider", {
            modules: [Autoplay, Navigation, Pagination, Parallax],
            slidesPerView: 1,
            speed,
            parallax: true,
            ...(autoplay && {
                autoplay: {
                    delay: autoplaySpeed,
                    pauseOnMouseEnter: true,
                },
            }),
            ...(arrows && {
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev", 
                },
            }),
            ...(dots && {
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
            }),
        });
    },
}));
