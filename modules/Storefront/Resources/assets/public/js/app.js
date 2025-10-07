import { trans, formatCurrency } from "./functions";
import { notify } from "./components/Toaster";
import Alpine from "alpinejs";
import jQuery from "jquery";
import * as bootstrap from "bootstrap/dist/js/bootstrap.js";
import "./vendors/axios";

window.Alpine = Alpine;
window.bootstrap = bootstrap;
window.$ = window.jQuery = jQuery;
window.trans = trans;
window.formatCurrency = formatCurrency;
window.notify = notify;

Alpine.data("App", () => ({
    hideOverlay() {
        const layoutStore = this.$store.layout;

        layoutStore.closeSidebarMenu();
        layoutStore.closeSidebarCart();
        layoutStore.closeSidebarFilter();
        layoutStore.closeLocalizationMenu();
    },
}));
