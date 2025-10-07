import countdown from "countdown";
import ProductMixin from "../../../../mixins/ProductMixin";

Alpine.data("FlashSaleProductCard", (product) => ({
    ...ProductMixin(product),

    date: {},
    countdown: null,

    get endDate() {
        return this.product.pivot.end_date;
    },

    get progress() {
        return (this.product.pivot.sold / this.product.pivot.qty) * 100 + "%";
    },

    init() {
        if (new Date() > new Date(this.endDate)) {
            this.setInitialDate();

            return;
        }

        this.countdown = this.initCountdown();
    },

    initCountdown() {
        return countdown(
            new Date(this.endDate),
            ({ days, hours, minutes, seconds }) => {
                if (new Date() > new Date(this.endDate)) {
                    this.setInitialDate();
                    window.clearInterval(this.countdown);

                    return;
                }

                this.date = {
                    days: this.leadingZero(days),
                    hours: this.leadingZero(hours),
                    minutes: this.leadingZero(minutes),
                    seconds: this.leadingZero(seconds),
                };
            },
            countdown.DAYS |
                countdown.HOURS |
                countdown.MINUTES |
                countdown.SECONDS
        );
    },

    setInitialDate() {
        this.date = {
            days: "00",
            hours: "00",
            minutes: "00",
            seconds: "00",
        };
    },

    leadingZero(value) {
        return value < 10 ? "0" + value : value;
    },
}));
