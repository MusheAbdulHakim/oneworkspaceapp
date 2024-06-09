@if(!empty($vendor))
    <div class="row">
        <div class="col-md-5 col-sm-6 col-12">
            <h6>{{__('Bill to')}}</h6>
            <div class="bill-to">
                <p>
                    <span>{{$vendor['billing_name']}}</span><br>
                    <span>{{$vendor['billing_address']}}</span><br>
                    <span>{{$vendor['billing_city'].' , '.$vendor['billing_state'].' ,'. $vendor['billing_zip']}}</span><br>
                    <span>{{$vendor['billing_country']}}</span><br>
                    <span>{{$vendor['billing_phone']}}</span><br>
                </p>
            </div>
        </div>
        <div class="col-md-5 col-sm-6 col-12">
            <h6>{{__('Ship to')}}</h6>
            <div class="bill-to">
                <p>
                    <span>{{$vendor['shipping_name']}}</span><br>
                    <span>{{$vendor['shipping_address']}}</span><br>
                    <span>{{$vendor['shipping_city'].' , '.$vendor['shipping_state'].' ,'. $vendor['shipping_zip']}}</span><br>
                    <span>{{$vendor['shipping_country']}}</span><br>
                    <span>{{$vendor['shipping_phone']}}</span><br>
                </p>
            </div>
        </div>
        <div class="col-md-2">
            <a href="#" id="remove" class="text-sm">{{__(' Remove')}}</a>
        </div>
    </div>
@endif
