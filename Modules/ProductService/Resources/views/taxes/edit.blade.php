{{ Form::model($tax, array('route' => array('tax.update', $tax->id), 'method' => 'PUT')) }}
<div class="modal-body">
<div class="row">
    <div class="form-group col-md-12">
        {{ Form::label('name', __('Tax Rate Name'),['class'=>'form-label']) }}
        {{ Form::text('name', null, array('class' => 'form-control font-style','required'=>'required')) }}
        @error('name')
        <small class="invalid-name" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </small>
        @enderror
    </div>
    <div class="form-group col-md-12">
        {{ Form::label('rate', __('Tax Rate %'),['class'=>'form-label']) }}
        {{ Form::number('rate', null, array('class' => 'form-control','required'=>'required','step'=>'0.01')) }}
        @error('rate')
        <small class="invalid-rate" role="alert">
            <strong class="text-danger">{{ $message }}</strong>
        </small>
        @enderror
    </div>
</div>
</div>

<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
