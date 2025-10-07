import { ref, reactive } from "vue";
import { useForm } from "./useForm";

const bulkEditVariantsUid = ref("");
const bulkEditVariantsField = ref("");

const bulkEditVariants = reactive({ ...bulkEditvariantsDefaultData() });

function bulkEditvariantsDefaultData() {
    return {
        is_active: true,
        media: [],
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

export function useBulkEditVariants() {
    const { form } = useForm();

    function resetBulkEditVariantFields() {
        bulkEditVariantsUid.value = "";

        resetVariantsSelection();
        resetBulkEditVariantsField();
    }

    function resetVariantsSelection() {
        form.variants.forEach((variant) => {
            variant.is_selected = false;
        });
    }

    function resetBulkEditVariantsField() {
        bulkEditVariantsField.value = "";

        resetBulkEditVariants();
    }

    function resetBulkEditVariants() {
        Object.assign(bulkEditVariants, {
            ...bulkEditvariantsDefaultData(),
        });
    }

    return {
        bulkEditVariantsUid,
        bulkEditVariantsField,
        bulkEditVariants,
        resetBulkEditVariantFields,
        resetVariantsSelection,
        resetBulkEditVariantsField,
        resetBulkEditVariants,
    };
}
