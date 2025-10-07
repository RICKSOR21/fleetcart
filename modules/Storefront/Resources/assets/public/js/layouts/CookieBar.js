Alpine.data("CookieBar", () => ({
    show: false,

    init() {
        setTimeout(() => {
            this.show = true;
        }, 1000);
    },

    decline() {
        this.show = false;

        axios.delete("/storefront/cookie-bar");
    },

    accept() {
        this.show = false;

        axios.delete("/storefront/cookie-bar");
    },
}));
