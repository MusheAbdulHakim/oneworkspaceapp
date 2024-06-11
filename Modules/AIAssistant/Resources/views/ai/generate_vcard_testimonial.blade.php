@php
    $currantLang = getActiveLanguage();
@endphp
<div class="modal-body">
    <div class="row">
        <form action="" id="myForm">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {{ Form::label('template', __('For What'), ['class' => 'col-form-label']) }}</br>
                        @foreach ($templateName as $key => $value)
                            <div class="form-check form-check-inline">
                                <input class="form-check-input template_name" type="radio" name="template_name"
                                    value="{{ $value->id }}" id="product_name_{{ $value->id }}"
                                    data-name="{{ $value->template_name }}">
                                <label class="form-check-label" for="product_name_{{ $value->id }}">
                                    {{ ucwords(str_replace('_', ' ', $value->template_name)) }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('language', __('Language'), ['class' => 'col-form-label']) }}
                        <select name="language" class="form-select" id="language">
                            @foreach (\Modules\AIAssistant\Entities\AssistantTemplate::flagOfCountry() as $key => $lang)
                                <option value="{{ $key }}" {{ $currantLang == $key ? 'selected' : '' }}>
                                    {{ Str::upper($lang) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="col-6 tone">
                    <div class="form-group">
                        {{ Form::label('', __('Tone'), ['class' => 'col-form-label']) }}
                        @php
                            $tone = [
                                'funny' => 'funny',
                                'casual' => 'casual',
                                'excited' => 'excited',
                                'professional' => 'professional',
                                'witty' => 'witty',
                                'sarcastic' => 'sarcastic',
                                'feminine' => 'feminine',
                                'masculine' => 'masculine',
                                'bold' => 'bold',
                                'dramatic' => 'dramatic',
                                'gumpy' => 'gumpy',
                                'secretive' => 'secretive',
                            ];
                        @endphp
                        {{ Form::select('tone', $tone, null, ['class' => 'form-control tone']) }}
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('', __('AI Creativity'), ['class' => 'col-form-label']) }}
                        <select name="ai_creativity" id="ai_creativity" class="form-select">
                            <option value="1">{{ __('High') }}</option>
                            <option value="0.5">{{ __('Meduium') }}</option>
                            <option value="0">{{ __('Low') }}</option>
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('', __('Number of Result'), ['class' => 'col-form-label']) }}
                        <select name="num_of_result" id="" class="form-select">
                            @for ($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        {{ Form::label('', __('Maximum Result Length'), ['class' => 'col-form-label']) }}
                        {{ Form::number('result_length', 10, ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="col-12" id="getkeywords">
                </div>

            </div>
            <div class="response">
                <a class="btn btn-primary btn-sm float-left" href="#!" id="generate_test">{{ __('Generate') }}</a>
                <a href="#!" onclick="copyText()" class="btn btn-primary btn-sm float-end"><i
                        class="ti ti-copy"></i>{{ __('Copy Text') }}</a>
                <a href="#!" onclick="copySelectedText()" class="btn btn-primary btn-sm float-end me-2"><i
                        class="ti ti-copy"></i>{{ __('Copy Selected Text') }}</a>
            </div>
        </form>
        <div class="form-group mt-3">
            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => 5, 'placeholder' => __('Description'), 'id' => 'ai-description']) }}

        </div>
    </div>
</div>
<script>
    function copyText() {
        var testimonial_id = "{{ $testimonial_id }}";
        var selected = $('input[name="template_name"]:checked').attr('data-name');
        var desc = 'testimonial_description_' + testimonial_id;

        var copied = $("#ai-description").val();

        if ($('textarea[name=' + selected + ']').hasClass('pc-tinymce-2')) {
            tinymce.get(selected).setContent(copied);
        } else if ($('textarea[name=' + selected + ']').hasClass('summernote-simple')) {
            $('textarea[name=' + selected + ']').summernote("code", copied);
        } else {
            // $('.service_title').val(copied)
            $('textarea[id=' + desc + ']').val(copied)
            $('#testimonial_description_' + testimonial_id + '_preview').text(copied);
        }

        toastrs('success', 'Result text has been copied successfully', 'success');
        $('#commonModalOver').modal('hide');
    }

    function copySelectedText() {
        var selected = $('input[name="template_name"]:checked').attr('data-name');
        var selectedText = window.getSelection().toString();
        var input = $('input[name=' + selected + ']').length;
        $('#ai-description').after("Copied to clipboard");

        var testimonial_id = "{{ $testimonial_id }}";
        var desc = 'testimonial_description_' + testimonial_id;

        if ($('textarea[name=' + selected + ']').hasClass('pc-tinymce-2')) {
            tinymce.get(selected).setContent(selectedText);
        } else if ($('textarea[name=' + selected + ']').hasClass('summernote-simple')) {
            $('textarea[name=' + selected + ']').summernote("code", copied);
        } else {
            // $('.service_title').val(copied)
            $('textarea[id=' + desc + ']').val(selectedText)
            $('#testimonial_description_' + testimonial_id + '_preview').text(selectedText);
        }
        toastrs('success', 'Result text has been copied successfully', 'success');
        $('#commonModalOver').modal('hide');

    }

    $('body').on('shown.bs.modal', function() {
        $("#commonModalOver input:radio:first").prop("checked", true).trigger("change");
    });

    $('body').on('change', '.template_name', function() {
        var templateId = $(this).val();
        var url =
            $.ajax({
                type: 'post',
                url: '{{ route('aiassistant.generate.keywords', ['__templateId']) }}'.replace(
                    '__templateId', templateId),
                datType: 'json',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'template_id': templateId,
                },
                success: function(data) {
                    if (data.tone == 1) {
                        $('.tone').removeClass('d-none');
                        $('.tone select').attr('name', 'tone');
                    } else {
                        $('.tone').addClass('d-none');
                        $('.d-none select').removeAttr('name');
                    }
                    $('#getkeywords').empty();
                    $('#getkeywords').append(data.template)
                },
            })
    })
    $('#generate_test').on('click', function () {
        var form = $("#myForm");
        $.ajax({
            type: 'post',
            url: '{{ route('aiassistant.generate.response') }}',
            datType: 'json',
            data: form.serialize(),
            beforeSend: function(msg) {
                $("#generate_test").empty();
                var html = '<span class="spinner-grow spinner-grow-sm" role="status"></span>';
                $("#generate_test").append(html);
            },
            afterSend: function(msg) {
                $("#generate2").empty();
                var html =
                    `<a class="btn btn-primary" href="#!" id="generate_test">{{ __('Generate') }}</a>`;
                $("#generate2").replaceWith(html);

            },
            success: function(data) {
                $('.response').removeClass('d-none');
                $('#generate_test').text('Re-Generate');
                if (data.message) {
                    toastrs('error', data.message, 'error');
                    // $('#commonModalOver').modal('hide');
                } else {
                    $('#ai-description').val(data)
                }
            },
        });
    })
</script>
