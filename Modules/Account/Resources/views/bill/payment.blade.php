{{ Form::open(array('route' => array('bill.payment', $bill->id),'method'=>'post','enctype' => 'multipart/form-data')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
                {{Form::date('date',null,array('class'=>'form-control ','required'=>'required','placeholder'=>'Select Date'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                {{ Form::number('amount',$bill->getDue(), array('class' => 'form-control','required'=>'required','min'=>'0','step'=>'0.01')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('account_id', __('Account'),['class'=>'form-label']) }}
                {{ Form::select('account_id',$accounts,null, array('class' => 'form-control ','required'=>'required','placeholder'=>'Select Account')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('reference', __('Reference'),['class'=>'form-label']) }}
                {{ Form::text('reference',null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
                {{ Form::textarea('description', '', array('class' => 'form-control','required'=>'required','rows'=>3)) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('add_receipt', __('Payment Receipt'), ['class' => 'form-label']) }}
            <div class="choose-files ">
                <label for="add_receipt">
                    <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                    <input type="file" class="form-control file" name="add_receipt" id="add_receipt">
                </label>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
