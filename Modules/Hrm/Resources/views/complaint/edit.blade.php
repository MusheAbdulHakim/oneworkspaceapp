{{ Form::model($complaint, ['route' => ['complaint.update', $complaint->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'complaint','module'=>'Hrm'])
        @endif
    </div>
    <div class="row">
        @if (in_array(Auth::user()->type, Auth::user()->not_emp_type))
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('complaint_from', __('Complaint From'), ['class' => 'col-form-label']) }}
                    {{ Form::select('complaint_from', $employees, !empty($complaint->complaint_from) ? $complaint->complaint_from : null, ['class' => 'form-control ', 'placeholder' => __('Select Employee'), 'required' => 'required']) }}
                </div>
            </div>
        @endif
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('complaint_against', __('Complaint Against'), ['class' => 'col-form-label']) }}
                {{ Form::select('complaint_against', $employees, !empty($complaint->complaint_against) ? $complaint->complaint_against : null, ['class' => 'form-control ', 'placeholder' => __('Select Employee'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Complaint Title', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('complaint_date', __('Complaint Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('complaint_date', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
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
    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
