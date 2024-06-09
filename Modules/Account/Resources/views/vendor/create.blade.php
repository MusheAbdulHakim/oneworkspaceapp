{{Form::open(array('url'=>'vendors','method'=>'post', 'enctype' => 'multipart/form-data'))}}
<div class="modal-body">
    <h6 class="sub-title">{{__('Basic Info')}}</h6>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('name',__('Name'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('name',null,array('class'=>'form-control','required'=>'required','placeholder'=>'Enter Name'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('contact',__('Contact'),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::text('contact',null,array('class'=>'form-control','required'=>'required','placeholder'=>'Enter Contact'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('email',__('Email'),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::email('email',null,array('class'=>'form-control','required'=>'required','placeholder'=>'Enter Email'))}}
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('password',__('Password'),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::password('password',array('class'=>'form-control','required'=>'required','minlength'=>"6",'placeholder'=>'Enter Password'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{Form::label('tax_number',__('Tax Number'),['class'=>'form-label'])}}
                <div class="form-icon-user">
                    {{Form::text('tax_number',null,array('class'=>'form-control','placeholder'=>'Enter Tax Number'))}}
                </div>
            </div>
        </div>
        @if (module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customfield::formBuilder')
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
                    {{Form::text('billing_name',null,array('class'=>'form-control','placeholder'=>'Enter Name','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_phone',__('Phone'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('billing_phone',null,array('class'=>'form-control','placeholder'=>'Enter Phone','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{Form::label('billing_address',__('Address'),array('class'=>'form-label')) }}
                <div class="input-group">
                    {{Form::textarea('billing_address',null,array('class'=>'form-control','rows'=>3,'placeholder'=>'Enter Address','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_city',__('City'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('billing_city',null,array('class'=>'form-control','placeholder'=>'Enter City','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_state',__('State'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('billing_state',null,array('class'=>'form-control','placeholder'=>'Enter State','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_country',__('Country'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('billing_country',null,array('class'=>'form-control','placeholder'=>'Enter Country','required'=>'required'))}}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{Form::label('billing_zip',__('Zip Code'),array('class'=>'form-label')) }}
                <div class="form-icon-user">
                    {{Form::text('billing_zip',null,array('class'=>'form-control','placeholder'=>__(''),'placeholder'=>'Enter Zip Code','required'=>'required'))}}
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
                        {{Form::text('shipping_name',null,array('class'=>'form-control','placeholder'=>'Enter Name'))}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_phone',__('Phone'),array('class'=>'form-label')) }}
                    <div class="form-icon-user">
                        {{Form::text('shipping_phone',null,array('class'=>'form-control','placeholder'=>'Enter Phone'))}}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('shipping_address',__('Address'),array('class'=>'form-label')) }}
                    <div class="input-group">
                        {{Form::textarea('shipping_address',null,array('class'=>'form-control','rows'=>3,'placeholder'=>'Enter Address'))}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_city',__('City'),array('class'=>'form-label')) }}
                    <div class="form-icon-user">
                        {{Form::text('shipping_city',null,array('class'=>'form-control','placeholder'=>'Enter City'))}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_state',__('State'),array('class'=>'form-label')) }}
                    <div class="form-icon-user">
                        {{Form::text('shipping_state',null,array('class'=>'form-control','placeholder'=>'Enter State'))}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_country',__('Country'),array('class'=>'form-label')) }}
                    <div class="form-icon-user">
                        {{Form::text('shipping_country',null,array('class'=>'form-control','placeholder'=>'Enter Country'))}}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{Form::label('shipping_zip',__('Zip Code'),array('class'=>'form-label')) }}
                    <div class="form-icon-user">
                        {{Form::text('shipping_zip',null,array('class'=>'form-control','placeholder'=>__(''),'placeholder'=>'Enter Zip Code'))}}
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>
{{Form::close()}}
