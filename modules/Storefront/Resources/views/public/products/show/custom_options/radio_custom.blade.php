<div class="form-group variant-custom-selection">
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
            <ul class="list-inline form-custom-radio custom-selection">
                @foreach ($option->values as $value)
                    <li
                        :class="{ active: customRadioTypeOptionValueIsActive({{ $option->id }}, {{ $value->id }}) }"
                        @click="syncCustomRadioTypeOptionValue({{ $option->id }}, {{ $value->id }})"
                    >
                        {{ $value->label }}
                    </li>
                @endforeach
            </ul>

            <template x-if="errors.has('{{ "options.{$option->id}" }}')">
                <span class="error-message" x-text="errors.get('{{ "options.{$option->id}" }}')"></span>
            </template>
        </div>
    </div>
</div>
