import tinymce from "tinymce";

export default function (options = {}) {
    tinymce.baseURL = `${window.location.origin}/build/assets/tinymce`;

    tinymce.init({
        selector: ".wysiwyg",
        theme: "silver",
        height: 350,
        menubar: false,
        branding: false,
        image_advtab: true,
        automatic_uploads: true,
        media_alt_source: false,
        media_poster: false,
        relative_urls: false,
        toolbar_mode: "sliding", // supported values: floating, sliding, scrolling, wrap
        directionality: FleetCart.rtl ? "rtl" : "ltr",
        cache_suffix: `?v=${FleetCart.version}`,
        content_style: "body { color: #333333; }",
        plugins:
            "lists, link, table, image, media, paste, autosave, autolink,quickbars, wordcount, code, fullscreen",
        toolbar:
            "styleselect | bold italic underline strikethrough blockquote | bullist numlist | alignleft aligncenter alignright alignjustify | outdent indent | forecolor removeformat | table | image media link | code fullscreen",
        quickbars_selection_toolbar:
            "bold italic | quicklink h2 h3 blockquote quickimage quicktable",
        extended_valid_elements: "img[class|src|alt|title|width|loading=lazy]",
        images_upload_handler(blobInfo, success, failure) {
            let formData = new FormData();

            formData.append("file", blobInfo.blob(), blobInfo.filename());

            axios
                .post("/media", formData)
                .then((response) => {
                    success(response.data.path);
                })
                .catch((error) => {
                    failure(error.response.data.message);
                });
        },
        ...options,
    });

    return tinymce;
}
