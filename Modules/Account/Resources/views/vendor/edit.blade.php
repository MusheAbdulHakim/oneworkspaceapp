@if(!empty($vendor))
    {{Form::model($vendor,array('route' => array('vendors.update', $vendor->id), 'method' => 'PUT', 'enctype' => 'multipart/form-data')) }}
@else
    {{ Form::open(['route' => ['vendors.store'], 'method' => 'post']) }}
@endif
<div class="modal-body">
    <input type="hidden" name="user_id" value="{{ $user->id}}">
    <h6 class="sub-title">{{__('Basic Info')}}</h6>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('name',__('Name'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    <span><i class="ti ti-address-card"></i></span>
                    {{Form::text('name',!empty($vendor)? $vendor->name : $user->name,array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('contact',__('Contact'),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    <span><i class="ti ti-mobile-alt"></i></span>
                    {{Form::text('contact',!empty($vendor)? $vendor->contact : $user->mobile_no,array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('tax_number',__('Tax Number'),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    <span><i class="ti ti-crosshairs"></i></span>
                    {{Form::text('tax_number',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customfield::formBuilder',['fildedata' => !empty($vendor->customField) ? $vendor->customField : ''])
                </div>
            </div>
        @endif
    </div>
    <h6 class="sub-title">{{__('BIlling Address')}}</h6>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_name',__('Name'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('billing_name',null,array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_phone',__('Phone'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('billing_phone',null,array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('billing_address',__('Address'),array('class'=>'form-label')) }}
                <div class="input-group">
                    {{Form::textarea('billing_address',null,array('class'=>'form-control','rows'=>3,'required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_city',__('City'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('billing_city',null,array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_state',__('State'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('billing_state',null,array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_country',__('Country'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('billing_country',null,array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_zip',__('Zip Code'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('billing_zip',null,array('class'=>'form-control','required'=>'required'))}}
                </div>
            </div>
        </div>
</div>
@if(company_setting('bill_shipping_display')=='on')
    <div class="col-md-12 text-end">
        <input type="button" id="billing_data" value="{{__('Shipping Same As Billing')}}" class="btn btn-primary">
    </div>
    <h6 class="sub-title">{{__('Shipping Address')}}</h6>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_name',__('Name'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('shipping_name',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_phone',__('Phone'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('shipping_phone',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('shipping_address',__('Address'),array('class'=>'form-label')) }}
                <div class="input-group">
                    {{Form::textarea('shipping_address',null,array('class'=>'form-control','rows'=>3))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_city',__('City'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('shipping_city',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_state',__('State'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('shipping_state',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_country',__('Country'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('shipping_country',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('shipping_zip',__('Zip Code'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('shipping_zip',null,array('class'=>'form-control'))}}
                </div>
            </div>
        </div>
    </div>
</div>

@endif
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
</div>

{{Form::close()}}
