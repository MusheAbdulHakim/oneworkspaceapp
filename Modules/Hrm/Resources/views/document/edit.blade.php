{{ Form::model($document, ['route' => ['document.update', $document->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'document','module'=>'Hrm'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Document Name')]) }}
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group">
                {{ Form::label('document', __('Document'), ['class' => 'form-label']) }}
                <div class="choose-files ">
                    <label for="documents">
                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                        <input type="file" class="form-control file" name="documents" id="documents"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                            data-filename="documents">
                    </label>
                    <img id="blah" width="100"
                        src="@if ($document->document) {{ get_file($document->document) }} @endif" />
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                {{ Form::label('role', __('Role'), ['class' => 'form-label']) }}
                {{ Form::select('role', $roles, null, ['class' => 'form-control ', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
