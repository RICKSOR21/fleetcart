$("#refresh-rates").on("click", (e) => {
    axios
        .get("currency-rates/refresh")
        .then(() => {
            DataTable.reload();

            window.admin.stopButtonLoading($(e.currentTarget));
        })
        .catch(({ response }) => {
            error(response.data.message);

            window.admin.stopButtonLoading($(e.currentTarget));
        });
});
