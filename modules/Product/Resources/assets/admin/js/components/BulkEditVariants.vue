<template>
    <div class="bulk-edit-variants overflow-hidden">
        <div class="form-group row">
            <label
                for="variation-values-list"
                class="col-sm-3 control-label text-left"
            >
                {{ trans("product::products.form.variants.bulk_edit") }}
            </label>

            <div class="col-sm-5">
                <select
                    name="variation_values_list"
                    id="variation-values-list"
                    class="form-control custom-select-black"
                    @change="changeBulkEditVariantsUid($event.target.value)"
                    v-model="bulkEditVariantsUid"
                >
                    <option value="">
                        {{ trans("admin::admin.form.please_select") }}
                    </option>
                    <option value="all">
                        {{
                            trans(
                                "product::products.form.variants.all_variants",
                            )
                        }}
                    </option>

                    <template
                        v-for="(variation, index) in form.variations"
                        :key="index"
                    >
                        <template
                            v-for="(value, valueIndex) in variation.values"
                            :key="valueIndex"
                        >
                            <option
                                v-if="
                                    variation.type !== '' &&
                                    Boolean(value.label)
                                "
                                :key="value.uid"
                                :value="value.uid"
                            >
                                {{ value.label }}
                            </option>
                        </template>
                    </template>
                </select>
            </div>
        </div>

        <div v-if="hasBulkEditVariantsUid" class="form-group row">
            <label
                for="bulk-edit-variants-field-type"
                class="col-sm-3 control-label text-left"
            >
                {{ trans("product::products.form.variants.field_type") }}
            </label>

            <div class="col-sm-5">
                <select
                    name="bulk_edit_variants_field_type"
                    id="bulk-edit-variants-field-type"
                    class="form-control custom-select-black"
                    @change="changeBulkEditVariantsField($event.target.value)"
                    v-model="bulkEditVariantsField"
                >
                    <option value="">
                        {{ trans("admin::admin.form.please_select") }}
                    </option>
                    <option value="is_active">
                        {{ trans("product::products.form.variants.is_active") }}
                    </option>
                    <option value="media">
                        {{ trans("product::products.form.variants.media") }}
                    </option>
                    <option value="sku">
                        {{ trans("product::products.form.variants.sku") }}
                    </option>
                    <option value="price">
                        {{ trans("product::products.form.variants.price") }}
                    </option>
                    <option value="special_price">
                        {{
                            trans(
                                "product::products.form.variants.special_price",
                            )
                        }}
                    </option>
                    <option value="manage_stock">
                        {{
                            trans(
                                "product::products.form.variants.manage_stock",
                            )
                        }}
                    </option>
                    <option value="in_stock">
                        {{ trans("product::products.form.variants.in_stock") }}
                    </option>
                </select>
            </div>
        </div>

        <template v-if="hasBulkEditVariantsUid && hasBulkEditVariantsField">
            <div
                v-if="bulkEditVariantsField === 'is_active'"
                class="form-group row"
            >
                <label
                    for="bulk-edit-variants-is-active"
                    class="col-sm-3 control-label text-left"
                >
                    {{ trans("product::products.form.variants.is_active") }}
                </label>

                <div class="col-sm-5">
                    <div class="checkbox">
                        <input
                            type="checkbox"
                            name="bulk_edit_variants_is_active"
                            id="bulk-edit-variants-is-active"
                            v-model="bulkEditVariants.is_active"
                        />

                        <label for="bulk-edit-variants-is-active">
                            {{
                                trans(
                                    "product::products.form.variants.enable_the_variants",
                                )
                            }}
                        </label>
                    </div>
                </div>
            </div>

            <div
                v-else-if="bulkEditVariantsField === 'media'"
                class="form-group row"
            >
                <label class="col-sm-3 control-label text-left">
                    {{ trans("product::products.form.variants.media") }}
                </label>

                <div class="col-sm-5">
                    <draggable
                        animation="200"
                        class="product-media-grid"
                        handle=".handle"
                        item-key="index"
                        :list="bulkEditVariants.media"
                    >
                        <template #item="{ element: media, index }">
                            <div class="media-grid-item handle">
                                <div class="image-holder">
                                    <img
                                        :src="media.path"
                                        alt="product variants media"
                                    />

                                    <button
                                        type="button"
                                        class="btn remove-image"
                                        @click="
                                            removeBulkEditVariantsMedia(index)
                                        "
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M6.00098 17.9995L17.9999 6.00053"
                                                stroke="#292D32"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M17.9999 17.9995L6.00098 6.00055"
                                                stroke="#292D32"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <template #footer>
                            <div
                                class="media-grid-item media-picker disabled"
                                @click="addBulkEditVariantsMedia"
                            >
                                <div class="image-holder">
                                    <img
                                        src="@admin/images/placeholder_image.png"
                                        class="placeholder-image"
                                        alt="Placeholder Image"
                                    />
                                </div>
                            </div>
                        </template>
                    </draggable>
                </div>
            </div>

            <div
                v-else-if="bulkEditVariantsField === 'sku'"
                class="form-group row"
            >
                <label
                    for="bulk-edit-variants-sku"
                    class="col-sm-3 control-label text-left"
                >
                    {{ trans("product::products.form.variants.sku") }}
                </label>

                <div class="col-sm-5">
                    <input
                        type="text"
                        name="bulk_edit_variants_sku"
                        id="bulk-edit-variants-sku"
                        class="form-control"
                        v-model="bulkEditVariants.sku"
                    />
                </div>
            </div>

            <div
                v-else-if="bulkEditVariantsField === 'price'"
                class="form-group row"
            >
                <label
                    for="bulk-edit-variants-price"
                    class="col-sm-3 control-label text-left"
                >
                    {{ trans("product::products.form.variants.price") }}
                </label>

                <div class="col-sm-5">
                    <div class="input-group">
                        <span class="input-group-addon">
                            {{ defaultCurrencySymbol }}
                        </span>

                        <input
                            type="number"
                            name="bulk_edit_variants_price"
                            min="0"
                            step="0.1"
                            id="bulk-edit-variants-price"
                            class="form-control"
                            @wheel="$event.target.blur()"
                            v-model.number="bulkEditVariants.price"
                        />
                    </div>
                </div>
            </div>

            <template v-else-if="bulkEditVariantsField === 'special_price'">
                <div class="form-group row">
                    <label
                        for="bulk-edit-variants-special-price"
                        class="col-sm-3 control-label text-left"
                    >
                        {{
                            trans(
                                "product::products.form.variants.special_price",
                            )
                        }}
                    </label>

                    <div class="col-sm-5">
                        <div class="input-group">
                            <span class="input-group-addon">
                                {{
                                    bulkEditVariants.special_price_type ===
                                    "fixed"
                                        ? defaultCurrencySymbol
                                        : "%"
                                }}
                            </span>

                            <input
                                type="number"
                                name="bulk_edit_variants_special_price"
                                min="0"
                                step="0.1"
                                id="bulk-edit-variants-special-price"
                                class="form-control"
                                @wheel="$event.target.blur()"
                                v-model="bulkEditVariants.special_price"
                            />
                        </div>
                    </div>
                </div>

                <div class="form-group row">
                    <label
                        for="bulk-edit-variants-special-price-type"
                        class="col-sm-3 control-label text-left"
                    >
                        {{
                            trans(
                                "product::products.form.variants.special_price_type",
                            )
                        }}
                    </label>

                    <div class="col-sm-5">
                        <select
                            name="bulk_edit_variants_special_price_type"
                            id="bulk-edit-variants-special-price-type"
                            class="form-control custom-select-black"
                            v-model="bulkEditVariants.special_price_type"
                        >
                            <option value="fixed">
                                {{
                                    trans(
                                        "product::products.form.variants.special_price_types.fixed",
                                    )
                                }}
                            </option>

                            <option value="percent">
                                {{
                                    trans(
                                        "product::products.form.variants.special_price_types.percent",
                                    )
                                }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label
                        for="bulk-edit-variants-special-price-start"
                        class="col-sm-3 control-label text-left"
                    >
                        {{
                            trans(
                                "product::products.form.variants.special_price_start",
                            )
                        }}
                    </label>

                    <div class="col-sm-5">
                        <flat-pickr
                            name="bulk_edit_variants_special_price_start"
                            id="bulk-edit-variants-special-price-start"
                            class="form-control"
                            :config="flatPickrConfig"
                            v-model="bulkEditVariants.special_price_start"
                        >
                        </flat-pickr>
                    </div>
                </div>

                <div class="form-group row">
                    <label
                        for="bulk-edit-variants-special-price-end"
                        class="col-sm-3 control-label text-left"
                    >
                        {{
                            trans(
                                "product::products.form.variants.special_price_end",
                            )
                        }}
                    </label>

                    <div class="col-sm-5">
                        <flat-pickr
                            name="bulk_edit_variants_special_price_end"
                            id="bulk-edit-variants-special-price-end"
                            class="form-control"
                            :config="flatPickrConfig"
                            v-model="bulkEditVariants.special_price_end"
                        >
                        </flat-pickr>
                    </div>
                </div>
            </template>

            <template v-else-if="bulkEditVariantsField === 'manage_stock'">
                <div class="form-group row">
                    <label
                        for="bulk-edit-variants-manage-stock"
                        class="col-sm-3 control-label text-left"
                    >
                        {{
                            trans(
                                "product::products.form.variants.manage_stock",
                            )
                        }}
                    </label>

                    <div class="col-sm-5">
                        <select
                            name="bulk_edit_variants_manage_stock`"
                            id="bulk-edit-variants-manage-stock"
                            class="form-control custom-select-black"
                            @change="
                                focusField({
                                    selector: '#bulk-edit-variants-qty',
                                })
                            "
                            v-model="bulkEditVariants.manage_stock"
                        >
                            <option
                                value="0"
                                v-html="
                                    trans(
                                        'product::products.form.variants.manage_stock_states.0',
                                    )
                                "
                            ></option>

                            <option value="1">
                                {{
                                    trans(
                                        "product::products.form.variants.manage_stock_states.1",
                                    )
                                }}
                            </option>
                        </select>
                    </div>
                </div>

                <div
                    v-if="bulkEditVariants.manage_stock == 1"
                    class="form-group row"
                >
                    <label
                        for="bulk-edit-variants-qty"
                        class="col-sm-3 control-label text-left"
                    >
                        {{ trans("product::products.form.variants.qty") }}
                    </label>

                    <div class="col-sm-5">
                        <input
                            type="number"
                            name="bulk_edit_variants_qty"
                            min="0"
                            step="1"
                            id="bulk-edit-variants-qty"
                            class="form-control"
                            @wheel="$event.target.blur()"
                            v-model.number="bulkEditVariants.qty"
                        />
                    </div>
                </div>
            </template>

            <div
                v-else-if="bulkEditVariantsField === 'in_stock'"
                class="form-group row"
            >
                <label
                    for="bulk-edit-variants-in-stock`"
                    class="col-sm-3 control-label text-left"
                >
                    {{ trans("product::products.form.variants.in_stock") }}
                </label>

                <div class="col-sm-5">
                    <select
                        name="bulk_edit_variants_in_stock`"
                        id="bulk-edit-variants-in-stock`"
                        class="form-control custom-select-black"
                        v-model="bulkEditVariants.in_stock"
                    >
                        <option value="0">
                            {{
                                trans(
                                    "product::products.form.variants.stock_availability_states.0",
                                )
                            }}
                        </option>

                        <option value="1">
                            {{
                                trans(
                                    "product::products.form.variants.stock_availability_states.1",
                                )
                            }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-sm-5 col-sm-offset-3">
                    <button
                        type="button"
                        class="btn btn-default"
                        @click="bulkUpdateVariants"
                    >
                        {{ trans("product::products.variants.apply") }}
                    </button>
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { computed, watch } from "vue";
import { useForm } from "../composables/useForm";
import { useBulkEditVariants } from "../composables/useBulkEditVariants";
import { toaster } from "@admin/js/Toaster";
import draggable from "vuedraggable";
import flatPickr from "vue-flatpickr-component";

const { form, shouldResetForm, errors, focusField } = useForm();
const {
    bulkEditVariantsUid,
    bulkEditVariantsField,
    bulkEditVariants,
    resetBulkEditVariantFields,
    resetVariantsSelection,
    resetBulkEditVariantsField,
    resetBulkEditVariants,
} = useBulkEditVariants();

const hasBulkEditVariantsUid = computed(() => bulkEditVariantsUid.value !== "");

const hasBulkEditVariantsField = computed(
    () => bulkEditVariantsField.value !== "",
);

function changeBulkEditVariantsUid(uid) {
    resetVariantsSelection();

    if (uid === "") {
        resetBulkEditVariantsField();

        return;
    }

    selectVariants(uid);
}

function selectVariants(uid) {
    resetVariantsSelection();

    if (uid === "") return;

    if (uid !== "all") {
        selectSpecificVariants(uid);

        return;
    }

    selectAllVariants();
}

function selectAllVariants() {
    form.variants.forEach((variant) => {
        variant.is_selected = true;
    });
}

function selectSpecificVariants(uid) {
    form.variants.forEach((variant) => {
        if (variant.uids.includes(uid)) {
            variant.is_selected = true;
        }
    });
}

function changeBulkEditVariantsField(fieldName) {
    const FOCUSABLE_FIELD_NAMES = ["sku", "price", "special_price"];

    if (FOCUSABLE_FIELD_NAMES.includes(fieldName)) {
        focusField({
            selector: `#bulk-edit-variants-${fieldName.replace(/_/g, "-")}`,
        });
    }

    resetBulkEditVariants();
}

function addBulkEditVariantsMedia() {
    const picker = new MediaPicker({ type: "image", multiple: true });

    picker.on("select", ({ id, path }) => {
        bulkEditVariants.media.push({
            id: +id,
            path,
        });
    });
}

function removeBulkEditVariantsMedia(index) {
    bulkEditVariants.media.splice(index, 1);
}

function clearVariantsSpecialPriceErrors(uid) {
    Object.keys(errors).forEach((key) => {
        if (
            key.startsWith(`variants.${uid}`) &&
            key.includes("special_price")
        ) {
            errors.clear(key);
        }
    });
}

function updateVariantsField(variant, { key, value }) {
    variant[key] = value;

    errors.clear(`variants.${variant.uid}.${key}`);
}

function updateVariantsStatus(variant, { key, value }) {
    if (variant.is_default === true) return;

    variant[key] = value;

    errors.clear(`variants.${variant.uid}.${key}`);
}

function updateVariantsSpecialPrice(
    variant,
    { key, value },
    { special_price_type, special_price_start, special_price_end },
) {
    variant[key] = value;
    variant.special_price_type = special_price_type;
    variant.special_price_start = special_price_start;
    variant.special_price_end = special_price_end;

    clearVariantsSpecialPriceErrors(variant.uid);
}

function updateVariantsManageStock(variant, { key, value }, { qty }) {
    variant[key] = value;
    variant.qty = qty;

    errors.clear([
        `variants.${variant.uid}.${key}`,
        `variants.${variant.uid}.qty`,
    ]);
}

function callUpdateVariantsMethodByField(key) {
    return {
        media: updateVariantsField,
        sku: updateVariantsField,
        is_active: updateVariantsStatus,
        price: updateVariantsField,
        special_price: updateVariantsSpecialPrice,
        manage_stock: updateVariantsManageStock,
        in_stock: updateVariantsField,
    }[key];
}

function updateVariants(field) {
    form.variants.forEach((variant) => {
        if (variant.is_selected) {
            callUpdateVariantsMethodByField(field.key)(
                variant,
                field,
                bulkEditVariants,
            );
        }
    });
}

function bulkUpdateVariants() {
    if (!hasBulkEditVariantsUid && !hasBulkEditVariantsField) {
        return;
    }

    const field = {
        key: bulkEditVariantsField.value,
        value: bulkEditVariants[bulkEditVariantsField.value],
    };

    updateVariants(field);
    resetBulkEditVariantFields();

    toaster(trans("product::products.variants.bulk_variants_updated"), {
        type: "default",
    });
}

watch(shouldResetForm, () => {
    resetBulkEditVariantFields();
});
</script>
