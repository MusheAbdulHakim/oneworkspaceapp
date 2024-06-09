{{ Form::open(array('route' => ['deals.labels.store',$deal->id])) }}
<div class="modal-body">
    <div class="row">
        <div class="col-12 form-group">
                @foreach ($labels as $label)
                    <div class="form-check  m-2">
                        {{ Form::checkbox('labels[]',$label->id,(array_key_exists($label->id,$selected))?true:false,['class' => 'form-check-input','id'=>'labels_'.$label->id]) }}
                        {{ Form::label('labels_'.$label->id, ucfirst($label->name),['class'=>'form-check-label ml-4 badge rounded p-2 px-3 bg-'.$label->color]) }}
                    </div>
                @endforeach
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    <button type="submit" class="btn  btn-primary">{{__('Save')}}</button>
</div
{{ Form::close() }}
