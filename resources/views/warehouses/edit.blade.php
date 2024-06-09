{{ Form::model($warehouse, array('route' => array('warehouses.update', $warehouse->id), 'method' => 'PUT', 'enctype'=>'multipart/form-data')) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @php
                $templateName = \Modules\AIAssistant\Entities\AssistantTemplate::where('template_module', 'warehouse')->where('module', 'Pos')->get();
            @endphp
            @if($templateName->isEmpty())
                @include('aiassistant::ai.generate_ai_btn',['template_module' => 'warehouse','module'=>'General'])
            @else
                @include('aiassistant::ai.generate_ai_btn',['template_module' => 'warehouse','module'=>'Pos'])
            @endif
        @endif
    </div>
    <div class="row">
        <div class="form-group col-md-12">

            {{ Form::label('name', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
            @error('name')
            <small class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </small>
            @enderror
        </div>
        <div class="form-group col-md-12">
            {{Form::label('address',__('Address'),array('class'=>'form-label')) }}
            {{Form::textarea('address',null,array('class'=>'form-control','rows'=>3))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('city',__('City'),array('class'=>'form-label')) }}
            {{Form::text('city',null,array('class'=>'form-control'))}}
        </div>
        <div class="form-group col-md-6">
            {{Form::label('city_zip',__('Zip Code'),array('class'=>'form-label')) }}
            {{Form::text('city_zip',null,array('class'=>'form-control'))}}
        </div>
        @if(module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-12 form-group">
                <div class="tab-pane fade show form-label" id="tab-2" role="tabpanel">
                    @include('customfield::formBuilder',['fildedata' => $warehouse->customField])
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Save Changes')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
