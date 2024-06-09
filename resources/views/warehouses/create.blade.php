{{ Form::open(['url' => 'warehouses', 'enctype'=>'multipart/form-data']) }}
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
            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
            {{ Form::text('name', '', ['class' => 'form-control', 'required' => 'required']) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('address', __('Address'), ['class' => 'form-label']) }}
            {{ Form::textarea('address', null, ['class' => 'form-control', 'rows' => 3]) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('city', __('City'), ['class' => 'form-label']) }}
            {{ Form::text('city', null, ['class' => 'form-control']) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('city_zip', __('Zip Code'), ['class' => 'form-label']) }}
            {{ Form::text('city_zip', null, ['class' => 'form-control']) }}
        </div>
        @if(module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-12 form-group">
                <div class="tab-pane fade show form-label" id="tab-2" role="tabpanel">
                    @include('customfield::formBuilder')
                </div>
            </div>
        @endif

    </div>
</div>
<div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
