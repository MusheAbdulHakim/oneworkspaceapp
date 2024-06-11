{{ Form::open(array('route' => 'pabbly.connect.store', 'enctype' => "multipart/form-data")) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group">
                {{ Form::label('',__('Module'),['class'=>'form-label']) }}
                {{ Form::select('module',$PabblyModule,null,['class'=>'form-control','required'=>'required']) }}
            </div>
            <div class="form-group">
                {{ Form::label('',__('Method'),['class'=>'form-label']) }}
                {{Form::select('method',$methods,null,array('class'=>'form-control select'))}}
            </div>
            <div class="form-group">
                {{ Form::label('',__('URL'),['class'=>'form-label']) }}
                {{ Form::text('url',null,['class'=>'form-control','placeholder'=>__('Enter Pabbly Webhook Url Here'),'required'=>'required']) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
    </div>
{{ Form::close() }}
