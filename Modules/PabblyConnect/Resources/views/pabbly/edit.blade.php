{{Form::model($pabbly,array('route' => array('pabbly.connect.update', $pabbly->id), 'method' => 'POST')) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group">
                {{ Form::label('',__('Module'),['class'=>'form-label']) }}
                {{ Form::select('module',$PabblyModule,[$pabbly->action],['class'=>'form-control']) }}
            </div>
            <div class="form-group">
                {{ Form::label('',__('Method'),['class'=>'form-label']) }}
                {{Form::select('method',$methods,null,array('class'=>'form-control select'))}}
            </div>
            <div class="form-group">
                {{ Form::label('',__('URL'),['class'=>'form-label']) }}
                {{ Form::text('url',$pabbly->url,['class'=>'form-control','placeholder'=>__('Enter Pabbly Webhook Url Here'),'required'=>'required']) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
    </div>
{{ Form::close() }}
