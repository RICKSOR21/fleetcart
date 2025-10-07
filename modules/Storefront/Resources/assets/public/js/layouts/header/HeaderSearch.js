import { throttle } from "lodash";

Alpine.data(
    "HeaderSearch",
    ({ categories, initialQuery, initialCategory }) => ({
        categories,
        initialQuery,
        initialCategory,
        skeleton: true,
        showMiniSearch: false,
        activeSuggestion: null,
        showSuggestions: false,
        form: {
            query: initialQuery,
            category: initialCategory,
        },
        suggestions: {
            categories: [],
            products: [],
            remaining: 0,
        },

        get shouldShowSuggestions() {
            if (!this.showSuggestions) {
                return false;
            }

            return this.hasAnySuggestion;
        },

        get moreResultsUrl() {
            if (this.form.category) {
                return `/categories/${this.form.category}/products?query=${this.form.query}`;
            }

            return `/products?query=${this.form.query}`;
        },

        get hasAnySuggestion() {
            return this.suggestions.products.length !== 0;
        },

        get hasAnyCategorySuggestion() {
            return this.suggestions.categories.length !== 0;
        },

        get allSuggestions() {
            return [
                ...this.suggestions.categories,
                ...this.suggestions.products,
            ];
        },

        get firstSuggestion() {
            return this.allSuggestions[0];
        },

        get lastSuggestion() {
            return this.allSuggestions[this.allSuggestions.length - 1];
        },

        init() {
            this.hideSkeleton();

            this.$watch(
                "form.query",
                throttle((newQuery) => {
                    if (newQuery === "") {
                        this.clearSuggestions();
                    } else {
                        this.showSuggestions = true;

                        this.fetchSuggestions();
                    }
                }, 600)
            );

            this.$watch("showMiniSearch", (newValue) => {
                if (newValue) {
                    this.$refs.miniSearchInput.focus();

                    return;
                }

                this.hideSuggestions();
            });

            this.fetchSuggestions();
        },

        hideSkeleton() {
            setTimeout(() => {
                this.skeleton = false;
            }, 100);
        },

        getCategoryNameBySlug(slug) {
            return (
                this.categories.find((category) => category.slug === slug)
                    ?.name || ""
            );
        },

        changeCategory(category = "") {
            this.form.category = category;

            this.fetchSuggestions();
        },

        async fetchSuggestions() {
            if (this.form.query === "") return;

            const { data } = await axios.get(`/suggestions`, {
                params: this.form,
            });

            this.clearActiveSuggestion();
            this.resetSuggestionScrollBar();

            this.suggestions.categories = data.categories;
            this.suggestions.products = data.products;
            this.suggestions.remaining = data.remaining;
        },

        search() {
            if (!this.form.query) {
                return;
            }

            if (this.activeSuggestion) {
                window.location.href = this.activeSuggestion.url;

                this.hideSuggestions();

                return;
            }

            if (this.form.category) {
                window.location.href = `/categories/${this.form.category}/products?query=${this.form.query}`;

                return;
            }

            window.location.href = `/products?query=${this.form.query}`;
        },

        showExistingSuggestions() {
            this.showSuggestions = true;
        },

        clearSuggestions() {
            this.suggestions.categories = [];
            this.suggestions.products = [];
        },

        hideSuggestions() {
            this.showSuggestions = false;

            this.clearActiveSuggestion();
        },

        isActiveSuggestion(suggestion) {
            if (!this.activeSuggestion) {
                return false;
            }

            return this.activeSuggestion.slug === suggestion.slug;
        },

        changeActiveSuggestion(suggestion) {
            this.activeSuggestion = suggestion;
        },

        clearActiveSuggestion() {
            this.activeSuggestion = null;
        },

        nextSuggestion() {
            if (!this.hasAnySuggestion) {
                return;
            }

            this.activeSuggestion =
                this.allSuggestions[this.nextSuggestionIndex()];

            if (!this.activeSuggestion) {
                this.activeSuggestion = this.firstSuggestion;
            }

            this.adjustSuggestionScrollBar();
        },

        prevSuggestion() {
            if (!this.hasAnySuggestion) {
                return;
            }

            if (this.prevSuggestionIndex() === -1) {
                this.clearActiveSuggestion();

                return;
            }

            this.activeSuggestion =
                this.allSuggestions[this.prevSuggestionIndex()];

            if (!this.activeSuggestion) {
                this.activeSuggestion = this.lastSuggestion;
            }

            this.adjustSuggestionScrollBar();
        },

        nextSuggestionIndex() {
            return this.currentSuggestionIndex() + 1;
        },

        prevSuggestionIndex() {
            return this.currentSuggestionIndex() - 1;
        },

        currentSuggestionIndex() {
            return this.allSuggestions.indexOf(this.activeSuggestion);
        },

        adjustSuggestionScrollBar() {
            const element = document.querySelector(
                `.search-suggestions-inner li[data-slug='${this.activeSuggestion.slug}']`
            );

            if (element) {
                this.$refs.searchSuggestionsInner.scrollTop =
                    element.offsetTop - 200;
            }
        },

        resetSuggestionScrollBar() {
            if (this.$refs.searchSuggestionsInner !== undefined) {
                this.$refs.searchSuggestionsInner.scrollTop = 0;
            }
        },

        hasBaseImage(product) {
            return product.base_image.length !== 0;
        },

        baseImage(product) {
            return this.hasBaseImage(product)
                ? product.base_image.path
                : `${window.location.origin}/build/assets/image-placeholder.png`;
        },
    })
);
