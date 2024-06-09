

{{Form::open(array('route'=>array('productslogtime.store'),'method'=>'post'))}}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'parts_logtime','module'=>'CMMS'])
        @endif
    </div>
    <input type="hidden" name="product_id" value="{{$product_id}}">
    <div class="row">
        <div class="col-md-12 form-group">
            {{Form::label('date',__('Date'),['class'=>'col-form-label']) }}
            {{Form::date('date',null,array('class'=>'form-control','placeholder'=>__('Enter Date'),'required'=>'required'))}}
        </div>
        <div class="col-md-12 form-group">
            {{Form::label('description',__('Description'),['class'=>'col-form-label']) }}
            {{Form::textarea('description',null,array('class'=>'form-control','placeholder'=>__('Enter Description'),'required'=>'required' , 'row' => 3))}}
        </div>
    </div>
</div>

        <div class="modal-footer">
            <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
            {{Form::submit(__('Create'),array('class'=>'btn btn-primary'))}}
        </div>
    {{Form::close()}}


