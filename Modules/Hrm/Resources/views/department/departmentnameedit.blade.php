@php
    $company_settings = getCompanyAllSetting();
@endphp

{{ Form::open(['url' => 'department-settings', 'method' => 'post']) }}
 <div class="modal-body">
     <div class="row">
         <div class="col-md-12">
             <div class="form-group">
                 {{ Form::label('hrm_department_name', (!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department')) . ' Name', ['class' => 'form-label']) }}
                 {{ Form::text('hrm_department_name', !empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department'), ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select '.(!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department')))]) }}
             </div>
         </div>
     </div>
 </div>
 <div class="modal-footer">
     <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
     {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
 </div>
 {{ Form::close() }}
