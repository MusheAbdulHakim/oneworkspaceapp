@php
    $planprice = !empty($plan) ? $plan->package_price_monthly : 0;
    $planpriceyearly = !empty($plan) ? $plan->package_price_yearly : 0;
    $currancy_symbol = admin_setting('defult_currancy_symbol');
    $plan_modules = explode(',',$plan->modules);
@endphp
@extends('layouts.main')
@section('page-title')
    {{ __('Plan Assign') }}
@endsection
@section('page-breadcrumb')
    {{ __('Plan Assign') }}
@endsection

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

                        @if ((count($plan_modules) > 0) && ( count($modules) > 0))
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
                                <input type="checkbox" name="time-period" class="switch-change">
                                <span class="slider round"></span>
                                <span class="lbl time-yearly">{{ __('Yearly')}}</span>
                            </label>
                        </div>
                        <div class="card-body">
                            <div class="subscription-summery">
                                <div class="">
                                    <span class="cart-sum-left"><h6 class="">{{ __('Payment Method') }}:</h6></span>
                                    <div class="cart-footer-total-row bg-primary text-white rounded p-3 d-flex align-items-center justify-content-between">
                                        <div class="mini-total-price">
                                            <div class="price">
                                                <h3 class="text-white mb-0 total">{{ super_currency_format_with_sym($plan->package_price_monthly) }}</h3>
                                                <span class="time-lbl plan-time-text">{{ __('/Month')}}</span>
                                            </div>
                                        </div>
                                       
                                        {{Form::open(array('route'=>['assign.plan.user',[Crypt::encrypt($plan->id),Crypt::encrypt($user->id)]],'method'=>'post'))}}
                                            <input type="hidden" name="time_period" value="Month" class="time_period_input">
                                            <div class="text-end form-btn">
                                                <button type="submit" class="btn btn-dark payment-btn"  >{{ __("Assign Now") }}</button>
                                            </div>
                                        {{Form::close()}}
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

        function ChangePrice(user = null, user_module_price = 0) {
            
            var planprice = '{{ super_currency_format_with_sym($planprice) }}';
            if ($('.switch-change').prop('checked')==true)
            {
                 planprice = '{{ super_currency_format_with_sym($planpriceyearly) }}';
            }

            var currancy_symbol = '{{ $currancy_symbol }}';
            if (user == null) {
                var user = parseInt($('.user_counter_input').val());    
            }
            if (user_module_price == 0) {
                var user_module_price = parseFloat($('.user_module_price_input').val());
            }

            $(".total").text(planprice);

        }
    </script>
    
@endpush
