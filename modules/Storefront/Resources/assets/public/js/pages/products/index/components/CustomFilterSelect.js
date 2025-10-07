Alpine.data("CustomFilterSelect", () => ({
    open: false,
    selected: FleetCart.data["initialSort"] || "latest",
    values: {},

    get activeClass() {
        return this.open ? "active" : "";
    },

    get selectedValueText() {
        return this.values[this.selected];
    },

    init() {
        this.setValues();

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

    setValues() {
        const values = {};

        $(this.$el)
            .find("li")
            .each(function () {
                values[$(this).attr("data-value")] = $(this).text().trim();
            });

        this.values = values;
    },

    changeValue(event) {
        const value = event.currentTarget.getAttribute("data-value");

        $(this.$el).siblings().removeClass("active");
        $(this.$el).addClass("active");

        this.open = false;

        if (this.selected !== value) {
            this.changeSort(value);
        }

        this.selected = value;
    },
}));
