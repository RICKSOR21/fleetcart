$("#order-status").on("change", (e) => {
    axios
        .put(`/orders/${e.currentTarget.dataset.id}/status`, {
            status: e.currentTarget.value,
        })
        .then((response) => {
            success(response.data);
        })
        .catch(({response}) => {
            error(response.data.message);
        });
});
