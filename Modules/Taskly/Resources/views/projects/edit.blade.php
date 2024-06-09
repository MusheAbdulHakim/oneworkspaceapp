
{{ Form::model($project, array('route' => array('projects.update', $project->id), 'method' => 'PUT','enctype'=>'multipart/form-data')) }}
<div class="modal-body">
    <div class="row">
        <div class="form-group col-md-12">
            {{ Form::label('projectname', __('Name'),['class'=>'form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required','id'=>"projectname",'placeholder'=> __('Project Name'))) }}
        </div>
        <div class="form-group col-md-12">
            {{ Form::label('description', __('Description'),['class'=>'form-label']) }}
            {{ Form::textarea('description', null, array('class' => 'form-control','rows'=>3,'required'=>'required','id'=>"description",'placeholder'=> __('Add Description'))) }}
        </div>
        <div class="form-group col-md-6">

            {{ Form::label('status', __('Status'),['class'=>'form-label']) }}
            {{ Form::select('status', ['Ongoing'=>__('Ongoing'),'Finished'=>__('Finished'),'OnHold'=>__('OnHold')],null, array('class' => 'form-control','id'=>'status')) }}
        </div>
        <div class="form-group col-md-6">
            {{ Form::label('budget', __('Budget'),['class'=>'form-label']) }}
            <div class="input-group mb-3">
                <span class="input-group-text">{{ company_setting('defult_currancy')}}</span>
                {{ Form::number('budget', null, array('class' => 'form-control currency_input','required'=>'required','id'=>"budget",'placeholder'=> __('Project Budget'))) }}
            </div>
        </div>
        @if($project->type == 'project')
            <div class="form-group col-md-6">
                {{ Form::label('start_date', __('Start Date'),['class'=>'form-label']) }}
                <div class="input-group date ">
                    {{ Form::date('start_date', null, array('class' => 'form-control','required'=>'required','id'=>"start_date")) }}
                </div>
            </div>
            <div class="form-group col-md-6">
                {{ Form::label('end_date', __('End Date'),['class'=>'form-label']) }}
                <div class="input-group date ">
                    {{ Form::date('end_date', null, array('class' => 'form-control','required'=>'required','id'=>"end_date")) }}
                </div>
            </div>
        @endif
        @if(module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="col-md-12">
                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                    @include('customfield::formBuilder',['fildedata' => $project->customField])
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close')}}</button>
    <input type="submit" value="{{ __('Save Changes')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}



