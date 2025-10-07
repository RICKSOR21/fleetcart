import Alpine from "alpinejs";
import Errors from "./components/Errors";
import "./vendors/Axios";

window.Alpine = Alpine;

Alpine.data("App", ({ requirementSatisfied, permissionProvided }) => ({
    step: 1,
    formSubmitting: false,
    animateAlert: false,
    appInstalled: false,
    errorMessage: null,
    form: {
        db_host: "127.0.0.1",
        db_port: 3306,
        store_search_engine: "mysql",
    },
    errors: new Errors(),

    get isShowPrev() {
        return this.step === 2 || this.step === 3;
    },

    get isPrevDisabled() {
        return this.formSubmitting;
    },

    get isNextDisabled() {
        if (this.step === 1) {
            return !requirementSatisfied || this.formSubmitting;
        }

        if (this.step === 2) {
            return !permissionProvided || this.formSubmitting;
        }

        if (this.step === 3) {
            return this.formSubmitting;
        }
    },

    get hasErrorMessage() {
        return this.errorMessage !== null;
    },

    prevStep() {
        if (this.isPrevDisabled) {
            return;
        }

        if (this.step > 1) {
            this.step--;
        }
    },

    nextStep() {
        if (this.isNextDisabled) {
            return;
        }

        if (this.step === 3) {
            this.submitForm();

            return;
        }

        this.step++;

        this.focusInitialFormField();
    },

    setErrorMessage(message) {
        this.errorMessage = message;

        this.triggerAlertAnimation();
    },

    resetErrorMessage() {
        this.errorMessage = null;
    },

    triggerAlertAnimation() {
        this.animateAlert = true;

        setTimeout(() => {
            this.animateAlert = false;
        }, 1000);
    },

    focusInitialFormField() {
        if (this.step === 3) {
            this.$nextTick(() => {
                this.$refs.configurationForm.elements[0].focus();
            });
        }
    },

    focusSearchEngineInputField(value) {
        if (value !== "mysql") {
            this.$nextTick(() => {
                const formFields = this.$refs.configurationForm.elements;

                formFields[formFields.length - 2].focus();
            });
        }
    },

    focusFirstErrorField(errors) {
        [...this.$refs.configurationForm.elements].some((el) => {
            if (el.name === Object.keys(errors)[0]) {
                el.focus();

                return true;
            }
        });
    },

    scrollToTop() {
        this.$refs.configurationContent.scroll({
            top: 0,
            behavior: "auto",
        });
    },

    resetForm() {
        this.form = {
            db_host: "127.0.0.1",
            db_port: 3306,
            store_search_engine: "mysql",
        };
    },

    submitForm() {
        this.formSubmitting = true;

        axios
            .post("/install", this.form)
            .then(() => {
                this.appInstalled = true;

                this.resetForm();
                this.resetErrorMessage();
                this.errors.reset();
            })
            .catch(({ response }) => {
                if (response.status === 422) {
                    const errors = response.data.errors;

                    this.resetErrorMessage();
                    this.focusFirstErrorField(errors);
                    this.errors.record(errors);

                    return;
                }

                this.scrollToTop();
                this.setErrorMessage(response.data.message);
            })
            .finally(() => {
                this.formSubmitting = false;
            });
    },
}));

Alpine.start();
