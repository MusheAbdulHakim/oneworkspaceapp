
{{ Form::model($pipeline, array('route' => array('pipelines.update', $pipeline->id), 'method' => 'PUT')) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-12">
                {{ Form::label('name', __('Pipeline Name'),['class'=>'col-form-label']) }}
                {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
        <button type="submit" class="btn  btn-primary">{{__('Update')}}</button>
    </div>
{{ Form::close() }}

