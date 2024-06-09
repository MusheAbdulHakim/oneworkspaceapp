@php
    $planprice = !empty($plan) ? $plan->package_price_monthly : 0;
    $planpriceyearly = !empty($plan) ? $plan->package_price_yearly : 0;
    $currancy_symbol = admin_setting('defult_currancy_symbol');
    $plan_modules = explode(',',$plan->modules);
@endphp
@extends('layouts.main')
@section('page-title')
    {{ __('Plan Payment') }}
@endsection
@section('page-breadcrumb')
    {{ __('Plan Payment') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('active.plans') }}" class="btn-submit btn btn-sm btn-primary " data-toggle="tooltip"
        title="{{ __('Back') }}">
        <i class=" ti ti-arrow-back-up"></i> </a>
    </div>
@endsection
@push('scripts')
@endpush
@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-8 col-xl-7">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body package-card-inner  d-flex align-items-center">
                                    <div class="package-itm theme-avtar border border-secondary">
                                        <img src="{{ (!empty(admin_setting('favicon')) && check_file(admin_setting('favicon'))) ? get_file(admin_setting('favicon')) : get_file('uploads/logo/favicon.png')}}{{'?'.time()}}" alt="">
                                    </div>
                                    <div class="package-content flex-grow-1  px-3">
                                        <h4>{{$plan->name}}</h4>
                                        <div class="text-muted"> <a href="#activated-add-on">{{ __(count($plan_modules).' Premium Add-on')}}</a></div>
                                    </div>
                                    <div class="price text-end">
                                        <ins class="plan-price-text">{{ $planprice . ' ' . admin_setting('defult_currancy')  }}</ins>
                                        <span class="time-lbl text-muted plan-time-text">{{ __('/Month') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ((count($plan_modules) > 0) &&( count($modules) > 0))
                        <h5 class="mb-1" id="add-on-list">{{ __('Modules') }}</h5>
                            @foreach ($modules as $module)
                                @if(in_array($module,$plan_modules))
                                    @php
                                        $path = $module->getPath() . '/module.json';
                                        $json = json_decode(file_get_contents($path), true);
                                    @endphp
                                    @if (!isset($json['display']) || $json['display'] == true)
                                        <div class="col-xxl-3 col-xl-4 col-lg-6 col-sm-6 product-card ">
                                            <div class="product-card-inner">
                                                <div class="card active_module">
                                                    <div class="product-img">
                                                        <div class="theme-avtar">
                                                            <img src="{{ get_module_img($module->getName()) }}"
                                                                alt="{{ $module->getName() }}" class="img-user"
                                                                style="max-width: 100%">
                                                        </div>
                                                    </div>
                                                    <div class="product-content">
                                                        <h4> {{ Module_Alias_Name($module->getName()) }}</h4>
                                                        <p class="text-muted text-sm mb-0">
                                                            {{ isset($json['description']) ? $json['description'] : '' }}
                                                        </p>
                                                        <a href="{{ route('software.details',Module_Alias_Name($module->getName())) }}" target="_new" class="btn  btn-outline-secondary w-100 mt-2">{{ __('View Details')}}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endforeach
                        @else
                            <div class="col-lg-12 col-md-12">
                                <div class="card p-5">
                                    <div class="d-flex justify-content-center">
                                        <div class="ms-3 text-center">
                                            <h3>{{ __('Add-on Not Available') }}</h3>
                                            <p class="text-muted">{{ __('Click ') }}<a
                                                    href="{{ url('/') }}">{{ __('here') }}</a>
                                                {{ __('to back home') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xxl-4 col-xl-5">
                    <div class="card subscription-counter">
                        <div class="card-header d-flex justify-content-between">
                            <h5 class="mt-1">{{$plan->name}}</h5>
                            <label class="switch ">
                                <span class="lbl time-monthly text-primary">{{ __('Monthly')}}</span>
                                <input type="checkbox" {{ ((isset($session) && !empty($session) && ($session['time_period'] == 'Year'))) ? 'checked' :''}} name="time-period" class="switch-change">
                                <span class="slider round"></span>
                                <span class="lbl time-yearly">{{ __('Yearly')}}</span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="row coupon_section">
                                <div class="col-sm-12 col-lg-12 col-md-12">
                                    <div class="d-flex align-items-center">
                                        <div class="form-group w-100">
                                            <label for="coupon" class="form-label">{{__('Coupon')}}</label>
                                            <input type="text" id="coupon" name="coupon" class="form-control coupon" placeholder="Enter Coupon Code">
                                        </div>
                                        <div class="form-group  ms-3 mt-4 apply-coupon">
                                            <button type="button" class="btn  btn-primary"  data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Apply') }}" id="coupon-apply" ><i class="ti ti-square-check btn-apply "></i></button>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            <div class="subscription-summery">
                                <div class="">
                                    <span class="cart-sum-left"><h6 class="">{{ __('Payment Method') }}:</h6></span>
                                    <div class="row">
                                        @if(admin_setting('bank_transfer_payment_is_on') == 'on' )
                                            <div class="col-sm-12 col-lg-12 col-md-12">
                                                <div class="card">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center">
                                                                <div class="">
                                                                    <label for="bank-payment">
                                                                        <h5 class="mb-0 text-capitalize pointer">{{__('Bank Transfer')}}</h5>
                                                                    </label>
                                                                </div>
                                                            </div>

                                                            <div class="form-check">
                                                                <input class="form-check-input payment_method" name="payment_method" id="bank-payment" type="radio" data-payment-action="{{ route('plan.pay.with.bank') }}">
                                                            </div>
                                                        </div>
                                                        <div class="row mt-2">
                                                            <div class="col-sm-8">
                                                                <div class="form-group">
                                                                    <label
                                                                        class="form-label">{{ __('Bank Details :') }}</label>
                                                                        <p class="">
                                                                            {!!admin_setting('bank_number') !!}
                                                                        </p>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-4">
                                                                <div class="form-group">
                                                                    <label class="form-label">{{ __('Payment Receipt') }}</label>
                                                                    <div class="choose-files">
                                                                    <label for="temp_receipt">
                                                                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i></div>
                                                                        <input type="file" class="form-control temp_receipt" accept="image/png, image/jpeg, image/jpg, .pdf" name="temp_receipt" id="temp_receipt" data-filename="temp_receipt" onchange="document.getElementById('blah3').src = window.URL.createObjectURL(this.files[0])">
                                                                    </label>
                                                                    <p class="text-danger error_msg d-none">{{ __('This field is required')}}</p>

                                                                    <img class="mt-2" width="70px" src=""  id="blah3">
                                                                </div>
                                                                    <div class="invalid-feedback">{{ __('invalid form file') }}</div>
                                                                </div>
                                                            </div>
                                                            <small class="text-danger">{{ __('first, make a payment and take a screenshot or download the receipt and upload it.')}}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        @stack('company_plan_payment')
                                    </div>
                                    <div class="cart-footer-total-row bg-primary text-white rounded p-3 d-flex align-items-center justify-content-between">
                                        <div class="mini-total-price">
                                            <div class="price">
                                                <h3 class="text-white mb-0 total">{{ '0.00'.$currancy_symbol }}</h3>
                                                <span class="time-lbl plan-time-text">{{ __('/Month')}}</span>
                                            </div>
                                        </div>
                                        {{Form::open(array('','method'=>'post','id'=>'payment_form','enctype' => 'multipart/form-data'))}}
                                            <input type="hidden" name="user_counter_input" value="{{ !empty($plan->number_of_user) ? $plan->number_of_user: -1 }}" class="user_counter_input">
                                            <input type="hidden" name="workspace_counter_input" value="{{!empty($plan->number_of_workspace) ? $plan->number_of_workspace : -1 }}" class="workspace_counter_input">
                                            <input type="hidden" name="user_module_input" value="{{$plan->modules}}" name="user_module_input"
                                                class="user_module_input">
                                            <input type="hidden" name="userprice_input" value="0" class="userprice_input">
                                            <input type="hidden" name="user_module_price_input" value="0" class="user_module_price_input">
                                            <input type="hidden" name="time_period" value="Month" class="time_period_input">
                                            <input type="hidden" name="coupon_code" value="" class="coupon_code">
                                            <input type="hidden" name="plan_id" value="{{$plan->id}}" class="plan_id">
                                            <div class="text-end form-btn">
                                            </div>
                                        {{Form::close()}}
                                    </div>
                                    <div class="cart-reset text-center  mt-3">
                                        <a href="{{ route('module.reset') }}" class="reset-btn"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                                                <path d="M6 0.625C3.036 0.625 0.625 3.0365 0.625 6C0.625 8.9635 3.036 11.375 6 11.375C8.964 11.375 11.375 8.9635 11.375 6C11.375 3.0365 8.964 0.625 6 0.625ZM6 10.625C3.4495 10.625 1.375 8.5505 1.375 6C1.375 3.4495 3.4495 1.375 6 1.375C8.5505 1.375 10.625 3.4495 10.625 6C10.625 8.5505 8.5505 10.625 6 10.625ZM7.765 4.76501L6.53 6L7.765 7.23499C7.9115 7.38149 7.9115 7.619 7.765 7.7655C7.692 7.8385 7.596 7.87549 7.5 7.87549C7.404 7.87549 7.308 7.839 7.235 7.7655L6 6.53049L4.765 7.7655C4.692 7.8385 4.596 7.87549 4.5 7.87549C4.404 7.87549 4.308 7.839 4.235 7.7655C4.0885 7.619 4.0885 7.38149 4.235 7.23499L5.47 6L4.235 4.76501C4.0885 4.61851 4.0885 4.381 4.235 4.2345C4.3815 4.088 4.619 4.088 4.7655 4.2345L6.0005 5.46951L7.2355 4.2345C7.382 4.088 7.61951 4.088 7.76601 4.2345C7.91151 4.381 7.9115 4.61901 7.765 4.76501Z" fill="#737373"></path>
                                            </svg>{{ __('Reset')}}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        <!-- [ sample-page ] end -->
    </div>
    <!-- [ Main Content ] end -->
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var planprice = '{{ $planprice }}';
            if(planprice  == 0){
                $(".coupon_section").addClass("d-none");
            }else{
                $(".coupon_section").removeClass("d-none");
            }
            if ($('.switch-change').prop('checked')==true)
            {
                planprice = '{{ $planpriceyearly }}';
            }
            var user = parseInt($('.user_counter_input').val());

            var currancy_symbol = '{{ $currancy_symbol }}';
            $(".total").text(parseFloat(planprice).toFixed(2) + currancy_symbol);
        });
        $(document).on("click", ".user_module_check", function() {
            if ($(this).closest(".user_module").hasClass("active_module"))
            {
                $(this).closest(".user_module").removeClass("active_module");

            } else {
                $(this).closest(".user_module").addClass("active_module");
            }
            ChangeModulePrice();
            ChangePrice();

        });
        $(document).on("click","#coupon-apply",function() {
            var coupon = $('#coupon').val();
            var duration = $('.time_period_input').val();
            var plan_id = '{{$plan->id}}';
            if(coupon == ''){
                toastrs('Error', "{{__('Coupon code required.')}}", 'error');
            }else{
                $.ajax({
                    url: '{{ route('apply.coupon') }}',
                    type: 'GET',
                    data: {
                        "plan_id": plan_id,
                        "coupon": coupon,
                        "duration": duration,
                        "_token": "{{ csrf_token() }}",
                    },
                    success: function(data)
                    {
                        if (data != '' ) {
                            if (data.is_success == true) {
                                    var currancy_symbol = '{{ $currancy_symbol }}';
                                    var finalPrice = data.final_price + currancy_symbol;
                                    var originalPrice = data.final_price + currancy_symbol;

                                    $('.total').html('<span class="original-price">' + data.price + currancy_symbol+ '</span> / ' + '<span class="">'+finalPrice + '</span>');
                                    // Apply text-decoration: line-through to the original price
                                    $('.original-price').css("text-decoration", "line-through");
                                    $('.coupon_code').val(coupon);
                                    toastrs('success', data.message, 'success');
                            } else {
                                $('.coupon_code').val("");
                                toastrs('Error', data.message, 'error');
                            }

                        } else {
                            toastrs('Error', "{{__('Coupon code required.')}}", 'error');
                        }
                    }
                });
            }
        });
    </script>
    <script>
        $(document).on('keyup mouseup', '#user_counter', function() {
            var user_counter = parseInt($(this).val());
            if (user_counter <= 0 || user_counter > 1000 || $(this).val() == '')
            {
                $(this).val(0)
                var user_counter = 0;
            }
            $(".user_counter_text").text(user_counter);
            $(".user_counter_input").val(user_counter);
            ChangePrice(user_counter)
        });
    </script>
    <script>
        function ChangePrice(user = null, user_module_price = 0) {
            var planprice = '{{ $planprice }}';
            if ($('.switch-change').prop('checked')==true)
            {
                 planprice = '{{ $planpriceyearly }}';
            }

            var currancy_symbol = '{{ $currancy_symbol }}';
            if (user == null) {
                var user = parseInt($('.user_counter_input').val());
            }
            if (user_module_price == 0) {
                var user_module_price = parseFloat($('.user_module_price_input').val());
            }

            $(".total").text(parseFloat(planprice).toFixed(2) + currancy_symbol);

        }
        function ChangeModulePrice() {
            var user_module_input = new Array();
            var user_module_price = parseFloat(0);
            var currancy_symbol = '{{ $currancy_symbol }}';
            var n = jQuery(".user_module_check:checked").length;

            var time = '/Month';
            if ($('.switch-change').prop('checked')==true)
            {
                time = '/Year';
            }

            $("#extension_div").empty();

            if (n > 0) {
                jQuery(".user_module_check:checked").each(function() {

                    var alias = $(this).attr('data-module-alias');
                    var img = $(this).attr('data-module-img');
                    var price = parseFloat($(this).attr('data-module-price-monthly'));

                    if ($('.switch-change').prop('checked')==true)
                    {
                        price = parseFloat($(this).attr('data-module-price-yearly'));
                    }

                    $("#extension_div").append(`<div class="col-md-6 col-sm-6  my-2">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar">
                                            <img src="` + img + `" alt="` + img + `" class="img-user" style="max-width: 100%">
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0 text-capitalize">` + alias + `</p>
                                            <h4 class="mb-0 text-primary">` + price + currancy_symbol + `<span class="text-sm">`+time+`</span></h4>
                                        </div>
                                    </div>
                                </div>`);

                    user_module_input.push($(this).val());
                    user_module_price = user_module_price + price;
                });
            }
            $(".module_counter_text").text(n);
            $(".module_price_text").text(parseFloat(user_module_price).toFixed(2) + currancy_symbol);
            // $(".user_module_input").val(user_module_input);
            $(".user_module_price_input").val(user_module_price);
        }
    /********* qty spinner ********/
    var quantity = 0;
    $('.quantity-increment').click(function()
    {
        var t = $(this).siblings('.quantity');
        var quantity = parseInt($(t).val());
        if(quantity < 1000 || $(this).val() != '')
        {
            $(t).val(quantity + 1);
            $(".user_counter_text").text(quantity + 1);
            $(".user_counter_input").val(quantity + 1);
        }
        else
        {
            $(t).val(1000);
            $(".user_counter_text").text(1000);
            $(".user_counter_input").val(1000);
        }

        ChangePrice()
    });
    $('.quantity-decrement').click(function()
    {
        var t = $(this).siblings('.quantity');
        var quantity = parseInt($(t).val());
        if(quantity > 1)
        {
            $(t).val(quantity - 1);
            $(".user_counter_text").text(quantity - 1);
            $(".user_counter_input").val(quantity - 1);

        }
        else
        {
            $(t).val(0);
            $(".user_counter_text").text(0);
            $(".user_counter_input").val(0);
        }
        ChangePrice()
    });
    </script>
    <script>
        $(document).on("click",".switch-change",function()
        {
            SwitchChange()
        });

        function SwitchChange()
        {
            var planprice = '{{ $planprice }}';
            var currancy_symbol = '{{ $currancy_symbol }}';
            var user = parseInt($('.user_counter_input').val());
            var time = '/Month';


            if ($('.switch-change').prop('checked') == true)
            {

                $(".time-monthly").removeClass("text-primary");
                $(".time-yearly").addClass("text-primary");

                $(".m-price-yearly").removeClass("d-none");
                $(".m-price-monthly").addClass("d-none");

                planprice = '{{ $planpriceyearly }}';

                time = '/Year';

                $(".time_period_input").val('Year');

            }
            else
            {
                $(".time-yearly").removeClass("text-primary");
                $(".time-monthly").addClass("text-primary");

                $(".m-price-monthly").removeClass("d-none");
                $(".m-price-yearly").addClass("d-none");

                $(".time_period_input").val('Month');

            }


            $(".plan-price-text").text(planprice + ' {{ admin_setting("defult_currancy") }}');
            $(".plan-time-text").text(time);


            ChangeModulePrice()
            ChangePrice()
        }
    </script>
    <script>
        $(document).ready(function () {
            var numItems = $('.payment_method').length

            if(numItems > 0)
            {
                $('.form-btn').append('<button type="submit" class="btn btn-dark payment-btn"  >{{ __("Buy Now") }}</button>');
                setTimeout(() => {
                    $(".payment_method").first().attr('checked', true);
                    $(".payment_method").first().trigger('click');
                }, 200);
            }
            else
            {
                $('.form-btn').append("<span class='text-danger'>{{ __('Admin payment settings not set')}}</span>");
            }

        });
        $( "#payment_form" ).on( "submit", function( event ) {
            "{{session()->put('Subscription','plan_subscription')}}";
        });

         $(document).on("click",".payment_method",function() {
            var payment_action = $(this).attr("data-payment-action");
            if(payment_action != '' && payment_action != undefined)
            {
                $("#payment_form").attr("action",payment_action);
            }
            else
            {
                $("#payment_form").attr("action",'');
            }
            if ($('#bank-payment').prop('checked'))
            {
                $(".temp_receipt").attr("required", "required");
            }
            else
            {
                $(".temp_receipt").removeAttr("required");
            }
        });
    </script>
    {{-- if session is not empty --}}
    @if (isset($session) && !empty($session))
    <script>
        $(document).ready(function () {
            $('#user_counter').val("{{ $session['user_counter']}}");
            $('#user_counter').trigger('keyup')
            SwitchChange();
        });
    </script>
    @endif
@if (admin_setting('bank_transfer_payment_is_on') == 'on')
<script>

    $('#payment_form').submit(function(e)
    {
        if ($('#bank-payment').prop('checked'))
        {
            e.preventDefault(); // Prevent form submission


            var file = document.getElementById('temp_receipt').files[0];

            if(file != undefined)
            {
                $('.error_msg').addClass('d-none');

                // Create a new FormData object
                const formData = new FormData();

                // Add file data from the file input element
                const file = $('#temp_receipt')[0].files[0];
                formData.append('payment_receipt', file, file.name);

                // Add data from the form's input elements
                $('#payment_form input').each(function() {
                formData.append(this.name, this.value);
                });

                var url = $('#payment_form').attr('action');


                $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if(response.status == 'success')
                    {
                        toastrs('Success', response.msg, 'success');
                        setTimeout(() => {
                            location.reload();
                        }, 1000);
                    }
                    else
                    {
                        toastrs('Error', response.msg, 'error');
                    }
                    // Handle success response
                },
                error: function(xhr, status, error) {
                    toastrs('Error',error, 'error');
                    // Handle error response
                }
                });

            }
            else
            {
                $('.error_msg').removeClass('d-none');
            }
        }
    });
</script>
@endif
@endpush
