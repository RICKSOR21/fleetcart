import MediaPicker from "./MediaPicker";

export default class {
    constructor() {
        $(".image-picker").on("click", (e) => {
            this.pickImage(e);
        });

        this.sortable();
        this.removeImageEventListener();
    }

    pickImage(e) {
        let inputName = e.currentTarget.dataset.inputName;
        let multiple = e.currentTarget.hasAttribute("data-multiple");

        let picker = new MediaPicker({ type: "image", multiple });

        picker.on("select", (file) => {
            this.addImage(inputName, file, multiple, e.currentTarget);
        });
    }

    addImage(inputName, file, multiple, target) {
        let html = this.getTemplate(inputName, file);

        if (multiple) {
            let multipleImagesWrapper = $(target).next(".multiple-images");

            multipleImagesWrapper.find(".image-holder.placeholder").remove();
            multipleImagesWrapper.find(".image-list").append(html);
        } else {
            $(target).siblings(".single-image").html(html);
        }
    }

    getTemplate(inputName, file) {
        return $(`
            <div class="image-holder">
                <img src="${file.path}">

                <button type="button" class="btn remove-image">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M6.00098 17.9995L17.9999 6.00053" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M17.9999 17.9995L6.00098 6.00055" stroke="#292D32" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>

                <input type="hidden" name="${inputName}" value="${file.id}">
            </div>
        `);
    }

    sortable() {
        let imageList = $(".image-list");

        if (imageList.length > 0) {
            Sortable.create(imageList[0], { animation: 150 });
        }
    }

    removeImageEventListener() {
        $(".image-holder-wrapper").on("click", ".remove-image", (e) => {
            e.preventDefault();

            let imageHolderWrapper = $(e.currentTarget).closest(
                ".image-holder-wrapper"
            );

            if (imageHolderWrapper.find(".image-holder").length === 1) {
                imageHolderWrapper.html(
                    this.getImagePlaceholder(e.currentTarget.dataset.inputName)
                );
            }

            $(e.currentTarget).parent().remove();
        });
    }

    getImagePlaceholder(inputName) {
        return `
            <div class="image-holder placeholder cursor-auto">
                <i class="fa fa-picture-o"></i>
                
                <input type="hidden" name="${inputName}">
            </div>
        `;
    }
}
