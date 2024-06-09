@php
    $company_settings = getCompanyAllSetting();
@endphp
{{ Form::open(['url' => 'leave', 'method' => 'post']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'leave','module'=>'Hrm'])
        @endif
    </div>
    <div class="row">
        @if (in_array(\Auth::user()->type, \Auth::user()->not_emp_type))
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
                    {{ Form::select('employee_id', $employees, null, ['class' => 'form-control', 'placeholder' => __('Select Employee'), 'required' => 'required']) }}
                </div>
            </div>
        @else
            {!! Form::hidden('employee_id', !empty($employees) ? $employees->id : 0,['id' => 'employee_id']) !!}
        @endif
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('leave_type_id', __('Leave Type'), ['class' => 'col-form-label']) }}
                <select name="leave_type_id" id="leave_type_id" class="form-control select2" required='required'>
                    <option value="">{{ __('Select Leave Type') }}</option>
                    @foreach ($leavetypes as $leave)
                        <option value="{{ $leave->id }}">{{ $leave->title }} (<p class="float-right pr-5">
                                {{ $leave->days }}</p>)</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('start_date', date('Y-m-d'), ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date','min'=>date('Y-m-d')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('end_date', date('Y-m-d'), ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date','min'=>date('Y-m-d')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('leave_reason', __('Leave Reason'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('leave_reason', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Leave Reason'), 'rows' => '3']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('remark', __('Remark'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('remark', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Leave Remark'), 'rows' => '3']) }}
            </div>
        </div>
        @if (module_is_active('Calender') && !empty($company_settings['google_calendar_enable']) && $company_settings['google_calendar_enable'] == 'on')
            @include('calender::setting.synchronize')
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
<script>
   $(document).ready(function () {
        setTimeout(() => {
            var employee_id = $('#employee_id').val();
            if(employee_id)
            {
                $('#employee_id').trigger('change');
            }
        }, 100);
    });
</script>
