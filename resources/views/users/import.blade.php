{{ Form::open(['method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'upload_form']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 mb-6">
            {{ Form::label('file', __('Download Sample Customer CSV File'), ['class' => 'col-form-label text-danger ']) }}
            @if(check_file('uploads/sample/sample_project.csv'))
                <a href="{{ asset('uploads/sample/sample_user.csv') }}"
                    class="btn btn-sm btn-primary btn-icon-only mx-2">
                    <i class="fa fa-download"></i>
                </a>
            @endif
        </div>
        <div class="col-md-12 mt-1">
            {{ Form::label('file', __('Select CSV File'), ['class' => 'col-form-label']) }}
            <div class="choose-file form-group">
                <label for="file" class="col-form-label">
                    <input type="file" class="form-control" name="file" id="file" accept=".csv" name="upload_file" data-filename="upload_file" required>
                </label>
                <p class="upload_file"></p>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <button type="submit" value="{{ __('Upload') }}" class="btn btn-primary ms-2">{{__('Upload')}}</button>
    <a href="" data-url="{{ route('users.import.modal') }}" data-ajax-popup-over="true" title="{{ __('Create') }}" data-size="xl" data-title="{{ __('Import User CSV Data') }}"  class="d-none import_modal_show"></a>
</div>
{{ Form::close() }}
<script>
    $('#upload_form').on('submit', function(event)
    {
        event.preventDefault();
        let data = new FormData(this);
        data.append('_token', "{{ csrf_token() }}");
        $.ajax({
            url: "{{ route('users.import') }}",
            method: "POST",
            data: data,
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function(data) {
                if (data.error != '')
                {
                    toastrs('Error',data.error, 'error');
                } else {
                    $('#commonModal').modal('hide');
                    $(".import_modal_show").trigger( "click" );
                    setTimeout(function() {
                        SetData(data.output);
                    }, 700);
                }
            }
        });

    });
    function SetData(params,count = 0)
    {
        if(count < 4)
        {
            var process_area = document.getElementById("process_area");
            if(process_area)
            {
                $('#process_area').html(params);
            }
            else
            {
                setTimeout(function() {
                    SetData(params,count+1);
                }, 500);
            }
        }
        else
        {
            toastrs('Success', '{{ __("Something went wrong please try again!") }}', 'success');
        }
    }
</script>
