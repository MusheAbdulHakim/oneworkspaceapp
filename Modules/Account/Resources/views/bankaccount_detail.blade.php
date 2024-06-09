@if(module_is_active('Account') && !empty($bankaccounts))
    <div class="row">
        @if(isset($bankaccounts['bank_name']))
            <div class="col-md-8">
                <h6>{{__('Bank Details')}}</h6>
                <div class="bill-to">

                        <span>{{__('Bank Name')}} : {{$bankaccounts['bank_name']}}</span><br>
                        <span>{{__('Account Number')}} : {{$bankaccounts['account_number']}}</span><br>
                        <span>{{__('Current Balance')}} : {{$bankaccounts['opening_balance']}}</span><br>
                        <span>{{__('Contact Number')}} : {{$bankaccounts['contact_number']}}</span><br>
                        <span>{{__('Bank Branch')}} : {{$bankaccounts['bank_branch']}}</span><br>

                </div>
            </div>
        @else
         <div class="col-md-10">
            <h6 class="">{{__('Please On Your Bank Account!')}}
                @if(module_is_active('Account'))
                    <a href="{{ route('settings.index') }}">{{ __('Click Here')}}</a>
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

    <div class="col-md-2 mt-5">
        <a href="#" id="remove" class="text-sm">{{__(' Remove')}}</a>
    </div>
</div>
@endif
