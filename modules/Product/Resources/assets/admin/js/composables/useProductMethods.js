import { useForm } from "./useForm";
import { generateSlug } from "@admin/js/functions";

export function useProductMethods() {
    const { form } = useForm();

    function setProductSlug(value) {
        form.slug = generateSlug(value);
    }

    function removeDatePickerValue(key) {
        form[key] = null;
    }

    function toggleAccordions({ selector, state, data }) {
        const event = new Event("click");
        const elements = document.querySelectorAll(selector);

        if (!state) {
            data.forEach(({ is_open }, index) => {
                if (is_open) {
                    elements[index].dispatchEvent(event);
                }
            });

            return;
        }

        [...elements].forEach((element) => {
            element.dispatchEvent(event);
        });
    }

    function toggleAccordion(event, data) {
        const target = $(event.currentTarget);
        const panelTitle = target.find('[data-toggle="collapse"]');
        const panelBody = target.next();

        if (data.is_open) {
            panelBody.css("display", "block");
        }

        panelTitle.attr("data-transition", true);

        data.is_open = !data.is_open;

        panelBody.slideToggle(300, () => {
            panelTitle.attr("data-transition", false);
            panelBody.removeAttr("style");
        });
    }

    return {
        setProductSlug,
        removeDatePickerValue,
        toggleAccordions,
        toggleAccordion,
    };
}
