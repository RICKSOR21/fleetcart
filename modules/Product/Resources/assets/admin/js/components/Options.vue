<template>
    <div class="box-header">
        <h5>
            {{ trans("product::products.group.options") }}
        </h5>

        <div class="d-flex">
            <span
                class="toggle-accordion"
                :class="{
                    collapsed: isCollapsedOptionsAccordion,
                }"
                data-toggle="tooltip"
                data-placement="top"
                :data-original-title="
                    isCollapsedOptionsAccordion
                        ? trans('product::products.section.expand_all')
                        : trans('product::products.section.collapse_all')
                "
                @click="
                    toggleAccordions({
                        selector: '.options-group .panel-heading',
                        state: isCollapsedOptionsAccordion,
                        data: form.options,
                    })
                "
            >
                <i class="fa fa-angle-double-up" aria-hidden="true"></i>
            </span>

            <div class="drag-handle">
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
                <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
            </div>
        </div>
    </div>

    <div class="box-body clearfix">
        <div class="accordion-box-content">
            <draggable
                animation="150"
                class="options-group"
                force-fallback="true"
                item-key="uid"
                handle=".drag-handle"
                @start="disableContentSelection"
                @end="enableContentSelection"
                :list="form.options"
            >
                <template #item="{ element: option, index }">
                    <div
                        :id="`option-${option.uid}`"
                        class="content-accordion panel-group options-group-wrapper"
                        :class="`option-${option.uid}`"
                    >
                        <div class="panel panel-default option">
                            <div
                                class="panel-heading"
                                @click.stop="toggleAccordion($event, option)"
                            >
                                <h4 class="panel-title">
                                    <div
                                        :aria-expanded="option.is_open"
                                        data-toggle="collapse"
                                        data-transition="false"
                                        :href="`#custom-collapse-${option.uid}`"
                                        :class="{
                                            collapsed: !option.is_open,
                                            'has-error': hasAnyError({
                                                name: 'options',
                                                uid: option.uid,
                                            }),
                                        }"
                                    >
                                        <div class="d-flex align-items-center">
                                            <span class="drag-handle">
                                                <i class="fa">&#xf142;</i>
                                                <i class="fa">&#xf142;</i>
                                            </span>

                                            <span
                                                v-text="
                                                    option.name ||
                                                    trans(
                                                        'product::products.options.new_option'
                                                    )
                                                "
                                            ></span>
                                        </div>

                                        <span
                                            class="delete-option"
                                            @click.stop="
                                                deleteOption(index, option.uid)
                                            "
                                        >
                                            <i class="fa fa-trash"></i>
                                        </span>
                                    </div>
                                </h4>
                            </div>

                            <div
                                class="panel-collapse"
                                :class="{
                                    collapse: !option.is_open,
                                }"
                            >
                                <div class="panel-body">
                                    <div class="new-option">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="form-group row">
                                                    <label
                                                        :for="`options-${option.uid}-name`"
                                                    >
                                                        {{
                                                            trans(
                                                                "product::products.form.options.name"
                                                            )
                                                        }}
                                                        <span
                                                            v-if="
                                                                option.name ||
                                                                option.type
                                                            "
                                                            class="text-red"
                                                            >*</span
                                                        >
                                                    </label>

                                                    <input
                                                        type="text"
                                                        :name="`options.${option.uid}.name`"
                                                        class="form-control option-name-field"
                                                        :id="`options-${option.uid}-name`"
                                                        v-model="option.name"
                                                    />

                                                    <span
                                                        class="help-block text-red"
                                                        v-if="
                                                            errors.has(
                                                                `options.${option.uid}.name`
                                                            )
                                                        "
                                                        v-text="
                                                            errors.get(
                                                                `options.${option.uid}.name`
                                                            )
                                                        "
                                                    >
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <label
                                                        :for="`options-${option.uid}-type`"
                                                    >
                                                        {{
                                                            trans(
                                                                "product::products.form.options.type"
                                                            )
                                                        }}
                                                        <span
                                                            v-if="
                                                                option.name ||
                                                                option.type
                                                            "
                                                            class="text-red"
                                                            >*</span
                                                        >
                                                    </label>

                                                    <select
                                                        :name="`options.${option.uid}.type`"
                                                        :id="`options-${option.uid}-type`"
                                                        class="form-control custom-select-black"
                                                        @change="
                                                            changeOptionType(
                                                                index,
                                                                option.uid
                                                            )
                                                        "
                                                        v-model="option.type"
                                                    >
                                                        <option value="">
                                                            {{
                                                                trans(
                                                                    "product::products.form.options.option_types.please_select"
                                                                )
                                                            }}
                                                        </option>

                                                        <optgroup
                                                            :label="
                                                                trans(
                                                                    'product::products.form.options.option_types.text'
                                                                )
                                                            "
                                                        >
                                                            <option
                                                                value="field"
                                                            >
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.option_types.field"
                                                                    )
                                                                }}
                                                            </option>

                                                            <option
                                                                value="textarea"
                                                            >
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.option_types.textarea"
                                                                    )
                                                                }}
                                                            </option>
                                                        </optgroup>

                                                        <optgroup
                                                            :label="
                                                                trans(
                                                                    'product::products.form.options.option_types.select'
                                                                )
                                                            "
                                                        >
                                                            <option
                                                                value="dropdown"
                                                            >
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.option_types.dropdown"
                                                                    )
                                                                }}
                                                            </option>

                                                            <option
                                                                value="checkbox"
                                                            >
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.option_types.checkbox"
                                                                    )
                                                                }}
                                                            </option>

                                                            <option
                                                                value="checkbox_custom"
                                                            >
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.option_types.checkbox_custom"
                                                                    )
                                                                }}
                                                            </option>

                                                            <option
                                                                value="radio"
                                                            >
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.option_types.radio"
                                                                    )
                                                                }}
                                                            </option>

                                                            <option
                                                                value="radio_custom"
                                                            >
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.option_types.radio_custom"
                                                                    )
                                                                }}
                                                            </option>

                                                            <option
                                                                value="multiple_select"
                                                            >
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.option_types.multiple_select"
                                                                    )
                                                                }}
                                                            </option>
                                                        </optgroup>

                                                        <optgroup
                                                            :label="
                                                                trans(
                                                                    'product::products.form.options.option_types.date'
                                                                )
                                                            "
                                                        >
                                                            <option
                                                                value="date"
                                                            >
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.option_types.date"
                                                                    )
                                                                }}
                                                            </option>

                                                            <option
                                                                value="date_time"
                                                                v-html="
                                                                    trans(
                                                                        'product::products.form.options.option_types.date_time'
                                                                    )
                                                                "
                                                            ></option>

                                                            <option
                                                                value="time"
                                                            >
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.option_types.time"
                                                                    )
                                                                }}
                                                            </option>
                                                        </optgroup>
                                                    </select>

                                                    <span
                                                        class="help-block text-red"
                                                        v-if="
                                                            errors.has(
                                                                `options.${option.uid}.type`
                                                            )
                                                        "
                                                        v-text="
                                                            errors.get(
                                                                `options.${option.uid}.type`
                                                            )
                                                        "
                                                    >
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="col-sm-3">
                                                <div class="form-group row">
                                                    <div class="checkbox">
                                                        <input
                                                            type="checkbox"
                                                            :name="`options.${option.uid}.is_required`"
                                                            :id="`options-${option.uid}-is-required`"
                                                            class="form-control"
                                                            v-model="
                                                                option.is_required
                                                            "
                                                        />

                                                        <label
                                                            :for="`options-${option.uid}-is-required`"
                                                        >
                                                            {{
                                                                trans(
                                                                    "product::products.form.options.is_required"
                                                                )
                                                            }}
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <template v-if="isOptionTypeText(option)">
                                        <div
                                            class="option-values"
                                            :id="`options.${option.uid}.values`"
                                        >
                                            <div
                                                class="table-responsive option-text"
                                            >
                                                <table
                                                    class="table table-bordered"
                                                >
                                                    <thead>
                                                        <tr>
                                                            <th>
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.price"
                                                                    )
                                                                }}
                                                            </th>
                                                            <th>
                                                                {{
                                                                    trans(
                                                                        "product::products.form.options.price_type"
                                                                    )
                                                                }}
                                                            </th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        <tr
                                                            v-for="(
                                                                value,
                                                                valueIndex
                                                            ) in option.values"
                                                            :key="valueIndex"
                                                        >
                                                            <td>
                                                                <div
                                                                    class="input-group"
                                                                >
                                                                    <span
                                                                        class="input-group-addon"
                                                                    >
                                                                        {{
                                                                            value.price_type ===
                                                                            "fixed"
                                                                                ? defaultCurrencySymbol
                                                                                : "%"
                                                                        }}
                                                                    </span>

                                                                    <input
                                                                        type="number"
                                                                        min="0"
                                                                        step="0.1"
                                                                        :name="`options.${option.uid}.values.${value.uid}.price`"
                                                                        :id="`options-${option.uid}-values-${value.uid}-price`"
                                                                        class="form-control"
                                                                        @wheel="
                                                                            $event.target.blur()
                                                                        "
                                                                        v-model="
                                                                            value.price
                                                                        "
                                                                    />
                                                                </div>

                                                                <span
                                                                    class="help-block text-red"
                                                                    v-if="
                                                                        errors.has(
                                                                            `options.${option.uid}.values.${value.uid}.price`
                                                                        )
                                                                    "
                                                                    v-text="
                                                                        errors.get(
                                                                            `options.${option.uid}.values.${value.uid}.price`
                                                                        )
                                                                    "
                                                                >
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <select
                                                                    :name="`options.${option.uid}.values.${value.uid}.price_type`"
                                                                    :id="`options-${option.uid}-values-${value.uid}-price-type`"
                                                                    class="form-control custom-select-black"
                                                                    v-model="
                                                                        value.price_type
                                                                    "
                                                                >
                                                                    <option
                                                                        value="fixed"
                                                                    >
                                                                        {{
                                                                            trans(
                                                                                "product::products.form.options.price_types.fixed"
                                                                            )
                                                                        }}
                                                                    </option>

                                                                    <option
                                                                        value="percent"
                                                                    >
                                                                        {{
                                                                            trans(
                                                                                "product::products.form.options.price_types.percent"
                                                                            )
                                                                        }}
                                                                    </option>
                                                                </select>

                                                                <span
                                                                    class="help-block text-red"
                                                                    v-if="
                                                                        errors.has(
                                                                            `options.${option.uid}.values.${value.uid}.price_type`
                                                                        )
                                                                    "
                                                                    v-text="
                                                                        errors.get(
                                                                            `options.${option.uid}.values.${value.uid}.price_type`
                                                                        )
                                                                    "
                                                                >
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </template>

                                    <template
                                        v-else-if="isOptionTypeSelect(option)"
                                    >
                                        <div
                                            class="option-values"
                                            :id="`options.${option.uid}.values`"
                                        >
                                            <div class="option-select">
                                                <div
                                                    class="table-responsive option-text"
                                                >
                                                    <table
                                                        class="options table table-bordered"
                                                    >
                                                        <thead>
                                                            <tr>
                                                                <th></th>
                                                                <th>
                                                                    {{
                                                                        trans(
                                                                            "product::products.form.options.label"
                                                                        )
                                                                    }}
                                                                    <span
                                                                        class="text-red"
                                                                        >*</span
                                                                    >
                                                                </th>
                                                                <th>
                                                                    {{
                                                                        trans(
                                                                            "product::products.form.options.price"
                                                                        )
                                                                    }}
                                                                </th>
                                                                <th>
                                                                    {{
                                                                        trans(
                                                                            "product::products.form.options.price_type"
                                                                        )
                                                                    }}
                                                                </th>
                                                                <th></th>
                                                            </tr>
                                                        </thead>

                                                        <tbody
                                                            is="vue:draggable"
                                                            animation="150"
                                                            handle=".drag-handle"
                                                            item-key="uid"
                                                            tag="tbody"
                                                            :list="
                                                                option.values
                                                            "
                                                        >
                                                            <template
                                                                #item="{
                                                                    element:
                                                                        value,
                                                                    index: valueIndex,
                                                                }"
                                                            >
                                                                <tr>
                                                                    <td
                                                                        class="text-center"
                                                                    >
                                                                        <span
                                                                            class="drag-handle"
                                                                        >
                                                                            <i
                                                                                class="fa"
                                                                                >&#xf142;</i
                                                                            >
                                                                            <i
                                                                                class="fa"
                                                                                >&#xf142;</i
                                                                            >
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <input
                                                                            type="text"
                                                                            :name="`options.${option.uid}.values.${value.uid}.label`"
                                                                            :id="`options-${option.uid}-values-${value.uid}-label`"
                                                                            class="form-control"
                                                                            @keyup.enter="
                                                                                addOptionRowOnPressEnter(
                                                                                    $event,
                                                                                    index,
                                                                                    valueIndex
                                                                                )
                                                                            "
                                                                            v-model="
                                                                                value.label
                                                                            "
                                                                        />

                                                                        <span
                                                                            class="help-block text-red"
                                                                            v-if="
                                                                                errors.has(
                                                                                    `options.${option.uid}.values.${value.uid}.label`
                                                                                )
                                                                            "
                                                                            v-text="
                                                                                errors.get(
                                                                                    `options.${option.uid}.values.${value.uid}.label`
                                                                                )
                                                                            "
                                                                        >
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <div
                                                                            class="input-group"
                                                                        >
                                                                            <span
                                                                                class="input-group-addon"
                                                                            >
                                                                                {{
                                                                                    value.price_type ===
                                                                                    "fixed"
                                                                                        ? defaultCurrencySymbol
                                                                                        : "%"
                                                                                }}
                                                                            </span>

                                                                            <input
                                                                                type="number"
                                                                                min="0"
                                                                                step="0.1"
                                                                                :name="`options.${option.uid}.values.${value.uid}.price`"
                                                                                :id="`options-${option.uid}-values-${value.uid}-price`"
                                                                                class="form-control"
                                                                                @wheel="
                                                                                    $event.target.blur()
                                                                                "
                                                                                v-model="
                                                                                    value.price
                                                                                "
                                                                            />
                                                                        </div>

                                                                        <span
                                                                            class="help-block text-red"
                                                                            v-if="
                                                                                errors.has(
                                                                                    `options.${option.uid}.values.${value.uid}.price`
                                                                                )
                                                                            "
                                                                            v-text="
                                                                                errors.get(
                                                                                    `options.${option.uid}.values.${value.uid}.price`
                                                                                )
                                                                            "
                                                                        >
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <select
                                                                            :name="`options.${option.uid}.values.${value.uid}.price_type`"
                                                                            :id="`options-${option.uid}-values-${value.uid}-price-type`"
                                                                            class="form-control custom-select-black"
                                                                            v-model="
                                                                                value.price_type
                                                                            "
                                                                        >
                                                                            <option
                                                                                value="fixed"
                                                                            >
                                                                                {{
                                                                                    trans(
                                                                                        "product::products.form.options.price_types.fixed"
                                                                                    )
                                                                                }}
                                                                            </option>

                                                                            <option
                                                                                value="percent"
                                                                            >
                                                                                {{
                                                                                    trans(
                                                                                        "product::products.form.options.price_types.percent"
                                                                                    )
                                                                                }}
                                                                            </option>
                                                                        </select>

                                                                        <span
                                                                            class="help-block text-red"
                                                                            v-if="
                                                                                errors.has(
                                                                                    `options.${option.uid}.values.${value.uid}.price_type`
                                                                                )
                                                                            "
                                                                            v-text="
                                                                                errors.get(
                                                                                    `options.${option.uid}.values.${value.uid}.price_type`
                                                                                )
                                                                            "
                                                                        >
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <button
                                                                            type="button"
                                                                            tabindex="-1"
                                                                            class="btn btn-default delete-row"
                                                                            @click="
                                                                                deleteOptionRow(
                                                                                    index,
                                                                                    option.uid,
                                                                                    valueIndex,
                                                                                    value.uid
                                                                                )
                                                                            "
                                                                        >
                                                                            <i
                                                                                class="fa fa-trash"
                                                                            ></i>
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
                                                    @click="
                                                        addOptionRow(
                                                            index,
                                                            option.uid
                                                        )
                                                    "
                                                >
                                                    {{
                                                        trans(
                                                            "product::products.options.add_row"
                                                        )
                                                    }}
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </draggable>

            <div class="accordion-box-footer">
                <button
                    type="button"
                    class="btn btn-default"
                    @click="addOption"
                >
                    {{ trans("product::products.options.add_option") }}
                </button>

                <div
                    v-if="hasAccess('admin.options.index')"
                    class="insert-template"
                >
                    <select
                        class="form-control custom-select-black"
                        v-model="globalOptionId"
                    >
                        <option value="">
                            {{
                                trans(
                                    "product::products.form.options.select_template"
                                )
                            }}
                        </option>

                        <option
                            v-for="globalOption in globalOptions"
                            :key="globalOption.id"
                            :value="globalOption.id"
                        >
                            {{ globalOption.name }}
                        </option>
                    </select>

                    <button
                        type="button"
                        class="btn btn-default"
                        :class="{
                            'btn-loading': addingGlobalOption,
                        }"
                        :disabled="isAddGlobalOptionDisabled"
                        @click="addGlobalOption"
                    >
                        {{ trans("product::products.options.insert") }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, nextTick } from "vue";
