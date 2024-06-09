@php
    $company_settings = getCompanyAllSetting();
@endphp
{{ Form::open(['url' => 'branch-settings', 'method' => 'post']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('hrm_branch_name', (!empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch')) . ' Name', ['class' => 'form-label']) }}
                {{ Form::text('hrm_branch_name', !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : 'Branch', ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select '.(!empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('select Branch')))]) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
