<div id="description" class="tab-pane description custom-page-content active">
    <div
        x-ref="descriptionContent" 
        class="content"
        :class="{ 
            active: showDescriptionContent,
            'less-content': !showMore }
        "
    >
        {!! $product->description !!}
    </div>

    <button
        x-cloak
        type="button"
        class="btn btn-default btn-show-more"
        :class="{ 'show': showMore }"
        @click="toggleDescriptionContent"
        x-text="
            showDescriptionContent ?
            '{{ trans('storefront::product.show_less') }}' :
            '{{ trans('storefront::product.show_more') }}'
        "
    >
    </button>
</div>
