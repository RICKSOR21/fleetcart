import DataTable from "datatables.net-bs";
import TranslationEditor from "./TranslationEditor";

new DataTable(".translations-table", {
    stateSave: true,
    sort: true,
    info: true,
    filter: true,
    lengthChange: true,
    paginate: true,
    autoWidth: false,
    pageLength: 20,
    lengthMenu: [10, 20, 50, 100, 200],
    drawCallback: () => {
        new TranslationEditor();
    },
    layout: {
        topEnd: {
            search: {
                placeholder: trans("admin::admin.table.search_here"),
            },
        },
    },
    language: {
        sInfo: trans("admin::admin.table.showing_start_end_total_entries"),
        sInfoEmpty: trans("admin::admin.table.showing_empty_entries"),
        sLengthMenu: trans("admin::admin.table.show_menu_entries"),
        sInfoFiltered: trans(
            "admin::admin.table.filtered_from_max_total_entries"
        ),
        sEmptyTable: trans("admin::admin.table.no_data_available_table"),
        sLoadingRecords: trans("admin::admin.table.loading"),
        sProcessing: trans("admin::admin.table.processing"),
        sZeroRecords: trans("admin::admin.table.no_matching_records_found"),
    },
});
