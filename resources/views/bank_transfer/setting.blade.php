{{-- Bank Paymet section --}}

<div class="card" id="bank-transfer-sidenav">
    {{ Form::open(['route' => ['bank.transfer.setting'], 'id' => 'payment-form']) }}
    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5 class="">{{ __('Bank Transfer') }}</h5>
                <small>{{ __('These details will be used to collect subscription, invoice, retainer, etc. payments.') }}</small>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="bank_transfer_payment_is_on" class="form-check-input input-primary" id="bank_transfer_payment_is_on" {{ (isset($settings['bank_transfer_payment_is_on']) && $settings['bank_transfer_payment_is_on'] =='on') ?' checked ':'' }} >
                    <label class="form-check-label" for="bank_transfer_payment_is_on"></label>
                </div>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="col-form-label">{{ __('Bank Details') }}</label>
                    <textarea type="text" name="bank_number" id="bank_number" class="form-control bank_transfer_text" {{ (isset($settings['bank_transfer_payment_is_on']) && $settings['bank_transfer_payment_is_on']  == 'on') ? '' : ' disabled' }} rows="3" placeholder="{{ __('Bank Transfer Number') }}">{{ !empty(company_setting('bank_number'))?company_setting('bank_number'):'' }}</textarea>
                    <small>{{ __('Example : Bank : bank name </br> Account Number : 0000 0000 </br>') }}</small>
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
    $(document).on('click', '#bank_transfer_payment_is_on', function() {
        if ($('#bank_transfer_payment_is_on').prop('checked')) {
            $(".bank_transfer_text").removeAttr("disabled");
        } else {
            $('.bank_transfer_text').attr("disabled", "disabled");
        }
    });
</script>
