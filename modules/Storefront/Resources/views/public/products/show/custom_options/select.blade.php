<div class="form-group variant-select">
    <div class="row">
        <div class="col-lg-18">
            <label for="option-{{ $option->id }}">
                {!! $option->name . ($option->is_required ? '<span>*</span>' : '') !!}
            </label>
        </div>

        <div class="col-lg-18">
            <div>
                @if ($option->type === 'multiple_select')
                    <select
                        name="options[{{ $option->id }}][]"
                        class="form-control"
                        id="option-{{ $option->id }}"
                        x-model.number="cartItemForm.options[{{ $option->id }}]"
                        multiple
                    >
                        @if ($option->type === 'dropdown')
                            <option value="" selected>{{ trans('storefront::product.options.choose_an_option') }}</option>
                        @endif
    
                        @foreach ($option->values as $value)
                            <option value="{{ $value->id }}">{{ $value->label }}</option>
                        @endforeach
                    </select>
                @else
                    <select
                        name="options[{{ $option->id }}]"
                        class="form-control"
                        id="option-{{ $option->id }}"
                        @change="updateSelectTypeOptionValue({{ $option->id }}, $event)"
                    >
                        @if ($option->type === 'dropdown')
                            <option value="" selected>{{ trans('storefront::product.options.choose_an_option') }}</option>
                        @endif
    
                        @foreach ($option->values as $value)
                            <option value="{{ $value->id }}">{{ $value->label }}</option>
                        @endforeach
                    </select>
                @endif
            </div>

            <template x-if="errors.has('{{ "options.{$option->id}" }}')">
                <span class="error-message" x-text="errors.get('{{ "options.{$option->id}" }}')"></span>
            </template>
        </div>
    </div>
</div>
