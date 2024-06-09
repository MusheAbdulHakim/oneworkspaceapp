@php
    $company_settings = getCompanyAllSetting();
@endphp

{{ Form::model($promotion, ['route' => ['promotion.update', $promotion->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'promotion','module'=>'Hrm'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
                {{ Form::select('employee_id', $employees, !empty($promotion->user_id) ? $promotion->user_id : null, ['class' => 'form-control ', 'placeholder' => __('Select Employee'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('designation_id', !empty($company_settings['hrm_designation_name']) ? $company_settings['hrm_designation_name'] : __('Designation'), ['class' => 'col-form-label']) }}
                {{ Form::select('designation_id', $designations, null, ['class' => 'form-control ', 'placeholder' => __('Select '.(!empty($company_settings['hrm_designation_name']) ? $company_settings['hrm_designation_name'] : __('Designation'))), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('promotion_title', __('Promotion Title'), ['class' => 'col-form-label']) }}
                {{ Form::text('promotion_title', null, ['class' => 'form-control', 'placeholder' => 'Enter Promotion Title', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('promotion_date', __('Promotion Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('promotion_date', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
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
