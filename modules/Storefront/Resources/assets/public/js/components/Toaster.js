import Toastify from "toastify-js";

export function notify(message, options = {}) {
    Toastify({
        text: message || trans("storefront::storefront.something_went_wrong"),
        duration: 3000,
        close: true,
        gravity: window.innerWidth > 991 ? "bottom" : "top",
        position: "right", // `left`, `center` or `right`
        stopOnFocus: true, // prevents dismissing of toast on hover
        style: {
            background: "#343a40",
        },
        ...options,
    }).showToast();
}