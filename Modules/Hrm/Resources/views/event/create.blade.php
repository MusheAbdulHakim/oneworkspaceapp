@php
    $company_settings = getCompanyAllSetting();
@endphp

<style>
    .event_color_active {
    box-shadow: inset 0 0 0 2px #000;
}
</style>
{{ Form::open(['url' => 'event', 'method' => 'post']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'event','module'=>'Hrm'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('branch_id', !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch'), ['class' => 'col-form-label']) }}
                <select class="form-control " name="branch_id" id="branch_id"
                    placeholder="{{ __('Select '.(!empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('select Branch'))) }}" required>
                    <option value="">{{ __('Select '.(!empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('select Branch'))) }}</option>
                    <option value="0">{{ __('All Branch') }}</option>
                    @foreach ($branch as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('department_id',!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department'), ['class' => 'col-form-label']) }}
                <div class="department_div">
                    <select class="form-control  department_id " name="department_id[]"
                         placeholder="__('Select '.(!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department')))" >
                    <option value="">{{ __('Select '.(!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department')))}}</option>
                    </select>
                </div>
            </div>
            <p class="text-danger d-none" id="department_validation">{{__('This filed is required.')}}</p>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
                <div class="employee_div">
                    <select class="form-control  employee_id " name="employee_id[]"
                        placeholder="Select Employee" >
                    <option value="">{{ __('Select Employee') }}</option>
                    </select>
                </div>
            </div>
            <p class="text-danger d-none" id="employee_validation">{{__('This filed is required.')}}</p>
        </div>

        <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12">
            <div class="form-group">
                {{ Form::label('title', __('Event Title'), ['class' => 'col-form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control ', 'placeholder' => __('Enter Event Title'),'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-lg-6 col-xl-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Event start Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('start_date', date('Y-m-d'), ['class' => 'form-control datetime-local ', 'autocomplete'=>'off', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-lg-6 col-xl-6">
            <div class="form-group">
                {{ Form::label('end_date', __('Event End Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('end_date', date('Y-m-d'), ['class' => 'form-control datetime-local ','autocomplete'=>'off', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12">
            <div class="form-group">
                {{ Form::label('color', __('Event Select Color'), ['class' => 'col-form-label d-block mb-3']) }}
                <div class="btn-group-toggle btn-group-colors event-tag" data-toggle="buttons">
                    <label class="btn bg-info active p-3"><input type="radio" name="color" value="event-info" checked class="d-none event_color"></label>
                    <label class="btn bg-warning p-3"><input type="radio" name="color" value="event-warning" class="d-none event_color"></label>
                    <label class="btn bg-danger p-3"><input type="radio" name="color" value="event-danger" class="d-none event_color"></label>
                    <label class="btn bg-success p-3"><input type="radio" name="color" value="event-success" class="d-none event_color"></label>
                    <label class="btn p-3" style="background-color: #51459d !important"><input type="radio" name="color" class="d-none event_color" value="event-primary"></label>
                </div>
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('description', __('Event Description'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Event Description'),'rows'=>'5']) }}
        </div>
        @if (module_is_active('Calender') && !empty($company_settings['google_calendar_enable']) && $company_settings['google_calendar_enable'] == 'on')
            @include('calender::setting.synchronize')
        @endif
    </div>
</div>

<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" id="submit" value="{{ __('Create') }}" class="btn  btn-primary">

</div>
{{ Form::close() }}
<script>
    $("#submit").click(function() {
        var department =  $(".department_id option:selected").length;
        if(department == 0){
        $('#department_validation').removeClass('d-none')
            return false;
        }else{
        $('#department_validation').addClass('d-none')
        }

        var employee =  $(".employee_id option:selected").length;
         if(employee == 0){
            $('#employee_validation').removeClass('d-none')
                return false;
        }
        else{
            $('#employee_validation').addClass('d-none')
        }
    });
</script>