import { useForm } from "../composables/useForm";
import { useProductMethods } from "../composables/useProductMethods";
import { useDraggableSections } from "../composables/useDraggableSections";
import { generateUid } from "@admin/js/functions";
import { toaster } from "@admin/js/Toaster";
import draggable from "vuedraggable";

const globalOptionId = ref("");
const addingGlobalOption = ref(false);
const globalOptions = ref(FleetCart.data["global-options"] ?? []);

const {
    form,
    errors,
    focusField,
    hasAnyError,
    clearErrors,
    clearValuesError,
    clearValueRowErrors,
} = useForm();
const { toggleAccordions, toggleAccordion } = useProductMethods();
const { enableContentSelection, disableContentSelection } =
    useDraggableSections();

const isAddGlobalOptionDisabled = computed(
    () => globalOptionId.value === "" || addingGlobalOption.value
);

const isCollapsedOptionsAccordion = computed(() =>
    form.options.every(({ is_open }) => is_open === false)
);

function addOption({ preventFocus }) {
    const uid = generateUid();

    form.options.push({
        uid,
        type: "",
        is_open: true,
        is_global: false,
        is_required: false,
        values: [
            {
                uid: generateUid(),
                price_type: "fixed",
            },
        ],
    });

    if (!preventFocus) {
        focusField({
            selector: `#options-${uid}-name`,
        });
    }
}

