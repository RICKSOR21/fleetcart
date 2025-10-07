<div class="form-group variant-radio">
    <div class="row">
        <div class="col-lg-18">
            <label>
                {!!
                    $option->name .
                    ($option->is_required ? '<span>*</span>' : '')
                !!}
            </label>
        </div>

        <div class="col-lg-18">
            @foreach ($option->values as $value)
                <div class="form-radio">
                    <input
                        type="radio"
                        name="options[{{ $option->id }}]"
                        value="{{ $value->id }}"
                        id="option-{{ $option->id }}-value-{{ $value->id }}"
                        x-model="cartItemForm.options[{{ $option->id }}]"
                    >

                    <label for="option-{{ $option->id }}-value-{{ $value->id }}">
                        {{ $value->label }}
                    </label>
                </div>
            @endforeach
            
            <template x-if="errors.has('{{ "options.{$option->id}" }}')">
                <span class="error-message" x-text="errors.get('{{ "options.{$option->id}" }}')"></span>
            </template>
        </div>
    </div>
</div>
