
{{ Form::model($lead, array('route' => array('leads.sources.update', $lead->id), 'method' => 'PUT')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-12 form-group">
                <div class="row gutters-xs">
                    @foreach ($sources as $source)
                        <div class="col-12 custom-control form-check mt-2 mb-2">
                            {{ Form::checkbox('sources[]',$source->id,($selected && array_key_exists($source->id,$selected))?true:false,['class' => 'form-check-input m-1','id'=>'sources_'.$source->id]) }}
                            {{ Form::label('sources_'.$source->id, ucfirst($source->name),['class'=>'form-check-labe ml-4 text-sm font-weight-bold m-1']) }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
        <button type="submit" class="btn  btn-primary">{{__('Save')}}</button>
    </div>

{{ Form::close() }}
