import Errors from "../../../components/Errors";
import "../../../components/CartItem";

Alpine.data(
    "Checkout",
    ({
        customerEmail,
        customerPhone,
        addresses,
        defaultAddress,
        gateways,
        countries,
    }) => ({
        addresses,
        defaultAddress,
        gateways,
        countries,
        form: {
            customer_email: customerEmail,
            customer_phone: customerPhone,
            billing: {},
            shipping: {},
            billingAddressId: null,
            shippingAddressId: null,
            newBillingAddress: false,
            newShippingAddress: false,
            ship_to_a_different_address: false,
        },
        states: {
            billing: {},
            shipping: {},
        },
        controller: null,
        shippingMethodName: null,
        applyingCoupon: false,
        couponCode: null,
        couponError: null,
        placingOrder: false,
        stripe: null,
        stripeElements: null,
        authorizeNetToken: null,
        payFastFormFields: {},
        errors: new Errors(),

        get cartFetched() {
            return this.$store.cart.fetched;
        },

        get cart() {
            return this.$store.cart.cart;
        },

        get cartIsEmpty() {
            return this.$store.cart.isEmpty;
        },

        get hasAddress() {
            return Object.keys(this.addresses).length !== 0;
        },

        get firstCountry() {
            return Object.keys(this.countries)[0];
        },

        get hasBillingStates() {
            return Object.keys(this.states.billing).length !== 0;
        },

        get hasShippingStates() {
            return Object.keys(this.states.shipping).length !== 0;
        },

        get hasNoPaymentMethod() {
            return Object.keys(this.gateways).length === 0;
        },

        get firstPaymentMethod() {
            return Object.keys(this.gateways)[0];
        },

        get shouldShowPaymentInstructions() {
            return ["bank_transfer", "check_payment"].includes(
                this.form.payment_method
            );
        },

        get paymentInstructions() {
            if (this.shouldShowPaymentInstructions) {
                return this.gateways[this.form.payment_method].instructions;
            }
        },

        get hasShippingMethod() {
            return Object.keys(this.cart.availableShippingMethods).length !== 0;
        },

        get hasFreeShipping() {
            return this.cart.coupon?.free_shipping ?? false;
        },

        get firstShippingMethod() {
            return Object.keys(this.cart.availableShippingMethods)[0];
        },

        init() {
            Alpine.effect(() => {
                if (this.cartFetched) {
                    this.hideSkeleton();
                    this.changePaymentMethod(this.firstPaymentMethod);

                    if (this.cart.shippingMethodName) {
                        this.changeShippingMethod(this.cart.shippingMethodName);
                    } else {
                        this.updateShippingMethod(this.firstShippingMethod);
                    }

                    if (
                        FleetCart.stripeEnabled &&
                        FleetCart.stripeIntegrationType === "embedded_form"
                    ) {
                        this.renderStripeElements();
                    }
                }
            });

            this.$watch("form.billing.city", (newCity) => {
                if (newCity) {
                    this.addTaxes();
                }
            });

            this.$watch("form.shipping.city", (newCity) => {
                if (newCity) {
                    this.addTaxes();
                }
            });

            this.$watch("form.billing.zip", (newZip) => {
                if (newZip) {
                    this.addTaxes();
                }
            });

            this.$watch("form.shipping.zip", (newZip) => {
                if (newZip) {
                    this.addTaxes();
                }
            });

            this.$watch("form.billing.state", (newState) => {
                if (newState) {
                    this.addTaxes();
                }
            });

            this.$watch("form.shipping.state", (newState) => {
                if (newState) {
                    this.addTaxes();
                }
            });

            this.$watch("form.ship_to_a_different_address", (newValue) => {
                if (newValue && this.form.shippingAddressId) {
                    this.form.shipping =
                        this.addresses[this.form.shippingAddressId];
                } else {
                    this.form.shipping = {};
                    this.resetAddressErrors("shipping");
                }

                this.addTaxes();
            });

            this.$watch("form.terms_and_conditions", () => {
                this.errors.clear("terms_and_conditions");
            });

            this.$watch("form.payment_method", (newPaymentMethod) => {
                if (newPaymentMethod === "paypal") {
                    this.$nextTick(this.renderPayPalButton());
                }
            });

            if (this.defaultAddress.address_id) {
                this.form.billingAddressId = this.defaultAddress.address_id;
                this.form.shippingAddressId = this.defaultAddress.address_id;

                this.mergeSavedBillingAddress();
                this.mergeSavedShippingAddress();
            }

            if (!this.hasAddress) {
                this.form.newBillingAddress = true;
                this.form.newShippingAddress = true;
            }

            this.setTabReminder();
        },

        setTabReminder() {
            const originalTitle = document.title;
            let timeoutId;

            document.addEventListener("visibilitychange", function () {
                if (document.hidden) {
                    timeoutId = setTimeout(() => {
                        document.title = trans(
                            "storefront::checkout.remember_about_your_order"
                        );
                    }, 1000);
                } else {
                    clearTimeout(timeoutId);

                    document.title = originalTitle;
                }
            });
        },

        hideSkeleton() {
            const selectors = [
                ".cart-items-skeleton",
                ".order-summary-list-skeleton",
                ".order-summary-total-skeleton",
            ];

            selectors.forEach((selector) => {
                const element = document.querySelector(selector);

                if (element) {
                    element.remove();
                }
            });
        },

        changeBillingAddress(address) {
            if (
                this.form.newBillingAddress ||
                this.form.billingAddressId === address.id
            ) {
                return;
            }

            this.form.billingAddressId = address.id;

            this.mergeSavedBillingAddress();
        },

        addNewBillingAddress() {
            this.resetAddressErrors("billing");

            this.form.billing = {};
            this.form.newBillingAddress = !this.form.newBillingAddress;

            if (!this.form.newBillingAddress) {
                this.mergeSavedBillingAddress();
            }
        },

        changeShippingAddress(address) {
            if (
                this.form.newShippingAddress ||
                this.form.shippingAddressId === address.id
            ) {
                return;
            }

            this.form.shippingAddressId = address.id;

            this.mergeSavedShippingAddress();
        },

        addNewShippingAddress() {
            this.resetAddressErrors("shipping");

            this.form.shipping = {};
            this.form.newShippingAddress = !this.form.newShippingAddress;

            if (!this.form.newShippingAddress) {
                this.mergeSavedShippingAddress();
            }
        },

        // Reset address errors based on address type
        resetAddressErrors(addressType) {
            Object.keys(this.errors.errors).map((key) => {
                key.indexOf(addressType) !== -1 && this.errors.clear(key);
            });
        },

        mergeSavedBillingAddress() {
            this.resetAddressErrors("billing");

            if (!this.form.newBillingAddress && this.form.billingAddressId) {
                this.form.billing = this.addresses[this.form.billingAddressId];
            }
        },

        mergeSavedShippingAddress() {
            this.resetAddressErrors("shipping");

            if (
                this.form.ship_to_a_different_address &&
                !this.form.newShippingAddress &&
                this.form.shippingAddressId
            ) {
                this.form.shipping =
                    this.addresses[this.form.shippingAddressId];
            }
        },

        changeBillingCity(city) {
            this.form.billing.city = city;
        },

        changeShippingCity(city) {
            this.form.shipping.city = city;
        },

        changeBillingZip(zip) {
            this.form.billing.zip = zip;
        },

        changeShippingZip(zip) {
            this.form.shipping.zip = zip;
        },

        changeBillingCountry(country) {
            this.form.billing.country = country;

            if (country === "") {
                this.form.billing.state = "";
                this.states.billing = {};

                return;
            }

            this.fetchStates(country, (response) => {
                this.states.billing = response.data;
                this.form.billing.state = "";
            });
        },

        changeShippingCountry(country) {
            this.form.shipping.country = country;

            if (country === "") {
                this.form.shipping.state = "";
                this.states.shipping = {};

                return;
            }

            this.fetchStates(country, (response) => {
                this.states.shipping = response.data;
                this.form.shipping.state = "";
            });
        },

        fetchStates(country, callback) {
            axios.get(`/countries/${country}/states`).then(callback);
        },

        changeBillingState(state) {
            this.form.billing.state = state;
        },

        changeShippingState(state) {
            this.form.shipping.state = state;
        },

        changePaymentMethod(paymentMethod) {
            this.form.payment_method = paymentMethod;
        },

        changeShippingMethod(shippingMethodName) {
            this.form.shipping_method = shippingMethodName;
        },

        async updateShippingMethod(shippingMethodName) {
            if (!shippingMethodName) {
                return;
            }

            this.changeShippingMethod(shippingMethodName);

            try {
                const response = await axios.post("/cart/shipping-method", {
                    shipping_method: shippingMethodName,
                });

                this.$store.cart.updateCart(response.data);
            } catch (error) {
                notify(error.response.data.message);
            }
        },

        async addTaxes() {
            try {
                const response = await axios.post("/cart/taxes", this.form);

                this.$store.cart.updateCart(response.data);
            } catch (error) {
                notify(error.response.data.message);
            }
        },

        applyCoupon() {
            if (!this.couponCode) {
                return;
            }

            this.applyingCoupon = true;

            axios
                .post("/cart/coupon", { coupon: this.couponCode })
                .then((response) => {
                    this.couponCode = null;
                    this.couponError = null;

                    this.$store.cart.updateCart(response.data);
                })
                .catch((error) => {
                    this.couponError = error.response.data.message;
                })
                .finally(() => {
                    this.applyingCoupon = false;
                });
        },

        removeCoupon() {
            axios
                .delete("/cart/coupon")
                .then(() => {
                    this.updateShippingMethod(this.form.shipping_method);
                })
                .catch((error) => {
                    notify(error.response.data.message);
                });
        },

        placeOrder() {
            if (!this.form.terms_and_conditions || this.placingOrder) {
                return;
            }

            this.placingOrder = true;

            axios
                .post("/checkout", {
                    ...this.form,
                    ship_to_a_different_address:
                        +this.form.ship_to_a_different_address,
                })
                .then(({ data }) => {
                    if (data.redirectUrl) {
                        window.location.href = data.redirectUrl;
                    } else if (this.form.payment_method === "stripe") {
                        this.confirmStripePayment(data);
                    } else if (this.form.payment_method === "paytm") {
                        this.confirmPaytmPayment(data);
                    } else if (this.form.payment_method === "razorpay") {
                        this.confirmRazorpayPayment(data);
                    } else if (this.form.payment_method === "paystack") {
                        this.confirmPaystackPayment(data);
                    } else if (this.form.payment_method === "authorizenet") {
                        this.confirmAuthorizeNetPayment(data);
                    } else if (this.form.payment_method === "flutterwave") {
                        this.confirmFlutterWavePayment(data);
                    } else if (this.form.payment_method === "mercadopago") {
                        this.confirmMercadoPagoPayment(data);
                    } else if (this.form.payment_method === "payfast") {
                        this.confirmPayFastPayment(data);
                    } else {
                        this.confirmOrder(
                            data.orderId,
                            this.form.payment_method
                        );
                    }
                })
                .catch(({ response }) => {
                    this.placingOrder = false;

                    if (response.status === 422) {
                        this.errors.record(response.data.errors);
                    }

                    notify(response.data.message);
                });
        },

        confirmOrder(orderId, paymentMethod, params = {}) {
            axios
                .get(`/checkout/${orderId}/complete`, {
                    params: {
                        paymentMethod,
                        ...params,
                    },
                })
                .then(() => {
                    window.location.href = "/checkout/complete";
                })
                .catch((error) => {
                    this.placingOrder = false;

                    this.deleteOrder(orderId);

                    notify(error.response.data.message);
                });
        },

        async deleteOrder(orderId) {
            if (!orderId) {
                return;
            }

            const response = await axios.get(
                `/checkout/${orderId}/payment-canceled`
            );

            notify(response.data.message);
        },

        renderPayPalButton() {
            let vm = this;
            let response;

            window.paypal
                .Buttons({
                    async createOrder() {
                        try {
                            response = await axios.post("/checkout", vm.form);

                            return response.data.resourceId;
                        } catch ({ response }) {
                            if (response.status === 422) {
                                vm.errors.record(response.data.errors);

                                return;
                            }

                            notify(response.data.message);
                        }
                    },
                    onApprove() {
                        vm.confirmOrder(
                            response.data.orderId,
                            "paypal",
                            response.data
                        );
                    },
                    onError() {
                        vm.deleteOrder(response.data.orderId);
                    },
                    onCancel() {
                        vm.deleteOrder(response.data.orderId);
                    },
                })
                .render("#paypal-button-container");
        },

        async renderStripeElements() {
            this.stripe = Stripe(FleetCart.stripePublishableKey, {});

            this.stripeElements = this.stripe.elements({
                mode: "payment",
                amount: Math.round(this.$store.cart.total * 100),
                currency: FleetCart.currency.toLowerCase(),
            });

            this.stripeElements.create("payment").mount("#stripe-element");
        },

        async confirmStripePayment({ client_secret, orderId, return_url }) {
            const elements = this.stripeElements;

            const { error: submitError } = await this.stripeElements.submit();

            if (submitError) {
                this.placingOrder = false;

                this.deleteOrder(orderId);

                notify(submitError.message);

                return;
            }

            const { error } = await this.stripe.confirmPayment({
                elements,
                clientSecret: client_secret,
                confirmParams: {
                    return_url,
                },
            });

            if (error) {
                this.placingOrder = false;

                this.deleteOrder(orderId);

                notify(error.message);
            }
        },

        confirmPaytmPayment({ orderId, amount, txnToken }) {
            let config = {
                root: "",
                flow: "DEFAULT",
                data: {
                    orderId: orderId,
                    token: txnToken,
                    tokenType: "TXN_TOKEN",
                    amount: amount,
                },
                merchant: {
                    name: FleetCart.storeName,
                    redirect: false,
                },
                handler: {
                    transactionStatus: (response) => {
                        if (response.STATUS === "TXN_SUCCESS") {
                            this.confirmOrder(orderId, "paytm", response);
                        } else if (response.STATUS === "TXN_FAILURE") {
                            this.placingOrder = false;

                            this.deleteOrder(orderId);
                        }

                        window.Paytm.CheckoutJS.close();
                    },
                    notifyMerchant: (eventName) => {
                        if (eventName === "APP_CLOSED") {
                            this.placingOrder = false;

                            this.deleteOrder(orderId);
                        }
                    },
                },
            };

            window.Paytm.CheckoutJS.init(config)
                .then(() => {
                    window.Paytm.CheckoutJS.invoke();
                })
                .catch(() => {
                    this.deleteOrder(orderId);
                });
        },

        confirmRazorpayPayment(razorpayOrder) {
            this.placingOrder = false;

            let vm = this;

            new window.Razorpay({
                key: razorpayOrder.razorpayKeyId,
                name: FleetCart.storeName,
                description: trans("storefront::checkout.payment_for_order", {
                    id: razorpayOrder.receipt,
                }),
                image: FleetCart.storeLogo,
                order_id: razorpayOrder.id,
                handler(response) {
                    vm.placingOrder = true;

                    vm.confirmOrder(
                        razorpayOrder.receipt,
                        "razorpay",
                        response
                    );
                },
                modal: {
                    ondismiss() {
                        vm.deleteOrder(razorpayOrder.receipt);
                    },
                },
                prefill: {
                    name: `${vm.form.billing.first_name} ${vm.form.billing.last_name}`,
                    email: vm.form.customer_email,
                    contact: vm.form.customer_phone,
                },
            }).open();
        },

        confirmPaystackPayment({
            key,
            email,
            amount,
            ref,
            currency,
            order_id,
        }) {
            let vm = this;

            PaystackPop.setup({
                key,
                email,
                amount,
                ref,
                currency,
                onClose() {
                    vm.placingOrder = false;

                    vm.deleteOrder(order_id);
                },
                callback(response) {
                    vm.placingOrder = false;

                    vm.confirmOrder(order_id, "paystack", response);
                },
                onBankTransferConfirmationPending(response) {
                    vm.placingOrder = false;

                    vm.confirmOrder(order_id, "paystack", response);
                },
            }).openIframe();
        },

        confirmAuthorizeNetPayment({ token }) {
            this.authorizeNetToken = token;

            this.$nextTick(() => {
                this.$refs.authorizeNetForm.submit();

                this.authorizeNetToken = null;
            });
        },

        confirmFlutterWavePayment({
            public_key,
            tx_ref,
            order_id,
            amount,
            currency,
            payment_options,
            redirect_url,
        }) {
            let vm = this;

            FlutterwaveCheckout({
                public_key,
                tx_ref,
                amount,
                currency,
                payment_options: payment_options.join(", "),
                redirect_url,
                customer: {
                    email: this.form.customer_email,
                    phone_number: this.form.customer_phone,
                    name: this.form.billing.full_name,
                },
                customizations: {
                    title: FleetCart.storeName,
                    logo: FleetCart.storeLogo,
                },
                onclose(incomplete) {
                    vm.placingOrder = false;

                    if (incomplete) {
                        vm.deleteOrder(order_id);
                    }
                },
            });
        },

        confirmMercadoPagoPayment(mercadoPagoOrder) {
            this.placingOrder = false;

            const SUPPORTED_LOCALES = {
                en_US: "en-US",
                es_AR: "es-AR",
                es_CL: "es-CL",
                es_CO: "es-CO",
                es_MX: "es-MX",
                es_VE: "es-VE",
                es_UY: "es-UY",
                es_PE: "es-PE",
                pt_BR: "pt-BR",
            };

            const mercadoPago = new MercadoPago(mercadoPagoOrder.publicKey, {
                locale:
                    SUPPORTED_LOCALES[mercadoPagoOrder.currentLocale] ||
                    "en-US",
            });

            mercadoPago.checkout({
                preference: {
                    id: mercadoPagoOrder.preferenceId,
                },
                autoOpen: true,
            });
        },

        confirmPayFastPayment(payFastOrder) {
            this.payFastFormFields = payFastOrder.formFields;

            this.$nextTick(() => {
                this.$refs.payFastForm.submit();
            });
        },
    })
);
