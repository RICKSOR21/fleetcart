import "./header/HeaderSearch";

Alpine.data("Header", () => ({
    stickyHeader: false,
    showStickyHeader: false,

    get isStickyHeader() {
        return this.stickyHeader;
    },

    get isShowingStickyHeader() {
        return this.showStickyHeader;
    },

    init() {
        this.toggleStickyHeader({ delay: 600 });
        this.addEventListeners();
    },

    toggleStickyHeader({ delay }) {
        const header = this.$refs.header;

        if (window.scrollY > header.offsetTop + header.offsetHeight) {
            this.stickyHeader = true;
            header.style.paddingTop = `${header.offsetHeight}px`;

            setTimeout(() => {
                this.showStickyHeader = true;
            }, delay);

            return;
        }

        this.stickyHeader = false;
        this.showStickyHeader = false;

        header.style.paddingTop = "0px";
    },

    addEventListeners() {
        window.addEventListener("resize", () => {
            this.toggleStickyHeader({ delay: 0 });
        });

        window.addEventListener("scroll", () => {
            this.toggleStickyHeader({ delay: 0 });
        });
    },
}));
