<div class="modal-body">
    <div class="row">
        <div class="col-12">
            <div id="process_area" class="overflow-auto import-data-table">
            </div>
        </div>
        <div class="form-group col-12 d-flex justify-content-end col-form-label">
            <input type="button" value="{{ __('Cancel') }}" class="btn btn-secondary btn-light" data-bs-dismiss="modal">
            <button type="submit" name="import" id="import" class="btn btn-primary ms-2" disabled>{{__('Import')}}</button>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        var total_selection = 0;

        var first_name = 0;

        var last_name = 0;

        var email = 0;

        var column_data = [];

        $(document).on('change', '.set_column_data', function() {
            var column_name = $(this).val();

            var column_number = $(this).data('column_number');

            if (column_name in column_data) {

                toastrs('Error', 'You have already define ' + column_name + ' column', 'error');

                $(this).val('');
                return false;
            }
            if (column_name != '') {
                column_data[column_name] = column_number;
            } else {
                const entries = Object.entries(column_data);

                for (const [key, value] of entries) {
                    if (value == column_number) {
                        delete column_data[key];
                    }
                }
            }

            total_selection = Object.keys(column_data).length;
            if (total_selection == 13) {
                $("#import").removeAttr("disabled");
                name = column_data.name;
                dob = column_data.dob;
                gender = column_data.gender;
                phone = column_data.phone;
                address = column_data.address;
                email = column_data.email;
                password = column_data.password;
                company_doj = column_data.company_doj;
                account_holder_name = column_data.account_holder_name;
                account_number = column_data.account_number;
                bank_name = column_data.bank_name;
                bank_identifier_code = column_data.bank_identifier_code;
                tax_payer_id = column_data.tax_payer_id;
            } else {
                $('#import').attr('disabled', 'disabled');
            }

        });

        $("#submit").click(function() {
            $(".doc_data").each(function() {
                if (!isNaN(this.value)) {
                    var id = '#doc_validation-' + $(this).data("key");
                    $(id).removeClass('d-none')
                    return false;
                }
            });
        });

        $(document).on('click', '#import', function(event) {

            event.preventDefault();
            var branch = [];
            var department = [];
            var designation = [];
            $(".branch-name-value").each(function() {
                var value = $(this).val();
                branch.push(value);
            })

            $(".department-name-value").each(function() {
                var value = $(this).val();
                department.push(value);
            })

            $(".designation-name-value").each(function() {
                var value = $(this).val();
                designation.push(value);
            })

            $.ajax({
                url: "{{ route('employee.import.data') }}",
                method: "POST",
                data: {
                    name: name,
                    dob: dob,
                    gender: gender,
                    phone: phone,
                    address: address,
                    email: email,
                    password: password,
                    company_doj: company_doj,
                    account_holder_name: account_holder_name,
                    account_number: account_number,
                    bank_name: bank_name,
                    bank_identifier_code: bank_identifier_code,
                    tax_payer_id: tax_payer_id,
                    branch: branch,
                    department: department,
                    designation: designation,
                    _token: "{{ csrf_token() }}"
                },
                beforeSend: function() {
                    $('#import').attr('disabled', 'disabled');
                    $('#import').text('Importing...');
                },
                success: function(data) {
                    $('#import').attr('disabled', false);
                    $('#import').text('Import');
                    $('#upload_form')[0].reset();

                    if (data.html == true) {
                        $('#process_area').html(data.response);
                        $("button").hide();
                        toastrs('Error', 'These data are not inserted', 'error');

                    } else {
                        $('#message').html(data.response);
                        $('#commonModalOver').modal('hide')
                        toastrs('Success', data.response, 'success');
                        location.reload();
                    }

                }
            })

        });
    });
</script>
