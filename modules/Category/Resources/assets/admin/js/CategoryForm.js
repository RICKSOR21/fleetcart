import CategoryTree from "./CategoryTree";

export default class {
    constructor() {
        let tree = $(".category-tree");

        new CategoryTree(this, tree);

        this.collapseAll(tree);
        this.expandAll(tree);
        this.addRootCategory();
        this.addSubCategory();
        this.removeSubmitButtonOffsetOn(
            "#image",
            ".category-details-tab li > a"
        );

        $("#category-form").on("submit", this.submit);
    }

    collapseAll(tree) {
        $(".collapse-all").on("click", (e) => {
            e.preventDefault();

            tree.jstree("close_all");
        });
    }

    expandAll(tree) {
        $(".expand-all").on("click", (e) => {
            e.preventDefault();

            tree.jstree("open_all");
        });
    }

    addRootCategory() {
        $(".add-root-category").on("click", () => {
            this.loading(true);

            $(".add-sub-category").addClass("disabled");

            $(".category-tree").jstree("deselect_all");

            this.clear();

            // Intentionally delay 150ms so that user feel new form is loaded.
            setTimeout(this.loading, 150, false);
        });
    }

    addSubCategory() {
        $(".add-sub-category").on("click", () => {
            let selectedId = $(".category-tree").jstree("get_selected")[0];

            if (selectedId === undefined) {
                return;
            }

            this.clear();
            this.loading(true);

            window.form.appendHiddenInput(
                "#category-form",
                "parent_id",
                selectedId
            );

            // Intentionally delay 150ms so that user feel new form is loaded.
            setTimeout(this.loading, 150, false);
        });
    }

    fetchCategory(id) {
        this.loading(true);

        $(".add-sub-category").removeClass("disabled");

        axios
            .get(`/categories/${id}`)
            .then((response) => {
                this.update(response.data);
                this.loading(false);
            })
            .catch((error) => {
                error(error.response.data.message);

                this.loading(false);
            });
    }

    update(category) {
        window.form.removeErrors();

        $(".btn-delete").removeClass("hide");
        $(".form-group .help-block").remove();

        $("#confirmation-form").attr(
            "action",
            `${window.FleetCart.baseUrl}/admin/categories/${category.id}`
        );

        $("#id-field").removeClass("hide");

        $("#id").val(category.id);
        $("#name").val(category.name);

        $("#slug").val(category.slug);
        $("#slug-field").removeClass("hide");
        $(".category-details-tab .seo-tab").removeClass("hide");

        $("#is_searchable").prop("checked", category.is_searchable);
        $("#is_active").prop("checked", category.is_active);

        $(".logo .image-holder-wrapper").html(
            this.categoryImage("logo", category.logo)
        );
        $(".banner .image-holder-wrapper").html(
            this.categoryImage("banner", category.banner)
        );

        $('#category-form input[name="parent_id"]').remove();
    }

    categoryImage(fieldName, file) {
        if (!file.exists) {
            return this.imagePlaceholder();
        }

        return `
            <div class="image-holder">
                <img src="${file.path}">
                <button type="button" class="btn remove-image" data-input-name="files[${fieldName}]">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M6.00098 17.9995L17.9999 6.00053" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.9999 17.9995L6.00098 6.00055" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
                <input type="hidden" name="files[${fieldName}]" value="${file.id}">
            </div>
        `;
    }

    clear() {
        $("#id-field").addClass("hide");

        $("#id").val("");
        $("#name").val("");

        $("#slug").val("");
        $("#slug-field").addClass("hide");
        $(".category-details-tab .seo-tab").addClass("hide");

        $("#is_searchable").prop("checked", false);
        $("#is_active").prop("checked", false);

        $(".logo .image-holder-wrapper").html(this.imagePlaceholder());
        $(".banner .image-holder-wrapper").html(this.imagePlaceholder());

        $(".btn-delete").addClass("hide");
        $(".form-group .help-block").remove();

        $('#category-form input[name="parent_id"]').remove();

        $(".general-information-tab a").click();
    }

    imagePlaceholder() {
        return `
            <div class="image-holder placeholder">
                <i class="fa fa-picture-o"></i>
            </div>
        `;
    }

    loading(state) {
        if (state === true) {
            $(".overlay.loader").removeClass("hide");
        } else {
            $(".overlay.loader").addClass("hide");
        }
    }

    submit(e) {
        let selectedId = $(".category-tree").jstree("get_selected")[0];

        if (!$("#slug-field").hasClass("hide")) {
            window.form.appendHiddenInput("#category-form", "_method", "put");

            $("#category-form").attr(
                "action",
                `${window.FleetCart.baseUrl}/admin/categories/${selectedId}`
            );
        }

        e.currentTarget.submit();
    }

    removeSubmitButtonOffsetOn(tabs, tabsSelector = null) {
        tabs = Array.isArray(tabs) ? tabs : [tabs];

        $(tabsSelector).on("click", (e) => {
            if (tabs.includes(e.currentTarget.getAttribute("href"))) {
                setTimeout(() => {
                    $("button[type=submit]")
                        .parent()
                        .removeClass("col-md-offset-3");
                }, 150);
            } else {
                setTimeout(() => {
                    $("button[type=submit]")
                        .parent()
                        .addClass("col-md-offset-3");
                }, 150);
            }
        });
    }
}
