@php
    $company_settings = getCompanyAllSetting();
@endphp

{{ Form::open(['url' => 'announcement', 'method' => 'post']) }}
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
                {{ Form::label('branch_id', !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch'), ['class' => 'col-form-label ']) }}
                {{ Form::select('branch_id', $branch, null, ['class' => 'form-control branch_data', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('department_id', !empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department'), ['class' => 'col-form-label']) }}
                <span id="department_id_span">
                    <select class="multi-select department_data choices" id="department_id" data-toggle="select2" required
                        name="department_id[]" multiple="multiple" data-placeholder="{{ __('Select '.(!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department')))}}" >
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                        @endforeach
                    </select>
                </span>
            </div>
            <p class="text-danger d-none" id="department_validation">{{__('This filed is required.')}}</p>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
                <div class="employee_div">
                    <select class="multi-select employee_data choices" name="employee_id[]" id="employee_id"
                        placeholder="Select Employee" multiple="multiple" required>
                    </select>
                </div>
            </div>
            <p class="text-danger d-none" id="employee_validation">{{__('This filed is required.')}}</p>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Announcement start Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('start_date', date('Y-m-d'), ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('Announcement end Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('end_date', date('Y-m-d'), ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
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
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary','id'=>'submit']) }}
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

        var employee =  $("#employee_id option:selected").length;
         if(employee == 0){
            $('#employee_validation').removeClass('d-none')
                return false;
        }
        else{
            $('#employee_validation').addClass('d-none')
        }
    });
</script>
