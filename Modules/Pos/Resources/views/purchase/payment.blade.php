{{ Form::open(array('route' => array('purchase.payment', $purchase->id),'method'=>'post','enctype' => 'multipart/form-data')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
                {{Form::date('date',null,array('class'=>'form-control','required'=>'required','placeholder'=>'Select Date'))}}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                {{ Form::number('amount',$purchase->getDue(), array('class' => 'form-control','required'=>'required','min'=>'0','step'=>'0.01')) }}
            </div>
        </div>
        @if(module_is_active('Account'))
            <div class="form-group col-md-6">
                    {{ Form::label('account_id', __('Account'),['class'=>'form-label']) }}
                    {{ Form::select('account_id',$accounts,null, array('class' => 'form-control', 'required'=>'required','placeholder'=>'Select Account')) }}
            </div>
        @endif
        <div class="form-group {{ (module_is_active('Account')) ? 'col-md-6' : 'col-md-12'}}">
            {{ Form::label('reference', __('Reference'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{ Form::tel('reference',null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
                {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3)) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('add_receipt', __('Payment Receipt'), ['class' => 'form-label']) }}
                <div class="choose-file form-group">
                    <label for="image" class="form-label">
                        <input type="file" name="add_receipt" id="image" class="form-control" accept="image/*, .txt, .rar, .zip" >
                    </label>
                    <p class="upload_file"></p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
