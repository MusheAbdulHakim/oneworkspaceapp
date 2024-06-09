
{{ Form::model($lead, array('route' => array('leads.update', $lead->id), 'method' => 'PUT','enctype'=>'multipart/form-data')) }}
    <div class="modal-body">
        <div class="text-end">
            @if (module_is_active('AIAssistant'))
                @include('aiassistant::ai.generate_ai_btn',['template_module' => 'lead_email','module'=>'Lead'])
            @endif
        </div>
        @if(module_is_active('CustomField') && !$customFields->isEmpty())
        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill" href="#tab-1" role="tab" aria-controls="pills-home" aria-selected="true">{{__('Lead Detail')}}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" href="#tab-2" role="tab" aria-controls="pills-profile" aria-selected="false">{{__('Custom Fields')}}</a>
            </li>
        </ul>
        @endif
            <div class="tab-content tab-bordered">
            <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                <div class="row">
                    <div class="col-6 form-group">
                        {{ Form::label('subject', __('Subject'),['class'=>'col-form-label']) }}
                        {{ Form::text('subject', null, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('user_id', __('User'),['class'=>'col-form-label']) }}
                        {{ Form::select('user_id', $users,null, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('name', __('Name'),['class'=>'col-form-label']) }}
                        {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('email', __('Email'),['class'=>'col-form-label']) }}
                        {{ Form::email('email', null, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('pipeline_id', __('Pipeline'),['class'=>'col-form-label']) }}
                        {{ Form::select('pipeline_id', $pipelines,null, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('stage_id', __('Stage'),['class'=>'col-form-label']) }}
                        {{ Form::select('stage_id', [''=>__('Select Stage')],null, array('class' => 'form-control','required'=>'required')) }}
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('phone', __('Phone No'),['class'=>'col-form-label']) }}
                        {{ Form::text('phone',null, array('class' => 'form-control','required'=>'required')) }}
                        <div class="text-xs text-danger">{{ __('Please add phone no with country code. (ex. +91)') }}</div>
                    </div>
                    <div class="col-6 form-group">
                        {{ Form::label('follow_up_date', __('Follow Up Date'),['class'=>'col-form-label']) }}
                        {{ Form::date('follow_up_date', null, array('class' => 'form-control')) }}
                    </div>
                    <div class="col-12 form-group">
                        {{ Form::label('sources', __('Sources'),['class'=>'col-form-label']) }}
                        {{ Form::select('sources[]', $sources,null, array('class' => 'form-control choices','id'=>'choices-multiple','multiple'=>'','required'=>'required')) }}
                    </div>
                    <div class="col-12 form-group">
                        {{ Form::label('products', __('Products'),['class'=>'col-form-label']) }}
                        {{ Form::select('products[]', $products,null, array('class' => 'form-control choices','id'=>'choices-multiple1','multiple'=>'','required'=>'required')) }}
                    </div>
                    <div class="col-12 form-group">
                        {{ Form::label('notes', __('Notes'),['class'=>'col-form-label']) }}
                        {{ Form::textarea('notes',null, array('class' => 'form-control summernote' , 'id' => 'lead-edit-notes')) }}
                    </div>
                </div>
            </div>
            @if(module_is_active('CustomField') && !$customFields->isEmpty())
            <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                <div class="col-md-6">
                    @include('customfield::formBuilder',['fildedata' => $lead->customField])
                </div>
            </div>
            @endif
        </div>
    </div>

    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
        <button type="submit" class="btn  btn-primary">{{__('Update')}}</button>

    </div>
{{ Form::close() }}

<script>
    var stage_id = '{{$lead->stage_id}}';

    $(document).ready(function () {
        var pipeline_id = $('[name=pipeline_id]').val();
        getStages(pipeline_id);
    });

    $(document).on("change", "#commonModal select[name=pipeline_id]", function () {
        var currVal = $(this).val();
        getStages(currVal);
    });

    function getStages(id) {
        $.ajax({
            url: '{{route('leads.json')}}',
            data: {pipeline_id: id, _token: $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            success: function (data) {
                var stage_cnt = Object.keys(data).length;
                $("#stage_id").empty();
                if (stage_cnt > 0) {
                    $.each(data, function (key, data) {
                        var select = '';
                        if (key == '{{ $lead->stage_id }}') {
                            select = 'selected';
                        }
                        $("#stage_id").append('<option value="' + key + '" ' + select + '>' + data + '</option>');
                    });
                }
                $("#stage_id").val(stage_id);
            }
        })
    }
</script>
<script>
    if ($(".summernote").length > 0) {
        $('.summernote').summernote({
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'italic', 'underline', 'strikethrough']],
                ['list', ['ul', 'ol', 'paragraph']],
                ['insert', ['link', 'unlink']],
            ],
            height: 200,
        });
    }
</script>
