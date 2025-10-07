Alpine.data("NewsletterSubscription", () => ({
    email: "",
    subscribing: false,
    subscribed: false,

    subscribe() {
        if (this.subscribing || this.subscribed) {
            return;
        }

        this.subscribing = true;

        document.activeElement.blur();

        axios
            .post("/subscribers", {
                email: this.email,
            })
            .then(() => {
                this.email = "";
                this.subscribed = true;
            })
            .catch((error) => {
                if (error.response.status === 422) {
                    this.$refs.form.elements[0].focus();

                    notify(error.response.data.errors.email[0]);

                    return;
                }

                notify(error.response.data.message);
            })
            .finally(() => {
                this.subscribing = false;
            });
    },
}));
