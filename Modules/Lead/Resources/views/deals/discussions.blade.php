
{{ Form::model($deal, array('route' => array('deals.discussion.store', $deal->id), 'method' => 'POST')) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('comment', __('Message'),['class'=>'col-form-label']) }}
            {{ Form::textarea('comment', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Save')}}</button>
</div>

{{ Form::close() }}

