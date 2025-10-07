<template>
    <div class="box-header">
        <h5>
            {{ trans("product::products.group.seo") }}
        </h5>

        <div class="drag-handle">
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
        </div>
    </div>

    <div class="box-body">
        <div class="form-group row">
            <label for="slug" class="col-sm-12 control-label text-left">
                {{ trans("product::attributes.slug") }}

                <span
                    v-if="window.location.pathname.endsWith('/edit')"
                    class="text-red"
                    >*</span
                >
            </label>

            <div class="col-sm-12">
                <input
                    type="text"
                    name="slug"
                    id="slug"
                    class="form-control"
                    @change="setProductSlug($event.target.value)"
                    v-model="form.slug"
                />

                <span
                    class="help-block text-red"
                    v-if="errors.has('slug')"
                    v-text="errors.get('slug')"
                ></span>
            </div>
        </div>

        <div class="form-group row">
            <label for="meta-title" class="col-sm-12 control-label text-left">
                {{ trans("meta::attributes.meta_title") }}
            </label>

            <div class="col-sm-12">
                <input
                    type="text"
                    name="meta.meta_title"
                    id="meta-title"
                    class="form-control"
                    v-model="form.meta.meta_title"
                />

                <span
                    class="help-block text-red"
                    v-if="errors.has('meta.meta_title')"
                    v-text="errors.get('meta.meta_title')"
                ></span>
            </div>
        </div>

        <div class="form-group row">
            <label
                for="meta-description"
                class="col-sm-12 control-label text-left"
            >
                {{ trans("meta::attributes.meta_description") }}
            </label>

            <div class="col-sm-12">
                <textarea
                    name="meta.meta_description"
                    rows="6"
                    cols="10"
                    id="meta-description"
                    class="form-control"
                    v-model="form.meta.meta_description"
                ></textarea>

                <span
                    class="help-block text-red"
                    v-if="errors.has('meta.meta_description')"
                    v-text="errors.get('meta.meta_description')"
                ></span>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "../composables/useForm";
import { useProductMethods } from "../composables/useProductMethods";

const { form, errors } = useForm();
const { setProductSlug } = useProductMethods();
</script>
