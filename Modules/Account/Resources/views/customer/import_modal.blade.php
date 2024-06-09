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
            if (total_selection == 18) {
                $("#import").removeAttr("disabled");
                name = column_data.name;
                email = column_data.email;
                password = column_data.password;
                contact = column_data.contact;
                billing_name = column_data.billing_name;
                billing_country = column_data.billing_country;
                billing_state = column_data.billing_state;
                billing_city = column_data.billing_city;
                billing_phone = column_data.billing_phone;
                billing_zip = column_data.billing_zip;
                billing_address = column_data.billing_address;
                shipping_name = column_data.shipping_name;
                shipping_country = column_data.shipping_country;
                shipping_state = column_data.shipping_state;
                shipping_city = column_data.shipping_city;
                shipping_phone = column_data.shipping_phone;
                shipping_zip = column_data.shipping_zip;
                shipping_address = column_data.shipping_address;
            } else {
                $('#import').attr('disabled', 'disabled');
            }

        });

        $(document).on('click', '#import', function(event) {

            event.preventDefault();

            $.ajax({
                url: "{{ route('customer.import.data') }}",
                method: "POST",
                data: {
                    name: name,
                    email: email,
                    password: password,
                    contact: contact,
                    billing_name: billing_name,
                    billing_country: billing_country,
                    billing_state: billing_state,
                    billing_city: billing_city,
                    billing_phone: billing_phone,
                    billing_zip: billing_zip,
                    billing_address: billing_address,
                    shipping_name: shipping_name,
                    shipping_country: shipping_country,
                    shipping_state: shipping_state,
                    shipping_city: shipping_city,
                    shipping_phone: shipping_phone,
                    shipping_zip: shipping_zip,
                    shipping_address: shipping_address,
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
