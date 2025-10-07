<template>
    <div class="box-header">
        <h5>
            {{ trans("product::products.group.attributes") }}
        </h5>

        <div class="drag-handle">
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
        </div>
    </div>

    <div class="box-body">
        <div id="product-attributes-wrapper">
            <div class="table-responsive">
                <table class="options table table-bordered">
                    <thead class="hidden-xs">
                        <tr>
                            <th></th>
                            <th>
                                {{
                                    trans(
                                        "product::products.attributes.attribute"
                                    )
                                }}
                            </th>
                            <th>
                                {{
                                    trans("product::products.attributes.values")
                                }}
                            </th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody
                        is="vue:draggable"
                        animation="150"
                        handle=".drag-handle"
                        force-fallback="true"
                        item-key="uid"
                        tag="tbody"
                        @start="disableContentSelection"
                        @end="enableContentSelection"
                        :list="form.attributes"
                    >
                        <template #item="{ element: attribute, index }">
                            <tr>
                                <td class="text-center">
                                    <span class="drag-handle">
                                        <i class="fa">&#xf142;</i>
                                        <i class="fa">&#xf142;</i>
                                    </span>
                                </td>
                                <td>
                                    <div class="form-group row">
                                        <label
                                            :for="`attributes-${attribute.uid}-attribute-id`"
                                            class="visible-xs"
                                        >
                                            {{
                                                trans(
                                                    "product::products.attributes.attribute"
                                                )
                                            }}
                                        </label>

                                        <select
                                            :name="`attributes.${attribute.uid}.attribute_id`"
                                            :id="`attributes-${attribute.uid}-attribute-id`"
                                            class="form-control attribute custom-select-black"
                                            @change="changeAttribute(attribute)"
                                            v-model.number="
                                                attribute.attribute_id
                                            "
                                        >
                                            <option value="">
                                                {{
                                                    trans(
                                                        "admin::admin.form.please_select"
                                                    )
                                                }}
                                            </option>

                                            <optgroup
                                                v-for="attributeSet in attributeSets"
                                                :key="attributeSet.id"
                                                :label="attributeSet.name"
                                            >
                                                <option
                                                    v-for="attribute in attributeSet.attributes"
                                                    :key="attribute.id"
                                                    :value="attribute.id"
                                                >
                                                    {{ attribute.name }}
                                                </option>
                                            </optgroup>
                                        </select>

                                        <span
                                            class="help-block text-red"
                                            v-if="
                                                errors.has(
                                                    `attributes.${attribute.uid}.attribute_id`
                                                )
                                            "
                                            v-text="
                                                errors.get(
                                                    `attributes.${attribute.uid}.attribute_id`
                                                )
                                            "
                                        >
                                        </span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group row">
                                        <label
                                            :for="`attributes-${attribute.uid}-values`"
                                            class="visible-xs"
                                        >
                                            {{
                                                trans(
                                                    "product::products.attributes.values"
                                                )
                                            }}
                                        </label>

                                        <select
                                            :name="`attributes.${attribute.uid}.values`"
                                            :id="`attributes-${attribute.uid}-values`"
                                            @input="
                                                clearValuesError({
                                                    name: 'attributes',
                                                    uid: attribute.uid,
                                                })
                                            "
                                            multiple
                                            v-model="attribute.values"
                                        ></select>

                                        <span
                                            class="help-block text-red"
                                            v-if="
                                                errors.has(
                                                    `attributes.${attribute.uid}.values`
                                                )
                                            "
                                            v-text="
                                                errors.get(
                                                    `attributes.${attribute.uid}.values`
                                                )
                                            "
                                        >
                                        </span>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <button
                                        type="button"
                                        class="btn btn-default delete-row"
                                        @click="
                                            deleteAttribute(
                                                index,
                                                attribute.uid
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

            <button type="button" class="btn btn-default" @click="addAttribute">
                {{ trans("product::products.attributes.add_attribute") }}
            </button>
        </div>
    </div>
</template>

<script setup>
import { watch, nextTick } from "vue";
import { useForm } from "../composables/useForm";
import { useAttributes } from "../composables/useAttributes";
import { useDraggableSections } from "../composables/useDraggableSections";
import { generateUid } from "@admin/js/functions";
import draggable from "vuedraggable";

const { form, errors, clearErrors, clearValuesError } = useForm();
const { attributeSets, getAttributeValuesById } = useAttributes();
const { enableContentSelection, disableContentSelection } =
    useDraggableSections();

function changeAttribute(attribute) {
    const attributeValuesSelectize = $(`#attributes-${attribute.uid}-values`)[0]
        .selectize;

    attributeValuesSelectize.clear();
    attributeValuesSelectize.clearOptions();

    if (attribute.attribute_id) {
        getAttributeValuesById(attribute.attribute_id).forEach(
            ({ id, value }) => {
                attributeValuesSelectize.addOption({
                    value: id,
                    text: value,
                });
            }
        );

        attributeValuesSelectize.focus();
    }
}

async function initAttributeValuesSelectize(uid) {
    await nextTick(() => {
        $(`#attributes-${uid}-values`).selectize({
            plugins: ["remove_button"],
            onChange: (values) => {
                const attribute = form.attributes.find(
                    (attribute) => attribute.uid === uid
                );

                attribute.values = values;

                clearErrors({ name: "attributes", uid });
            },
        });
    });
}

function addAttribute() {
    const uid = generateUid();

    form.attributes.push({
        attribute_id: "",
        uid,
        values: [],
    });

    initAttributeValuesSelectize(uid);
}

function deleteAttribute(index, uid) {
    $(`#attributes-${uid}-values`)[0].selectize.destroy();

    form.attributes.splice(index, 1);

    clearErrors({ name: "attributes", uid });
}

watch(
    () => form.attributes,
    (newValue) => {
        if (newValue.length === 0) {
            addAttribute();
        }
    },
    { deep: true, immediate: true }
);
</script>
