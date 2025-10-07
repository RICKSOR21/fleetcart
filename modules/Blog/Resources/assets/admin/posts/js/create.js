import { nprogress } from "@admin/js/NProgress";
import { fullscreenMode } from "@admin/js/functions";
import Alpine from "alpinejs";
import tinyMce from "@admin/js/wysiwyg";
import Errors from "@admin/js/Errors";

window.Alpine = Alpine;

let textEditor;
let tagsSelect;

Alpine.data("postCreate", () => ({
    formSubmitting: false,
    formSubmissionType: null,
    form: {
        meta: {},
        featured_image: {},
        publish_status: "published",
        blog_category_id: "",
    },
    errors: new Errors(),

    init() {
        nprogress();
        fullscreenMode();

        textEditor = this.initTinyMce();
        tagsSelect = this.initTagsSelectize();
    },

    initTinyMce() {
        return tinyMce({
            setup: (editor) => {
                editor.on("change", () => {
                    editor.save();
                    editor.getElement().dispatchEvent(new Event("input"));

                    this.errors.clear("description");
                });
            },
        });
    },

    initTagsSelectize() {
        return $(".selectize").selectize({
            plugins: ["remove_button"],
            delimiter: ",",
            persist: false,
            onChange: (values) => {
                this.form.tags = values;
            },
        });
    },

    focusDescriptionField() {
        textEditor.get("description").focus();
    },

    addFeaturedImage() {
        const picker = new MediaPicker({ type: "image" });

        picker.on("select", ({ id, path }) => {
            this.form.featured_image = {
                id: +id,
                path,
            };
        });
    },

    removeFeaturedImage() {
        this.form.featured_image = {};
    },

    focusFirstErrorField(formElements) {
        const errorKeys = Object.keys(this.errors.errors);

        const firstErrorField = [...formElements].find((element) => {
            return errorKeys.includes(element.name);
        });

        if (firstErrorField.classList.contains("wysiwyg")) {
            textEditor.get(firstErrorField.getAttribute("name")).focus();

            return;
        }

        firstErrorField.focus();
    },

    resetForm() {
        this.errors.reset();

        this.form = {
            meta: {},
            featured_image: {},
            publish_status: "published",
            blog_category_id: "",
        };

        textEditor.get("description").setContent("");
        textEditor.get("description").execCommand("mceCancel");
        tagsSelect[0].selectize.clear();
    },

    handleSubmit({ submissionType }) {
        this.formSubmitting = true;
        this.formSubmissionType = submissionType;

        const {
            title,
            description,
            meta,
            featured_image,
            publish_status,
            blog_category_id,
            tags,
        } = this.form;

        axios
            .post(
                "/blog/posts",
                {
                    title,
                    description,
                    meta,
                    publish_status,
                    blog_category_id,
                    tags,
                    files: {
                        featured_image: featured_image.id
                            ? [featured_image.id]
                            : [],
                    },
                },
                {
                    params: {
                        ...((submissionType === "save_and_edit" ||
                            submissionType === "save_and_exit") && {
                            exit_flash: true,
                        }),
                    },
                }
            )
            .then(({ data }) => {
                if (this.formSubmissionType === "save_and_edit") {
                    location.href = `/admin/blog/posts/${data.blog_post_id}/edit`;

                    return;
                }

                if (this.formSubmissionType === "save_and_exit") {
                    location.href = "/admin/blog/posts";

                    return;
                }

                success(data.message);

                this.resetForm();
                this.$refs.form.elements[0].focus();
            })
            .catch(({ response }) => {
                this.errors.record(response.data.errors);
                this.focusFirstErrorField(this.$refs.form.elements);

                error(response.data.message);
            })
            .finally(() => {
                this.formSubmitting = false;
                this.formSubmissionType = null;
            });
    },
}));

Alpine.start();
