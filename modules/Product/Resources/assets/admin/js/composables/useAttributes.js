import { ref } from "vue";
import { useForm } from "./useForm";

const attributeSets = ref(FleetCart.data["attribute-sets"] ?? {});

export function useAttributes() {
    const { form, clearErrors } = useForm();

    function getAttributeValuesById(id) {
        if (id === "") return;

        let values = null;

        for (const attributeSet of Object.values(attributeSets.value)) {
            for (const attribute of attributeSet.attributes) {
                if (attribute.id === id) {
                    values = attribute.values;

                    return values;
                }
            }
        }

        return values;
    }

    function initAllAttributeValuesSelectize() {
        form.attributes.forEach((attribute, index) => {
            const options =
                getAttributeValuesById(attribute.attribute_id)?.map((value) => {
                    return { value: value.id, text: value.value };
                }) ?? [];

            $(`#attributes-${attribute.uid}-values`).selectize({
                plugins: ["remove_button"],
                onChange: (values) => {
                    form.attributes[index].values = values;

                    clearErrors({
                        name: "attributes",
                        uid: attribute.uid,
                    });
                },
                items: attribute.values,
                options: [...options],
            });
        });
    }

    function destroyAllAttributeValuesSelectize() {
        form.attributes.forEach((attribute) => {
            $(`#attributes-${attribute.uid}-values`)[0].selectize.destroy();
        });
    }

    return {
        // refs
        attributeSets,

        // methods
        getAttributeValuesById,
        initAllAttributeValuesSelectize,
        destroyAllAttributeValuesSelectize,
    };
}
