<template>
    <div class="table-responsive mb-0">
        <table class="table languages-table">
            <thead>
                <tr>
                    <th>{{ trans("translation::languages.table.name") }}</th>
                    <th>{{ trans("translation::languages.table.default") }}</th>
                    <th>{{ trans("translation::languages.table.actions") }}</th>
                </tr>
            </thead>

            <tbody>
                <tr v-for="locale in languages" :key="locale.key">
                    <td>
                        <a
                            :href="
                                window.FleetCart.baseUrl +
                                '/admin/languages/' +
                                locale['key'] +
                                '/translations'
                            "
                        >
                            {{ locale.name }}
                        </a>
                    </td>
                    <td>
                        <div class="switch">
                            <input
                                type="radio"
                                name="is_default"
                                :id="`is_default_${locale.key}`"
                                :value="locale.key"
                                v-model="isDefault"
                                @change="makeDefault(locale.key)"
                            />

                            <label :for="`is_default_${locale.key}`"></label>
                        </div>
                    </td>
                    <td>
                        <a
                            :href="
                                window.FleetCart.baseUrl +
                                '/admin/languages/' +
                                locale['key'] +
                                '/translations'
                            "
                            class="btn btn-default mr-8"
                            :title="
                                trans(
                                    'translation::languages.table.translations'
                                )
                            "
                        >
                            <i class="fa fa-language" aria-hidden="true"></i>
                        </a>

                        <button
                            class="btn btn-default"
                            @click="deleteLanguage(locale.key)"
                            :disabled="
                                languages.length > 1 && locale.is_default
                            "
                            :title="
                                trans('translation::languages.table.delete')
                            "
                        >
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import { toaster } from "@admin/js/Toaster";
import { nprogress } from "@admin/js/NProgress";

window.toaster = toaster;

export default {
    data() {
        return {
            isDefault: null,
            languages: [],
        };
    },

    created() {
        this.languages = FleetCart.data["languages"];

        this.isDefault =
            this.languages.find((language) => language.is_default === true)
                ?.key || "en";

        nprogress();
    },

    methods: {
        makeDefault(key) {
            this.languages.forEach((language) => {
                language.is_default = language.key === key;
            });

            axios
                .post("languages/make-default", { language: key })
                .then((response) => {
                    this.languages = response.data;

                    toaster(
                        trans(
                            "translation::languages.default_language_updated"
                        ),
                        {
                            type: "default",
                        }
                    );
                })
                .catch((error) => {
                    toaster(error.response.data.message, {
                        type: "default",
                    });
                });
        },

        deleteLanguage(key) {
            if (key === this.isDefault) {
                return;
            }

            axios
                .delete("languages/" + key)
                .then((response) => {
                    this.languages = response.data;

                    window.location.reload();
                })
                .catch((error) => {
                    toaster(error.response.data.message, {
                        type: "default",
                    });
                });
        },
    },
};
</script>
