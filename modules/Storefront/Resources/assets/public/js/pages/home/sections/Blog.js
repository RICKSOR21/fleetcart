import Swiper from "swiper";
import { Pagination } from "swiper/modules";

Alpine.data("Blog", () => ({
    init() {
        this.initBlogPostsSlider();
    },

    initBlogPostsSlider() {
        new Swiper(".blog-posts", {
            modules: [Pagination],
            slidesPerView: 1,
            pagination: {
                el: ".swiper-pagination",
                dynamicBullets: true,
                clickable: true,
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                920: {
                    slidesPerView: 3,
                },
                1300: {
                    slidesPerView: 4,
                },
                1700: {
                    slidesPerView: 5,
                },
            },
        });
    },
}));
