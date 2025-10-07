<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('nagad_enabled', trans('setting::attributes.nagad_enabled'), trans('setting::settings.form.enable_nagad'), $errors, $settings) }}
        {{ Form::text('translatable[nagad_label]', trans('setting::attributes.translatable.nagad_label'), $errors, $settings, ['required' => true]) }}
        {{ Form::textarea('translatable[nagad_description]', trans('setting::attributes.translatable.nagad_description'), $errors, $settings, ['rows' => 3, 'required' => true]) }}
        {{ Form::checkbox('nagad_test_mode', trans('setting::attributes.nagad_test_mode'), trans('setting::settings.form.use_sandbox_for_test_payments'), $errors, $settings) }}

        <div class="{{ old('nagad_enabled', array_get($settings, 'nagad_enabled')) ? '' : 'hide' }}" id="nagad-fields">
            {{ Form::text('nagad_merchant_id', trans('setting::attributes.nagad_merchant_id'), $errors, $settings, ['required' => true]) }}
            {{ Form::text('nagad_merchant_number', trans('setting::attributes.nagad_merchant_number'), $errors, $settings, ['required' => true]) }}
            {{ Form::textarea('nagad_public_key', trans('setting::attributes.nagad_public_key'), $errors, $settings, ['required' => true]) }}
            {{ Form::textarea('nagad_private_key', trans('setting::attributes.nagad_private_key'), $errors, $settings, ['required' => true]) }}
        </div>
    </div>
</div>
