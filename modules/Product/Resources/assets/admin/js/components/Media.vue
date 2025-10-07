<template>
    <div class="box-header">
        <h5>
            {{ trans("product::products.group.media") }}
        </h5>

        <div class="drag-handle">
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
            <i class="fa fa-ellipsis-h" aria-hidden="true"></i>
        </div>
    </div>

    <div class="box-body">
        <div class="row">
            <div class="col-md-12">
                <div class="">
                    <draggable
                        class="product-media-grid"
                        animation="200"
                        item-key="index"
                        handle=".handle"
                        :list="form.media"
                    >
                        <template #item="{ element: media, index }">
                            <div class="media-grid-item handle">
                                <div class="image-holder">
                                    <img
                                        :src="media.path"
                                        alt="product media"
                                    />

                                    <button
                                        type="button"
                                        class="btn remove-image"
                                        @click="removeMedia(index)"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            width="24"
                                            height="24"
                                            viewBox="0 0 24 24"
                                            fill="none"
                                        >
                                            <path
                                                d="M6.00098 17.9995L17.9999 6.00053"
                                                stroke="#292D32"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                            <path
                                                d="M17.9999 17.9995L6.00098 6.00055"
                                                stroke="#292D32"
                                                stroke-width="1.5"
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>

                        <template #footer>
                            <div
                                class="media-grid-item media-picker disabled"
                                @click="addMedia"
                            >
                                <div class="image-holder">
                                    <img
                                        src="@admin/images/placeholder_image.png"
                                        class="placeholder-image"
                                        alt="Placeholder Image"
                                    />
                                </div>
                            </div>
                        </template>
                    </draggable>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm } from "../composables/useForm";
import draggable from "vuedraggable";

const { form } = useForm();

function addMedia() {
    const picker = new MediaPicker({ type: "image", multiple: true });

    picker.on("select", ({ id, path }) => {
        form.media.push({
            id: +id,
            path,
        });
    });
}

function removeMedia(index) {
    form.media.splice(index, 1);
}
</script>
