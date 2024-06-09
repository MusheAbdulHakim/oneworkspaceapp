@php
    $admin_settings = getAdminAllSetting();
    $temp_lang = \App::getLocale('lang');
    if($temp_lang == 'ar' || $temp_lang == 'he'){
        $rtl = 'on';
    }
    else {
        $rtl = isset($admin_settings['site_rtl']) ? $admin_settings['site_rtl'] : 'off';
    }
    $color = !empty($admin_settings['color'])?$admin_settings['color']:'theme-1';

    if(isset($admin_settings['color_flag']) && $admin_settings['color_flag'] == 'true')
    {
        $themeColor = 'custom-color';
    }
    else {
        $themeColor = $color;
    }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{  $rtl == 'on'?'rtl':''}}">

<head>

    <title>@yield('page-title') | {{ !empty($admin_settings['title_text']) ? $admin_settings['title_text'] : config('app.name', 'WorkDo') }}</title>

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

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="icon" href="{{ (!empty($admin_settings['favicon']) && check_file($admin_settings['favicon'])) ? get_file($admin_settings['favicon']) : get_file('uploads/logo/favicon.png')}}{{'?'.time()}}" type="image/x-icon" />
     <!-- CSS Libraries -->
     <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">

      <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">
    <!-- vendor css -->
    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <!-- custom css -->
    <link rel="stylesheet" href="{{ asset('css/custome.css') }}">
    <style>
        :root {
            --color-customColor: <?= $color ?>;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">

    @if ( $rtl == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom-auth-rtl.css') }}" id="main-style-link">
    @else
        <link rel="stylesheet" href="{{ asset('css/custom-auth.css') }}" id="main-style-link">
    @endif

    @if((isset($admin_settings['cust_darklayout']) ? $admin_settings['cust_darklayout'] : 'off') == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}" id="main-style-link">
        <link rel="stylesheet" href="{{ asset('css/custom-auth-dark.css') }}" id="main-style-link">
    @endif

    @if( $rtl != 'on' && (isset($admin_settings['cust_darklayout']) ? $admin_settings['cust_darklayout'] : 'off') != 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif

    <style>
        .navbar-brand .auth-navbar-brand
        {
            max-height: 38px !important;
        }
    </style>
</head>
<body class="{{ $themeColor }}">
    <div class="custom-login">
        <div class="login-bg-img">
            {{-- <img src="{{ asset('images/'.$themeColor.'.svg') }}" class="login-bg-1"> --}}
            <img src="{{ isset($admin_settings['color_flag']) && $admin_settings['color_flag'] == 'false' ? asset('images/' . $color . '.svg') : asset('images/theme-1.svg')  }}" class="login-bg-1">
            <img src="{{ asset('images/common.svg') }}" class="login-bg-2">
        </div>
        <div class="bg-login bg-primary"></div>
        <div class="custom-login-inner">
            <header class="dash-header">
                <nav class="navbar navbar-expand-md default">
                    <div class="container">
                        <div class="navbar-brand">
                            <a class="navbar-brand" href="{{ url('/') }}">
                                <img src="{{ get_file(sidebar_logo()) }}{{'?'.time()}}" alt="{{ config('app.name', 'WorkDo') }}" class="navbar-brand-img auth-navbar-brand">
                            </a>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarlogin">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarlogin">
                            <ul class="navbar-nav align-items-center ms-auto mb-lg-0">

                                @stack('custom_page_links')
                                @yield('language-bar')
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <main class="custom-wrapper">
                <div class="custom-row">
                    <div class="card">
                        @yield('content')
                    </div>
                </div>
            </main>
            <footer>
                <div class="auth-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <span>
                                    @if (!empty($admin_settings['footer_text'])) {{$admin_settings['footer_text']}} @else{{__('Copyright')}} &copy; {{ config('app.name', 'WorkDo') }}@endif{{date('Y')}}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    @if((isset($admin_settings['enable_cookie']) ? $admin_settings['enable_cookie'] : 'off') == 'on')
        @include('layouts.cookie_consent')
    @endif
@stack('custom-scripts')
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/custom.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
@stack('script')
@if((isset($admin_settings['cust_darklayout']) ? $admin_settings['cust_darklayout'] : 'off') == 'on')
<script>
       document.addEventListener('DOMContentLoaded', (event) => {
       const recaptcha = document.querySelector('.g-recaptcha');
       recaptcha.setAttribute("data-theme", "dark");
       });
</script>
@endif
</body>
</html>
