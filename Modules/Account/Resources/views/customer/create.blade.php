{{ Form::open(['url' => 'customer', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <h6 class="sub-title">{{ __('Basic Info') }}</h6>
    <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Name']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('contact', __('Contact'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('contact', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Contact']) }}
                    <div class=" text-xs text-danger">
                        {{ __('Please add contact number with country code. (ex. +91)') }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('email', __('Email'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::email('email', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Email']) }}
                </div>
            </div>
        </div>
        @stack('electronic_address')
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('password', __('Password'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::password('password', ['class' => 'form-control', 'required' => 'required', 'minlength' => '6', 'placeholder' => 'Enter Password']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6">
            <div class="form-group">
                {{ Form::label('tax_number', __('Tax Number'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('tax_number', null, ['class' => 'form-control', 'placeholder' => 'Enter Tax Number']) }}
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

    <h6 class="sub-title">{{ __('Billing Address') }}</h6>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_name', __('Name'), ['class' => '', 'class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('billing_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6  col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_phone', __('Phone'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('billing_phone', null, ['class' => 'form-control', 'placeholder' => __('Enter Phone'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('billing_address', __('Address'), ['class' => 'form-label']) }}
                <div class="input-group">
                    {{ Form::textarea('billing_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_city', __('City'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('billing_city', null, ['class' => 'form-control', 'placeholder' => __('Enter City'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_state', __('State'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('billing_state', null, ['class' => 'form-control', 'placeholder' => __('Enter State'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_country', __('Country'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('billing_country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6">
            <div class="form-group">
                {{ Form::label('billing_zip', __('Zip Code'), ['class' => 'form-label']) }}
                <div class="form-icon-user">
                    {{ Form::text('billing_zip', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code'), 'required' => 'required']) }}
                </div>
            </div>
        </div>
    </div>
    @if (company_setting('invoice_shipping_display') == 'on' || company_setting('proposal_shipping_display') == 'on' )
        <div class="col-md-12 text-end mb-2">
            <input type="button" id="billing_data" value="{{ __('Shipping Same As Billing') }}"
                class="btn btn-primary">
        </div>
        <h6 class="sub-title">{{ __('Shipping Address') }}</h6>
        <div class="row">
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_name', __('Name'), ['class' => 'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('shipping_name', null, ['class' => 'form-control', 'placeholder' => __('Enter Name')]) }}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_phone', __('Phone'), ['class' => 'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('shipping_phone', null, ['class' => 'form-control', 'placeholder' => __('Enter Phone')]) }}
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('shipping_address', __('Address'), ['class' => 'form-label']) }}
                    <label class="form-label" for="example2cols1Input"></label>
                    <div class="input-group">
                        {{ Form::textarea('shipping_address', null, ['class' => 'form-control', 'rows' => 3, 'placeholder' => __('Enter Address')]) }}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_city', __('City'), ['class' => 'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('shipping_city', null, ['class' => 'form-control', 'placeholder' => __('Enter City')]) }}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_state', __('State'), ['class' => 'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('shipping_state', null, ['class' => 'form-control', 'placeholder' => __('Enter State')]) }}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_country', __('Country'), ['class' => 'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('shipping_country', null, ['class' => 'form-control', 'placeholder' => __('Enter Country')]) }}
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-6 col-sm-6">
                <div class="form-group">
                    {{ Form::label('shipping_zip', __('Zip Code'), ['class' => 'form-label']) }}
                    <div class="form-icon-user">
                        {{ Form::text('shipping_zip', null, ['class' => 'form-control', 'placeholder' => __('Enter Zip Code')]) }}
                    </div>
                </div>
            </div>

        </div>
    @endif
</div>

<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Create') }}" class="btn btn-primary">
</div>
{{ Form::close() }}
