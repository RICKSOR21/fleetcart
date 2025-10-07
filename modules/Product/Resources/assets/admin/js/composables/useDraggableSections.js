import { ref, computed, onMounted, nextTick } from "vue";
import { useAttributes } from "./useAttributes";
import { useVariations } from "./useVariations";
import { toaster } from "@admin/js/Toaster";

const isLeftColumnSectionDragging = ref(false);
const isRightColumnSectionDragging = ref(false);

const sections = ref({
    "product-form-left-sections": [],
    "product-form-right-sections": [],
});

export function useDraggableSections() {
    const { initAllAttributeValuesSelectize } = useAttributes();
    const { initVariationsColorPicker } = useVariations();

    function getInitialSectionsOrder(key) {
        return {
            "product-form-left-sections": [
                "attributes",
                "variations",
                "variants",
                "options",
                "downloads",
            ],
            "product-form-right-sections": [
                "media",
                "pricing",
                "inventory",
                "seo",
                "additional",
            ],
        }[key];
    }

    // Load section order from localStorage or fallback to default
    function getSectionsOrder(key) {
        const stored = JSON.parse(localStorage.getItem(key));

        return stored === null ? getInitialSectionsOrder(key) : stored;
    }

    // Save section order to localStorage
    function setSectionsOrder(sortable) {
        const key = sortable.el.dataset.name;

        localStorage.setItem(key, JSON.stringify(sections.value[key]));
    }

    // Computed version of storeSections getter/setter
    const storeSections = computed(() => ({
        get: (sortable) => getSectionsOrder(sortable.el.dataset.name),
        set: (sortable) => setSectionsOrder(sortable),
    }));

    function enableContentSelection() {
        document.body.classList.remove("disable-content-selection");
    }

    function disableContentSelection() {
        document.body.classList.add("disable-content-selection");
    }

    // Notify & refresh attribute/color pickers
    async function notifySectionOrderChange() {
        toaster(trans("product::products.section.order_saved"), {
            type: "default",
        });

        await nextTick(() => {
            initAllAttributeValuesSelectize();
            initVariationsColorPicker();
        });
    }

    onMounted(() => {
        sections.value["product-form-left-sections"] = getSectionsOrder(
            "product-form-left-sections",
        );

        sections.value["product-form-right-sections"] = getSectionsOrder(
            "product-form-right-sections",
        );
    });

    return {
        // refs
        isLeftColumnSectionDragging,
        isRightColumnSectionDragging,
        sections,

        // computed
        storeSections,

        // methods
        enableContentSelection,
        disableContentSelection,
        notifySectionOrderChange,
    };
}
