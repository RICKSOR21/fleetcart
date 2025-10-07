import "flatpickr";
import "mousetrap";
import "./FleetCart";
import "./jquery.keypressAction";
import "./vendors/axios";

import Admin from "./Admin";
import Form from "./Form";
import DataTable from "./DataTable";
import {
    trans,
    keypressAction,
    notify,
    info,
    success,
    warning,
    error,
} from "./functions";

const regex =
    /^\/[a-z]{2}\/admin\/(products|blog\/posts)\/(create|(\d+)\/edit)$/;

if (!window.location.pathname.match(regex)) {
    window.admin = new Admin();
}

window.form = new Form();
window.DataTable = DataTable;

window.trans = trans;
window.keypressAction = keypressAction;
window.notify = notify;
window.info = info;
window.success = success;
window.warning = warning;
window.error = error;
