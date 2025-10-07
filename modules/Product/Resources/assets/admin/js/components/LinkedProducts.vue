<template>
    <div class="box">
        <div class="box-header">
            <h5>
                {{ trans("product::products.group.linked_products") }}
            </h5>
        </div>

        <div class="box-body">
            <div class="form-group row">
                <label for="up-sells" class="col-sm-12 control-label text-left">
                    {{ trans("product::attributes.up_sells") }}
                </label>

                <div class="col-sm-12">
                    <select
                        name="up_sells"
                        id="up-sells"
                        multiple
                        v-model="form.up_sells"
                        ref="upSellProductsField"
                    >
                        <option
                            v-for="upSellProduct in upSellProducts"
                            :key="upSellProduct.id"
                            :value="upSellProduct.id"
                        >
                            {{ upSellProduct.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label
                    for="cross-sells"
                    class="col-sm-12 control-label text-left"
                >
                    {{ trans("product::attributes.cross_sells") }}
                </label>

                <div class="col-sm-12">
                    <select
                        name="cross_sells"
                        id="cross-sells"
                        multiple
                        v-model="form.cross_sells"
                        ref="crossSellProductsField"
                    >
                        <option
                            v-for="crossSellProduct in crossSellProducts"
                            :key="crossSellProduct.id"
                            :value="crossSellProduct.id"
                        >
                            {{ crossSellProduct.name }}
                        </option>
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label
                    for="related-products"
                    class="col-sm-12 control-label text-left"
                >
                    {{ trans("product::attributes.related_products") }}
                </label>

                <div class="col-sm-12">
                    <select
                        name="related_products"
                        id="related-products"
                        multiple
                        v-model="form.related_products"
                        ref="relatedProductsField"
                    >
                        <option
                            v-for="relatedProduct in relatedProducts"
                            :key="relatedProduct.id"
                            :value="relatedProduct.id"
                        >
                            {{ relatedProduct.name }}
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from "vue";
import { useForm } from "../composables/useForm";
import { useConfigs } from "../composables/useConfigs";

const upSellProducts = ref(FleetCart.data["up-sell-products"] ?? []);
const upSellProductsField = ref(null);
const crossSellProducts = ref(FleetCart.data["cross-sell-products"]);
const crossSellProductsField = ref(null);
const relatedProducts = ref(FleetCart.data["related-products"]);
const relatedProductsField = ref(null);

const { form, shouldResetForm } = useForm();
const { searchableSelectizeConfig } = useConfigs();

function initUpSellProductsSelectize() {
    $(upSellProductsField.value).selectize({
        ...searchableSelectizeConfig.value,
        onChange: (values) => {
            form.up_sells = values;
        },
    });
}

function initCrossSellProductsSelectize() {
    $(crossSellProductsField.value).selectize({
        ...searchableSelectizeConfig.value,
        onChange: (values) => {
            form.cross_sells = values;
        },
    });
}

function initRelatedProductsSelectize() {
    $(relatedProductsField.value).selectize({
        ...searchableSelectizeConfig.value,
        onChange: (values) => {
            form.related_products = values;
        },
    });
}

function resetFields() {
    $(upSellProductsField.value)[0].selectize.clear();
    $(upSellProductsField.value)[0].selectize.clearOptions();
    $(crossSellProductsField.value)[0].selectize.clear();
    $(crossSellProductsField.value)[0].selectize.clearOptions();
    $(relatedProductsField.value)[0].selectize.clear();
    $(relatedProductsField.value)[0].selectize.clearOptions();
}

watch(shouldResetForm, () => {
    resetFields();
});

onMounted(() => {
    initUpSellProductsSelectize();
    initCrossSellProductsSelectize();
    initRelatedProductsSelectize();
});
</script>
