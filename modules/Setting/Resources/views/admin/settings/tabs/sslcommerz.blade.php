<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('sslcommerz_enabled', trans('setting::attributes.sslcommerz_enabled'), trans('setting::settings.form.enable_sslcommerz'), $errors, $settings) }}
        {{ Form::text('translatable[sslcommerz_label]', trans('setting::attributes.translatable.sslcommerz_label'), $errors, $settings, ['required' => true]) }}
        {{ Form::textarea('translatable[sslcommerz_description]', trans('setting::attributes.translatable.sslcommerz_description'), $errors, $settings, ['rows' => 3, 'required' => true]) }}
        {{ Form::checkbox('sslcommerz_test_mode', trans('setting::attributes.sslcommerz_test_mode'), trans('setting::settings.form.use_sandbox_for_test_payments'), $errors, $settings) }}

        <div class="{{ old('sslcommerz_enabled', array_get($settings, 'sslcommerz_enabled')) ? '' : 'hide' }}" id="sslcommerz-fields">
            {{ Form::text('sslcommerz_store_id', trans('setting::attributes.sslcommerz_store_id'), $errors, $settings, ['required' => true]) }}
            {{ Form::password('sslcommerz_store_password', trans('setting::attributes.sslcommerz_store_password'), $errors, $settings, ['required' => true]) }}
            {{ Form::checkbox('sslcommerz_is_localhost', trans('setting::attributes.sslcommerz_is_localhost'), trans('setting::settings.form.sslcommerz_is_localhost'), $errors, $settings) }}
        </div>
    </div>
</div>
