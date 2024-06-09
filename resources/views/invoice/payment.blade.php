{{ Form::open(array('route' => array('invoice.payment', $invoice->id),'method'=>'post','enctype' => 'multipart/form-data')) }}
<div class="modal-body">
<div class="row">
        <div class="form-group col-md-6">
            {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{Form::date('date',null,array('class'=>'form-control ','required'=>'required','placeholder'=>'Select Date'))}}
            </div>
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
            <div class="form-icon-user">
                {{ Form::number('amount',$invoice->getDue(), array('class' => 'form-control','required'=>'required','step'=>'1','max' => $invoice->getDue())) }}
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
        <div class="form-group  col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {{ Form::textarea('description', '', array('class' => 'form-control','rows'=>3)) }}
        </div>
        <div class="form-group col-md-12">
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
<script>
    document.getElementById('files').onchange = function () {
    var src = URL.createObjectURL(this.files[0])
    document.getElementById('image').src = src
    }
</script>
