import { ref, reactive, nextTick } from "vue";
import { useVariants } from "./useVariants";
import { generateUid } from "@admin/js/functions";
import Errors from "@admin/js/Errors";

const shouldResetForm = ref(0);

let form = reactive(initialFormData());
const errors = reactive(new Errors());
const { variantPosition } = useVariants();

function initialFormData() {
    return {
        name: null,
        description: null,
        brand_id: "",
        categories: [],
        tax_class_id: "",
        tags: [],
        is_virtual: false,
        is_active: true,
        attributes: [],
        variations: [],
        variants: [],
        options: [],
        downloads: [],
        media: [],
        price: null,
        special_price: null,
        special_price_type: "fixed",
        special_price_start: null,
        special_price_end: null,
        sku: null,
        manage_stock: 0,
        qty: null,
        in_stock: 1,
        slug: null,
        meta: {},
        short_description: null,
        new_from: null,
        new_to: null,
        up_sells: [],
        cross_sells: [],
        related_products: [],
    };
}

export function useForm() {
    function prepareFormData(data) {
        prepareAttributes(data);
        prepareVariations(data);
        prepareVariants(data);
        prepareOptions(data);

        return data;
    }

    function prepareAttributes({ attributes }) {
        attributes.forEach((attribute) => {
            attribute.uid = generateUid();
        });
    }

    function prepareVariations({ variations }) {
        variations.forEach((variation) => {
            variation.is_open = false;

            variation.values.forEach((value) => {
                if (!value?.image?.id) {
                    value.image = {
                        id: null,
                        path: null,
                    };
                }
            });
        });
    }

    function prepareVariants({ variants }) {
        variants.forEach((variant) => {
            variant.position = variantPosition.value++;
            variant.is_open = false;
            variant.is_selected = false;
            variant.special_price_start = null;
            variant.special_price_end = null;
        });
    }

    function prepareOptions({ options }) {
        options.forEach((option) => {
            option.uid = generateUid();
            option.is_open = false;

            option.values.forEach((_, valueIndex) => {
                option.values[valueIndex].uid = generateUid();
            });
        });
    }

    function resetForm() {
        shouldResetForm.value++;

        Object.assign(form, initialFormData());
    }

    function hasAnyError({ name, uid }) {
        return Object.keys(errors.errors).some((key) =>
            key.startsWith(`${name}.${uid}`)
        );
    }

    function clearErrors({ name, uid }) {
        clearMatchedErrors(`${name}.${uid}`);
    }

    function clearValuesError({ name, uid }) {
        clearMatchedErrors(`${name}.${uid}.values`);
    }

    function clearValueRowErrors({ name, uid, valueUid }) {
        clearMatchedErrors(`${name}.${uid}.values.${valueUid}`);
    }

    function clearMatchedErrors(str) {
        Object.keys(errors.errors).forEach((key) => {
            if (key.startsWith(str)) {
                errors.clear(key);
            }
        });
    }

    async function focusFirstErrorField(elements) {
        await nextTick(() => {
            const element = [...elements].find(
                (el) => el.name === Object.keys(errors.errors)[0]
            );

            if (element) {
                element.focus();
            }
        });
    }

    async function focusField({ selector, key }) {
        if (key !== undefined) {
            errors.clear(key);
        }

        await nextTick(() => {
            $(`${selector}`).trigger("focus");
        });
    }

    return {
        form,
        prepareFormData,
        resetForm,
        shouldResetForm,
        errors,
        hasAnyError,
        clearErrors,
        clearValuesError,
        clearValueRowErrors,
        focusFirstErrorField,
        focusField,
    };
}
