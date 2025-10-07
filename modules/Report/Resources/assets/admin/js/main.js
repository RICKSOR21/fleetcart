$("form").on("submit", (e) => {
    $(e.currentTarget)
        .find(":input")
        .filter((i, el) => {
            return !el.value;
        })
        .attr("disabled", "disabled");
});

$("#report-type").on("change", (e) => {
    window.location = `/admin/reports?type=${e.currentTarget.value}`;
});
