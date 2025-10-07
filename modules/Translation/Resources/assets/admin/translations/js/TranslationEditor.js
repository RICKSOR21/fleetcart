import "x-editable/dist/bootstrap3-editable/js/bootstrap-editable";

export default class {
    constructor() {
        $(".translation")
            .editable({
                url: this.update,
                type: "text",
                mode: "inline",
                send: "always",
            })
            .on("shown", (_, editable) => {
                editable.input.postrender = () => {
                    editable.input.$input.select();
                };
            });
    }

    update(data) {
        axios
            .put(
                `languages/${this.dataset.locale}/translations/${this.dataset.key}`,
                {
                    locale: this.dataset.locale,
                    value: data.value,
                }
            )
            .then((response) => {
                success(response.data);
            })
            .catch((err) => {
                error(err.response.data.message);
            });
    }
}
