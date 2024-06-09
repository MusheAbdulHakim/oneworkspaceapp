{{ Form::open(['url' => 'document', 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
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
                {{ Form::text('name', null, ['class' => 'form-control','required' => 'required', 'placeholder' => __('Enter Document Name')]) }}
            </div>
        </div>
        <div class="col-md-7">
            <div class="form-group">
                {{ Form::label('document', __('Document'), ['class' => 'form-label']) }}
                <div class="choose-files ">
                    <label for="documents">
                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                        <input type="file" class="form-control file doc_data" name="documents" id="documents"
                            onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])"
                            data-filename="documents" required>
                        <img id="blah" width="100"  />
                    </label>
                </div>
                <p class="text-danger d-none" id="doc_validation">{{__('This filed is required.')}}</p>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                {{ Form::label('role', __('Role'), ['class' => 'form-label']) }}
                {{ Form::select('role', $roles, null, ['class' => 'form-control ','required' => 'required']) }}
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
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary submit']) }}
</div>
{{ Form::close() }}
<script>
     $(".submit").click(function() {
            var documents = $('.doc_data').val();
            if(!isNaN(documents)) {
                    $('#doc_validation').removeClass('d-none')
                    return false;
            }else{
                $('#doc_validation').addClass('d-none')
            }
        });
</script>
