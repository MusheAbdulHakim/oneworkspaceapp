@php
    $admin_settings = getAdminAllSetting();
    $company_settings = getCompanyAllSetting($company_id,$workspace_id);
    $temp_lang = \App::getLocale('lang');
    if($temp_lang == 'ar' || $temp_lang == 'he'){
        $rtl = 'on';
    }
    else {
        $rtl = isset($company_settings['site_rtl']) ? $company_settings['site_rtl'] : 'off';

    }
    $favicon = isset($company_settings['favicon']) ? $company_settings['favicon'] : (isset($admin_settings['favicon']) ? $admin_settings['favicon'] : 'uploads/logo/favicon.png');
@endphp

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{  $rtl == 'on'?'rtl':''}}">
<meta name="csrf-token" id="csrf-token" content="{{ csrf_token() }}">
<head>

<title>@yield('page-title') | {{ !empty($company_settings['title_text']) ? $company_settings['title_text'] : (!empty($admin_settings['title_text']) ? $admin_settings['title_text'] :'WorkDo') }}</title>

<meta name="title" content="{{ !empty($admin_settings['meta_title']) ? $admin_settings['meta_title'] : 'WOrkdo Dash' }}">
<meta name="keywords" content="{{ !empty($admin_settings['meta_keywords']) ? $admin_settings['meta_keywords'] : 'WorkDo Dash,SaaS solution,Multi-workspace' }}">
<meta name="description" content="{{ !empty($admin_settings['meta_description']) ? $admin_settings['meta_description'] : 'Discover the efficiency of Dash, a user-friendly web application by Rajodiya Apps.'}}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ env('APP_URL') }}">
<meta property="og:title" content="{{ !empty($admin_settings['meta_title']) ? $admin_settings['meta_title'] : 'WOrkdo Dash' }}">
<meta property="og:description" content="{{ !empty($admin_settings['meta_description']) ? $admin_settings['meta_description'] : 'Discover the efficiency of Dash, a user-friendly web application by Rajodiya Apps.'}} ">
<meta property="og:image" content="{{ get_file( (!empty($admin_settings['meta_image'])) ? (check_file($admin_settings['meta_image'])) ?  $admin_settings['meta_image'] : 'uploads/meta/meta_image.png' : 'uploads/meta/meta_image.png'  ) }}{{'?'.time() }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ env('APP_URL') }}">
<meta property="twitter:title" content="{{ !empty($admin_settings['meta_title']) ? $admin_settings['meta_title'] : 'WOrkdo Dash' }}">
<meta property="twitter:description" content="{{ !empty($admin_settings['meta_description']) ? $admin_settings['meta_description'] : 'Discover the efficiency of Dash, a user-friendly web application by Rajodiya Apps.'}} ">
<meta property="twitter:image" content="{{ get_file( (!empty($admin_settings['meta_image'])) ? (check_file($admin_settings['meta_image'])) ?  $admin_settings['meta_image'] : 'uploads/meta/meta_image.png' : 'uploads/meta/meta_image.png'  ) }}{{'?'.time() }}">

<meta name="author" content="Workdo.io">

<meta charset="UTF-8">
<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

 <!-- Favicon icon -->
 <link rel="icon" href="{{ check_file($favicon) ? get_file($favicon) : get_file('uploads/logo/favicon.png')  }}{{'?'.time()}}" type="image/x-icon" />

 <!-- font css -->
 <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
 <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
 <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
 <link rel="stylesheet" href="{{ asset('assets/fonts/material.css')}}">

 <!-- vendor css -->
 <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">

 <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}" id="main-style-link">
 <link rel="stylesheet" href="{{ asset('assets/css/plugins/datepicker-bs5.min.css') }}" >
 <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}" >
 <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
 <link rel="stylesheet" href="{{ asset('css/custome.css') }}">
 @stack('css')

 @if ( $rtl == 'on')
 <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
@endif

@if((isset($company_settings['cust_darklayout']) ? $company_settings['cust_darklayout'] : 'off') == 'on')
 <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}" id="main-style-link">
@endif

@if ( $rtl != 'on' && (isset($company_settings['cust_darklayout']) ? $company_settings['cust_darklayout'] : 'off')!= 'on')
 <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
@endif

 <script src="{{ asset('js/jquery.min.js') }}"></script>
 <link rel="stylesheet" href="{{ asset('Modules/Account/Resources/assets/css/nprogress.css') }}" >
<script src="{{ asset('Modules/Account/Resources/assets/js/nprogress.js') }}"></script>
</head>

<body class="{{ !empty($company_settings['color'])?$company_settings['color']:'theme-1' }}">
    <div class="container">
        <div class="dash-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                  <div class="page-block">
                      <div class="row align-items-center">
                          <div class="col-md-12 mt-5 mb-4">
                              <div class="d-block d-sm-flex align-items-center justify-content-between">
                                  <div>
                                      <div class="page-header-title">
                                          <h4 class="m-b-10">@yield('page-title')</h4>
                                      </div>
                                      <ul class="breadcrumb">
                                              @yield('breadcrumb')
                                      </ul>
                                  </div>
                                  <div>
                                    @yield('action-btn')
                                  </div>

                              </div>
                          </div>
                      </div>
                  </div>
              </div>

            <!-- <div class="row"> -->
                   @yield('content')

            <!-- </div> -->

        </div>
    </div>
    <div  id="commonModal" class="modal" tabindex="-1" aria-labelledby="exampleModalLongTitle" aria-modal="true" role="dialog" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="body">
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
        <div id="liveToast" class="toast text-white  fade" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"> </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/dash.js') }}"></script>
<script src="{{asset('assets/js/plugins/simple-datatables.js')}}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>



<script src="{{ asset('js/custom.js') }}"></script>
@if($message = Session::get('success'))
    <script>
        toastrs('Success', '{!! $message !!}', 'success');
    </script>
@endif
@if($message = Session::get('error'))
    <script>
        toastrs('Error', '{!! $message !!}', 'error');
    </script>
@endif
@if($admin_settings['enable_cookie'] == 'on')
@include('layouts.cookie_consent')
@endif
@stack('scripts')
</body>
</html>
