@if(module_is_active('Account') && !empty($customer))
    <div class="row">
        @if(isset($customer['billing_name']))
            <div class="col-md-5">
                <h6>{{__('Bill to')}}</h6>
                <div class="bill-to">
                    <small>
                        <span>{{$customer['billing_name']}}</span><br>
                        <span>{{$customer['billing_address']}}</span><br>
                        <span>{{$customer['billing_city'].' , '.$customer['billing_state'].' ,'. $customer['billing_zip']}}</span><br>
                        <span>{{$customer['billing_country']}}</span><br>
                        <span>{{$customer['billing_phone']}}</span><br>
                    </small>
                </div>
            </div>
            <div class="col-md-5">
                <h6>{{__('Ship to')}}</h6>
                <div class="bill-to">
                    <small>
                        <span>{{$customer['shipping_name']}}</span><br>
                        <span>{{$customer['shipping_address']}}</span><br>
                        <span>{{$customer['shipping_city'].' , '.$customer['shipping_state'].' ,'. $customer['shipping_zip']}}</span><br>
                        <span>{{$customer['shipping_country']}}</span><br>
                        <span>{{$customer['shipping_phone']}}</span><br>
                    </small>
                </div>
            </div>
        @else
        <div class="col-md-10">
                <div class="mt-3"><h6>{{$customer['name']}}</h6><h6>{{$customer['email']}}</h6></div>
            <h6 class="">{{__('Please Set Customer Shipping And Billing  Details !')}}
                @if(module_is_active('Account'))
                    <a href="{{ route('customer.index') }}">{{ __('Click Here')}}</a>
                @endif
            </h6>
        </div>
        @endif

        <div class="col-md-2">
            <a href="#" id="remove" class="text-sm">{{__(' Remove')}}</a>
        </div>
    </div>
@else
<div class="row">
    <div class="col-md-5">
        <h6 class="mt-5">{{__('Please Set Customer Details !')}}
            <div class="mt-3"><h6>{{$customer['name']}}</h6><h6>{{$customer['email']}}</h6></div>
            @if(module_is_active('Account'))
                <a href="{{ route('customer.index') }}">{{ __('Click Here')}}</a>
            @endif
        </h6>
    </div>
    <div class="col-md-2 mt-5">
        <a href="#" id="remove" class="text-sm">{{__(' Remove')}}</a>
    </div>
</div>
@endif
