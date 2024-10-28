<form class="form-horizontal" action="{{ route('payment_method.update') }}" method="POST">
    @csrf
    <input type="hidden" name="payment_method" value="payfast">
    <div class="form-group row">
        <input type="hidden" name="types[]" value="PAYFAST_MERCHANT_ID">
        <div class="col-lg-4">
            <label class="col-from-label">{{ translate('PAYFAST_MERCHANT_ID') }}</label>
        </div>
        <div class="col-lg-8">
            <input type="text" class="form-control" name="PAYFAST_MERCHANT_ID"
                value="{{ env('PAYFAST_MERCHANT_ID') }}"
                placeholder="{{ translate('PAYFAST_MERCHANT_ID') }}" required>
        </div>
    </div>
    <div class="form-group row">
        <input type="hidden" name="types[]" value="PAYFAST_MERCHANT_KEY">
        <div class="col-lg-4">
            <label class="col-from-label">{{ translate('PAYFAST_MERCHANT_KEY') }}</label>
        </div>
        <div class="col-lg-8">
            <input type="text" class="form-control" name="PAYFAST_MERCHANT_KEY"
                value="{{ env('PAYFAST_MERCHANT_KEY') }}"
                placeholder="{{ translate('PAYFAST_MERCHANT_KEY') }}" required>
        </div>
    </div>

    <div class="form-group row">
        <div class="col-md-4">
            <label class="col-from-label">{{ translate('PAYFAST Sandbox Mode') }}</label>
        </div>
        <div class="col-md-8">
            <label class="aiz-switch aiz-switch-success mb-0">
                <input value="1" name="payfast_sandbox" type="checkbox"
                    @if (get_setting('payfast_sandbox') == 1) checked @endif>
                <span class="slider round"></span>
            </label>
        </div>
    </div>

    <div class="form-group mb-0 text-right">
        <button type="submit" class="btn btn-sm btn-primary">{{ translate('Save') }}</button>
    </div>
</form>