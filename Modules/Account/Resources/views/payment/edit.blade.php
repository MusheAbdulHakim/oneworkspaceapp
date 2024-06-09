{{ Form::model($payment, array('route' => array('payment.update', $payment->id), 'method' => 'PUT','enctype' => 'multipart/form-data')) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'payment','module'=>'Account'])
        @endif
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{ Form::date('date',null, ['class' => 'form-control ','required'=>'required','placeholder' => 'Select Date']) }}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{ Form::number('amount',null, array('class' => 'form-control','required'=>'required','min'=>'0','step'=>'0.01','placeholder' => 'Enter Amount')) }}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('account_id', __('Account'),['class'=>'form-label']) }}
            {{ Form::select('account_id',$accounts,null, array('class' => 'form-control ','required'=>'required','placeholder' => 'Select Account')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('vendor_id', __('Vendor'),['class'=>'form-label']) }}
            {{ Form::select('vendor_id', $vendors,null, array('class' => 'form-control ','required'=>'required','placeholder' => 'Select Vendor')) }}
        </div>
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {{ Form::textarea('description',null, array('class' => 'form-control','placeholder' => 'Enter Description','rows'=>3,'required'=>'required')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('category_id', __('Category'),['class'=>'form-label']) }}
            {{ Form::select('category_id', $categories,null, array('class' => 'form-control ','required'=>'required','placeholder' => 'Select Category')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('reference', __('Reference'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{ Form::text('reference',null, array('class' => 'form-control','placeholder' => 'Enter Reference','required'=>'required')) }}
            </div>
        </div>
        <div class="form-group">
            {{ Form::label('add_receipt', __('Payment Receipt'), ['class' => 'form-label']) }}
            <div class="choose-files ">
                <label for="add_receipt">
                    <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                    <input type="file" class="form-control file" name="add_receipt" id="add_receipt" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                        data-filename="add_receipt">
                </label>
                <img id="image" class="mt-2" src="{{ !empty($payment->add_receipt) ? get_file($payment->add_receipt):'' }}" style="width:25%;"/>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Save Changes')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}



