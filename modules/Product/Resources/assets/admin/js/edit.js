import { createApp } from "vue";
import { trans, hasAccess } from "@admin/js/functions";
import App from "./save/App.vue";

const app = createApp(App);

app.config.globalProperties.window = window;
app.config.globalProperties.trans = trans;
app.config.globalProperties.hasAccess = hasAccess;
app.config.globalProperties.defaultCurrencySymbol =
    FleetCart.defaultCurrencySymbol;

app.mount("#app");
