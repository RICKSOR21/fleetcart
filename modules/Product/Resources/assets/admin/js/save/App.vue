<template>
    <form
        class="product-form"
        @input="errors.clear($event.target.name)"
        @submit.prevent
        ref="productForm"
    >
        <div class="row">
            <div class="product-form-left-column col-lg-8 col-md-12">
                <General />

                <draggable
                    animation="150"
                    class="product-form-column"
                    :class="{ dragging: isLeftColumnSectionDragging }"
                    data-name="product-form-left-sections"
                    force-fallback="true"
                    item-key="element"
                    handle=".drag-handle"
                    :list="sections['product-form-left-sections']"
                    :store="storeSections"
                    @start="disableContentSelection"
                    @end="enableContentSelection"
                    @choose="isLeftColumnSectionDragging = true"
                    @unchoose="isLeftColumnSectionDragging = false"
                    @change="notifySectionOrderChange"
                >
                    <template #item="{ element: section }">
                        <div class="box">
                            <template v-if="section === 'attributes'">
                                <Attributes />
                            </template>

                            <template v-else-if="section === 'variations'">
                                <Variations />
                            </template>

                            <template v-else-if="section === 'variants'">
                                <Variants />
                            </template>

                            <template v-else-if="section === 'options'">
                                <Options />
                            </template>

                            <template v-else-if="section === 'downloads'">
                                <Downloads />
                            </template>
                        </div>
                    </template>
                </draggable>
            </div>

            <div class="product-form-right-column col-lg-4 col-md-12">
                <draggable
                    animation="150"
                    class="product-form-column"
                    :class="{ dragging: isRightColumnSectionDragging }"
                    data-name="product-form-right-sections"
                    force-fallback="true"
                    item-key="element"
                    handle=".drag-handle"
                    :list="sections['product-form-right-sections']"
                    :store="storeSections"
                    @start="disableContentSelection"
                    @end="enableContentSelection"
                    @choose="isRightColumnSectionDragging = true"
                    @unchoose="isRightColumnSectionDragging = false"
                    @change="notifySectionOrderChange"
                >
                    <template #item="{ element: section }">
                        <div class="box">
                            <template v-if="section === 'media'">
                                <Media />
                            </template>

                            <template v-if="section === 'pricing'">
                                <Pricing />
                            </template>

                            <template v-else-if="section === 'inventory'">
                                <Inventory />
                            </template>

                            <template v-else-if="section === 'seo'">
                                <Seo />
                            </template>

                            <template v-else-if="section === 'additional'">
                                <Additional />
                            </template>
                        </div>
                    </template>
                </draggable>

                <LinkedProducts />
            </div>
        </div>

        <div class="page-form-footer">
            <button
                type="button"
                class="btn btn-default"
                :class="{ 'btn-loading': formSubmissionType === 'save' }"
                :disabled="formSubmissionType"
                @click="submit({ submissionType: 'save' })"
            >
                {{ trans("product::products.save") }}
            </button>

            <button
                type="button"
                class="btn btn-primary"
                :class="{
                    'btn-loading': formSubmissionType === 'save_and_exit',
                }"
                :disabled="formSubmissionType"
                @click="submit({ submissionType: 'save_and_exit' })"
                v-html="trans('product::products.save_and_exit')"
            ></button>
        </div>
    </form>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from "vue";
import { useForm } from "../composables/useForm";
import { useAttributes } from "../composables/useAttributes";
import { useVariations } from "../composables/useVariations";
import { useBulkEditVariants } from "../composables/useBulkEditVariants";
import { useVariants } from "../composables/useVariants";
import { useDraggableSections } from "../composables/useDraggableSections";
import { toaster } from "@admin/js/Toaster";
import { nprogress } from "@admin/js/NProgress";
import { fullscreenMode } from "@admin/js/functions";
import draggable from "vuedraggable";
import ProductTransformer from "../transformers/ProductTransformer";
import General from "../components/General.vue";
import Attributes from "../components/Attributes.vue";
import Variations from "../components/Variations.vue";
import Variants from "../components/Variants.vue";
import Options from "../components/Options.vue";
import Downloads from "../components/Downloads.vue";
import Media from "../components/Media.vue";
import Pricing from "../components/Pricing.vue";
import Inventory from "../components/Inventory.vue";
import Seo from "../components/Seo.vue";
import Additional from "../components/Additional.vue";
import LinkedProducts from "../components/LinkedProducts.vue";

const productForm = ref(null);
const formSubmissionType = ref(null);

const { form, prepareFormData, resetForm, errors, focusFirstErrorField } =
    useForm();
const { initAllAttributeValuesSelectize, destroyAllAttributeValuesSelectize } =
    useAttributes();
const { regenerateVariationsAndVariantsUid, initVariationsColorPicker } =
    useVariations();
const { resetBulkEditVariantFields } = useBulkEditVariants();
const {
    hasAnyVariant,
    setDefaultVariantUid,
    setVariantsLength,
    setVariantsName,
} = useVariants();
const {
    isLeftColumnSectionDragging,
    isRightColumnSectionDragging,
    sections,
    storeSections,
    enableContentSelection,
    disableContentSelection,
    notifySectionOrderChange,
} = useDraggableSections();

// Computer Properties
const actionUrl = computed(() =>
    form.id ? `/products/${form.id}` : "/products"
);

const methodAction = computed(() => (form.id ? "PUT" : "POST"));

if (FleetCart.data["product"]) {
    Object.assign(form, prepareFormData(FleetCart.data["product"]));
}

setDefaultVariantUid();
setVariantsLength();

function hideAlertExitFlash() {
    const alertExitFlash = $(".alert-exit-flash");

    if (alertExitFlash.length !== 0) {
        setTimeout(() => {
            alertExitFlash.remove();
        }, 3000);
    }
}

async function submit({ submissionType }) {
    formSubmissionType.value = submissionType;

    const transformer = new ProductTransformer();
    const payload = transformer.transform(form);

    try {
        const { data } = await axios.request({
            url: actionUrl.value,
            method: methodAction.value,
            data: payload,
        });

        toaster(data.message, {
            type: "success",
        });

        if (!form.id) {
            resetForm();

            return;
        }

        destroyAllAttributeValuesSelectize();

        Object.assign(form, { ...data.product_resource });

        errors.reset();
        
        prepareFormData(form);
        resetBulkEditVariantFields();

        if (hasAnyVariant.value) {
            setVariantsName();
        }

        await nextTick(() => {
            initAllAttributeValuesSelectize();
            initVariationsColorPicker();
        });
    } catch ({ response }) {
        formSubmissionType.value = null;

        toaster(response.data.message, {
            type: "default",
        });

        if (response.status === 422) {
            errors.reset();
            errors.record(response.data.errors);

            focusFirstErrorField(productForm.value.elements);

            return;
        }

        if (hasAnyVariant.value) {
            regenerateVariationsAndVariantsUid();
        }
    } finally {
        if (submissionType === "save") {
            formSubmissionType.value = null;
        }
    }
}

onMounted(async () => {
    nprogress();
    fullscreenMode();
    hideAlertExitFlash();

    await nextTick(() => {
        initAllAttributeValuesSelectize();
    });
});
</script>
