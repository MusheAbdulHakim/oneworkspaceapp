@php
    $company_settings = getCompanyAllSetting();
@endphp

{{ Form::open(['url' => 'company-policy', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'company policy','module'=>'Hrm'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('branch', !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch'), ['class' => 'form-label']) }}
                {{ Form::select('branch', $branches, null, ['class' => 'form-control    ', 'placeholder' => __('Select '.(!empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __(' Branch'))), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Company Policy Title')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('document', __('Attachment'), ['class' => 'form-label']) }}
                <div class="choose-files ">
                    <label for="attachment">
                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                        <input type="file" class="form-control file" name="attachment" id="attachment"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                            data-filename="attachment">
                    </label>
                    <img id="blah" width="100" src="" />
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
