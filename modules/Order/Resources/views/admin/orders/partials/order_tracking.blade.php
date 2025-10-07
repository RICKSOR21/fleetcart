<div class="order-tracking-wrapper">
    <h4 class="section-title">{{ trans('order::orders.order_tracking') }}</h4>

    <form action="{{ route('admin.orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-lg-5 col-md-8">
                <label for="tracking_reference">{{ trans('order::orders.tracking_reference') }}</label>

                <div class="form-group">
                    <input
                        type="text"
                        name="tracking_reference"
                        id="tracking_reference"
                        class="form-control @error('tracking_reference') is-invalid @enderror"
                        value="{{ old('tracking_reference', $order->tracking_reference) }}"
                        placeholder="{{ trans('order::orders.tracking_reference_placeholder') }}"
                    >

                    @error('tracking_reference')
                        <span class="help-block text-red">
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="text-left mt-3">
                    <button type="submit" class="btn btn-primary">
                        {{ trans('admin::admin.buttons.save') }}
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
