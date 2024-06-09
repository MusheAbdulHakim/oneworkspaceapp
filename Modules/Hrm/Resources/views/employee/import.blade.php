{{ Form::open(['method' => 'post', 'enctype' => 'multipart/form-data', 'id' => 'upload_form']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 mb-6">
            {{ Form::label('file', __('Download Sample Employee CSV File'), ['class' => 'col-form-label text-danger mx-1']) }}
            @if(check_file('uploads/sample/sample_employee.csv'))
                <a href="{{ asset('uploads/sample/sample_employee.csv') }}"
                    class="btn btn-sm btn-primary btn-icon-only">
                    <i class="fa fa-download"></i>
                </a>
            @endif
        </div>
        <div class="col-md-12 mt-1">
            {{ Form::label('file', __('Select CSV File'), ['class' => 'col-form-label']) }}
            <div class="choose-file form-group">
                <label for="file" class="col-form-label">
                    <input type="file" class="form-control" name="file" id="file" data-filename="upload_file"
                        required>
                </label>
                <p class="upload_file"></p>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
    <button type="submit" value="{{ __('Upload') }}" class="btn btn-primary ms-2">
        {{__('Upload')}}
    </button>
    <a href="" data-url="{{ route('employee.import.modal') }}" data-ajax-popup-over="true" title="{{ __('Create') }}" data-size="xl" data-title="{{ __('Import Employee CSV Data') }}"  class="d-none import_modal_show"></a>
</div>
{{ Form::close() }}

<script>
    $(document).on('change', '.branch-name-value', function() {
        var branchDropdown = $(this);
        var branch_id = branchDropdown.val();
        var departmentDropdown = branchDropdown.closest('tr').find('.department-name-value');

        getDepartment(branch_id, departmentDropdown);
    });

    function getDepartment(branch_id, departmentDropdown) {
        var data = {
            "branch_id": branch_id,
            "_token": "{{ csrf_token() }}",
        }

        $.ajax({
            url: '{{ route('employee.getdepartments') }}',
            method: 'POST',
            data: data,
            success: function(data) {
                departmentDropdown.empty();
                departmentDropdown.append(
                    '<option value="" disabled>{{ __('Select Department') }}</option>');

                $.each(data, function(key, value) {
                    departmentDropdown.append('<option value="' + key + '">' + value + '</option>');
                });
                departmentDropdown.val('');

                // Trigger change event on department dropdown to update designations
                departmentDropdown.change();
            }
        });
    }

    $(document).on('change', '.department-name-value', function() {
        var departmentDropdown = $(this);
        var department_id = departmentDropdown.val();
        var designationDropdown = departmentDropdown.closest('tr').find('.designation-name-value');

        getDesignation(department_id, designationDropdown);
    });

    function getDesignation(department_id, designationDropdown) {
        $.ajax({
            url: '{{ route('employee.getdesignations') }}',
            type: 'POST',
            data: {
                "department_id": department_id,
                "_token": "{{ csrf_token() }}",
            },
            success: function(data) {
                designationDropdown.empty();
                designationDropdown.append('<option value="">{{ __('Select Designation') }}</option>');

                $.each(data, function(key, value) {
                    designationDropdown.append('<option value="' + key + '">' + value +
                    '</option>');
                });
            }
        });
    }

    $('#upload_form').on('submit', function(event) {

        event.preventDefault();
        let data = new FormData(this);
        data.append('_token', "{{ csrf_token() }}");
        $.ajax({
            url: "{{ route('employee.import') }}",
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
                    $(".import_modal_show").trigger( "click");
                    setTimeout(function() {
                        SetData(data.output);
                    }, 700);
                }
            }
        });

    });
</script>
