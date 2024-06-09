<!--Bank Accounts Settings-->

<div id="bank-accounts-sidenav" class="card">
    {{ Form::open(['route' => ['bankaccount.setting.store'], 'id' => 'payment-form']) }}
    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5 class="">{{ __('Bank Accounts') }}</h5>
                <small>{{ __('Edit Bank Accounts settings') }}</small>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="bank_account_payment_is_on" class="form-check-input input-primary"
                        id="bank_account_payment_is_on"
                        {{ isset($settings['bank_account_payment_is_on']) && $settings['bank_account_payment_is_on'] == 'on' ? ' checked ' : '' }}>
                    <label class="form-check-label" for="bank_account_payment_is_on"></label>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body" style="max-height: 270px; overflow:auto">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Bank') }}</th>
                        <th>{{ __('Account number') }}</th>
                        <th>{{ __('Current Balance') }}</th>
                        <th>{{ __('Bank Address') }}</th>
                        <th>{{ __('Contact Number') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>
                <tbody class="list"   {{ isset($settings['bank_account_payment_is_on']) && $settings['bank_account_payment_is_on'] == 'on' ? '' : ' disabled' }} id="bank_accounts_details">
                    @foreach ($accounts as $account)
                        <tr>
                            <td>{{ $account->holder_name }}</td>
                            <td>{{ $account->bank_name }}</td>
                            <td>{{ $account->account_number }}</td>
                            <td>{{ currency_format_with_sym($account->opening_balance) }}</td>
                            <td>{{ $account->bank_address }}</td>
                            <td>{{ $account->contact_number }}</td>

                            <td>
                                <div class="form-check form-switch custom-switch-v1 float-end">
                                    <input type="hidden" name="bank_account[{{ $account->id }}]" value="off">
                                    @php
                                        $bankAccountArray = isset($settings['bank_account']) ? explode(',', $settings['bank_account']) : [];

                                    @endphp
                                    <input type="checkbox" class="form-check-input input-primary" name="bank_account[{{ $account->id }}]"
                                        data-bs-placement="top" data-id="{{ $account->id }}" data-title="{{ __('Enable/Disable') }}" id="bank_account_{{ $account->id }}"
                                        data-bs-toggle="tooltip"
                                        {{ is_array($bankAccountArray) && in_array($account->id, $bankAccountArray) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="bank_account_{{ $account->id }}"></label>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer text-end">
        <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
    </div>
    {{ Form::close() }}
</div>
