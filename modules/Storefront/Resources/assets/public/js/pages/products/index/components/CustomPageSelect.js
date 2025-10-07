Alpine.data("CustomPageSelect", () => ({
    open: false,
    selected: FleetCart.data["initialPerPage"] || 20,

    get activeClass() {
        return this.open ? "active" : "";
    },

    init() {
        $(this.$el)
            .find(`li.dropdown-item[data-value="${this.selected}"]`)
            .addClass("active");
    },

    toggleOpen() {
        this.open = !this.open;
    },

    hideDropdown() {
        this.open = false;
    },

    changeValue(event) {
        const value = event.currentTarget.getAttribute("data-value");

        $(this.$el).siblings().removeClass("active");
        $(this.$el).addClass("active");

        this.open = false;

        if (this.selected !== value) {
            this.changePerPage(value);
        }

        this.selected = value;
    },
}));
