Alpine.data("ScrollToTop", () => ({
    percent: 0,
    scrolled: false,

    get circumference() {
        return 19 * 2 * Math.PI;
    },

    init() {
        this.addEventListener();
    },

    scrollToTop() {
        window.scrollTo({ top: 0, behavior: "smooth" });
    },

    addEventListener() {
        window.addEventListener("scroll", () => {
            let windowScroll =
                document.body.scrollTop || document.documentElement.scrollTop;
            let height =
                document.documentElement.scrollHeight -
                document.documentElement.clientHeight;

            this.percent = Math.round((windowScroll / height) * 100);
            this.scrolled = window.scrollY > 100;
        });
    },
}));
