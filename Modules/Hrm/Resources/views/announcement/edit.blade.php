@php
    $company_settings = getCompanyAllSetting();
@endphp

{{ Form::model($announcement, ['route' => ['announcement.update', $announcement->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'announcement','module'=>'Hrm'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('title', __('Announcement Title'), ['class' => 'col-form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Announcement Title'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('branch_id', !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch'), ['class' => 'col-form-label']) }}
                {{ Form::select('branch_id', $branch, null, ['class' => 'form-control choices', 'required' => 'required', 'placeholder' => __('Select '.(!empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('select Branch')))]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('department_id', !empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department'), ['class' => 'col-form-label']) }}
                <span id="department_id_span">
                    {{ Form::select('department_id[]', $departments, !empty($announcement->department_id) ? explode(',', $announcement->department_id) : null, ['class' => 'form-control choices', 'required' => 'required', 'multiple' => 'multiple', 'id' => 'department_id']) }}
                </span>
            </div>
            <p class="text-danger d-none" id="department_validation">{{__('This filed is required.')}}</p>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Announcement start Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('start_date', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('Announcement start Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('end_date', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'rows' => '3', 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary','id'=>'submit']) }}
</div>
{{ Form::close() }}
<script>
    $("#submit").click(function() {
        var department =  $("#department_id option:selected").length;
        if(department == 0){
        $('#department_validation').removeClass('d-none')
            return false;
        }else{
        $('#department_validation').addClass('d-none')
        }
    });
</script>
