<template>
    <div class="box">
        <div class="box-header">
            <h5>{{ trans("product::products.group.general") }}</h5>
        </div>

        <div class="box-body">
            <div class="form-group row">
                <label for="name" class="col-sm-12 control-label text-left">
                    {{ trans("product::attributes.name") }}
                    <span class="text-red">*</span>
                </label>

                <div class="col-sm-12">
                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control"
                        v-model="form.name"
                        @change="
                            if (
                                window.location.pathname.endsWith(
                                    'products/create'
                                )
                            ) {
                                setProductSlug($event.target.value);
                            }
                        "
                    />

                    <span
                        class="help-block text-red"
                        v-if="errors.has('name')"
                        v-text="errors.get('name')"
                    ></span>
                </div>
            </div>

            <div class="form-group row">
                <label
                    for="description"
                    class="col-sm-12 control-label text-left"
                    @click="focusEditor"
                >
                    {{ trans("product::attributes.description") }}
                    <span class="text-red">*</span>
                </label>

                <div class="col-sm-12">
                    <textarea
                        name="description"
                        id="description"
                        class="form-control wysiwyg"
                        v-model="form.description"
                    >
                    </textarea>

                    <span
                        class="help-block text-red"
                        v-if="errors.has('description')"
                        v-text="errors.get('description')"
                    ></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="brand-id" class="col-sm-12 control-label text-left">
                    {{ trans("product::attributes.brand_id") }}
                </label>

                <div class="col-sm-6">
                    <select
                        name="brand_id"
                        id="brand-id"
                        class="form-control custom-select-black"
                        v-model="form.brand_id"
                    >
                        <option value="">
                            {{ trans("admin::admin.form.please_select") }}
                        </option>

                        <option
                            v-for="(brand, index) in brands"
                            :key="index"
                            :value="brand.value"
                        >
                            {{ brand.name }}
                        </option>
                    </select>

                    <span
                        class="help-block text-red"
                        v-if="errors.has('brand_id')"
                        v-text="errors.get('brand_id')"
                    ></span>
                </div>
            </div>

            <div class="form-group row">
                <label
                    for="categories"
                    class="col-sm-12 control-label text-left"
                >
                    {{ trans("product::attributes.categories") }}
                </label>

                <div class="col-sm-6">
                    <select
                        name="categories"
                        id="categories"
                        multiple
                        v-model="form.categories"
                        ref="categoriesField"
                    >
                        <option
                            v-for="(category, index) in categories"
                            :key="index"
                            :value="category.value"
                        >
                            {{ category.name }}
                        </option>
                    </select>

                    <span
                        class="help-block text-red"
                        v-if="errors.has('categories')"
                        v-text="errors.get('categories')"
                    ></span>
                </div>
            </div>

            <div class="form-group row">
                <label
                    for="tax-class-id"
                    class="col-sm-12 control-label text-left"
                >
                    {{ trans("product::attributes.tax_class_id") }}
                </label>

                <div class="col-sm-6">
                    <select
                        name="tax_class_id"
                        id="tax-class-id"
                        class="form-control custom-select-black"
                        v-model="form.tax_class_id"
                    >
                        <option value="">
                            {{ trans("admin::admin.form.please_select") }}
                        </option>

                        <option
                            v-for="(taxClass, index, key) in taxClasses"
                            :key="key"
                            :value="index"
                        >
                            {{ taxClass }}
                        </option>
                    </select>

                    <span
                        class="help-block text-red"
                        v-if="errors.has('tax_class_id')"
                        v-text="errors.get('tax_class_id')"
                    ></span>
                </div>
            </div>

            <div class="form-group row">
                <label for="tags" class="col-sm-12 control-label text-left">
                    {{ trans("product::attributes.tags") }}
                </label>

                <div class="col-sm-6">
                    <select
                        name="tags"
                        id="tags"
                        multiple
                        v-model="form.tags"
                        ref="tagsField"
                    >
                        <option
                            v-for="(tag, index) in tags"
                            :key="index"
                            :value="tag.value"
                        >
                            {{ tag.name }}
                        </option>
                    </select>

                    <span
                        class="help-block text-red"
                        v-if="errors.has('tags')"
                        v-text="errors.get('tags')"
                    ></span>
                </div>
            </div>

            <div class="form-group row">
                <label
                    for="is_virtual"
                    class="col-sm-12 control-label text-left"
                >
                    {{ trans("product::attributes.is_virtual") }}
                </label>

                <div class="col-sm-6">
                    <div class="switch">
                        <input
                            type="checkbox"
                            name="is_virtual"
                            id="is-virtual"
                            v-model="form.is_virtual"
                        />

                        <label
                            for="is-virtual"
                            v-html="
                                trans(
                                    'product::products.form.the_product_won\'t_be_shipped'
                                )
                            "
                        >
                        </label>
                    </div>

                    <span
                        class="help-block text-red"
                        v-if="errors.has('is_virtual')"
                        v-text="errors.get('is_virtual')"
                    ></span>
                </div>
            </div>

            <div class="form-group row">
                <label
                    for="is-active"
                    class="col-sm-12 control-label text-left"
                >
                    {{ trans("product::attributes.is_active") }}
                </label>

                <div class="col-sm-9">
                    <div class="switch">
                        <input
                            type="checkbox"
                            name="is_active"
                            id="is-active"
                            v-model="form.is_active"
                        />

                        <label for="is-active">
                            {{
                                trans(
                                    "product::products.form.enable_the_product"
                                )
                            }}
                        </label>

                        <span
                            class="help-block text-red"
                            v-if="errors.has('is_active')"
                            v-text="errors.get('is_active')"
                        ></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import { useForm } from "../composables/useForm";
