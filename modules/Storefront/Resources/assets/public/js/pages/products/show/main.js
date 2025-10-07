import { Manipulation, Pagination, Navigation, Thumbs } from "swiper/modules";
import md5 from "blueimp-md5";
import Swiper from "swiper";
import Drift from "drift-zoom";
import GLightbox from "glightbox";
import Errors from "../../../components/Errors";
import "../../../components/ProductRating";
import "../../../components/Pagination";
import "../../../components/ProductCard";

let galleryPreviewSlider;
let galleryPreviewLightbox;
let galleryPreviewZoomInstances = [];

Alpine.data("ProductShow", ({ product, variant, reviewCount, avgRating }) => ({
    product: product,
    item: variant || product,
    optionPrices: {},
    addingToCart: false,
    oldMediaLength: null,
    activeVariationValues: {},
    variationImagePath: null,
    showDescriptionContent: false,
    showMore: false,
    fetchingReviews: false,
    reviews: {},
    reviewCount,
    avgRating,
    addingNewReview: false,
    reviewForm: {},
    currentPage: 1,
    cartItemForm: {
        product_id: product.id,
        qty: 1,
        variations: {},
        options: {},
    },
    errors: new Errors(),

    get productName() {
        return this.product.name;
    },

    get isActiveItem() {
        return this.item.is_active === true;
    },

    get productUrl() {
        let url = `/products/${this.product.slug}`;

        if (this.hasAnyVariant) {
            url += `?variant=${this.item.uid}`;
        }

        return url;
    },

    get hasAnyMedia() {
        return this.item.media.length !== 0;
    },

    get productPrice() {
        return this.hasSpecialPrice
            ? this.item.selling_price.inCurrentCurrency.amount
            : this.item.price.inCurrentCurrency.amount;
    },

    get regularPrice() {
        const productPrice = this.item.price.inCurrentCurrency.amount;

        if (
            this.hasAnyOption &&
            !this.hasSpecialPrice &&
            this.hasAnyOptionPrice
        ) {
            return productPrice + this.optionsPrice;
        }

        return productPrice;
    },

    get hasSpecialPrice() {
        return this.item.special_price !== null;
    },

    get hasPercentageSpecialPrice() {
        return this.item.has_percentage_special_price;
    },

    get specialPrice() {
        const productPrice = this.item.selling_price.inCurrentCurrency.amount;

        if (
            this.hasAnyOption &&
            this.hasSpecialPrice &&
            this.hasAnyOptionPrice
        ) {
            return productPrice + this.optionsPrice;
        }

        return productPrice;
    },

    get isInStock() {
        return this.item.is_in_stock;
    },

    get isOutOfStock() {
        return this.item.is_out_of_stock;
    },

    get doesManageStock() {
        return this.item.does_manage_stock;
    },

    get hasAnyVariationImage() {
        return this.variationImagePath !== null;
    },

    get inWishlist() {
        return this.$store.wishlist.inWishlist(this.product.id);
    },

    get inCompareList() {
        return this.$store.compare.inCompareList(this.product.id);
    },

    get hasAnyVariant() {
        return this.product.variant !== null;
    },

    get hasAnyOption() {
        return this.product.options.length > 0;
    },

    get hasAnyOptionPrice() {
        return Object.keys(this.optionPrices).length !== 0;
    },

    get optionsPrice() {
        return Object.values(this.optionPrices).reduce(
            (total, value) => total + value,
            0
        );
    },

    get isAddToCartDisabled() {
        return this.isActiveItem ? this.isOutOfStock : true;
    },

    get maxQuantity() {
        return this.isInStock && this.doesManageStock ? this.item.qty : null;
    },

    get isQtyIncreaseDisabled() {
        return (
            this.isOutOfStock ||
            (this.maxQuantity !== null &&
                this.cartItemForm.qty >= this.item.qty) ||
            !this.isActiveItem
        );
    },

    get isQtyDecreaseDisabled() {
        return (
            this.isOutOfStock ||
            this.cartItemForm.qty <= 1 ||
            !this.isActiveItem
        );
    },

    get totalReviews() {
        if (!this.reviews.total) {
            return this.reviewCount;
        }

        return this.reviews.total;
    },

    get ratingPercent() {
        return (this.avgRating / 5) * 100;
    },

    get emptyReviews() {
        return this.totalReviews === 0;
    },

    get totalPage() {
        return Math.ceil(this.reviews.total / 5);
    },

    init() {
        this.$watch("cartItemForm.options", () => {
            this.productPriceWithOptionsPrice();
        });

        galleryPreviewSlider = this.initGalleryPreviewSlider();
        galleryPreviewLightbox = this.initGalleryPreviewLightbox();

        this.fetchReviews();
        this.setOldMediaLength();
        this.initGalleryPreviewZoom();
        this.setActiveVariationsValue();
        this.setDescriptionContentHeight();
        this.initUpSellProductsSlider();
        this.initRelatedProductsSlider();
    },

    syncWishlist() {
        this.$store.wishlist.syncWishlist(this.product.id);
    },

    syncCompareList() {
        this.$store.compare.syncCompareList(this.product.id);
    },

    setOldMediaLength() {
        if (this.hasAnyVariant) {
            this.oldMediaLength = this.item.media.length;
        }
    },

    initGalleryPreviewSlider() {
        return new Swiper(".product-gallery-preview", {
            modules: [Manipulation, Navigation, Thumbs],
            slidesPerView: 1,
            allowTouchMove: false,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: this.initGalleryThumbnailSlider(),
            },
        });
    },

    initGalleryThumbnailSlider() {
        return new Swiper(".product-gallery-thumbnail", {
            modules: [Manipulation, Navigation],
            slidesPerView: 4,
            spaceBetween: 10,
            watchSlidesProgress: true,
            touchEventsTarget: "container",
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                450: {
                    slidesPerView: 6,
                },
                576: {
                    slidesPerView: 7,
                },
                992: {
                    slidesPerView: 6,
                },
                1600: {
                    slidesPerView: 7,
                },
            },
        });
    },

    updateGallerySlider() {
        this.removeAllGallerySlides();

        // If product and variant has not media
        if (this.product.media.length === 0 && !this.hasAnyMedia) {
            this.addGalleryEmptySlide();
        } else {
            this.addGallerySlides();
        }

        this.addGalleryEventListeners();
    },

    addGallerySlides() {
        const galleryPreviewSlides = [];
        const galleryThumbnailSlides = [];

        // Merge variant and product media
        [...this.item.media, ...this.product.media].forEach(({ path }) => {
            galleryPreviewSlides.unshift(this.galleryPreviewSlide(path));
            galleryThumbnailSlides.unshift(this.galleryThumbnailSlide(path));
        });

        // Add gallery preview and thumbnail slides
        galleryPreviewSlider.addSlide(0, galleryPreviewSlides);
        galleryPreviewSlider.thumbs.swiper.addSlide(0, galleryThumbnailSlides);

        // Set the first slide as active
        galleryPreviewSlider.slideTo(0);
        galleryPreviewSlider.thumbs.swiper.slideTo(0);
    },

    addGalleryEmptySlide() {
        const filePath = `${FleetCart.baseUrl}/build/assets/image-placeholder.png`;

        galleryPreviewSlider.addSlide(
            0,
            this.galleryPreviewEmptySlide(filePath)
        );
        galleryPreviewSlider.thumbs.swiper.addSlide(
            0,
            this.galleryThumbnailEmptySlide(filePath)
        );
    },

    removeAllGallerySlides() {
        galleryPreviewSlider.removeAllSlides();
        galleryPreviewSlider.thumbs.swiper.removeAllSlides();
    },

    addGalleryEventListeners() {
        this.$nextTick(() => {
            this.initGalleryPreviewZoom();
            galleryPreviewLightbox.reload();
        });
    },

    initGalleryPreviewZoom() {
        if (this.isMobileDevice()) {
            this.initGalleryPreviewMobileZoom();

            return;
        }

        this.initGalleryPreviewDesktopZoom();
    },

    initGalleryPreviewMobileZoom() {
        this.destroyGalleryPreviewZoomInstances();

        [...document.querySelectorAll(".gallery-preview-item > img")].forEach(
            (el) => {
                galleryPreviewZoomInstances.push(
                    new Drift(el, {
                        namespace: "mobile-drift",
                        inlinePane: true,
                        inlineOffsetY: -50,
                        passive: true,
                    })
                );
            }
        );
    },

    initGalleryPreviewDesktopZoom() {
        this.destroyGalleryPreviewZoomInstances();

        [...document.querySelectorAll(".gallery-preview-item > img")].forEach(
            (el) => {
                galleryPreviewZoomInstances.push(
                    new Drift(el, {
                        inlinePane: false,
                        hoverBoundingBox: true,
                        boundingBoxContainer: document.body,
                        paneContainer:
                            document.querySelector(".product-gallery"),
                    })
                );
            }
        );
    },

    destroyGalleryPreviewZoomInstances() {
        if (galleryPreviewZoomInstances.length !== 0) {
            galleryPreviewZoomInstances.forEach((instance) => {
                instance.destroy();
            });
        }
    },

    initGalleryPreviewLightbox() {
        return GLightbox({
            zoomable: true,
            preload: false,
        });
    },

    triggerGalleryPreviewLightbox(event) {
        if (window.innerWidth > 990) {
            event.currentTarget.nextElementSibling.click();
        }
    },

    galleryPreviewSlide(filePath) {
        return `
                <div class="swiper-slide">
                    <div class="gallery-preview-slide">
                        <div class="gallery-preview-item" @click="triggerGalleryPreviewLightbox(event)">
                            <img src="${filePath}" data-zoom="${filePath}" alt="${this.productName}">
                        </div>

                        <a href="${filePath}" data-gallery="product-gallery-preview" class="gallery-view-icon glightbox">
                            <i class="las la-search-plus"></i>
                        </a>
                    </div>
                </div>
            `;
    },

    galleryThumbnailSlide(filePath) {
        return `
                <div class="swiper-slide">
                    <div class="gallery-thumbnail-slide">
                        <div class="gallery-thumbnail-item">
                            <img src="${filePath}" alt="${this.productName}">
                        </div>
                    </div>
                </div>
            `;
    },

    galleryPreviewEmptySlide(filePath) {
        return `
                <div class="swiper-slide">
                    <div class="gallery-preview-slide">
                        <div class="gallery-preview-item" @click="triggerGalleryPreviewLightbox(event)">
                            <img src="${filePath}" data-zoom="${filePath}" alt="${this.productName}" class="image-placeholder">
                        </div>

                        <a href="${filePath}" data-gallery="product-gallery-preview" class="gallery-view-icon glightbox">
                            <i class="las la-search-plus"></i>
                        </a>
                    </div>
                </div>
            `;
    },

    galleryThumbnailEmptySlide(filePath) {
        return `
                <div class="swiper-slide">
                    <div class="gallery-thumbnail-slide">
                        <div class="gallery-thumbnail-item">
                            <img src="${filePath}" alt="${this.productName}" class="image-placeholder">
                        </div>
                    </div>
                </div>
            `;
    },

    productPriceWithOptionsPrice() {
        const cartItemoptions = Object.entries(this.cartItemForm.options);

        cartItemoptions.forEach(([key, value]) => {
            const option = this.product.options.find(
                ({ id }) => id === Number(key)
            );

            // Single select with single value
            if (
                ["field", "textarea", "date", "date_time", "time"].includes(
                    option.type
                )
            ) {
                if (!Boolean(this.cartItemForm.options[option.id])) {
                    delete this.optionPrices[option.id];

                    return;
                }

                const optionValue = option.values[0];
                const price =
                    optionValue.price?.inCurrentCurrency?.amount ??
                    (+optionValue.price / 100) * this.productPrice;

                this.optionPrices[key] = price;

                return;
            }

            // Single select with multiple values
            if (["dropdown", "radio", "radio_custom"].includes(option.type)) {
                const optionValue = option.values.find(
                    ({ id }) => id === Number(value)
                );

                const price =
                    optionValue.price?.inCurrentCurrency?.amount ??
                    (+optionValue.price / 100) * this.productPrice;

                this.optionPrices[key] = price;

                return;
            }

            // Multiple select with multiple values
            if (
                ["checkbox", "checkbox_custom", "multiple_select"].includes(
                    option.type
                ) &&
                value.length !== 0
            ) {
                const values = this.product.options
                    .find(({ id }) => id === Number(key))
                    .values.filter((data) => value.includes(data.id));

                const price = values.reduce(
                    (accumulator, value) =>
                        accumulator +
                        (value.price?.inCurrentCurrency?.amount ??
                            (+value.price / 100) * this.productPrice),
                    0
                );

                this.optionPrices[key] = price;
            }
        });
    },

    isVariationValueEnabled(variationUid, variationIndex, valueUid) {
        // Check if enabled first variation values
        if (variationIndex === 0) {
            return this.doesVariantExist(valueUid);
        }

        // Check if enabled variation values between first and last variation
        if (
            variationIndex > 0 &&
            variationIndex < this.product.variations.length - 1
        ) {
            return this.doesVariantExist(valueUid);
        }

        // Check if enabled last variation values
        if (variationIndex === this.product.variations.length - 1) {
            const variations = this.cartItemForm.variations;
            const valueUids = Object.values(variations).filter(
                (uid) => uid !== variations[variationUid]
            );

            valueUids.push(valueUid);

            return this.doesVariantExist(valueUids.sort().join("."));
        }
    },

    setActiveVariationsValue() {
        if (!this.hasAnyVariant) return;

        this.item.uids.split(".").forEach((uid) => {
            this.product.variations.some((variation) => {
                const value = variation.values.find(
                    (value) => value.uid === uid
                );

                if (value !== undefined) {
                    this.activeVariationValues[variation.uid] = value.label;
                    this.cartItemForm.variations[variation.uid] = uid;

                    return true;
                }
            });
        });
    },

    setActiveVariationValueLabel(variationIndex) {
        this.variationImagePath = null;

        const variation = this.product.variations[variationIndex];
        const value = variation.values.find(
            (value) => value.uid === this.cartItemForm.variations[variation.uid]
        );

        this.activeVariationValues[variation.uid] = value.label;
    },

    setVariationValueLabel(variationIndex, valueIndex) {
        const variation = this.product.variations[variationIndex];
        const value = variation.values[valueIndex];

        if (!this.isMobileDevice() && variation.type === "image") {
            this.variationImagePath = value.image.path;
        }

        this.activeVariationValues[variation.uid] = value.label;
    },

    isActiveVariationValue(variationUid, valueUid) {
        if (!this.cartItemForm.variations.hasOwnProperty(variationUid)) {
            return false;
        }

        return this.cartItemForm.variations[variationUid] === valueUid;
    },

    syncVariationValue(variationUid, variationIndex, valueUid, valueIndex) {
        if (!this.isActiveVariationValue(variationUid, valueUid)) {
            this.cartItemForm.variations[variationUid] = valueUid;

            this.setVariationValueLabel(variationIndex, valueIndex);
            this.updateVariantDetails();
        }
    },

    doesVariantExist(uid) {
        return this.product.variants.some(({ uids }) => uids.includes(uid));
    },

    setVariant() {
        const selectedUids = Object.values(this.cartItemForm.variations)
            .sort()
            .join(".");

        const variant = this.product.variants.find(
            (variant) => variant.uids === selectedUids
        );

        if (variant !== undefined) {
            this.item = { ...variant };

            this.reduceToMaxQuantity();

            return;
        }

        // Set empty variant data if variant does not exist
        const uid = md5(
            Object.values(this.cartItemForm.variations).sort().join(".")
        );

        this.item = {
            uid,
            media: [],
            base_image: [],
        };

        this.cartItemForm.qty = 1;
    },

    setVariantSlug() {
        const url = `${FleetCart.baseUrl}/products/${this.product.slug}?variant=${this.item.uid}`;

        window.history.replaceState({}, "", url);
    },

    updateVariantDetails() {
        this.setOldMediaLength();
        this.setVariant();
        this.setVariantSlug();
        this.updateGallerySlider();
    },

    updateSelectTypeOptionValue(optionId, event) {
        this.cartItemForm.options = Object.assign(
            {},
            this.cartItemForm.options,
            {
                [optionId]: event.target.value,
            }
        );

        this.errors.clear(`options.${optionId}`);
    },

    updateCheckboxTypeOptionValue(optionId, event) {
        let values = $(event.target)
            .parents(".variant-check")
            .find('input[type="checkbox"]:checked')
            .map((_, el) => {
                return el.value;
            });

        this.cartItemForm.options = Object.assign(
            {},
            this.cartItemForm.options,
            {
                [optionId]: values.get(),
            }
        );
    },

    customRadioTypeOptionValueIsActive(optionId, valueId) {
        if (!this.cartItemForm.options.hasOwnProperty(optionId)) {
            return false;
        }

        return this.cartItemForm.options[optionId] === valueId;
    },

    syncCustomRadioTypeOptionValue(optionId, valueId) {
        if (this.customRadioTypeOptionValueIsActive(optionId, valueId)) {
            delete this.cartItemForm.options[optionId];
        } else {
            this.cartItemForm.options = Object.assign(
                {},
                this.cartItemForm.options,
                {
                    [optionId]: valueId,
                }
            );

            this.errors.clear(`options.${optionId}`);
        }
    },

    customCheckboxTypeOptionValueIsActive(optionId, valueId) {
        if (!this.cartItemForm.options.hasOwnProperty(optionId)) {
            this.cartItemForm.options = Object.assign(
                {},
                this.cartItemForm.options,
                {
                    [optionId]: [],
                }
            );

            return false;
        }

        return this.cartItemForm.options[optionId].includes(valueId);
    },

    syncCustomCheckboxTypeOptionValue(optionId, valueId) {
        if (this.customCheckboxTypeOptionValueIsActive(optionId, valueId)) {
            this.cartItemForm.options[optionId].splice(
                this.cartItemForm.options[optionId].indexOf(valueId),
                1
            );
        } else {
            this.cartItemForm.options[optionId].push(valueId);

            // Reassign the existing data due to reactivity issue
            this.cartItemForm = Object.assign(
                {},
                this.cartItemForm,
                this.cartItemForm.options
            );

            this.errors.clear(`options.${optionId}`);
        }
    },

    setDescriptionContentHeight() {
        this.$nextTick(() => {
            this.showMore =
                this.$refs.descriptionContent.clientHeight >= 400
                    ? true
                    : false;
        });
    },

    setInactiveItemData() {
        this.item = {
            uid: this.item.uid,
            media: [],
            base_image: [],
        };
    },

    isMobileDevice() {
        return window.matchMedia("only screen and (max-width: 992px)").matches;
    },

    updateQuantity(qty) {
        if (isNaN(qty) || qty < 1) {
            this.cartItemForm.qty = 1;

            return;
        }

        this.cartItemForm.qty = qty;

        if (this.exceedsMaxStock(qty)) {
            this.cartItemForm.qty = this.item.qty;

            return;
        }
    },

    exceedsMaxStock(qty) {
        return this.doesManageStock && this.item.qty < qty;
    },

    reduceToMaxQuantity() {
        if (this.doesManageStock && this.cartItemForm.qty > this.item.qty) {
            this.cartItemForm.qty = this.item.qty || 1;
        }
    },

    addToCart() {
        if (this.isAddToCartDisabled) return;

        this.addingToCart = true;

        axios
            .post("/cart/items", {
                ...this.cartItemForm,
                ...(this.hasAnyVariant && { variant_id: this.item.id }),
            })
            .then((response) => {
                this.$store.cart.updateCart(response.data);
                this.$store.layout.openSidebarCart();
            })
            .catch(({ response }) => {
                if (response.status === 422) {
                    this.errors.record(response.data.errors);
                }

                notify(response.data.message);
            })
            .finally(() => {
                this.addingToCart = false;
            });
    },

    toggleDescriptionContent() {
        this.showDescriptionContent = !this.showDescriptionContent;
    },

    async fetchReviews() {
        this.fetchingReviews = true;

        try {
            const response = await axios.get(
                `/products/${this.product.id}/reviews?page=${this.currentPage}`
            );

            this.reviews = response.data;
        } catch (error) {
            notify(error.response.data.message);
        } finally {
            this.fetchingReviews = false;
        }
    },

    addNewReview() {
        this.addingNewReview = true;

        axios
            .post(`/products/${this.product.id}/reviews`, {
                ...this.reviewForm,
                ...(window.grecaptcha && {
                    "g-recaptcha-response": grecaptcha.getResponse(),
                }),
            })
            .then((response) => {
                this.reviewForm = {};
                this.reviews.total++;
                this.reviews.data.unshift(response.data);

                notify(trans("storefront::product.review_submitted"));

                this.errors.reset();
            })
            .catch(({ response }) => {
                if (response.status === 422) {
                    this.errors.record(response.data.errors);

                    return;
                }

                notify(response.data.message);
            })
            .finally(() => {
                this.addingNewReview = false;

                if (window.grecaptcha) {
                    grecaptcha.reset();
                }
            });
    },

    changePage(page) {
        this.currentPage = page;

        this.fetchReviews();
    },

    hideRelatedProductsSkeleton() {
        const skeletons = document.querySelectorAll(
            ".landscape-products .swiper-slide-skeleton"
        );

        skeletons.forEach((skeleton) => skeleton.remove());
    },

    initUpSellProductsSlider() {
        new Swiper(this.$refs.upSellProducts, {
            modules: [Navigation],
            slidesPerView: 1,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
        });
    },

    initRelatedProductsSlider() {
        this.hideRelatedProductsSkeleton();

        new Swiper(this.$refs.landscapeProducts, {
            modules: [Navigation, Pagination],
            slidesPerView: 2,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            breakpoints: {
                640: {
                    slidesPerView: 3,
                },
                880: {
                    slidesPerView: 4,
                },
                992: {
                    slidesPerView: 3,
                },
                1100: {
                    slidesPerView: 4,
                },
                1300: {
                    slidesPerView: 5,
                },
                1600: {
                    slidesPerView: 6,
                },
            },
        });
    },
}));
