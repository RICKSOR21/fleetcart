import { ref } from "vue";

const flatpickrConfig = ref({
    mode: "single",
    enableTime: true,
    altInput: true,
});

const searchableSelectizeConfig = ref({
    plugins: ["remove_button"],
    valueField: "id",
    labelField: "name",
    searchField: "name",
    load: function (query, callback) {
        const url = "/products";

        if (url === undefined || query.length === 0) {
            return callback();
        }

        axios
            .get(url, {
                params: {
                    query,
                },
            })
            .then((response) => {
                callback(response.data);
            });
    },
});

export function useConfigs() {
    return { flatpickrConfig, searchableSelectizeConfig };
}