function deleteOption(index, uid) {
    form.options.splice(index, 1);

    clearErrors({ name: "options", uid });
}

function changeOptionType(index, uid) {
    let option = form.options[index];
    const fieldName = isOptionTypeText(option) ? "price" : "label";

    clearValuesError({ name: "options", uid });

    if (option.values.length === 1) {
        focusField({
            selector: `#options-${uid}-values-${option.values[0].uid}-${fieldName}`,
        });
    }
}

function isOptionTypeText(option) {
    const TYPES = ["field", "textarea", "date", "date_time", "time"];

    if (TYPES.includes(option.type)) {
        // keep only first value
        option.values.splice(1);

        return true;
    }

    return false;
}

function isOptionTypeSelect({ type }) {
    const TYPES = [
        "dropdown",
        "checkbox",
        "checkbox_custom",
        "radio",
        "radio_custom",
        "multiple_select",
    ];

    return TYPES.includes(type) ? true : false;
}

function addOptionRow(index, optionUid) {
    const valueUid = generateUid();

    form.options[index].values.push({
        uid: valueUid,
        price_type: "fixed",
    });

    focusField({
        selector: `#options-${optionUid}-values-${valueUid}-label`,
    });
}

function addOptionRowOnPressEnter(event, optionIndex, valueIndex) {
    const option = form.options[optionIndex];
    const values = option.values;

    if (event.target.value === "") return;

    if (values.length - 1 === valueIndex) {
        addOptionRow(optionIndex, option.uid);

        return;
    }

    if (valueIndex < values.length - 1) {
        $(
            `#options-${option.uid}-values-${values[valueIndex + 1].uid}-label`
        ).trigger("focus");
    }
}

function deleteOptionRow(optionIndex, optionUid, valueIndex, valueUid) {
    const option = form.options[optionIndex];

    option.values.splice(valueIndex, 1);

    clearValueRowErrors({ name: "options", uid: optionUid, valueUid });

    if (option.values.length === 0) {
        addOptionRow(optionIndex, optionUid);
    }
}

function addGlobalOption() {
    if (globalOptionId.value === "") return;

    addingGlobalOption.value = true;

    axios
        .get(`/options/${globalOptionId.value}`)
        .then(({ data: option }) => {
            option.uid = generateUid();
            option.is_open = true;

            option.values.forEach((value) => {
                value.uid = generateUid();
            });

            form.options.push(option);

            nextTick(() => {
                $(`#options-${option.uid}-name`).trigger("focus");
            });

            toaster(trans("product::products.options.option_inserted"), {
                type: "default",
            });
        })
        .catch((error) => {
            toaster(error.response.data.message, {
                type: "error",
            });
        })
        .finally(() => {
            globalOptionId.value = "";
            addingGlobalOption.value = false;
        });
}

watch(
    () => form.options,
    (newValue) => {
        if (newValue.length === 0) {
            addOption({ preventFocus: true });
        }
    },
    { deep: true, immediate: true }
);
</script>
