@push('scripts')
    <script>
        $("#plan_package").click(function() {
            var plan_package = ($("input[name=plan_package]").is(':checked')) ? $("input[name=plan_package]")
                .val() : "off";
            packages(plan_package, null);
        });

        $("#custome_package").click(function() {
            var custome_package = ($("input[name=custome_package]").is(':checked')) ? $(
                "input[name=custome_package]").val() : "off";
            packages(null, custome_package);

        });
        function packages(plan_package = null, custome_package = null) {
            $.ajax({
                url: '{{ route('package.data') }}',
                type: 'POST',
                data: {
                    "custome_package": custome_package,
                    "plan_package": plan_package,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    console.log(data);
                    if (data.custome_package == 'on' || data.plan_package == 'on') {
                        location.reload(true);
                    }
                    if (data.custome_package == 'off') {
                        var url = '{{ route('plan.list') }}';
                        window.location.replace(url)
                    }
                    if (data.plan_package == 'off') {
                        var url = '{{ route('plans.index') }}';
                        window.location.replace(url)
                    }
                    if (data == 'error') {
                        toastrs('Error', 'Both options cannot be disabled at the same time.', 'error');
                        setTimeout(function() {
                            location.reload(true);
                        }, 2000);
                    }
                }
            });
        }

    </script>
    <script>
        $(document).on('change', '#is_free_plan', function() {
            var value =  $(this).val();
            PlanLable(value);
        });
        $(document).on('change', '#trial', function() {
            if ($(this).is(':checked')) {
                $('.plan_div').removeClass('d-none');
                $('#trial').attr("required", true);

            } else {
                $('.plan_div').addClass('d-none');
                $('#trial').removeAttr("required");
            }
        });

        $(document).on('keyup mouseup', '#number_of_user', function() {
            var user_counter = parseInt($(this).val());
            if (user_counter == 0  || user_counter < -1)
            {
                $(this).val(1)
            }

        });
        $(document).on('keyup mouseup', '#number_of_workspace', function() {
            var workspace_counter = parseInt($(this).val());
            if (workspace_counter == 0 || workspace_counter < -1)
            {
                $(this).val(1)
            }
        });

        function PlanLable(value){


            if(value == 1){
                $('.plan_price_div').addClass('d-none');
            }
            if(value == 0){
                $('.plan_price_div').removeClass('d-none');
                if ($(".add_lable").find(".text-danger").length === 0) {
                    $(".add_lable").append(`<span class="text-danger"> <sup>Paid</sup></span>`);
                }
            }
            if (!$('#trial').is(':checked')) {
                $('.plan_div').addClass('d-none');
                $('#trial').removeAttr("required");
            }
        }
    </script>
    <script>
        $('.input-primary').on('change', function() {
            var planId = $(this).data('id');
            var isChecked = $(this).prop('checked');

            $.ajax({
                type: 'POST',
                url: '{{ route('update.plan.status') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'plan_id': planId
                },
                success: function(response) {
                    console.log(response);
                    if(response.status == 'error'){
                        toastrs('Error',response.message, 'error');
                    }
                    else{
                        toastrs('Success',response.message ,'success')
                    }

                },
                error: function(error) {

                    if (error.status === 404) {
                        $(this).prop('checked', !isChecked);
                    }
                }
            });
        });
    </script>
@endpush
