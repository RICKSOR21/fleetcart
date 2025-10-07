<template>
    <div class="box-body">
        <form
            class="form"
            @input="errors.clear($event.target.name)"
            @submit.prevent
            ref="form"
        >
            <div
                class="row"
                :class="{ 'has-variation-type': !isEmptyVariationType }"
            >
                <div class="col-lg-2 col-sm-2">
                    <h5>{{ trans("variation::variations.group.general") }}</h5>
                </div>

                <div class="col-lg-7 col-sm-10">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="name">
                                    {{ trans("variation::attributes.name") }}
                                    <span class="text-red">*</span>
                                </label>

                                <input
                                    type="text"
                                    name="name"
                                    id="name"
                                    class="form-control"
                                    v-model="form.name"
                                />

                                <span
                                    class="help-block text-red"
                                    v-if="errors.has('name')"
                                    v-text="errors.get('name')"
                                ></span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="type">
                                    {{ trans("variation::attributes.type") }}
                                    <span class="text-red">*</span>
                                </label>

                                <select
                                    name="type"
                                    id="type"
                                    class="form-control custom-select-black"
                                    @change="
                                        changeVariationType($event.target.value)
                                    "
                                    v-model="form.type"
                                >
                                    <option value="">
                                        {{
                                            trans(
                                                "variation::variations.form.variation_types.please_select"
                                            )
                                        }}
                                    </option>

                                    <option value="text">
                                        {{
                                            trans(
                                                "variation::variations.form.variation_types.text"
                                            )
                                        }}
                                    </option>

                                    <option value="color">
                                        {{
                                            trans(
                                                "variation::variations.form.variation_types.color"
                                            )
                                        }}
                                    </option>

                                    <option value="image">
                                        {{
                                            trans(
                                                "variation::variations.form.variation_types.image"
                                            )
                                        }}
                                    </option>
                                </select>

                                <span
                                    class="help-block text-red"
                                    v-if="errors.has('type')"
                                    v-text="errors.get('type')"
                                ></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div v-cloak class="row" v-if="!isEmptyVariationType">
                <div class="col-lg-2 col-sm-2">
                    <h5>{{ trans("variation::variations.group.values") }}</h5>
                </div>

                <div class="col-lg-7 col-sm-10">
                    <div class="variation-values clearfix">
                        <div class="table-responsive">
                            <table
                                class="options table table-bordered table-striped"
                                :class="
                                    form.type !== '' ? `type-${form.type}` : ''
                                "
                            >
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>
                                            {{
                                                trans(
                                                    "variation::variations.form.label"
                                                )
                                            }}
                                            <span class="text-red">*</span>
                                        </th>
                                        <th v-if="form.type === 'color'">
                                            {{
                                                trans(
                                                    "variation::variations.form.color"
                                                )
                                            }}
                                            <span class="text-red">*</span>
                                        </th>
                                        <th v-else-if="form.type === 'image'">
                                            {{
                                                trans(
                                                    "variation::variations.form.image"
                                                )
                                            }}
                                            <span class="text-red">*</span>
                                        </th>
                                        <th></th>
                                    </tr>
                                </thead>

                                <tbody
                                    is="vue:draggable"
                                    tag="tbody"
                                    handle=".drag-handle"
                                    item-key="uid"
                                    animation="150"
                                    :list="form.values"
                                    @end="updateColorThumbnails"
                                >
                                    <template #item="{ element, index }">
                                        <tr class="option-row">
                                            <td class="text-center">
                                                <span class="drag-handle">
                                                    <i class="fa">&#xf142;</i>
                                                    <i class="fa">&#xf142;</i>
                                                </span>
                                            </td>
                                            <td>
                                                <input
                                                    type="text"
                                                    :name="`values.${element.uid}.label`"
                                                    :id="`values-${element.uid}-label`"
                                                    class="form-control"
                                                    @keyup.enter="
                                                        addRowOnPressEnter(
                                                            $event,
                                                            index
                                                        )
                                                    "
                                                    v-model="element.label"
                                                />

                                                <span
                                                    class="help-block text-red"
                                                    v-if="
                                                        errors.has(
                                                            `values.${element.uid}.label`
                                                        )
                                                    "
                                                    v-text="
                                                        errors.get(
                                                            `values.${element.uid}.label`
                                                        )
                                                    "
                                                >
                                                </span>
                                            </td>
                                            <td v-if="form.type === 'color'">
                                                <div>
                                                    <input
                                                        type="text"
                                                        :name="`values.${element.uid}.color`"
                                                        :id="`values-${element.uid}-color`"
                                                        class="form-control color-picker"
                                                        v-model="element.color"
                                                    />
                                                </div>

                                                <span
                                                    class="help-block text-red"
                                                    v-if="
                                                        errors.has(
                                                            `values.${element.uid}.color`
                                                        )
                                                    "
                                                    v-text="
                                                        errors.get(
                                                            `values.${element.uid}.color`
                                                        )
                                                    "
                                                >
                                                </span>
                                            </td>
                                            <td
                                                v-else-if="
                                                    form.type === 'image'
                                                "
                                            >
                                                <div class="d-flex">
                                                    <div
                                                        class="image-holder"
                                                        @click="
                                                            chooseImage(
                                                                index,
                                                                element.uid
                                                            )
                                                        "
                                                    >
                                                        <template
                                                            v-if="
                                                                element.image.id
                                                            "
                                                        >
                                                            <img
                                                                :src="
                                                                    element
                                                                        .image
                                                                        .path
                                                                "
                                                                alt="variation image"
                                                            />
                                                        </template>

                                                        <img
                                                            v-else
                                                            src="@admin/images/placeholder_image.png"
                                                            class="placeholder-image"
                                                            alt="Placeholder Image"
                                                        />
                                                    </div>
                                                </div>

                                                <span
                                                    class="help-block text-red"
                                                    v-if="
                                                        errors.has(
                                                            `values.${element.uid}.image`
                                                        )
                                                    "
                                                    v-text="
                                                        errors.get(
                                                            `values.${element.uid}.image`
                                                        )
                                                    "
                                                >
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <button
                                                    type="button"
                                                    tabindex="-1"
                                                    class="btn btn-default delete-row"
                                                    @click="
                                                        deleteRow(
                                                            index,
                                                            element.uid
                                                        )
                                                    "
                                                >
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </div>

                        <button
                            type="button"
                            class="btn btn-default"
                            @click="addRow"
                        >
                            {{ trans("variation::variations.form.add_row") }}
                        </button>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-7 col-lg-offset-2 col-md-12 text-right">
                    <button
                        type="button"
                        class="btn btn-primary"
                        :class="{
                            'btn-loading': formSubmitting,
                        }"
                        :disabled="formSubmitting"
                        @click="submit"
                    >
                        {{ trans("admin::admin.buttons.save") }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import { toaster } from "@admin/js/Toaster";
import VariationMixin from "../mixins/VariationMixin";

export default {
    mixins: [VariationMixin],

    created() {
        this.setFormDefaultData();
    },

    mounted() {
        this.focusInitialField();
    },

    methods: {
        setFormDefaultData() {
            this.form = {
                uid: this.uid(),
                type: "",
                values: [
                    {
                        uid: this.uid(),
                        image: {
                            id: null,
                            path: null,
                        },
                    },
                ],
            };
        },

        focusInitialField() {
            this.$nextTick(() => {
                $("#name").trigger("focus");
            });
        },

        submit() {
            this.formSubmitting = true;

            axios
                .post("/variations", this.transformData(this.form))
                .then((response) => {
                    toaster(response.data.message, {
                        type: "success",
                    });

                    this.resetForm();
                    this.errors.reset();
                })
                .catch(({ response }) => {
                    toaster(response.data.message, {
                        type: "default",
                    });

                    this.errors.reset();
                    this.errors.record(response.data.errors);
                    this.focusFirstErrorField(this.$refs.form.elements);
                })
                .finally(() => {
                    this.formSubmitting = false;
                });
        },
    },
};
</script>
