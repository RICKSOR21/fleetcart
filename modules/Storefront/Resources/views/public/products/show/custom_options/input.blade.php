<div class="form-group variant-input">
    <div class="row">
        <div class="col-lg-18">
            <label for="option-{{ $option->id }}">
                {!! $option->name . ($option->is_required ? '<span>*</span>' : '') !!}
            </label>
        </div>

        <div class="col-lg-18">
            <div class="form-input">
                <input
                    name="options[{{ $option->id }}]"
                    class="form-control {{ array_pull($attributes, 'class') }}"
                    id="option-{{ $option->id }}"
                    x-model="cartItemForm.options[{{ $option->id }}]"
                    {{ html_attrs($attributes) }}
                >
            </div>

            <template x-if="errors.has('{{ "options.{$option->id}" }}')">
                <span class="error-message" x-text="errors.get('{{ "options.{$option->id}" }}')"></span>
            </template>
        </div>
    </div>
</div>
