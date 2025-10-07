import { useToast } from "vue-toast-notification";
import "@admin/sass/toaster.scss";

export function toaster(message, options = {}) {
    useToast().open({
        message,
        type: options.type || "default",
        duration: 5000,
        dismissible: true,
        position: "top-right",
        pauseOnHover: true,
        ...options,
    });
}
