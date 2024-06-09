@if ($type == 'mobileservice')

    <div class="row">
        <div class="col-12">
            <div class="col-md-11 text-end">
                <a href="#" id="remove" class="text-sm">{{ __(' Remove') }}</a>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="customer_name"
                        class="form-label">{{ __('Customer Name : ') }}</label><br>
                </div>
                <div class="col-md-6">
                    {{ $customer->customer_name }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="sender_mobileno"
                        class="form-label">{{ __('Customer Mobile No : ') }}</label><br>
                </div>
                <div class="col-md-6">
                    {{ $customer->mobile_no }}

                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="sender_email"
                        class="form-label">{{ __('Customer Email Address : ') }}</label><br>
                </div>
                <div class="col-md-6">
                    {{ $customer->email }}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="sender_email"
                        class="form-label">{{ __('Created By : ') }}</label><br>
                </div>
                <div class="col-md-6">
                    {{ $customer->getServiceCreatedName->name }}
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <label class="form-label" for="sender_email"
                        class="form-label">{{ __('Request Status : ') }}</label><br>
                </div>
                <div class="col-md-6">
                    <span
                        class="badge fix_badge @if ($customer->is_approve == 1) bg-success @else bg-danger @endif  p-2 px-3 rounded">
                        @if ($customer->is_approve == 1)
                            {{ __('Accepted') }}
                        @else
                            {{ __('Rejected') }}
                        @endif
                    </span>
                </div>
            </div>

        </div>

    </div>
@else
    @if (module_is_active('Account') && !empty($customer))
        <div class="row">
            @if (isset($customer['billing_name']))
                <div class="col-md-5 col-sm-6 col-12">
                    <h6>{{ __('Bill to') }}</h6>
                    <div class="bill-to">
                        <p>
                            <span>{{ $customer['billing_name'] }}</span><br>
                            <span>{{ $customer['billing_address'] }}</span><br>
                            <span>{{ $customer['billing_city'] . ' , ' . $customer['billing_state'] . ' ,' . $customer['billing_zip'] }}</span><br>
                            <span>{{ $customer['billing_country'] }}</span><br>
                            <span>{{ $customer['billing_phone'] }}</span><br>
                        </p>
                    </div>
                </div>
                <div class="col-md-5  col-sm-6 col-12">
                    <h6>{{ __('Ship to') }}</h6>
                    <div class="bill-to">
                        <p>
                            <span>{{ $customer['shipping_name'] }}</span><br>
                            <span>{{ $customer['shipping_address'] }}</span><br>
                            <span>{{ $customer['shipping_city'] . ' , ' . $customer['shipping_state'] . ' ,' . $customer['shipping_zip'] }}</span><br>
                            <span>{{ $customer['shipping_country'] }}</span><br>
                            <span>{{ $customer['shipping_phone'] }}</span><br>
                        </p>
                    </div>
                </div>
            @else
                <div class="col-md-10">
                    <div class="mt-3">
                        <h6>{{ $customer['name'] }}</h6>
                        <h6>{{ $customer['email'] }}</h6>
                    </div>
                    <h6 class="">{{ __('Please Set Customer Shipping And Billing  Details !') }}
                        @if (module_is_active('Account'))
                            <a href="{{ route('customer.index') }}">{{ __('Click Here') }}</a>
                        @endif
                    </h6>
                </div>
            @endif

            <div class="col-md-2">
                <a href="#" id="remove" class="text-sm">{{ __(' Remove') }}</a>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-10">
                <h6 class="">
                    <div class="mt-3">
                        <h6>{{ $customer['name'] }}</h6>
                        <h6>{{ $customer['email'] }}</h6>
                    </div>
                    {{ __('Please Set Customer Details !') }}
                </h6>
            </div>
            <div class="col-md-2 mt-3">
                <a href="#" id="remove" class="text-sm">{{ __(' Remove') }}</a>
            </div>
        </div>
    @endif
@endif
