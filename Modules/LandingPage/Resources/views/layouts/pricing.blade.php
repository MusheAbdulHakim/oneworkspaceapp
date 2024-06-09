@php
    $admin_settings = getAdminAllSetting();
    $currancy_symbol = admin_setting('defult_currancy_symbol');
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();

    $userprice = !empty($plan) ? $plan->price_per_user_monthly : 0;
    $planprice = !empty($plan) ? $plan->package_price_monthly : 0;
    $workspaceprice = !empty($plan) ? $plan->price_per_workspace_monthly : 0;
    $userpriceyearly = !empty($plan) ? $plan->price_per_user_yearly : 0;
    $planpriceyearly = !empty($plan) ? $plan->package_price_yearly : 0;
    $workspacepriceyearly = !empty($plan) ? $plan->price_per_workspace_yearly : 0;

@endphp
@extends($layout)
@section('page-title')
    {{ __('Pricing') }}
@endsection
@section('content')
    <!-- wrapper start -->
    <div class="wrapper">
        <section class="pricing-banner common-banner-section">
            <div class="offset-container offset-left">
                <div class="row row-gap align-items-center justify-content-center">
                    <div class="col-lg-9 col-md-7 col-12">
                        <div class="common-banner-content">
                            <div class="section-title text-center">
                                <h1><b>{{ __('Simple Pricing') }}</b></h1>
                                <p>{{ __('Choose extensions that best match your business needs') }}</p>
                            </div>
                            <div class="pricing-switch">
                                <label class="switch ">
                                    <span class="lbl time-monthly active">{{ __('Monthly') }}</span>
                                    <input type="checkbox" name="time-period" class="switch-change">
                                    <span class="slider round"></span>
                                    <span class="lbl time-yearly">{{ __('Yearly') }}</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-5 col-12">
                        <div class="banner-image">
                            <img src="{{ asset('market_assets/images/dash-banner-image.png') }}" alt="">
                            <div class="ripple-icon position-top">
                                <div class="pulse0"></div>
                                <div class="pulse1"></div>
                                <div class="pulse2"></div>
                                <div class="pulse3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="module-section padding-bottom ">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-xl-9 col-12 module-section-left">
                        <div class="selected-module-list">
                            <div class="package-card">
                                <div class="package-card-inner">
                                    <div class="package-itm">
                                        <img src="{{ !empty(admin_setting('favicon')) && check_file(admin_setting('favicon')) ? get_file(admin_setting('favicon')) : get_file('uploads/logo/favicon.png') }}{{ '?' . time() }}"
                                            alt="">
                                    </div>
                                    <div class="package-content flex-grow-1  px-3">
                                        <h4>{{ __('Basic Package') }}</h4>
                                        <div class="text-muted">{{ __('+' . count($modules) . ' Premium Add-on') }}</div>
                                    </div>
                                    <div class="price text-end">
                                        <ins
                                            class="plan-price-text">{{ $planprice . ' ' . admin_setting('defult_currancy') }}</ins>
                                        <span class="time-lbl text-muted plan-time-text">{{ __('/Month') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="product-selection">
                            <div class="row product-row">
                                @if (count($modules) > 0)
                                    @foreach ($modules as $key => $module)
                                        @php
                                            $path = $module->getPath() . '/module.json';
                                            $json = json_decode(file_get_contents($path), true);
                                        @endphp
                                        @if (!isset($json['display']) || $json['display'] == true)
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12 product-card">
                                                <div class="product-card-inner">
                                                    <div class="product-img">
                                                        <div class="theme-avtar">
                                                            <a
                                                                href="{{ route('software.details', Module_Alias_Name($module->getName())) }}">
                                                                <img src="{{ get_module_img($module->getName()) }}"
                                                                    alt="{{ $module->getName() }}" class="img-user">
                                                        </div>
                                                        </a>
                                                        <div class="checkbox-custom">
                                                            <input type="checkbox" id="{{ 'checkbox-' . $key }}"
                                                                class="form-check-input pointer user_module_check"
                                                                data-module-img="{{ get_module_img($module->getName()) }}"
                                                                data-module-price-monthly="{{ ModulePriceByName($module->getName())['monthly_price'] }}"
                                                                data-module-price-yearly="{{ ModulePriceByName($module->getName())['yearly_price'] }}"
                                                                data-module-alias="{{ Module_Alias_Name($module->getName()) }}"
                                                                value="{{ $module->getName() }}">
                                                            <label for="{{ 'checkbox-' . $key }}"></label>
                                                        </div>
                                                    </div>
                                                    <div class="product-content">
                                                        <div class="lbl">{{ __('Statistics') }}</div>
                                                        <h4> {{ Module_Alias_Name($module->getName()) }}</h4>
                                                        <p class="text-muted text-sm mb-0">
                                                            {{ isset($json['description']) ? $json['description'] : '' }}
                                                        </p>
                                                        <div class="price d-flex justify-content-between">
                                                            <ins class="m-price-monthly"><span
                                                                    class="currency-type">{{ super_currency_format_with_sym(ModulePriceByName($module->getName())['monthly_price']) }}</span>
                                                                <span
                                                                    class="time-lbl text-muted">{{ __('/Month') }}</span></ins>
                                                            <ins class="m-price-yearly d-none"><span
                                                                    class="currency-type">{{ super_currency_format_with_sym(ModulePriceByName($module->getName())['yearly_price']) }}</span>
                                                                <span
                                                                    class="time-lbl text-muted">{{ __('/Year') }}</span></ins>
                                                        </div>
                                                        <a href="{{ route('software.details', Module_Alias_Name($module->getName())) }}"
                                                            target="_new"
                                                            class="btn  btn-outline-secondary w-100 mt-2">{{ __('View Details') }}</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @else
                                    <div class="col-lg-12 col-md-12">
                                        <div class="card p-5">
                                            <div class="d-flex justify-content-center">
                                                <div class="ms-3 text-center">
                                                    <h3>{{ __('Modules Not Available') }}</h3>
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
                    </div>

                    <div class="col-lg-5 col-xl-3 col-12">
                        <div class="cart-summery">
                            <div class="summery-header">
                                <h5>{{ __('Basic Package') }}</h5>
                            </div>
                            <ul>
                                <li>
                                    <span class="cart-sum-left"> <svg xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="25" viewBox="0 0 24 25" fill="none">
                                            <path fill-rule="evenodd"
                                                d="M0 10.5A1.5 1.5 0 0 1 1.5 9h1A1.5 1.5 0 0 1 4 10.5v1A1.5 1.5 0 0 1 2.5 13h-1A1.5 1.5 0 0 1 0 11.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zm10.5.5A1.5 1.5 0 0 1 13.5 9h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5v-1zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1zM6 4.5A1.5 1.5 0 0 1 7.5 3h1A1.5 1.5 0 0 1 10 4.5v1A1.5 1.5 0 0 1 8.5 7h-1A1.5 1.5 0 0 1 6 5.5v-1zM7.5 4a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1z"
                                                fill="#002333" />
                                            <path
                                                d="M6 4.5H1.866a1 1 0 1 0 0 1h2.668A6.517 6.517 0 0 0 1.814 9H2.5c.123 0 .244.015.358.043a5.517 5.517 0 0 1 3.185-3.185A1.503 1.503 0 0 1 6 5.5v-1zm3.957 1.358A1.5 1.5 0 0 0 10 5.5v-1h4.134a1 1 0 1 1 0 1h-2.668a6.517 6.517 0 0 1 2.72 3.5H13.5c-.123 0-.243.015-.358.043a5.517 5.517 0 0 0-3.185-3.185z"
                                                fill="#002333" />
                                        </svg>{{ __('workspace ') }}:</span>
                                    <span class="cart-sum-right workspace_counter_text">0</span>
                                </li>
                                <li>
                                    <span class="cart-sum-left"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                            height="25" viewBox="0 0 24 25" fill="none">
                                            <path
                                                d="M9.00903 11.25C11.353 11.25 13.259 9.343 13.259 7C13.259 4.657 11.353 2.75 9.00903 2.75C6.66503 2.75 4.75903 4.657 4.75903 7C4.75903 9.343 6.66503 11.25 9.00903 11.25ZM9.00903 4.25C10.526 4.25 11.759 5.483 11.759 7C11.759 8.517 10.526 9.75 9.00903 9.75C7.49203 9.75 6.25903 8.517 6.25903 7C6.25903 5.483 7.49203 4.25 9.00903 4.25ZM16.75 18.519V21.5C16.75 21.914 16.414 22.25 16 22.25C15.586 22.25 15.25 21.914 15.25 21.5V18.519C15.25 17.518 14.943 14.25 11 14.25H7C3.057 14.25 2.75 17.517 2.75 18.519V21.5C2.75 21.914 2.414 22.25 2 22.25C1.586 22.25 1.25 21.914 1.25 21.5V18.519C1.25 15.858 2.756 12.75 7 12.75H11C15.244 12.75 16.75 15.857 16.75 18.519ZM14.155 10.7159C13.859 10.4259 13.8529 9.95103 14.1429 9.65503C14.4339 9.35903 14.909 9.35504 15.204 9.64404C15.563 9.99604 16.045 10.1899 16.559 10.1899C17.664 10.1899 18.53 9.32497 18.53 8.21997C18.53 7.13397 17.646 6.25 16.559 6.25C16.251 6.25 15.9731 6.31301 15.7321 6.43701C15.3651 6.62801 14.912 6.48104 14.722 6.11304C14.532 5.74504 14.678 5.29303 15.046 5.10303C15.502 4.86903 16.011 4.75 16.559 4.75C18.473 4.75 20.03 6.30697 20.03 8.21997C20.03 10.133 18.473 11.6899 16.559 11.6899C15.65 11.6899 14.797 11.3439 14.155 10.7159ZM22.75 17.1801V19.5C22.75 19.914 22.414 20.25 22 20.25C21.586 20.25 21.25 19.914 21.25 19.5V17.1801C21.25 16.4411 21.023 14.03 18.11 14.03H16.599C16.185 14.03 15.849 13.694 15.849 13.28C15.849 12.866 16.185 12.53 16.599 12.53H18.11C21.535 12.53 22.75 15.0351 22.75 17.1801Z"
                                                fill="#002333"></path>
                                        </svg>{{ __('Users ') }}:</span>
                                    <span class="cart-sum-right user_counter_text">0</span>
                                </li>
                                <li class="set has-children">
                                    <a href="javascript:;" class="acnav-label">
                                        <span class="cart-sum-left"><svg xmlns="http://www.w3.org/2000/svg"
                                                width="24" height="25" viewBox="0 0 24 25" fill="none">
                                                <path
                                                    d="M21 14.75H19.75V10.25H21C21.414 10.25 21.75 9.914 21.75 9.5C21.75 9.086 21.414 8.75 21 8.75H19.75V8.5C19.75 6.082 18.418 4.75 16 4.75H15.75V3.5C15.75 3.086 15.414 2.75 15 2.75C14.586 2.75 14.25 3.086 14.25 3.5V4.75H9.75V3.5C9.75 3.086 9.414 2.75 9 2.75C8.586 2.75 8.25 3.086 8.25 3.5V4.75H8C5.582 4.75 4.25 6.082 4.25 8.5V8.75H3C2.586 8.75 2.25 9.086 2.25 9.5C2.25 9.914 2.586 10.25 3 10.25H4.25V14.75H3C2.586 14.75 2.25 15.086 2.25 15.5C2.25 15.914 2.586 16.25 3 16.25H4.25V16.5C4.25 18.918 5.582 20.25 8 20.25H8.25V21.5C8.25 21.914 8.586 22.25 9 22.25C9.414 22.25 9.75 21.914 9.75 21.5V20.25H14.25V21.5C14.25 21.914 14.586 22.25 15 22.25C15.414 22.25 15.75 21.914 15.75 21.5V20.25H16C18.418 20.25 19.75 18.918 19.75 16.5V16.25H21C21.414 16.25 21.75 15.914 21.75 15.5C21.75 15.086 21.414 14.75 21 14.75ZM18.25 16.5C18.25 18.077 17.577 18.75 16 18.75H8C6.423 18.75 5.75 18.077 5.75 16.5V8.5C5.75 6.923 6.423 6.25 8 6.25H16C17.577 6.25 18.25 6.923 18.25 8.5V16.5ZM14 8.25H10C8.591 8.25 7.75 9.091 7.75 10.5V14.5C7.75 15.909 8.591 16.75 10 16.75H14C15.409 16.75 16.25 15.909 16.25 14.5V10.5C16.25 9.091 15.409 8.25 14 8.25ZM14.75 14.5C14.75 15.089 14.589 15.25 14 15.25H10C9.411 15.25 9.25 15.089 9.25 14.5V10.5C9.25 9.911 9.411 9.75 10 9.75H14C14.589 9.75 14.75 9.911 14.75 10.5V14.5Z"
                                                    fill="#002333"></path>
                                            </svg>{{ __('Extension') }}:</span>
                                        <span class="cart-sum-right module_counter_text">0</span>
                                    </a>
                                    <div class="acnav-list">
                                        <div class="collapse" id="extension_div">
                                        </div>
                                    </div>
                                </li>

                            </ul>
                            <div class="summery-footer">
                                <div class="user-qty">
                                    <div class="lbl"> {{ __('Choose Workspace') }}:</div>
                                    <div class="qty-spinner">
                                        <button type="button" class="quantity-decrement" data-name = "workspace">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M12 1.25C6.072 1.25 1.25 6.073 1.25 12C1.25 17.927 6.072 22.75 12 22.75C17.928 22.75 22.75 17.927 22.75 12C22.75 6.073 17.928 1.25 12 1.25ZM12 21.25C6.899 21.25 2.75 17.101 2.75 12C2.75 6.899 6.899 2.75 12 2.75C17.101 2.75 21.25 6.899 21.25 12C21.25 17.101 17.101 21.25 12 21.25ZM16.25 12C16.25 12.414 15.914 12.75 15.5 12.75H8.5C8.086 12.75 7.75 12.414 7.75 12C7.75 11.586 8.086 11.25 8.5 11.25H15.5C15.914 11.25 16.25 11.586 16.25 12Z"
                                                    fill="#002333"></path>
                                            </svg>
                                        </button>
                                        <input id="workspace_counter" type="number" data-cke-saved-name="quantity"
                                            name="quantity" class="quantity" step="1" value="0"
                                            min="0" max="1000" data-name = "workspace">
                                        <button type="button" class="quantity-increment " data-name = "workspace">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M12 1.25C6.072 1.25 1.25 6.073 1.25 12C1.25 17.927 6.072 22.75 12 22.75C17.928 22.75 22.75 17.927 22.75 12C22.75 6.073 17.928 1.25 12 1.25ZM12 21.25C6.899 21.25 2.75 17.101 2.75 12C2.75 6.899 6.899 2.75 12 2.75C17.101 2.75 21.25 6.899 21.25 12C21.25 17.101 17.101 21.25 12 21.25ZM16.25 12C16.25 12.414 15.914 12.75 15.5 12.75H12.75V15.5C12.75 15.914 12.414 16.25 12 16.25C11.586 16.25 11.25 15.914 11.25 15.5V12.75H8.5C8.086 12.75 7.75 12.414 7.75 12C7.75 11.586 8.086 11.25 8.5 11.25H11.25V8.5C11.25 8.086 11.586 7.75 12 7.75C12.414 7.75 12.75 8.086 12.75 8.5V11.25H15.5C15.914 11.25 16.25 11.586 16.25 12Z"
                                                    fill="#002333"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="user-qty">
                                    <div class="lbl"> {{ __('Choose Users') }}:</div>
                                    <div class="qty-spinner">
                                        <button type="button" class="quantity-decrement" data-name = "user">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M12 1.25C6.072 1.25 1.25 6.073 1.25 12C1.25 17.927 6.072 22.75 12 22.75C17.928 22.75 22.75 17.927 22.75 12C22.75 6.073 17.928 1.25 12 1.25ZM12 21.25C6.899 21.25 2.75 17.101 2.75 12C2.75 6.899 6.899 2.75 12 2.75C17.101 2.75 21.25 6.899 21.25 12C21.25 17.101 17.101 21.25 12 21.25ZM16.25 12C16.25 12.414 15.914 12.75 15.5 12.75H8.5C8.086 12.75 7.75 12.414 7.75 12C7.75 11.586 8.086 11.25 8.5 11.25H15.5C15.914 11.25 16.25 11.586 16.25 12Z"
                                                    fill="#002333"></path>
                                            </svg>
                                        </button>
                                        <input id="user_counter" type="number" data-cke-saved-name="quantity"
                                            name="quantity" class="quantity" step="1" value="0"
                                            min="0" max="1000" data-name = "user">
                                        <button type="button" class="quantity-increment " data-name = "user">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none">
                                                <path
                                                    d="M12 1.25C6.072 1.25 1.25 6.073 1.25 12C1.25 17.927 6.072 22.75 12 22.75C17.928 22.75 22.75 17.927 22.75 12C22.75 6.073 17.928 1.25 12 1.25ZM12 21.25C6.899 21.25 2.75 17.101 2.75 12C2.75 6.899 6.899 2.75 12 2.75C17.101 2.75 21.25 6.899 21.25 12C21.25 17.101 17.101 21.25 12 21.25ZM16.25 12C16.25 12.414 15.914 12.75 15.5 12.75H12.75V15.5C12.75 15.914 12.414 16.25 12 16.25C11.586 16.25 11.25 15.914 11.25 15.5V12.75H8.5C8.086 12.75 7.75 12.414 7.75 12C7.75 11.586 8.086 11.25 8.5 11.25H11.25V8.5C11.25 8.086 11.586 7.75 12 7.75C12.414 7.75 12.75 8.086 12.75 8.5V11.25H15.5C15.914 11.25 16.25 11.586 16.25 12Z"
                                                    fill="#002333"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <ul>
                                    <li>
                                        <span class="cart-sum-left">
                                            <h6 class="mb-0">{{ __('Basic Package') }}</h6>
                                        </span>
                                        <span class="cart-sum-right"><b
                                                class="planpricetext">{{ $planprice > 0 ? $planprice . $currancy_symbol : 'Free' }}</b></span>
                                    </li>
                                    <li>
                                        <span class="cart-sum-left">
                                            <h6 class="mb-0">{{ __('Workspace') }} <small
                                                    class="text-muted workspace-price">{{ '( Per Workspace ' . $workspaceprice . $currancy_symbol . ' )' }}</small>
                                            </h6>
                                        </span>
                                        <span class="cart-sum-right"><b
                                                class="workspacepricetext">{{ '0.00' . $currancy_symbol }}</b></span>
                                    </li>
                                    <li>
                                        <span class="cart-sum-left">
                                            <h6 class="mb-0">{{ __('Users') }} <small
                                                    class="text-muted user-price">{{ '( Per User ' . $userprice . $currancy_symbol . ' )' }}</small>
                                            </h6>
                                        </span>
                                        <span class="cart-sum-right"><b
                                                class="userpricetext">{{ '0.00' . $currancy_symbol }}</b></span>
                                    </li>
                                    <li>
                                        <span class="cart-sum-left">
                                            <h6 class="mb-0">{{ __('Extension') }}:</h6>
                                        </span>
                                        <span class="cart-sum-right"><b
                                                class="module_price_text">{{ '0.00' . $currancy_symbol }}</b></span>
                                    </li>
                                </ul>
                                <div class="cart-footer-total-row d-flex align-items-center justify-content-between">
                                    <div class="mini-total-price">
                                        <div class="price">
                                            <ins><span
                                                    class="currency-type total">{{ '0.00' . $currancy_symbol }}</span></ins>
                                            <span class="time-lbl plan-time-text">{{ __('/Month') }}</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="user_counter_input" value="0"
                                        class="user_counter_input">
                                    <input type="hidden" name="workspace_counter_input" value="0"
                                        class="workspace_counter_input">
                                    <input type="hidden" name="user_module_input" value=""
                                        class="user_module_input">
                                    <input type="hidden" name="userprice_input" value="0" class="userprice_input">
                                    <input type="hidden" name="workspaceprice_input" value="0"
                                        class="workspaceprice_input">
                                    <input type="hidden" name="user_module_price_input" value="0"
                                        class="user_module_price_input">
                                    <input type="hidden" name="time_period" value="Month" class="time_period_input">
                                    <button type="button"
                                        class="btn btn-dark user-register-btn">{{ __('Buy Now') }}</button>
                                </div>
                                <div class="cart-reset text-center">
                                    <a href="#" onclick="location.reload();" class="reset-btn"><svg
                                            xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                            viewBox="0 0 12 12" fill="none">
                                            <path
                                                d="M6 0.625C3.036 0.625 0.625 3.0365 0.625 6C0.625 8.9635 3.036 11.375 6 11.375C8.964 11.375 11.375 8.9635 11.375 6C11.375 3.0365 8.964 0.625 6 0.625ZM6 10.625C3.4495 10.625 1.375 8.5505 1.375 6C1.375 3.4495 3.4495 1.375 6 1.375C8.5505 1.375 10.625 3.4495 10.625 6C10.625 8.5505 8.5505 10.625 6 10.625ZM7.765 4.76501L6.53 6L7.765 7.23499C7.9115 7.38149 7.9115 7.619 7.765 7.7655C7.692 7.8385 7.596 7.87549 7.5 7.87549C7.404 7.87549 7.308 7.839 7.235 7.7655L6 6.53049L4.765 7.7655C4.692 7.8385 4.596 7.87549 4.5 7.87549C4.404 7.87549 4.308 7.839 4.235 7.7655C4.0885 7.619 4.0885 7.38149 4.235 7.23499L5.47 6L4.235 4.76501C4.0885 4.61851 4.0885 4.381 4.235 4.2345C4.3815 4.088 4.619 4.088 4.7655 4.2345L6.0005 5.46951L7.2355 4.2345C7.382 4.088 7.61951 4.088 7.76601 4.2345C7.91151 4.381 7.9115 4.61901 7.765 4.76501Z"
                                                fill="#737373" />
                                        </svg>{{ __('Reset') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="review-section padding-bottom">
            <div class="container">
                <div class="review-slider">
                    @if (is_array(json_decode($settings['reviews'], true)) || is_object(json_decode($settings['reviews'], true)))
                        @foreach (json_decode($settings['reviews'], true) as $key => $value)
                            <div class="review-content-itm">
                                <div class="review-content">
                                    <div class="section-title">
                                        <div class="quats">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="83" height="59"
                                                viewBox="0 0 83 59" fill="none">
                                                <path
                                                    d="M17.4193 58.9563C12.8711 58.9563 8.86601 57.2932 5.40398 53.9669C1.94195 50.5727 0.210938 45.8209 0.210938 39.7115C0.210938 34.2809 1.33101 29.0878 3.57114 24.1324C5.81128 19.109 8.76418 14.5609 12.4299 10.4879C16.1634 6.34704 20.2364 2.85108 24.6488 0L35.1367 7.73865C33.9827 8.689 32.5232 10.2503 30.7582 12.4226C28.9933 14.5948 27.2962 16.9707 25.667 19.5503C24.0378 22.1298 22.9178 24.5397 22.3068 26.7798C25.701 27.8659 28.4842 29.7327 30.6564 32.3801C32.8965 34.9597 34.0166 38.3199 34.0166 42.4607C34.0166 47.4841 32.3195 51.4892 28.9254 54.476C25.5991 57.4629 21.7638 58.9563 17.4193 58.9563ZM65.073 58.9563C60.457 58.9563 56.4519 57.2932 53.0578 53.9669C49.6636 50.5727 47.9666 45.8209 47.9666 39.7115C47.9666 34.3487 49.0866 29.1896 51.3268 24.2342C53.6348 19.2108 56.6216 14.6288 60.2873 10.4879C64.0209 6.27917 68.0259 2.7832 72.3026 0L82.8923 7.73865C81.6704 8.82478 80.177 10.42 78.412 12.5244C76.6471 14.6288 74.9839 16.9707 73.4226 19.5503C71.8613 22.0619 70.7413 24.4718 70.0624 26.7798C73.3887 27.8659 76.1719 29.7327 78.412 32.3801C80.6522 34.9597 81.7722 38.3199 81.7722 42.4607C81.7722 47.4841 80.0752 51.4892 76.681 54.476C73.3548 57.4629 69.4854 58.9563 65.073 58.9563Z"
                                                    fill="#002332" />
                                            </svg>
                                        </div>
                                        <div class="subtitle">
                                            {!! $value['review_header_tag'] !!}
                                        </div>
                                        <h2> {!! $value['review_heading'] !!}</h2>
                                    </div>
                                    <p> {!! $value['review_description'] !!} </p>
                                    <div class="btn-group">
                                        <a href="{{ route('apps.pricing') }}"
                                            class="btn btn-white">{{ __('Get the Package') }} <svg
                                                xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                viewBox="0 0 16 16" fill="none">
                                                <g clip-path="url(#clip0_14_726)">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M5.88967 10.9856C6.96087 11.2611 7.75238 12.233 7.75238 13.3897C7.75238 14.7607 6.64043 15.8721 5.26877 15.8721C3.89711 15.8721 2.78516 14.7607 2.78516 13.3897C2.78516 12.233 3.57667 11.2611 4.64787 10.9856V10.5959C4.64787 8.7099 6.1768 7.18097 8.06283 7.18097C9.26304 7.18097 10.236 6.20801 10.236 5.00781V3.09158L8.81233 4.51524C8.56985 4.75772 8.17672 4.75772 7.93424 4.51524C7.69176 4.27276 7.69176 3.87963 7.93424 3.63715L10.4179 1.15354C10.6603 0.91106 11.0535 0.91106 11.2959 1.15354L13.7796 3.63715C14.022 3.87962 14.022 4.27276 13.7796 4.51524C13.5371 4.75771 13.1439 4.75772 12.9015 4.51524L11.4778 3.09158V5.00781C11.4778 6.89384 9.94887 8.42278 8.06283 8.42278C6.86263 8.42278 5.88967 9.39573 5.88967 10.5959V10.9856ZM6.51058 13.3897C6.51058 14.0743 5.95517 14.6303 5.26877 14.6303C4.58237 14.6303 4.02696 14.0743 4.02696 13.3897C4.02696 12.7052 4.58237 12.1492 5.26877 12.1492C5.95517 12.1492 6.51058 12.7052 6.51058 13.3897Z"
                                                        fill="white"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_14_726">
                                                        <rect width="14.9017" height="14.9017" fill="white"
                                                            transform="translate(0.921875 0.97168)"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg></a>
                                        <a href="{!! $value['review_live_demo_link'] !!}" class="link-btn">{!! $value['review_live_demo_button_text'] !!}<svg
                                                xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                viewBox="0 0 20 20" fill="none">
                                                <g clip-path="url(#clip0_7_820)">
                                                    <path
                                                        d="M9.33984 1.18359L9.33985 18.519L4.612 18.519C2.87125 18.519 1.4601 17.1079 1.4601 15.3671L1.4601 4.33549C1.4601 2.59475 2.87125 1.18359 4.612 1.18359L9.33984 1.18359Z"
                                                        fill="#002332"></path>
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M17.222 19.3066C18.5276 19.3066 19.5859 18.2483 19.5859 16.9427L19.5859 15.6294L19.5859 2.75918C19.5859 1.45362 18.5276 0.39526 17.222 0.39526L10.1302 0.39526L9.77566 0.39526C9.34047 0.39526 8.98768 0.748047 8.98768 1.18324C8.98768 1.61842 9.34047 1.97121 9.77566 1.97121L10.1302 1.97121L17.222 1.97121C17.6572 1.97121 18.01 2.324 18.01 2.75918L18.01 15.6294L18.01 16.9427C18.01 17.3779 17.6572 17.7307 17.222 17.7307L10.1302 17.7307L9.77566 17.7307C9.34047 17.7307 8.98769 18.0835 8.98769 18.5187C8.98769 18.9539 9.34047 19.3066 9.77566 19.3066L10.1302 19.3066L17.222 19.3066ZM7.72693 18.5187C7.72693 18.0835 7.37414 17.7307 6.93895 17.7307L6.22977 17.7307C5.79459 17.7307 5.4418 18.0835 5.4418 18.5187C5.4418 18.9539 5.79459 19.3066 6.22977 19.3066L6.93895 19.3066C7.37414 19.3066 7.72693 18.9539 7.72693 18.5187ZM7.72693 1.18324C7.72693 0.748047 7.37414 0.39526 6.93895 0.39526L6.22977 0.39526C5.79459 0.39526 5.4418 0.748047 5.4418 1.18324C5.4418 1.61842 5.79459 1.97121 6.22977 1.97121L6.93895 1.97121C7.37414 1.97121 7.72693 1.61842 7.72693 1.18324ZM4.18104 18.5187C4.18104 18.0835 3.82825 17.7307 3.39307 17.7307L3.03848 17.7307C2.99569 17.7307 2.95423 17.7274 2.9142 17.7211C2.48429 17.6535 2.08101 17.9472 2.01344 18.3772C1.94588 18.8071 2.23962 19.2103 2.66953 19.2779C2.79021 19.2969 2.91347 19.3066 3.03848 19.3066L3.39307 19.3066C3.82825 19.3066 4.18104 18.9539 4.18104 18.5187ZM4.18104 1.18324C4.18104 0.748048 3.82825 0.395261 3.39307 0.395261L3.03848 0.395261C2.91347 0.395261 2.7902 0.405034 2.66953 0.423997C2.23962 0.491559 1.94588 0.894841 2.01344 1.32475C2.08101 1.75466 2.48429 2.0484 2.9142 1.98084C2.95423 1.97455 2.99569 1.97121 3.03848 1.97121L3.39307 1.97121C3.82825 1.97121 4.18104 1.61842 4.18104 1.18324ZM1.60405 17.9678C2.03396 17.9002 2.3277 17.4969 2.26014 17.067C2.25384 17.027 2.25051 16.9855 2.25051 16.9427L2.25051 16.5881C2.25051 16.1529 1.89772 15.8002 1.46253 15.8002C1.02735 15.8002 0.674557 16.1529 0.674557 16.5881L0.674557 16.9427C0.674557 17.0677 0.68433 17.191 0.703293 17.3117C0.770857 17.7416 1.17414 18.0353 1.60405 17.9678ZM1.60405 1.73415C1.17414 1.66659 0.770856 1.96033 0.703292 2.39024C0.684329 2.51091 0.674556 2.63417 0.674556 2.75918L0.674556 3.11377C0.674556 3.54896 1.02734 3.90175 1.46253 3.90175C1.89772 3.90175 2.2505 3.54896 2.2505 3.11377L2.2505 2.75918C2.2505 2.7164 2.25384 2.67493 2.26013 2.6349C2.3277 2.20499 2.03396 1.80171 1.60405 1.73415ZM1.46253 14.5394C1.89772 14.5394 2.25051 14.1866 2.25051 13.7514L2.25051 13.0422C2.25051 12.6071 1.89772 12.2543 1.46253 12.2543C1.02735 12.2543 0.674556 12.6071 0.674556 13.0422L0.674556 13.7514C0.674557 14.1866 1.02735 14.5394 1.46253 14.5394ZM1.46253 10.9935C1.89772 10.9935 2.25051 10.6407 2.25051 10.2055L2.25051 9.49636C2.2505 9.06118 1.89772 8.70839 1.46253 8.70839C1.02735 8.70839 0.674556 9.06118 0.674556 9.49636L0.674556 10.2055C0.674556 10.6407 1.02735 10.9935 1.46253 10.9935ZM1.46253 7.44763C1.89772 7.44763 2.2505 7.09484 2.2505 6.65966L2.2505 5.95048C2.2505 5.51529 1.89772 5.16251 1.46253 5.16251C1.02735 5.16251 0.674556 5.51529 0.674556 5.95048L0.674556 6.65966C0.674556 7.09484 1.02735 7.44763 1.46253 7.44763ZM6.97806 9.06298C6.54288 9.06298 6.19009 9.41577 6.19009 9.85095C6.19009 10.2861 6.54288 10.6389 6.97806 10.6389L11.3798 10.6389L10.3611 11.6577C10.0534 11.9654 10.0534 12.4643 10.3611 12.7721C10.6688 13.0798 11.1677 13.0798 11.4754 12.7721L13.8394 10.4081C13.9871 10.2604 14.0702 10.0599 14.0702 9.85095C14.0702 9.64197 13.9871 9.44154 13.8394 9.29377L11.4754 6.92985C11.1677 6.62213 10.6688 6.62213 10.3611 6.92985C10.0534 7.23757 10.0534 7.73649 10.3611 8.04421L11.3798 9.06298L6.97806 9.06298Z"
                                                        fill="white"></path>
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_7_820">
                                                        <rect width="18.9114" height="18.9114" fill="white"
                                                            transform="translate(0.675781 0.395508)"></rect>
                                                    </clipPath>
                                                </defs>
                                            </svg></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="progress" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <span class="slider__label sr-only">
                </div>
            </div>
        </section>

        <section class="faq-section padding-top padding-bottom">
            <div class="container">
                <div class="section-title text-center">
                    <h2>
                        {!! !empty($settings['faq_heading']) ? $settings['faq_heading'] : '' !!}
                    </h2>
                    <p>
                        {!! !empty($settings['faq_description']) ? $settings['faq_description'] : '' !!}
                    </p>
                </div>
                <div class="faq-list">
                    @if (is_array(json_decode($settings['faqs'], true)) || is_object(json_decode($settings['faqs'], true)))
                        @foreach (json_decode($settings['faqs'], true) as $key => $value)
                            <div class="set has-children">
                                <a href="javascript:;" class="acnav-label">
                                    <span class="acnav-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36"
                                            viewBox="0 0 36 36" fill="none">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M18 33C9.71573 33 3 26.2843 3 18C3 9.71573 9.71573 3 18 3C26.2843 3 33 9.71573 33 18C33 26.2843 26.2843 33 18 33ZM18 6C11.3726 6 6 11.3726 6 18C6 24.6274 11.3726 30 18 30C24.6274 30 30 24.6274 30 18C30 11.3726 24.6274 6 18 6ZM18 16.125C19.0355 16.125 19.875 16.9645 19.875 18V24C19.875 25.0355 19.0355 25.875 18 25.875C16.9645 25.875 16.125 25.0355 16.125 24V18C16.125 16.9645 16.9645 16.125 18 16.125ZM18 10.5C16.9645 10.5 16.125 11.3395 16.125 12.375C16.125 13.4105 16.9645 14.25 18 14.25C19.0355 14.25 19.875 13.4105 19.875 12.375C19.875 11.3395 19.0355 10.5 18 10.5Z"
                                                fill="#F491AF" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M18 33C9.71573 33 3 26.2843 3 18C3 9.71573 9.71573 3 18 3C26.2843 3 33 9.71573 33 18C33 26.2843 26.2843 33 18 33ZM18 6C11.3726 6 6 11.3726 6 18C6 24.6274 11.3726 30 18 30C24.6274 30 30 24.6274 30 18C30 11.3726 24.6274 6 18 6ZM18 16.125C19.0355 16.125 19.875 16.9645 19.875 18V24C19.875 25.0355 19.0355 25.875 18 25.875C16.9645 25.875 16.125 25.0355 16.125 24V18C16.125 16.9645 16.9645 16.125 18 16.125ZM18 10.5C16.9645 10.5 16.125 11.3395 16.125 12.375C16.125 13.4105 16.9645 14.25 18 14.25C19.0355 14.25 19.875 13.4105 19.875 12.375C19.875 11.3395 19.0355 10.5 18 10.5Z"
                                                fill="url(#paint0_linear_105_825)" />
                                            <defs>
                                                <linearGradient id="paint0_linear_105_825" x1="4.17978" y1="3.92308"
                                                    x2="29.447" y2="35.7472" gradientUnits="userSpaceOnUse">
                                                    <stop stop-color="#6FD943" />
                                                    <stop offset="1" stop-color="#6FD943" />
                                                </linearGradient>
                                            </defs>
                                        </svg>
                                    </span>
                                    <span>{!! isset($value['faq_questions']) ? $value['faq_questions'] : '' !!}</span>
                                </a>
                                <div class="acnav-list">
                                    <p>
                                        {!! isset($value['faq_answer']) ? $value['faq_answer'] : '' !!}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </section>
    </div>
    <!-- wrapper end -->
    <div class="register-popup">
        <div class="register-popup-body">
            <div class="popup-header">
                <h5>{{ __('Register Form') }}</h5>
                <div class="close-register">
                    <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 50 50"
                        fill="none">
                        <path
                            d="M27.7618 25.0008L49.4275 3.33503C50.1903 2.57224 50.1903 1.33552 49.4275 0.572826C48.6647 -0.189868 47.428 -0.189965 46.6653 0.572826L24.9995 22.2386L3.33381 0.572826C2.57102 -0.189965 1.3343 -0.189965 0.571605 0.572826C-0.191089 1.33562 -0.191186 2.57233 0.571605 3.33503L22.2373 25.0007L0.571605 46.6665C-0.191186 47.4293 -0.191186 48.666 0.571605 49.4287C0.952952 49.81 1.45285 50.0007 1.95275 50.0007C2.45266 50.0007 2.95246 49.81 3.3339 49.4287L24.9995 27.763L46.6652 49.4287C47.0465 49.81 47.5464 50.0007 48.0463 50.0007C48.5462 50.0007 49.046 49.81 49.4275 49.4287C50.1903 48.6659 50.1903 47.4292 49.4275 46.6665L27.7618 25.0008Z"
                            fill="white"></path>
                    </svg>
                </div>
            </div>
            <form method="POST" action="{{ route('register') }}" id="register-form">
                @csrf
                <div class="popup-content">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Name') }}</label>
                                <input id="name" type="text" placeholder="Enter name" class="form-control"
                                    name="name" value="" required="required" autocomplete="name" autofocus>
                            </div>
                        </div>
                        <input type="hidden" name = "type" value="pricing" id="type">
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('WorkSpace Name') }}</label>
                                <input id="store_name" placeholder="Enter workspace" type="text" class="form-control"
                                    name="workspace" value="" required>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Email') }}</label>
                                <input id="email" placeholder="Enter email" type="email" class="form-control "
                                    name="email" value="" required="required">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Password') }}</label>
                                <input id="password" placeholder="Enter password" type="password" class="form-control"
                                    name="password" required="required">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-12">
                            <div class="form-group">
                                <label class="form-label">{{ __('Confirm password') }}</label>
                                <input id="password-confirm" placeholder="Enter confirm password" type="password"
                                    class="form-control" name="password_confirmation" required="required">
                                <small class="text-danger password-msg d-none">{{ __('Passwords do not match!') }}</small>
                            </div>
                        </div>
                        @if (module_is_active('GoogleCaptcha') &&
                                (isset($admin_settings['google_recaptcha_is_on']) ? $admin_settings['google_recaptcha_is_on'] : 'off') == 'on')
                            @if (isset($admin_settings['google_recaptcha_version']) && $admin_settings['google_recaptcha_version'] == 'v2-checkbox')
                                <div class="form-group col-lg-12 col-md-12 mt-3">

                                    {!! NoCaptcha::display() !!}
                                    @error('g-recaptcha-response')
                                        <span class="error small text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @else
                                <div class="form-group col-lg-12 col-md-12 mt-3">
                                    <input type="hidden" id="g-recaptcha-response" name="g-recaptcha-response"
                                        class="form-control">
                                    @error('g-recaptcha-response')
                                        <span class="error small text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
                <div class="popup-footer d-flex align-items-center justify-content-end">
                    <button type="button" class="btn-danger btn close-register">{{ __('Cancel') }}</button>
                    <button type="button" class="btn btn-secondary register-form-btn">{{ __('Register') }}</button>
                </div>
            </form>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var userprice = '{{ $userprice }}';
            var planprice = '{{ $planprice }}';
            if ($('.switch-change').prop('checked') == true) {
                userprice = '{{ $userpriceyearly }}';
                planprice = '{{ $planpriceyearly }}';
            }
            var user = parseInt($('.user_counter_input').val());
            var userpricetext = userprice * user;

            var currancy_symbol = '{{ $currancy_symbol }}';
            var total = parseFloat(userpricetext) + parseFloat(planprice);
            $(".total").text(parseFloat(total).toFixed(2) + currancy_symbol);
        });
        $(document).on("click", ".user_module_check", function() {
            if ($(this).closest(".user_module").hasClass("active_module")) {
                $(this).closest(".user_module").removeClass("active_module");

            } else {
                $(this).closest(".user_module").addClass("active_module");
            }
            ChangeModulePrice();
            ChangePrice();

        });
    </script>
    <script>
        $(document).on('keyup mouseup', '#user_counter, #workspace_counter', function() {
            var name = $(this).attr('data-name');
            var counter = parseInt($(this).val());
            if (counter <= 0 || counter > 1000 || $(this).val() == '') {
                $(this).val(0)
                var counter = 0;
            }
            if (name == "user") {
                $(".user_counter_text").text(counter);
                $(".user_counter_input").val(counter);
                ChangePrice(counter)
            } else if (name == "workspace") {
                $(".workspace_counter_text").text(counter);
                $(".workspace_counter_input").val(counter);
                ChangePrice(null, counter)
            }
        });
    </script>
    <script>
        function ChangePrice(user = null, workspace = null, user_module_price = 0) {
            var userprice = '{{ $userprice }}';
            var workspaceprice = '{{ $workspaceprice }}';
            var planprice = '{{ $planprice }}';
            if ($('.switch-change').prop('checked') == true) {
                userprice = '{{ $userpriceyearly }}';
                workspaceprice = '{{ $workspacepriceyearly }}';
                planprice = '{{ $planpriceyearly }}';
            }

            var currancy_symbol = '{{ $currancy_symbol }}';
            if (user == null) {
                var user = parseInt($('.user_counter_input').val());
            }
            if (user_module_price == 0) {
                var user_module_price = parseFloat($('.user_module_price_input').val());
            }
            if (workspace == null) {
                var workspace = parseInt($('.workspace_counter_input').val());
            }
            var userpricetext = userprice * user;
            var workspacepricetext = workspaceprice * workspace;
            var total = userpricetext + user_module_price + workspacepricetext + parseFloat(planprice);

            $(".userpricetext").text(parseFloat(userpricetext).toFixed(2) + currancy_symbol);
            $(".workspacepricetext").text(parseFloat(workspacepricetext).toFixed(2) + currancy_symbol);
            $(".total").text(parseFloat(total).toFixed(2) + currancy_symbol);
            $(".userprice_input").val(userpricetext);
            $(".workspaceprice_input").val(workspacepricetext);

        }

        function ChangeModulePrice() {
            var user_module_input = new Array();
            var user_module_price = parseFloat(0);
            var currancy_symbol = '{{ $currancy_symbol }}';
            var n = jQuery(".user_module_check:checked").length;

            var time = '/Month';
            if ($('.switch-change').prop('checked') == true) {
                time = '/Year';
            }

            $("#extension_div").empty();

            if (n > 0) {
                jQuery(".user_module_check:checked").each(function() {

                    var alias = $(this).attr('data-module-alias');
                    var img = $(this).attr('data-module-img');
                    var price = parseFloat($(this).attr('data-module-price-monthly'));

                    if ($('.switch-change').prop('checked') == true) {
                        price = parseFloat($(this).attr('data-module-price-yearly'));
                    }

                    $("#extension_div").append(`
                                    <div class="d-flex align-items-start extension-card-min">
                                        <div class="theme-avtar">
                                            <img src="` + img + `" alt="` + img + `" class="img-user" style="max-width: 100%">
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0 text-capitalize">` + alias + `</p>
                                            <h4 class="mb-0 text-primary">` + price + currancy_symbol +
                        `<span class="text-sm">` + time + `</span></h4>
                                        </div>
                                    </div>
                                `);
                    user_module_input.push($(this).val());
                    user_module_price = user_module_price + price;
                });
            }
            $(".module_counter_text").text(n);
            $(".module_price_text").text(parseFloat(user_module_price).toFixed(2) + currancy_symbol);
            $(".user_module_input").val(user_module_input);
            $(".user_module_price_input").val(user_module_price);
        }
        /********* qty spinner ********/
        var quantity = 0;
        $('.quantity-increment').click(function() {
            var id = $(this).attr('data-name');
            var t = $(this).siblings('.quantity');
            var quantity = parseInt($(t).val());
            if (quantity < 1000 || $(this).val() != '') {
                $(t).val(quantity + 1);
                if (id == 'user') {
                    $(".user_counter_text").text(quantity + 1);
                    $(".user_counter_input").val(quantity + 1);
                } else if (id == 'workspace') {
                    $(".workspace_counter_text").text(quantity + 1);
                    $(".workspace_counter_input").val(quantity + 1);
                }
            } else {
                $(t).val(1000);
                if (id == 'user') {
                    $(".user_counter_text").text(1000);
                    $(".user_counter_input").val(1000);
                } else if (id == 'workspace') {
                    $(".workspace_counter_text").text(1000);
                    $(".workspace_counter_input").val(1000);
                }
            }

            ChangePrice()
        });
        $('.quantity-decrement').click(function() {
            var id = $(this).attr('data-name');
            var t = $(this).siblings('.quantity');
            var quantity = parseInt($(t).val());
            if (quantity > 1) {
                $(t).val(quantity - 1);
                if (id == 'user') {
                    $(".user_counter_text").text(quantity - 1);
                    $(".user_counter_input").val(quantity - 1);
                } else if (id == 'workspace') {
                    $(".workspace_counter_text").text(quantity - 1);
                    $(".workspace_counter_input").val(quantity - 1);
                }
            } else {
                $(t).val(0);
                if (id == 'user') {
                    $(".user_counter_text").text(0);
                    $(".user_counter_input").val(0);
                } else if (id == 'workspace') {
                    $(".workspace_counter_text").text(0);
                    $(".workspace_counter_input").val(0);
                }
            }
            ChangePrice()
        });
    </script>
    <script>
        $(document).on("click", ".switch-change", function() {
            var userprice = '{{ $userprice }}';
            var workspaceprice = '{{ $workspaceprice }}';
            var planprice = '{{ $planprice }}';
            var currancy_symbol = '{{ $currancy_symbol }}';
            var user = parseInt($('.user_counter_input').val());
            var workspace = parseInt($('.workspace_counter_input').val());
            var time = '/Month';


            if ($(this).prop('checked') == true) {

                $(".time-monthly").removeClass("active");
                $(".time-yearly").addClass("active");

                $(".m-price-yearly").removeClass("d-none");
                $(".m-price-monthly").addClass("d-none");

                userprice = '{{ $userpriceyearly }}';
                workspaceprice = '{{ $workspacepriceyearly }}';
                planprice = '{{ $planpriceyearly }}';

                time = '/Year';

                $(".time_period_input").val('Year');

            } else {
                $(".time-yearly").removeClass("active");
                $(".time-monthly").addClass("active");

                $(".m-price-monthly").removeClass("d-none");
                $(".m-price-yearly").addClass("d-none");

                $(".time_period_input").val('Month');

            }

            var userpricetext = userprice * user;
            var workspacepricetext = workspaceprice * workspace;

            $(".plan-price-text").text(planprice + ' {{ admin_setting('defult_currancy') }}');
            $(".plan-time-text").text(time);

            $(".planpricetext").text(planprice + currancy_symbol);
            $(".workspace-price").text('( Per Work ' + workspaceprice + currancy_symbol + ')');
            $(".user-price").text('( Per User ' + userprice + currancy_symbol + ')');
            $(".userpricetext").text(parseFloat(userpricetext).toFixed(2) + currancy_symbol);
            $(".workspacepricetext").text(parseFloat(workspacepricetext).toFixed(2) + currancy_symbol);

            ChangeModulePrice()
            ChangePrice()
        });
    </script>
    <script>
        $(document).on("click", ".user-register-btn", function() {
            var workspace_counter = $('.workspace_counter_input').val();
            var workspaceprice = $('.workspaceprice_input').val();

            var user_counter = $('.user_counter_input').val();
            var user_module = $('.user_module_input').val();
            var userprice = $('.userprice_input').val();
            var user_module_price = $('.user_module_price_input').val();

            var time_period = $('.time_period_input').val();

            const formData = new FormData();
            formData.append("workspace_counter", workspace_counter);
            formData.append("workspaceprice", workspaceprice);

            formData.append("user_counter", user_counter);
            formData.append("user_module", user_module);
            formData.append("userprice", userprice);
            formData.append("user_module_price", user_module_price);
            formData.append("time_period", time_period);
            formData.append('_token', "{{ csrf_token() }}");
            $.ajax({
                url: "{{ route('guest.module.selection') }}",
                method: "POST",
                data: formData,
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {

                }
            });
        });
    </script>
    <script>
        $(document).on("click", ".register-form-btn", function() {
            var status = true;
            var name = $('#name').val();
            var store_name = $('#store_name').val();
            var email = $('#email').val();
            var password = $('#password').val();
            var type = $('#type').val();
            var password_confirm = $('#password-confirm').val();
            $('.password-msg').addClass('d-none');

            // Check for empty fields
            if (name.trim() === '' || store_name.trim() === '' || email.trim() === '' || password.trim() === '' ||
                type.trim() === '' || password_confirm.trim() === '') {
                alert('Please fill in all required fields.');
                status = false;
            }

            if (password != password_confirm) {
                $('.password-msg').removeClass('d-none');
                status = false;
            }
            if (status == true) {
                $('#register-form').submit();
            }
        });
    </script>
    @if (module_is_active('GoogleCaptcha') &&
            (isset($admin_settings['google_recaptcha_is_on']) ? $admin_settings['google_recaptcha_is_on'] : 'off') == 'on')
        @if (isset($admin_settings['google_recaptcha_version']) && $admin_settings['google_recaptcha_version'] == 'v2-checkbox')
            {!! NoCaptcha::renderJs() !!}
        @else
            <script src="https://www.google.com/recaptcha/api.js?render={{ $admin_settings['google_recaptcha_key'] }}"></script>
            <script>
                $(document).ready(function() {
                    grecaptcha.ready(function() {
                        grecaptcha.execute('{{ $admin_settings['google_recaptcha_key'] }}', {
                            action: 'submit'
                        }).then(function(token) {

                            $('#g-recaptcha-response').val(token);
                        });
                    });
                });
            </script>
        @endif
    @endif
@endpush
