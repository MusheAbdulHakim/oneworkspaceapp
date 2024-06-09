<div class="tab-pane fade" id="bankaccount-payment" role="tabpanel" aria-labelledby="bankaccount-payment">
    <form method="post" action="{{ route('invoice.pay.with.bankaccount') }}" class="require-validation" id="payment-form"
        enctype="multipart/form-data">
        <input type="hidden" name="payment_type" id="payment_type" value="Bank Account">
        @csrf
        @if ($type == 'invoice')
            <input type="hidden" name="type" value="invoice">
        @elseif ($type == 'salesinvoice')
            <input type="hidden" name="type" value="salesinvoice">
        @elseif ($type == 'retainer')
            <input type="hidden" name="type" value="retainer">
        @endif
        <div class="row">
            <div class="row mt-2">
                <div class="col-sm-8">
                    <div class="form-group" id="bankaccount-box">
                        {{ Form::label('customer_id', __('Bank Account'), ['class' => 'form-label']) }}
                        {{ Form::select('customer_id', $bank_accounts, $bankaccountId, ['class' => 'form-control bank_account', 'id' => 'account', 'data-url' => route('bankaccount.details'), 'required' => 'required', 'placeholder' => 'Select Customer']) }}
                    </div>
                    <div id="account_detail" class="col-sm-8 d-none">
                    </div>
                </div>

                <div class="col-sm-4">

                    <div class="form-group">
                        <label class="form-label">{{ __('Payment Receipt') }}</label>
                        <div class="choose-files">
                            <label for="paymentbank_receipt">
                                <div class=" bg-primary "> <i class="ti ti-upload px-1"></i></div>
                                <input type="file" class="form-control paymentbank_receipt"
                                    accept="image/png, image/jpeg, image/jpg, .pdf" name="paymentbank_receipt"
                                    id="paymentbank_receipt" data-filename="paymentbank_receipt"
                                    onchange="document.getElementById('blah4').src = window.URL.createObjectURL(this.files[0])">
                            </label>
                            <p class="text-danger error_msg d-none" id="payment_validation">
                                {{ __('This field is required') }}</p>

                            <img class="mt-2" width="70px" id="blah4">
                        </div>
                    </div>
                </div>
                <small
                    class="text-danger">{{ __('first, make a payment and take a screenshot or download the receipt and upload it.') }}</small>
                <div class="form-group col-md-12">
                    <label for="amount">{{ __('Amount') }}</label>
                    <div class="input-group">
                        <span class="input-group-prepend"><span
                                class="input-group-text">{{ !empty(company_setting('defult_currancy', $invoice->created_by, $invoice->workspace)) ? company_setting('defult_currancy', $invoice->created_by, $invoice->workspace) : '$' }}</span>
                        </span>
                        <input class="form-control" required="required" min="0" name="amount" type="number"
                            value="{{ $invoice->getDue() }}" min="0" step="0.01"
                            max="{{ $invoice->getDue() }}" id="amount">
                        <input type="hidden" value="{{ $invoice->id }}" name="invoice_id">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="error" style="display: none;">
                        <div class='alert-danger alert'>
                            {{ __('Please correct the errors and try again.') }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="text-end">
                <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
                <button class="btn btn-primary" type="submit" id="submit">{{ __('Make Payment') }}</button>
            </div>
        </div>
    </form>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            var bankaccountId = '{{ $bankaccountId }}';

            if (bankaccountId > 0) {
                $('.bank_account').val(bankaccountId).change();
            }
        });
        $(document).on('change', '.bank_account', function() {

            $('#account_detail').removeClass('d-none');
            $('#account_detail').addClass('d-block');
            $('#bankaccount-box').addClass('d-none');
            var id = $(this).val();

            var url = $(this).data('url');


            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function(data) {
                    if (data != '') {
                        $('#account_detail').html(data);
                    } else {
                        $('#bankaccount-box').removeClass('d-none');
                        $('#account_detail').removeClass('d-block');
                        $('#account_detail').addClass('d-none');
                    }

                },

            });
        });

        $(document).on('click', '#remove', function() {
            $('#bankaccount-box').removeClass('d-none');
            $('#account_detail').removeClass('d-block');
            $('#account_detail').addClass('d-none');
        })

        $("#submit").click(function() {
            var skill = $('.paymentbank_receipt').val();
            if (skill == '') {
                $('#payment_validation').removeClass('d-none')
                return false;
            } else {
                $('#payment_validation').addClass('d-none')
            }
        })
    </script>
@endpush
