import "nestable2";

window.admin.removeSubmitButtonOffsetOn("#image");

$("#type").on("change", (e) => {
    $(".link-field").addClass("hide");
    $(`.${e.currentTarget.value}-field`).removeClass("hide");
});

$(".dd").nestable({ maxDepth: 15 });

$(".dd").on("change", () => {
    axios
        .put("/menus/items/order", $(".dd").nestable("serialize")[0], {
            headers: {
                "Content-Type": "application/json; charset=utf-8",
            },
        })
        .then(() => {
            success(trans("menu::messages.menu_items_order_updated"));
        })
        .catch((error) => {
            error(error.response.data.message);
        });
});

let id;
let confirmationModal = $("#confirmation-modal");

$(".delete-menu-item").on("click", (e) => {
    id = $(e.currentTarget).closest(".dd-item").data("id");

    confirmationModal.modal("show");
});

confirmationModal.find("form").on("submit", (e) => {
    e.preventDefault();

    confirmationModal.modal("hide");

    axios
        .delete(`/menus/items/${id}`)
        .then(() => {
            success(trans("menu::messages.menu_item_deleted"));

            $(`.dd-item[data-id="${id}"]`).fadeOut();
        })
        .catch((error) => {
            error(error.response.data.message);
        });
});
