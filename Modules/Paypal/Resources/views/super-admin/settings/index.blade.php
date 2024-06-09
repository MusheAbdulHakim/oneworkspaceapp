<div class="card" id="paypal-sidenav">
    {{ Form::open(['route' => ['paypal.setting.store'], 'enctype' => 'multipart/form-data', 'id' => 'payment-form']) }}

    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5 class="">{{ __('Paypal') }}</h5>
                <small>{{ __('These details will be used to collect subscription plan payments.Each subscription plan will have a payment button based on the below configuration.') }}</small>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="paypal_payment_is_on" class="form-check-input input-primary"
                        id="paypal_payment_is_on"
                        {{ isset($settings['paypal_payment_is_on']) && $settings['paypal_payment_is_on'] == 'on' ? ' checked ' : '' }}>
                    <label class="form-check-label" for="paypal_payment_is_on"></label>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <label class="paypal-label col-form-label" for="company_paypal_mode">{{ __('Paypal Mode') }}</label>
                <br>
                <div class="d-flex">
                    <div class="mr-2">
                        <div class="p-3">
                            <div class="form-check">
                                <label class="form-check-labe text-dark">
                                    <input type="radio" name="company_paypal_mode" value="sandbox"
                                        class="form-check-input"
                                        {{ !isset($settings['company_paypal_mode']) || $settings['company_paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>
                                    {{ __('Sandbox') }}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mr-2">
                        <div class="p-3">
                            <div class="form-check">
                                <label class="form-check-labe text-dark">
                                    <input type="radio" name="company_paypal_mode" value="live"
                                        class="form-check-input"
                                        {{ isset($settings['company_paypal_mode']) && $settings['company_paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>
                                    {{ __('Live') }}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="company_paypal_client_id" class="col-form-label">{{ __('Client ID') }}</label>
                    <input type="text" name="company_paypal_client_id" id="company_paypal_client_id"
                        class="form-control"
                        value="{{ !isset($settings['company_paypal_client_id']) || is_null($settings['company_paypal_client_id']) ? '' : $settings['company_paypal_client_id'] }}"
                        placeholder="{{ __('Client ID') }}"{{ isset($settings['paypal_payment_is_on']) && $settings['paypal_payment_is_on'] == 'on' ? '' : ' disabled' }}>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="company_paypal_secret_key" class="col-form-label">{{ __('Secret Key') }}</label>
                    <input type="text" name="company_paypal_secret_key" id="company_paypal_secret_key"
                        class="form-control"
                        value="{{ !isset($settings['company_paypal_secret_key']) || is_null($settings['company_paypal_secret_key']) ? '' : $settings['company_paypal_secret_key'] }}"
                        placeholder="{{ __('Secret Key') }}"
                        {{ isset($settings['paypal_payment_is_on']) && $settings['paypal_payment_is_on'] == 'on' ? '' : ' disabled' }}>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
    </div>
    {{ Form::close() }}

</div>

<script>
    $(document).on('click', '#paypal_payment_is_on', function() {
        if ($('#paypal_payment_is_on').prop('checked')) {
            $("#company_paypal_client_id").removeAttr("disabled");
            $("#company_paypal_secret_key").removeAttr("disabled");
        } else {
            $('#company_paypal_client_id').attr("disabled", "disabled");
            $('#company_paypal_secret_key').attr("disabled", "disabled");
        }
    });
</script>
