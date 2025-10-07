import { useForm } from "./useForm";
import { generateUid } from "@admin/js/functions";
import md5 from "blueimp-md5";
import Coloris from "@melloware/coloris";

export function useVariations() {
    const { form } = useForm();

    function getFilteredVariations() {
        return form.variations
            .map(({ type, values }) =>
                values
                    .map(({ uid, label }) => {
                        if (type !== "" && Boolean(label)) {
                            return { uid, label };
                        }
                    })
                    .filter(Boolean),
            )
            .filter((data) => data.length !== 0);
    }

    function regenerateVariationsAndVariantsUid() {
        // Generate new variations UID
        form.variations.forEach((variation) => {
            variation.uid = generateUid();

            variation.values.forEach((_, valueIndex) => {
                variation.values[valueIndex].uid = generateUid();
            });
        });

        const newVariants = generateNewVariants(getFilteredVariations());

        // Generate new variants UID
        newVariants.forEach(({ uids }, index) => {
            form.variants[index].uid = md5(uids);
            form.variants[index].uids = uids;
        });
    }

    function generateNewVariants(variations) {
        return variations
            .reduce((accumulator, currentValue) =>
                accumulator.flatMap((x) =>
                    currentValue.map((y) => {
                        return {
                            uid: x.uid + "." + y.uid,
                            label: x.label + " / " + y.label,
                        };
                    }),
                ),
            )
            .map(({ uid, label }) => {
                return {
                    uids: uid.split(".").sort().join("."),
                    name: label,
                };
            });
    }

    function initVariationsColorPicker() {
        Coloris.init();

        Coloris({
            el: ".variation-color-picker",
            alpha: false,
            rtl: FleetCart.rtl,
            theme: "large",
            wrap: true,
            format: "hex",
            selectInput: true,
            swatches: [
                "#D01C1F",
                "#3AA845",
                "#118257",
                "#0A33AE",
                "#0D46A0",
                "#000000",
                "#5F4C3A",
                "#726E6E",
                "#F6D100",
                "#C0E506",
                "#FF540A",
                "#C5A996",
                "#4B80BE",
                "#A1C3DA",
                "#C8BFC2",
                "#A9A270",
            ],
        });
    }

    function updateVariationsColorThumbnail() {
        form.variations.forEach(({ uid, type, values }) => {
            if (type !== "color") return;

            const elements = document.querySelectorAll(
                `.variation-${uid} .clr-field`,
            );

            values.forEach(({ color }, valueIndex) => {
                elements[valueIndex].style.color = color || "";
            });
        });
    }

    return {
        getFilteredVariations,
        regenerateVariationsAndVariantsUid,
        initVariationsColorPicker,
        updateVariationsColorThumbnail,
    };
}
