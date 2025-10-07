import { ref, computed, nextTick } from "vue";
import { useForm } from "./useForm";
import { useVariations } from "./useVariations";
import { useBulkEditVariants } from "./useBulkEditVariants";
import { toaster } from "@admin/js/Toaster";
import md5 from "blueimp-md5";

const defaultVariantUid = ref("");
const variantsLength = ref(0);
const variantPosition = ref(0);

export function useVariants() {
    const { form, clearErrors } = useForm();
    const {
        getFilteredVariations,
        initVariationsColorPicker,
        updateVariationsColorThumbnail,
    } = useVariations();
    const { resetBulkEditVariantFields } = useBulkEditVariants();

    const hasAnyVariant = computed(() => form.variants.length !== 0);

    function setDefaultVariant() {
        const variants = form.variants;
        const index = variants.findIndex(
            ({ uid }) => uid === defaultVariantUid.value,
        );

        resetDefaultVariant();

        const variant = variants[index === -1 ? 0 : index];

        defaultVariantUid.value = variant.uid;
        variant.is_default = true;

        if (index === -1) {
            defaultVariantUid.value = variants[0].uid;
            variants[0].is_active = true;
        }
    }

    function setVariantData({ uids, name }, index) {
        if (uids !== undefined) {
            form.variants[index].uid = md5(uids);
            form.variants[index].uids = uids;
        }

        form.variants[index].name = name;
    }

    function setDefaultVariantUid() {
        if (hasAnyVariant.value) {
            defaultVariantUid.value = form.variants.find(
                ({ is_default }) => is_default === true,
            ).uid;
        }
    }

    function setVariantsLength() {
        variantsLength.value = form.variants.length;
    }

    function setVariantsName() {
        generateNewVariants(getFilteredVariations()).forEach(
            (variant, index) => {
                form.variants[index].name = variant.name;
            },
        );
    }

    function resetDefaultVariant() {
        form.variants.some((variant) => {
            if (variant.is_default === true) {
                variant.is_default = false;

                return true;
            }
        });
    }

    async function generateVariants(isReordered) {
        await nextTick(() => {
            initVariationsColorPicker();
            updateVariationsColorThumbnail();
        });

        // Filter empty variation values
        const variations = getFilteredVariations();

        if (variations.length === 0) {
            form.variants = [];
            variantsLength.value = 0;

            return;
        }

        const newVariants = generateNewVariants(variations);
        const oldVariants = form.variants.map(({ uids }) => {
            return {
                uids,
            };
        });

        // Do not generate variants if empty value is reordered
        if (isReordered === true && isEqualVariants(newVariants, oldVariants)) {
            return;
        }

        if (isReordered === true) {
            notifyVariantsReordered();
        }

        if (newVariants.length > variantsLength.value) {
            // Variation added
            addVariants(newVariants, oldVariants);
        } else if (newVariants.length < variantsLength.value) {
            // Variation removed
            removeVariants(newVariants, oldVariants);
        } else if (newVariants.length === variantsLength.value) {
            // Variations reordered
            reorderVariants(newVariants, oldVariants);
        }

        variantsLength.value = newVariants.length;

        setDefaultVariant();
    }

    function generateNewVariants(variations) {
        return variations
            .reduce((accumulator, currentValue) =>
                accumulator.flatMap((x) =>
                    currentValue.map((y) => {
                        return {
                            uid: x.uid + "." + y.uid,
                            label: x.label + " / " + y.label,
                        };
                    }),
                ),
            )
            .map(({ uid, label }) => {
                return {
                    uids: uid.split(".").sort().join("."),
                    name: label,
                };
            });
    }

    function addVariants(newVariants, oldVariants) {
        notifyVariantsCreated(newVariants.length);

        // Add initial variation with single or multiple values when variants are empty
        if (oldVariants.length === 0) {
            newVariants.forEach((newVariant) => {
                form.variants.push(initialVariantData(newVariant));
            });

            return;
        }

        // A new single value has been added with existing variation values
        if (hasCommonVariantUids(newVariants, oldVariants)) {
            const oldVariantsUids = oldVariants.map(({ uids }) => uids);

            newVariants.forEach((newVariant, index) => {
                if (!oldVariantsUids.includes(newVariant.uids)) {
                    form.variants.splice(
                        index,
                        0,
                        initialVariantData(newVariant),
                    );
                }
            });

            return;
        }

        // A new variation with multiple values has been added
        const matchedUids = [];

        oldVariants.forEach(({ uids }) => {
            newVariants.forEach((newVariant, index) => {
                const doesUidExist = uids
                    .split(".")
                    .every((uids) => newVariant.uids.split(".").includes(uids));

                if (doesUidExist && !matchedUids.includes(uids)) {
                    matchedUids.push(uids);

                    setVariantData(newVariant, index);

                    return;
                }

                if (doesUidExist) {
                    form.variants.splice(
                        index,
                        0,
                        initialVariantData(newVariant),
                    );
                }
            });
        });
    }

    function removeVariants(newVariants, oldVariants) {
        resetBulkEditVariantFields();
        notifyVariantsRemoved(oldVariants.length - newVariants.length);

        // Variation single value has been removed
        if (hasCommonVariantUids(newVariants, oldVariants)) {
            const newVariantsUids = newVariants.map(({ uids }) => uids);

            oldVariants.forEach(({ uids }) => {
                if (!newVariantsUids.includes(uids)) {
                    const index = form.variants.findIndex(
                        (variant) => variant.uids === uids,
                    );

                    clearErrors({
                        name: "variants",
                        uid: form.variants[index].uid,
                    });

                    form.variants.splice(index, 1);
                }
            });

            return;
        }

        // A variation with multiple values has been removed
        const matchedUids = [];

        newVariants.forEach(({ uids, name }) => {
            oldVariants.forEach((oldVariant) => {
                const index = form.variants.findIndex(
                    (variant) => variant.uids === oldVariant.uids,
                );
                const doesUidExist = uids
                    .split(".")
                    .every((uids) => oldVariant.uids.split(".").includes(uids));

                if (doesUidExist && !matchedUids.includes(uids)) {
                    matchedUids.push(uids);
                    setVariantData({ uids, name }, index);

                    return;
                }

                if (doesUidExist) {
                    clearErrors({
                        name: "variants",
                        uid: form.variants[index].uid,
                    });

                    form.variants.splice(index, 1);
                }
            });
        });
    }

    function reorderVariants(newVariants, oldVariants) {
        // Reordered variations or variation values
        const newVariantUids = newVariants.map(({ uids }) => uids);

        if (hasCommonVariantUids(newVariants, oldVariants)) {
            oldVariants.forEach(({ uids }) => {
                const index = newVariantUids.indexOf(uids);
                const formIndex = form.variants.findIndex(
                    (variant) => variant.uids === uids,
                );

                // Update variant data before swap
                setVariantData({ name: newVariants[index].name }, formIndex);

                // Swap variant elements
                form.variants[formIndex] = form.variants.splice(
                    index,
                    1,
                    form.variants[formIndex],
                )[0];
            });

            return;
        }

        // A new variation with a single value has been added
        newVariants.forEach((newVariant, index) => {
            setVariantData(newVariant, index);
        });
    }

    function isEqualVariants(newVariants, oldVariants) {
        return (
            newVariants.map(({ uids }) => uids).toString() ===
            oldVariants.map(({ uids }) => uids).toString()
        );
    }

    function hasCommonVariantUids(newVariants, oldVariants) {
        // Check if the old variants UID is present in the new variants
        return oldVariants.some(({ uids }) =>
            newVariants.map(({ uids }) => uids).includes(uids),
        );
    }

    function initialVariantData({ uids, name }) {
        return {
            position: variantPosition.value++,
            uid: md5(uids),
            uids,
            name,
            is_default: false,
            is_selected: false,
            is_active: true,
            is_open: false,
            media: [],
            sku: null,
            price: null,
            special_price: null,
            special_price_type: "fixed",
            special_price_start: null,
            special_price_end: null,
            manage_stock: 0,
            qty: null,
            in_stock: 1,
        };
    }

    function notifyVariantChanges({ count, status }) {
        toaster(
            trans(`product::products.variants.variants_${status}`, {
                count,
                suffix: trans(
                    `product::products.variants.${
                        count > 1 ? "variants" : "variant"
                    }`,
                ),
            }).toLowerCase(),
            {
                type: "default",
            },
        );
    }

    function notifyVariantsCreated(count) {
        notifyVariantChanges({ count, status: "created" });
    }

    function notifyVariantsRemoved(count) {
        notifyVariantChanges({ count, status: "removed" });
    }

    function notifyVariantsReordered() {
        toaster(trans("product::products.variants.variants_reordered"), {
            type: "default",
        });
    }

    return {
        // refs
        defaultVariantUid,
        variantsLength,
        variantPosition,

        // computed
        hasAnyVariant,

        // methods
        setDefaultVariantUid,
        setVariantsLength,
        setVariantsName,
        resetDefaultVariant,
        generateVariants,
        generateNewVariants,
        notifyVariantsReordered,
    };
}