import { useProductMethods } from "../composables/useProductMethods";
import tinyMCE from "@admin/js/wysiwyg";

const textEditor = ref(null);
const brands = ref(FleetCart.data["brands"] ?? {});
const categories = ref(FleetCart.data["categories"] ?? {});
const categoriesField = ref(null);
const taxClasses = FleetCart.data["tax-classes"] ?? {};
const tags = ref(FleetCart.data["tags"] ?? {});
const tagsField = ref(null);

const { form, shouldResetForm, errors, focusField } = useForm();
const { setProductSlug } = useProductMethods();

function focusEditor() {
    textEditor.value.get("description").focus();
}

function initTextEditor() {
    textEditor.value = tinyMCE({
        setup: (editor) => {
            editor.on("change", () => {
                editor.save();
                editor.getElement().dispatchEvent(new Event("input"));

                errors.clear("description");
            });
        },
    });
}

function initCategoriesSelectize() {
    $(categoriesField.value).selectize({
        plugins: ["remove_button"],
        delimiter: ",",
        persist: true,
        selectOnTab: true,
        hideSelected: false,
        allowEmptyOption: true,
        onChange: (values) => {
            form.categories = values;
        },
        onItemAdd(value) {
            this.getItem(value)[0].innerHTML = this.getItem(
                value
            )[0].innerHTML.replace(/¦––\s/g, "");
        },
        onItemRemove(value) {
            const element = [...this.$dropdown_content.children()].find(
                (el) => el.getAttribute("data-value") === value
            );

            if (element) {
                element.classList.remove("selected");
            }
        },
        onInitialize() {
            $("#categories")
                .next()
                .find("[data-value]")
                .each((_, el) => {
                    $(el).html(
                        $(el).text().slice(0, -1).replace(/¦––\s/g, "") +
                            '<a href="javascript:void(0)" class="remove" tabindex="-1">×</a>'
                    );
                });
        },
    });
}

function initTagsSelectize() {
    $(tagsField.value).selectize({
        plugins: ["remove_button"],
        delimiter: ",",
        persist: true,
        selectOnTab: true,
        hideSelected: true,
        allowEmptyOption: true,
        onChange: (values) => {
            form.tags = values;
        },
    });
}

function resetFields() {
    textEditor.value.get("description").setContent("");
    textEditor.value.get("description").execCommand("mceCancel");

    $(categoriesField.value)[0].selectize.clear();
    $(tagsField.value)[0].selectize.clear();

    [
        ...$(categoriesField.value)[0].selectize.$dropdown_content.children(),
    ].forEach((el) => {
        if (el.classList.contains("selected")) {
            el.classList.remove("selected");
        }
    });
}

watch(shouldResetForm, () => {
    resetFields();

    focusField({
        selector: "#name",
    });
});

onMounted(() => {
    initTextEditor();
    initCategoriesSelectize();
    initTagsSelectize();
});
</script>
