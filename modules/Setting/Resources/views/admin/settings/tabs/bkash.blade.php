<div class="row">
    <div class="col-md-8">
        {{ Form::checkbox('bkash_enabled', trans('setting::attributes.bkash_enabled'), trans('setting::settings.form.enable_bkash'), $errors, $settings) }}
        {{ Form::text('translatable[bkash_label]', trans('setting::attributes.translatable.bkash_label'), $errors, $settings, ['required' => true]) }}
        {{ Form::textarea('translatable[bkash_description]', trans('setting::attributes.translatable.bkash_description'), $errors, $settings, ['rows' => 3, 'required' => true]) }}
        {{ Form::checkbox('bkash_test_mode', trans('setting::attributes.bkash_test_mode'), trans('setting::settings.form.use_sandbox_for_test_payments'), $errors, $settings) }}

        <div class="{{ old('bkash_enabled', array_get($settings, 'bkash_enabled')) ? '' : 'hide' }}" id="bkash-fields">
            {{ Form::text('bkash_app_key', trans('setting::attributes.bkash_app_key'), $errors, $settings, ['required' => true]) }}
            {{ Form::password('bkash_app_secret', trans('setting::attributes.bkash_app_secret'), $errors, $settings, ['required' => true]) }}
            {{ Form::text('bkash_username', trans('setting::attributes.bkash_username'), $errors, $settings, ['required' => true]) }}
            {{ Form::password('bkash_password', trans('setting::attributes.bkash_password'), $errors, $settings, ['required' => true]) }}
        </div>
    </div>
</div>
