@php
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    $old_path = url("/market_assets/images/");
    $admin_settings = getAdminAllSetting();
    $color = !empty($admin_settings['color']) ? $admin_settings['color'] : 'theme-1';
      if (isset($admin_settings['color_flag']) && $admin_settings['color_flag'] == 'true') {
          $themeColor = 'custom-color';
      } else {
          $themeColor = $color;
      }

@endphp

<!DOCTYPE html>
<html lang="en">

<head>

    

    <title>@yield('page-title') | {{!empty(admin_setting('title_text')) ? admin_setting('title_text') :'WorkDo-Dash' }}</title>

    <meta name="title" content="{{ !empty($settings['meta_title']) ? $settings['meta_title'] : 'Workdo Dash' }}">
    <meta name="keywords" content="{{ !empty($settings['meta_keywords']) ? $settings['meta_keywords'] : 'WorkDo Dash,SaaS solution,Multi-workspace' }}">
    <meta name="description" content="{{ !empty($settings['meta_description']) ? $settings['meta_description'] : 'Discover the efficiency of Dash, a user-friendly web application by Rajodiya Apps.'}}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ !empty($settings['meta_title']) ? $settings['meta_title'] : 'Workdo Dash' }}">
    <meta property="og:description" content="{{ !empty($settings['meta_description']) ? $settings['meta_description'] : 'Discover the efficiency of Dash, a user-friendly web application by Rajodiya Apps.'}} ">
    <meta property="og:image" content="{{ get_file( (!empty($settings['meta_image'])) ? (check_file($settings['meta_image'])) ?  $settings['meta_image'] : 'uploads/meta/meta_image.png' : 'uploads/meta/meta_image.png'  ) }}{{'?'.time() }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ !empty($settings['meta_title']) ? $settings['meta_title'] : 'Workdo Dash' }}">
    <meta property="twitter:description" content="{{ !empty($settings['meta_description']) ? $settings['meta_description'] : 'Discover the efficiency of Dash, a user-friendly web application by Rajodiya Apps.'}} ">
    <meta property="twitter:image" content="{{ get_file( (!empty($settings['meta_image'])) ? (check_file($settings['meta_image'])) ?  $settings['meta_image'] : 'uploads/meta/meta_image.png' : 'uploads/meta/meta_image.png'  ) }}{{'?'.time() }}">

    <meta name="author" content="Workdo.io">

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <link rel="icon" href="{{ (!empty($settings['favicon']) && check_file($settings['favicon'])) ? get_file($settings['favicon']) : get_file('uploads/logo/favicon.png')}}{{'?'.time()}}" type="image/x-icon" />


    <style>
        :root {
            --color-customColor: <?= $color ?>;
        }
    </style>


    <link rel="stylesheet" href="{{ asset('css/custom-color.css') }}">

    @if (isset($settings['body_fontfamily']))
        @php
            $body_fontfamily = $settings['body_fontfamily'];
        @endphp
        <style id="dynamic-fonts">
            {!!  \Modules\LandingPage\Entities\LandingPageSetting::generateFontCSSFromJSON($body_fontfamily) !!} 
        </style>

        <style>
            :root {
                --first-font: '{{ $body_fontfamily }}' !important ;
            }
        </style>
    @endif
    @if (admin_setting('site_rtl') == 'on')
        <link rel="stylesheet" href="{{ asset('market_assets/css/main-style-rtl.css') }}">
        <link rel="stylesheet" href="{{ asset('market_assets/css/responsive-rtl.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('market_assets/css/main-style1.css') }}">
        <link rel="stylesheet" href="{{ asset('market_assets/css/responsive1.css') }}">
    @endif

    @if(admin_setting('cust_darklayout') == 'on')
        <link rel="stylesheet" href="{{ asset('market_assets/css/main-style-dark.css') }}" id="main-style-link">
    @endif

    {{-- pwa customer app --}}
    <meta name="mobile-wep-app-capable" content="yes">
    <meta name="apple-mobile-wep-app-capable" content="yes">
    <meta name="msapplication-starturl" content="/">
    <link rel="apple-touch-icon"
        href="{{ (!empty(admin_setting('favicon')) && check_file(admin_setting('favicon'))) ? get_file(admin_setting('favicon')) : get_file('uploads/logo/favicon.png')}}" />

    @if (isset($settings['is_pwa_store_active'])  && $settings['is_pwa_store_active'] == 'on')
        <link rel="manifest" href="{{ asset('uploads/customer_app/manifest.json') }}" />
    @endif
    @php
        $pwa = \Modules\LandingPage\Entities\LandingPageSetting::pwa_store();
    @endphp
    @if (!empty($settings['theme_color']))
        <meta name="theme-color" content="{{ $pwa->theme_color }}" />
    @endif
    @if (!empty($settings['background_color']))
        <meta name="apple-mobile-web-app-status-bar"
            content="{{ isset($pwa->background_color) ? $pwa->background_color : '' }}" />
    @endif

    @if ( isset($settings['is_pwa_store_active'])  && $settings['is_pwa_store_active'] == 'on')
    <script type="text/javascript">
        const container = document.querySelector("body")
        console.log(container);
        const coffees = [];

        if ("serviceWorker" in navigator) {

            window.addEventListener("load", function() {
                navigator.serviceWorker
                    .register("{{ asset('Modules/LandingPage/Resources/assets/js/serviceWorker.js') }}")
                    .then(res => console.log(""))
                    .catch(err => console.log("service worker not registered", err))
            })
        }
    </script>
    @endif

    {{-- Include your custom CSS --}}
    <style>
        {!! isset($settings['landingpage_custom_css']) ? $settings['landingpage_custom_css'] : '' !!}
    </style>

</head>

<body class="{{ !empty($themeColor)?$themeColor:'theme-1' }}" >

    <!-- header start here -->
    <header class="site-header header-style-one">
        @if ($settings['is_top_bar_active'] == 'on')
        <div class="announcebar bg-dark text-center">
            {!! $settings['topbar_notification_msg'] !!}
        </div>
        @endif
        <div class="main-navigationbar">
            <div class="container">
                <div class="navigationbar-row d-flex align-items-center justify-content-between">
                    <div class="logo-col">
                        <a href="{{ route('start') }}">
                            <img src="{{ check_file($settings['site_logo']) ? get_file($settings['site_logo']) : get_file('uploads/logo/logo_dark.png')}}" alt="">
                        </a>
                    </div>
                    <div class="menu-items-col">
                        <ul class="main-nav">
                            <li class="menu-lnk">
                                <a href="{{  url('/')  }}">{{ $settings['home_title'] }}</a>
                            </li>
                            <li class="menu-lnk">
                                <a href="{{ route('apps.software') }}">{{ __('Add-on')}}</a>
                            </li>
                            <li class="menu-lnk">
                                <a href="{{ route('apps.pricing') }}">{{ __('Pricing')}}</a>
                            </li>
                            @if (is_array(json_decode($settings['menubar_page'])) || is_object(json_decode($settings['menubar_page'])))
                                @foreach (json_decode($settings['menubar_page']) as $key => $value)
                                    @if ($value->header == 'on')
                                        @if($value->template_name == 'page_content')
                                            <li class="menu-lnk">
                                                <a class="nav-link" href="{{ route('custom.page',$value->page_slug) }}">{{ $value->menubar_page_name }}</a>
                                            </li>
                                        @else
                                            <li class="menu-lnk">
                                                <a class="nav-link" target="_blank" href="{{ $value->page_url }}">{{ $value->menubar_page_name }}</a>
                                            </li>
                                        @endif
                                    @endif
                                @endforeach
                            @endif
                            <li class="menu-lnk lnk-btn">
                                <a href="{{ (Auth::check()) ? route('home') :route('login') }}">{{ (Auth::check()) ? __('Home') :__('Login') }}<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16" fill="none">
                                        <g clip-path="url(#clip0_14_726)">
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M5.88967 10.9856C6.96087 11.2611 7.75238 12.233 7.75238 13.3897C7.75238 14.7607 6.64043 15.8721 5.26877 15.8721C3.89711 15.8721 2.78516 14.7607 2.78516 13.3897C2.78516 12.233 3.57667 11.2611 4.64787 10.9856V10.5959C4.64787 8.7099 6.1768 7.18097 8.06283 7.18097C9.26304 7.18097 10.236 6.20801 10.236 5.00781V3.09158L8.81233 4.51524C8.56985 4.75772 8.17672 4.75772 7.93424 4.51524C7.69176 4.27276 7.69176 3.87963 7.93424 3.63715L10.4179 1.15354C10.6603 0.91106 11.0535 0.91106 11.2959 1.15354L13.7796 3.63715C14.022 3.87962 14.022 4.27276 13.7796 4.51524C13.5371 4.75771 13.1439 4.75772 12.9015 4.51524L11.4778 3.09158V5.00781C11.4778 6.89384 9.94887 8.42278 8.06283 8.42278C6.86263 8.42278 5.88967 9.39573 5.88967 10.5959V10.9856ZM6.51058 13.3897C6.51058 14.0743 5.95517 14.6303 5.26877 14.6303C4.58237 14.6303 4.02696 14.0743 4.02696 13.3897C4.02696 12.7052 4.58237 12.1492 5.26877 12.1492C5.95517 12.1492 6.51058 12.7052 6.51058 13.3897Z"
                                                fill="white" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_14_726">
                                                <rect width="14.9017" height="14.9017" fill="white"
                                                    transform="translate(0.921875 0.97168)" />
                                            </clipPath>
                                        </defs>
                                    </svg></a>
                            </li>
                        </ul>
                        <div class="mobile-menu mobile-only">
                            <button class="mobile-menu-button">
                                <div class="one"></div>
                                <div class="two"></div>
                                <div class="three"></div>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header end here -->

    <div class="home-wrapper">
        @foreach (json_decode( $settings['landing_page_section_sequence'], true) as $key => $value)
            <!-- [ Banner ] start -->
            @if ($settings['is_banner_section_active'] == 'on' && $value == 'is_banner_section_active')
                <section class="main-home-first-section">
                    <div class="offset-container offset-left">
                        <div class="row row-gap align-items-center">
                            <div class="col-md-6 col-12">
                                <div class="banner-content">
                                    <h1>{{ $settings['home_heading'] }}
                                    </h1>
                                    <p>{{ $settings['home_description'] }}</p>
                                    <div class="btn-group">
                                        <a href="{{ route('apps.pricing') }}" class="btn">{{ __('Get the Package')}} <svg xmlns="http://www.w3.org/2000/svg"
                                                width="16" height="16" viewBox="0 0 16 16" fill="none">
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
                                            </svg>
                                        </a>
                                        @if ($settings['home_live_demo_link'])
                                        <a href="{{ $settings['home_live_demo_link'] }}" class="link-btn">{{ $settings['home_link_button_text']}} <svg xmlns="http://www.w3.org/2000/svg"
                                                width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                <g clip-path="url(#clip0_7_820)">
                                                    <path
                                                        d="M9.33984 1.18359L9.33985 18.519L4.612 18.519C2.87125 18.519 1.4601 17.1079 1.4601 15.3671L1.4601 4.33549C1.4601 2.59475 2.87125 1.18359 4.612 1.18359L9.33984 1.18359Z"
                                                        fill="#002332" />
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M17.222 19.3066C18.5276 19.3066 19.5859 18.2483 19.5859 16.9427L19.5859 15.6294L19.5859 2.75918C19.5859 1.45362 18.5276 0.39526 17.222 0.39526L10.1302 0.39526L9.77566 0.39526C9.34047 0.39526 8.98768 0.748047 8.98768 1.18324C8.98768 1.61842 9.34047 1.97121 9.77566 1.97121L10.1302 1.97121L17.222 1.97121C17.6572 1.97121 18.01 2.324 18.01 2.75918L18.01 15.6294L18.01 16.9427C18.01 17.3779 17.6572 17.7307 17.222 17.7307L10.1302 17.7307L9.77566 17.7307C9.34047 17.7307 8.98769 18.0835 8.98769 18.5187C8.98769 18.9539 9.34047 19.3066 9.77566 19.3066L10.1302 19.3066L17.222 19.3066ZM7.72693 18.5187C7.72693 18.0835 7.37414 17.7307 6.93895 17.7307L6.22977 17.7307C5.79459 17.7307 5.4418 18.0835 5.4418 18.5187C5.4418 18.9539 5.79459 19.3066 6.22977 19.3066L6.93895 19.3066C7.37414 19.3066 7.72693 18.9539 7.72693 18.5187ZM7.72693 1.18324C7.72693 0.748047 7.37414 0.39526 6.93895 0.39526L6.22977 0.39526C5.79459 0.39526 5.4418 0.748047 5.4418 1.18324C5.4418 1.61842 5.79459 1.97121 6.22977 1.97121L6.93895 1.97121C7.37414 1.97121 7.72693 1.61842 7.72693 1.18324ZM4.18104 18.5187C4.18104 18.0835 3.82825 17.7307 3.39307 17.7307L3.03848 17.7307C2.99569 17.7307 2.95423 17.7274 2.9142 17.7211C2.48429 17.6535 2.08101 17.9472 2.01344 18.3772C1.94588 18.8071 2.23962 19.2103 2.66953 19.2779C2.79021 19.2969 2.91347 19.3066 3.03848 19.3066L3.39307 19.3066C3.82825 19.3066 4.18104 18.9539 4.18104 18.5187ZM4.18104 1.18324C4.18104 0.748048 3.82825 0.395261 3.39307 0.395261L3.03848 0.395261C2.91347 0.395261 2.7902 0.405034 2.66953 0.423997C2.23962 0.491559 1.94588 0.894841 2.01344 1.32475C2.08101 1.75466 2.48429 2.0484 2.9142 1.98084C2.95423 1.97455 2.99569 1.97121 3.03848 1.97121L3.39307 1.97121C3.82825 1.97121 4.18104 1.61842 4.18104 1.18324ZM1.60405 17.9678C2.03396 17.9002 2.3277 17.4969 2.26014 17.067C2.25384 17.027 2.25051 16.9855 2.25051 16.9427L2.25051 16.5881C2.25051 16.1529 1.89772 15.8002 1.46253 15.8002C1.02735 15.8002 0.674557 16.1529 0.674557 16.5881L0.674557 16.9427C0.674557 17.0677 0.68433 17.191 0.703293 17.3117C0.770857 17.7416 1.17414 18.0353 1.60405 17.9678ZM1.60405 1.73415C1.17414 1.66659 0.770856 1.96033 0.703292 2.39024C0.684329 2.51091 0.674556 2.63417 0.674556 2.75918L0.674556 3.11377C0.674556 3.54896 1.02734 3.90175 1.46253 3.90175C1.89772 3.90175 2.2505 3.54896 2.2505 3.11377L2.2505 2.75918C2.2505 2.7164 2.25384 2.67493 2.26013 2.6349C2.3277 2.20499 2.03396 1.80171 1.60405 1.73415ZM1.46253 14.5394C1.89772 14.5394 2.25051 14.1866 2.25051 13.7514L2.25051 13.0422C2.25051 12.6071 1.89772 12.2543 1.46253 12.2543C1.02735 12.2543 0.674556 12.6071 0.674556 13.0422L0.674556 13.7514C0.674557 14.1866 1.02735 14.5394 1.46253 14.5394ZM1.46253 10.9935C1.89772 10.9935 2.25051 10.6407 2.25051 10.2055L2.25051 9.49636C2.2505 9.06118 1.89772 8.70839 1.46253 8.70839C1.02735 8.70839 0.674556 9.06118 0.674556 9.49636L0.674556 10.2055C0.674556 10.6407 1.02735 10.9935 1.46253 10.9935ZM1.46253 7.44763C1.89772 7.44763 2.2505 7.09484 2.2505 6.65966L2.2505 5.95048C2.2505 5.51529 1.89772 5.16251 1.46253 5.16251C1.02735 5.16251 0.674556 5.51529 0.674556 5.95048L0.674556 6.65966C0.674556 7.09484 1.02735 7.44763 1.46253 7.44763ZM6.97806 9.06298C6.54288 9.06298 6.19009 9.41577 6.19009 9.85095C6.19009 10.2861 6.54288 10.6389 6.97806 10.6389L11.3798 10.6389L10.3611 11.6577C10.0534 11.9654 10.0534 12.4643 10.3611 12.7721C10.6688 13.0798 11.1677 13.0798 11.4754 12.7721L13.8394 10.4081C13.9871 10.2604 14.0702 10.0599 14.0702 9.85095C14.0702 9.64197 13.9871 9.44154 13.8394 9.29377L11.4754 6.92985C11.1677 6.62213 10.6688 6.62213 10.3611 6.92985C10.0534 7.23757 10.0534 7.73649 10.3611 8.04421L11.3798 9.06298L6.97806 9.06298Z"
                                                        fill=href="#""white" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_7_820">
                                                        <rect width="18.9114" height="18.9114" fill="white"
                                                            transform="translate(0.675781 0.395508)" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12">
                                <div class="banner-image-wrapper">
                                    <div class="ripple-icon position-top">
                                        <div class="pulse0"></div>
                                        <div class="pulse1"></div>
                                        <div class="pulse2"></div>
                                        <div class="pulse3"></div>
                                    </div>
                                    <div class="ripple-icon position-left">
                                        <div class="pulse0"></div>
                                        <div class="pulse1"></div>
                                        <div class="pulse2"></div>
                                        <div class="pulse3"></div>
                                    </div>
                                    <div class="banner-img-wrapper">
                                        <img src="{{ asset('market_assets/images/banner-image.png')}}" alt=""  class="main-banner" >
                                        <img src="{{ asset('market_assets/images/banner-image-rtl.png')}}" alt=""  class="rtl-banner">
                                        <img src="{{ check_file($settings['home_banner']) ? get_file($settings['home_banner']) : $old_path."/images1.png" }}" alt="" class="inner-frame-img">
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $logo_path = 'uploads/landing_page_image/';
                    @endphp
                    <div class="exclusive-partners">
                        <div class="container">
                            <div class="section-title">
                                <p>{{ $settings['home_trusted_by'] }}</p>
                            </div>
                            <div class="partners-logo-slider">
                                @if (explode(',', $settings['home_logo']) && count(explode(',', $settings['home_logo'])) > 1 )
                                    @foreach (explode(',', $settings['home_logo'])  as $k => $home_logo )
                                    <div class="logo-item">
                                        <a href="#">
                                            <img src="{{ check_file($logo_path.$home_logo) ? get_file($logo_path.$home_logo) : $old_path."/logo-dark.png" }}" alt="exclusive partner">
                                        </a>
                                    </div>
                                    @endforeach
                                @else
                                    @for ($i = 1; $i < 15; $i++)
                                        <div class="logo-item">
                                            <a href="#">
                                                <img src="{{ check_file($logo_path.$settings['home_logo']) ? get_file($logo_path.$settings['home_logo']) : $old_path."/logo-dark.png" }}" alt="exclusive partner">
                                            </a>
                                        </div>
                                    @endfor
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            @endif
            <!-- [ Banner ] end -->
            <!-- [ features Cards ] start -->
            @if ($settings['is_features_section_active'] == 'on' && $value == 'is_features_section_active')
                <section class="support-section">
                    <div class="container">
                        <div class="row align-items-start justify-content-center ">
                            @if (is_array(json_decode($settings['feature_of_features'], true)) ||
                                is_object(json_decode($settings['feature_of_features'], true)))
                                @foreach (json_decode($settings['feature_of_features'], true) as $key => $value)
                                    <div class="col-lg-4 col-md-6 col-12 support-card">
                                        <div class="support-card-inner">
                                            <div class="support-card-media">
                                                <div class="card-icon">
                                                    @if(check_file($value['feature_logo']))
                                                        <img src="{{ get_file($value['feature_logo']) }}" alt="">
                                                    @else
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="82" height="82" viewBox="0 0 82 82" fill="none">
                                                            <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd" d="M20.5 58.0833H61.5C68.3333 58.0833 71.75 54.6667 71.75 47.8333V20.5C71.75 13.6667 68.3333 10.25 61.5 10.25H20.5C13.6667 10.25 10.25 13.6667 10.25 20.5V47.8333C10.25 54.6667 13.6667 58.0833 20.5 58.0833Z" fill="white"></path>
                                                            <path d="M56.375 69.1872H47.8333V58.083H34.1667V69.1872H25.625C24.2105 69.1872 23.0625 70.3352 23.0625 71.7497C23.0625 73.1642 24.2105 74.3122 25.625 74.3122H56.375C57.7895 74.3122 58.9375 73.1642 58.9375 71.7497C58.9375 70.3352 57.7895 69.1872 56.375 69.1872Z" fill="white"></path>
                                                            <path d="M34.1646 43.5618C33.5086 43.5618 32.8525 43.3125 32.3537 42.8103L25.5203 35.9769C24.5193 34.9758 24.5193 33.3528 25.5203 32.3517L32.3537 25.5184C33.3548 24.5173 34.9778 24.5173 35.9789 25.5184C36.98 26.5195 36.98 28.1425 35.9789 29.1436L30.9565 34.166L35.9789 39.1884C36.98 40.1895 36.98 41.8125 35.9789 42.8136C35.4766 43.3124 34.8206 43.5618 34.1646 43.5618ZM49.6422 42.8103L56.4755 35.9769C57.4766 34.9758 57.4766 33.3528 56.4755 32.3517L49.6422 25.5184C48.6411 24.5173 47.0181 24.5173 46.017 25.5184C45.0159 26.5195 45.0159 28.1425 46.017 29.1436L51.0394 34.166L46.017 39.1884C45.0159 40.1895 45.0159 41.8125 46.017 42.8136C46.5158 43.3124 47.1719 43.5652 47.8279 43.5652C48.4839 43.5652 49.1434 43.3125 49.6422 42.8103Z" fill="white"></path>
                                                        </svg>
                                                    @endif

                                                </div>
                                            </div>
                                            <div class="support-content">
                                                <h3>{!! $value['feature_heading'] !!}</h3>
                                                <p>{!! $value['feature_description'] !!}</p>
                                            </div>
                                            <div class="support-content-bottom">
                                                <a href="{{ $value['feature_more_details_link'] }}" class="btn">{!! $value['feature_more_details_button_text'] !!} <svg xmlns="http://www.w3.org/2000/svg" width="16"
                                                        height="16" viewBox="0 0 16 16" fill="none">
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
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </section>
            {{-- @endif
            <!-- [ features ] end -->

            <!-- [ features Sections ] start -->
            @if ($settings['is_features_section_active'] == 'on' && $value == 'is_features_section_active') --}}
                <section class="dedicated-themes-section">
                    <div class="container">
                        <div class="section-title text-center padding-bottom">
                            <h2> {!! $settings['highlight_feature_heading'] !!}</h2>
                            <p> {!! $settings['highlight_feature_description'] !!} </p>
                        </div>
                    </div>
                    @if (is_array(json_decode($settings['other_features'], true)) ||
                        is_object(json_decode($settings['other_features'], true)))
                            @foreach (json_decode($settings['other_features'], true) as $key => $value)
                                @if ($key % 2 == 0)
                                    <div class="bg-light padding-bottom">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-lg-5 col-md-6 col-12">
                                                    <div class="abt-theme">
                                                        <div class="section-title">
                                                            <div class="subtitle">
                                                                {!! $value['other_features_tag'] !!}
                                                            </div>
                                                            <h2>{!! $value['other_features_heading'] !!}</h2>
                                                        </div>
                                                        <p>{!! $value['other_featured_description'] !!}</p>
                                                        @if (!empty($value['cards']))
                                                            @foreach ($value['cards'] as $key => $card)
                                                                <div class="theme-acnav">
                                                                    <div class="set has-children">
                                                                        <a href="javascript:;" class="acnav-label">
                                                                            <span class="acnav-icon">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="33"
                                                                                    viewBox="0 0 30 33" fill="none">
                                                                                    <path
                                                                                        d="M15 2C9.48 2 5 6.48 5 12C5 17.52 9.48 22 15 22C20.52 22 25 17.52 25 12C25 6.48 20.52 2 15 2ZM19.03 10.2L14.36 14.86C14.22 15.01 14.03 15.08 13.83 15.08C13.64 15.08 13.45 15.01 13.3 14.86L10.97 12.53C10.68 12.24 10.68 11.76 10.97 11.47C11.26 11.18 11.74 11.18 12.03 11.47L13.83 13.27L17.97 9.14001C18.26 8.84001 18.74 8.84001 19.03 9.14001C19.32 9.43001 19.32 9.90001 19.03 10.2Z"
                                                                                        fill="#002332" />
                                                                                    <g filter="url(#filter0_d_14_1908)">
                                                                                        <circle cx="15" cy="12.5596" r="11" fill="#6FD943" />
                                                                                    </g>
                                                                                    <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                                                                                        d="M19.668 8.91699C19.668 8.91699 19.668 10.7131 19.668 10.7819C19.668 10.8508 19.6143 10.9587 19.4929 10.9587C19.3372 10.9587 16.7104 10.9587 16.7104 10.9587C15.4738 10.9587 14.7096 11.7286 14.7096 12.9653V13.0003H9.16797V8.91699C9.16797 7.75033 9.7513 7.16699 10.918 7.16699H17.918C19.0846 7.16699 19.668 7.75033 19.668 8.91699Z"
                                                                                        fill="white" />
                                                                                    <path
                                                                                        d="M14.7096 17.7017C14.7096 17.8417 14.7213 17.9758 14.7388 18.1042H11.793C11.5538 18.1042 11.3555 17.9058 11.3555 17.6667C11.3555 17.4275 11.5538 17.2292 11.793 17.2292H13.2513V15.3333H10.918C9.7513 15.3333 9.16797 14.75 9.16797 13.5833V13H14.7096V17.7017Z"
                                                                                        fill="white" />
                                                                                    <path opacity="0.4"
                                                                                        d="M16.7067 18.8336H19.7068C20.457 18.8336 20.8315 18.4562 20.8315 17.7008V12.9658C20.8315 12.2109 20.4564 11.833 19.7068 11.833H16.7067C15.9565 11.833 15.582 12.2104 15.582 12.9658V17.7008C15.582 18.4562 15.9571 18.8336 16.7067 18.8336Z"
                                                                                        fill="white" />
                                                                                    <path
                                                                                        d="M18.2083 17.9587C18.5305 17.9587 18.7917 17.6975 18.7917 17.3753C18.7917 17.0532 18.5305 16.792 18.2083 16.792C17.8862 16.792 17.625 17.0532 17.625 17.3753C17.625 17.6975 17.8862 17.9587 18.2083 17.9587Z"
                                                                                        fill="white" />
                                                                                    <defs>
                                                                                        <filter id="filter0_d_14_1908" x="0" y="1.55957" width="30"
                                                                                            height="31" filterUnits="userSpaceOnUse"
                                                                                            color-interpolation-filters="sRGB">
                                                                                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                                                                            <feColorMatrix in="SourceAlpha" type="matrix"
                                                                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                                                                result="hardAlpha" />
                                                                                            <feOffset dy="5" />
                                                                                            <feGaussianBlur stdDeviation="2" />
                                                                                            <feComposite in2="hardAlpha" operator="out" />
                                                                                            <feColorMatrix type="matrix"
                                                                                                values="0 0 0 0 0.435294 0 0 0 0 0.85098 0 0 0 0 0.262745 0 0 0 0.31 0" />
                                                                                            <feBlend mode="normal" in2="BackgroundImageFix"
                                                                                                result="effect1_dropShadow_14_1908" />
                                                                                            <feBlend mode="normal" in="SourceGraphic"
                                                                                                in2="effect1_dropShadow_14_1908" result="shape" />
                                                                                        </filter>
                                                                                    </defs>
                                                                                </svg>
                                                                            </span>
                                                                            <span>{!! $card['title'] !!}</span>
                                                                        </a>
                                                                        <div class="acnav-list">
                                                                            <p>{!! $card['description'] !!}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="col-lg-7 col-md-6 col-12">
                                                    <div class="dash-theme-preview">
                                                        <img src="{{ check_file($value['other_features_image']) ? get_file($value['other_features_image']) : $old_path."/account_image1.png" }}" alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-white padding-bottom ">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-lg-7  col-md-6 col-12">
                                                    <div class="dash-theme-preview">
                                                        <img src="{{ check_file($value['other_features_image']) ? get_file($value['other_features_image']) : $old_path."/hrm_image1.png" }}" alt="">
                                                    </div>
                                                </div>
                                                <div class="col-lg-5 col-md-6 col-12">
                                                    <div class="abt-theme">
                                                        <div class="section-title">
                                                            <div class="subtitle">
                                                                {!! $value['other_features_tag'] !!}
                                                            </div>
                                                            <h2>{!! $value['other_features_heading'] !!}</h2>
                                                        </div>
                                                        <p>{!! $value['other_featured_description'] !!}</p>
                                                        @if (!empty($value['cards'][1]['title']) && $value['cards'][1]['title'] != null && $value['cards'][1]['title'] != '')
                                                            <div class="theme-acnav">
                                                                @foreach ($value['cards'] as $key => $card)
                                                                    <div class="set has-children">
                                                                        <a href="javascript:;" class="acnav-label">
                                                                            <span class="acnav-icon">
                                                                                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="33"
                                                                                    viewBox="0 0 30 33" fill="none">
                                                                                    <path
                                                                                        d="M15 2C9.48 2 5 6.48 5 12C5 17.52 9.48 22 15 22C20.52 22 25 17.52 25 12C25 6.48 20.52 2 15 2ZM19.03 10.2L14.36 14.86C14.22 15.01 14.03 15.08 13.83 15.08C13.64 15.08 13.45 15.01 13.3 14.86L10.97 12.53C10.68 12.24 10.68 11.76 10.97 11.47C11.26 11.18 11.74 11.18 12.03 11.47L13.83 13.27L17.97 9.14001C18.26 8.84001 18.74 8.84001 19.03 9.14001C19.32 9.43001 19.32 9.90001 19.03 10.2Z"
                                                                                        fill="#002332" />
                                                                                    <g filter="url(#filter0_d_14_1908)">
                                                                                        <circle cx="15" cy="12.5596" r="11" fill="#6FD943" />
                                                                                    </g>
                                                                                    <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                                                                                        d="M19.668 8.91699C19.668 8.91699 19.668 10.7131 19.668 10.7819C19.668 10.8508 19.6143 10.9587 19.4929 10.9587C19.3372 10.9587 16.7104 10.9587 16.7104 10.9587C15.4738 10.9587 14.7096 11.7286 14.7096 12.9653V13.0003H9.16797V8.91699C9.16797 7.75033 9.7513 7.16699 10.918 7.16699H17.918C19.0846 7.16699 19.668 7.75033 19.668 8.91699Z"
                                                                                        fill="white" />
                                                                                    <path
                                                                                        d="M14.7096 17.7017C14.7096 17.8417 14.7213 17.9758 14.7388 18.1042H11.793C11.5538 18.1042 11.3555 17.9058 11.3555 17.6667C11.3555 17.4275 11.5538 17.2292 11.793 17.2292H13.2513V15.3333H10.918C9.7513 15.3333 9.16797 14.75 9.16797 13.5833V13H14.7096V17.7017Z"
                                                                                        fill="white" />
                                                                                    <path opacity="0.4"
                                                                                        d="M16.7067 18.8336H19.7068C20.457 18.8336 20.8315 18.4562 20.8315 17.7008V12.9658C20.8315 12.2109 20.4564 11.833 19.7068 11.833H16.7067C15.9565 11.833 15.582 12.2104 15.582 12.9658V17.7008C15.582 18.4562 15.9571 18.8336 16.7067 18.8336Z"
                                                                                        fill="white" />
                                                                                    <path
                                                                                        d="M18.2083 17.9587C18.5305 17.9587 18.7917 17.6975 18.7917 17.3753C18.7917 17.0532 18.5305 16.792 18.2083 16.792C17.8862 16.792 17.625 17.0532 17.625 17.3753C17.625 17.6975 17.8862 17.9587 18.2083 17.9587Z"
                                                                                        fill="white" />
                                                                                    <defs>
                                                                                        <filter id="filter0_d_14_1908" x="0" y="1.55957" width="30"
                                                                                            height="31" filterUnits="userSpaceOnUse"
                                                                                            color-interpolation-filters="sRGB">
                                                                                            <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                                                                            <feColorMatrix in="SourceAlpha" type="matrix"
                                                                                                values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0"
                                                                                                result="hardAlpha" />
                                                                                            <feOffset dy="5" />
                                                                                            <feGaussianBlur stdDeviation="2" />
                                                                                            <feComposite in2="hardAlpha" operator="out" />
                                                                                            <feColorMatrix type="matrix"
                                                                                                values="0 0 0 0 0.435294 0 0 0 0 0.85098 0 0 0 0 0.262745 0 0 0 0.31 0" />
                                                                                            <feBlend mode="normal" in2="BackgroundImageFix"
                                                                                                result="effect1_dropShadow_14_1908" />
                                                                                            <feBlend mode="normal" in="SourceGraphic"
                                                                                                in2="effect1_dropShadow_14_1908" result="shape" />
                                                                                        </filter>
                                                                                    </defs>
                                                                                </svg>
                                                                            </span>
                                                                            <span>{!! $card['title'] !!}</span>
                                                                        </a>
                                                                        <div class="acnav-list">
                                                                            <p>{!! $card['description'] !!}</p>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif

                </section>
            @endif
            <!-- [ features Sections ] start -->

            <!-- [ reviews Sections ] start -->
            @if ($settings['is_reviews_section_active'] == 'on' && $value == 'is_reviews_section_active')
                <section class="review-section">
                    <div class="container">
                        <div class="review-slider">
                            @if (is_array(json_decode($settings['reviews'], true)) ||
                            is_object(json_decode($settings['reviews'], true)))
                                @foreach (json_decode($settings['reviews'], true) as $key => $value)
                                    <div class="review-content-itm">
                                        <div class="review-content">
                                            <div class="section-title">
                                                <div class="quats">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="83" height="59" viewBox="0 0 83 59"
                                                        fill="none">
                                                        <path
                                                            d="M17.4193 58.9563C12.8711 58.9563 8.86601 57.2932 5.40398 53.9669C1.94195 50.5727 0.210938 45.8209 0.210938 39.7115C0.210938 34.2809 1.33101 29.0878 3.57114 24.1324C5.81128 19.109 8.76418 14.5609 12.4299 10.4879C16.1634 6.34704 20.2364 2.85108 24.6488 0L35.1367 7.73865C33.9827 8.689 32.5232 10.2503 30.7582 12.4226C28.9933 14.5948 27.2962 16.9707 25.667 19.5503C24.0378 22.1298 22.9178 24.5397 22.3068 26.7798C25.701 27.8659 28.4842 29.7327 30.6564 32.3801C32.8965 34.9597 34.0166 38.3199 34.0166 42.4607C34.0166 47.4841 32.3195 51.4892 28.9254 54.476C25.5991 57.4629 21.7638 58.9563 17.4193 58.9563ZM65.073 58.9563C60.457 58.9563 56.4519 57.2932 53.0578 53.9669C49.6636 50.5727 47.9666 45.8209 47.9666 39.7115C47.9666 34.3487 49.0866 29.1896 51.3268 24.2342C53.6348 19.2108 56.6216 14.6288 60.2873 10.4879C64.0209 6.27917 68.0259 2.7832 72.3026 0L82.8923 7.73865C81.6704 8.82478 80.177 10.42 78.412 12.5244C76.6471 14.6288 74.9839 16.9707 73.4226 19.5503C71.8613 22.0619 70.7413 24.4718 70.0624 26.7798C73.3887 27.8659 76.1719 29.7327 78.412 32.3801C80.6522 34.9597 81.7722 38.3199 81.7722 42.4607C81.7722 47.4841 80.0752 51.4892 76.681 54.476C73.3548 57.4629 69.4854 58.9563 65.073 58.9563Z"
                                                            fill="#002332" />
                                                    </svg>
                                                </div>
                                                <div class="subtitle">
                                                    {!! $value['review_header_tag'] !!}
                                                </div>
                                                <h2>  {!! $value['review_heading'] !!}</h2>
                                            </div>
                                            <p> {!! $value['review_description'] !!} </p>
                                            <div class="btn-group">
                                                <a href="{{ route('apps.pricing') }}" class="btn btn-white">{{ __('Get the Package')}} <svg
                                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                                        fill="none">
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
                                                <a href="{!! $value['review_live_demo_link'] !!}" class="link-btn">{!! $value['review_live_demo_button_text'] !!}<svg xmlns="http://www.w3.org/2000/svg"
                                                        width="20" height="20" viewBox="0 0 20 20" fill="none">
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
            @endif
            <!-- [ reviews Sections ] end -->

            <!-- [ Screenshots ] start -->
            @if ($settings['is_screenshots_section_active'] == 'on' && $value == 'is_screenshots_section_active')
                <section class="theme-preview-section">
                    <div class="container">
                        <div class="theme-preview-slider">
                            @if (is_array(json_decode($settings['screenshots'], true)) || is_object(json_decode($settings['screenshots'], true)))
                                @foreach (json_decode($settings['screenshots'], true) as $value)
                                    <div class="theme-preview-itm">
                                        <div class="preview-itm">
                                            <img src="{{ check_file($value['screenshots']) ? get_file($value['screenshots']) : $old_path."/hrm_image1.png" }}" alt="{{ $value['screenshots_heading'] }}">
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </section>
            @endif
            <!-- [ Screenshots ] end -->

            <!-- [ dedicated ] start -->
            @if ($settings['is_dedicated_section_active'] == 'on' && $value == 'is_dedicated_section_active')
                <section class="dedicated-section">
                    <div class="container">
                        <div class="section-title text-center">
                            <h2>{{ $settings['dedicated_heading'] }}</b></h2>
                            <p>{!! $settings['dedicated_description'] !!}</p>
                            <div class="btn-group">
                                <a href="{{ route('apps.pricing') }}" class="btn-secondary">{{ __('Get the Package')}} <svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" viewBox="0 0 16 16" fill="none">
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
                                    </svg>
                                </a>
                                <a href="{{ $settings['dedicated_live_demo_link'] }}" class="link-btn">{{ $settings['dedicated_link_button_text'] }} <svg xmlns="http://www.w3.org/2000/svg" width="20"
                                        height="20" viewBox="0 0 20 20" fill="none">
                                        <g clip-path="url(#clip0_23_832)">
                                            <path
                                                d="M9.62891 1.18359L9.62891 18.519L4.90106 18.519C3.16032 18.519 1.74917 17.1079 1.74917 15.3671L1.74916 4.33549C1.74916 2.59475 3.16032 1.18359 4.90106 1.18359L9.62891 1.18359Z"
                                                fill="#6FD943" />
                                            <path fill-rule="evenodd" clip-rule="evenodd"
                                                d="M17.5111 19.3071C18.8166 19.3071 19.875 18.2488 19.875 16.9432L19.875 15.6299L19.875 2.75967C19.875 1.45411 18.8166 0.395748 17.5111 0.395748L10.4193 0.395749L10.0647 0.395749C9.62953 0.395749 9.27675 0.748536 9.27675 1.18372C9.27675 1.61891 9.62953 1.9717 10.0647 1.9717L10.4193 1.9717L17.5111 1.9717C17.9463 1.9717 18.2991 2.32449 18.2991 2.75967L18.2991 15.6299L18.2991 16.9432C18.2991 17.3784 17.9463 17.7312 17.5111 17.7312L10.4193 17.7312L10.0647 17.7312C9.62953 17.7312 9.27675 18.084 9.27675 18.5192C9.27675 18.9543 9.62953 19.3071 10.0647 19.3071L10.4193 19.3071L17.5111 19.3071ZM8.01599 18.5192C8.01599 18.084 7.6632 17.7312 7.22801 17.7312L6.51884 17.7312C6.08365 17.7312 5.73086 18.084 5.73086 18.5192C5.73086 18.9543 6.08365 19.3071 6.51884 19.3071L7.22801 19.3071C7.6632 19.3071 8.01599 18.9543 8.01599 18.5192ZM8.01599 1.18372C8.01599 0.748536 7.6632 0.395749 7.22801 0.395749L6.51884 0.395749C6.08365 0.395749 5.73086 0.748536 5.73086 1.18372C5.73086 1.61891 6.08365 1.9717 6.51884 1.9717L7.22801 1.9717C7.6632 1.9717 8.01599 1.61891 8.01599 1.18372ZM4.47011 18.5192C4.47011 18.084 4.11732 17.7312 3.68213 17.7312L3.32754 17.7312C3.28476 17.7312 3.24329 17.7278 3.20326 17.7216C2.77335 17.654 2.37007 17.9477 2.30251 18.3776C2.23495 18.8075 2.52869 19.2108 2.9586 19.2784C3.07927 19.2974 3.20253 19.3071 3.32754 19.3071L3.68213 19.3071C4.11732 19.3071 4.47011 18.9543 4.47011 18.5192ZM4.4701 1.18372C4.4701 0.748536 4.11732 0.395749 3.68213 0.395749L3.32754 0.395749C3.20253 0.395749 3.07927 0.405522 2.9586 0.424485C2.52869 0.492047 2.23494 0.895329 2.30251 1.32524C2.37007 1.75515 2.77335 2.04889 3.20326 1.98133C3.24329 1.97504 3.28475 1.9717 3.32754 1.9717L3.68213 1.9717C4.11732 1.9717 4.4701 1.61891 4.4701 1.18372ZM1.89311 17.9682C2.32302 17.9007 2.61676 17.4974 2.5492 17.0675C2.54291 17.0275 2.53957 16.986 2.53957 16.9432L2.53957 16.5886C2.53957 16.1534 2.18678 15.8006 1.75159 15.8006C1.31641 15.8006 0.963619 16.1534 0.963619 16.5886L0.963619 16.9432C0.963619 17.0682 0.973392 17.1915 0.992355 17.3122C1.05992 17.7421 1.4632 18.0358 1.89311 17.9682ZM1.89311 1.73464C1.4632 1.66708 1.05992 1.96081 0.992355 2.39073C0.973392 2.5114 0.963619 2.63466 0.963619 2.75967L0.963619 3.11426C0.963619 3.54945 1.31641 3.90223 1.75159 3.90223C2.18678 3.90223 2.53957 3.54945 2.53957 3.11426L2.53957 2.75967C2.53957 2.71689 2.54291 2.67542 2.5492 2.63539C2.61676 2.20548 2.32302 1.8022 1.89311 1.73464ZM1.75159 14.5399C2.18678 14.5399 2.53957 14.1871 2.53957 13.7519L2.53957 13.0427C2.53957 12.6075 2.18678 12.2548 1.75159 12.2548C1.31641 12.2548 0.963619 12.6075 0.963619 13.0427L0.963619 13.7519C0.963619 14.1871 1.31641 14.5399 1.75159 14.5399ZM1.75159 10.994C2.18678 10.994 2.53957 10.6412 2.53957 10.206L2.53957 9.49685C2.53957 9.06167 2.18678 8.70888 1.75159 8.70888C1.31641 8.70888 0.963619 9.06167 0.963619 9.49685L0.963619 10.206C0.963619 10.6412 1.31641 10.994 1.75159 10.994ZM1.75159 7.44812C2.18678 7.44812 2.53957 7.09533 2.53957 6.66014L2.53957 5.95097C2.53957 5.51578 2.18678 5.16299 1.75159 5.16299C1.31641 5.16299 0.963619 5.51578 0.963619 5.95097L0.963619 6.66014C0.963619 7.09533 1.31641 7.44812 1.75159 7.44812ZM7.26712 9.06346C6.83194 9.06346 6.47915 9.41625 6.47915 9.85144C6.47915 10.2866 6.83194 10.6394 7.26712 10.6394L11.6689 10.6394L10.6501 11.6582C10.3424 11.9659 10.3424 12.4648 10.6501 12.7725C10.9579 13.0803 11.4568 13.0803 11.7645 12.7725L14.1284 10.4086C14.2762 10.2608 14.3592 10.0604 14.3592 9.85144C14.3592 9.64246 14.2762 9.44203 14.1284 9.29426L11.7645 6.93034C11.4568 6.62261 10.9579 6.62261 10.6501 6.93034C10.3424 7.23806 10.3424 7.73698 10.6501 8.0447L11.6689 9.06347L7.26712 9.06346Z"
                                                fill="#002332" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_23_832">
                                                <rect width="18.9114" height="18.9114" fill="white"
                                                    transform="translate(0.964844 0.395508)" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="row row-gap justify-content-center ">
                            @if (is_array(json_decode($settings['dedicated_card_details'], true)) || is_object(json_decode($settings['dedicated_card_details'], true)))
                                @foreach (json_decode($settings['dedicated_card_details'], true) as $key => $value)

                                    <div class="col-lg-4 col-md-6 col-12 information-card">
                                        <div class="information-card-inner">
                                            <div class="card-icon">
                                                    <img src="{{ check_file($value['dedicated_card_logo']) ? get_file($value['dedicated_card_logo']) : '#' }}" alt="">
                                            </div>
                                            <div class="information-content">
                                                <h5>{{ $value['dedicated_card_heading'] }}</h5>
                                                <p>{!! $value['dedicated_card_description'] !!}</p>
                                                <a href="{{ $value['dedicated_card_more_details_link'] }}" class="btn btn-white" tabindex="0">{{ $value['dedicated_card_more_details_button_text'] }} <svg
                                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                                        fill="none">
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
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </section>
            @endif
            <!-- [ dedicated ] end -->

            <!-- [ built-technology ] start -->
            @if ($settings['is_buildtech_section_active'] == 'on' && $value == 'is_buildtech_section_active')
                <section class="built-technology">
                    <div class="container">
                        <div class="section-title text-center">
                            <h2>{{ $settings['buildtech_heading'] }}</h2>
                            <p>{!! $settings['buildtech_description'] !!}</p>
                        </div>
                        <div class="row row-gap justify-content-center">

                            @if (is_array(json_decode($settings['buildtech_card_details'], true)) || is_object(json_decode($settings['buildtech_card_details'], true)))
                                @foreach (json_decode($settings['buildtech_card_details'], true) as $key => $value)
                                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-12 information-card">
                                        <div class="information-card-inner">
                                            <div class="card-icon">
                                                <img src="{{ check_file($value['buildtech_card_logo']) ?  get_file($value['buildtech_card_logo']) : '#' }}" alt="">
                                            </div>
                                            <div class="information-content">
                                                <h5>{{ $value['buildtech_card_heading'] }}</h5>
                                                <p>{!! $value['buildtech_card_description'] !!}</p>
                                                <a href="{{ $value['buildtech_card_more_details_link']  }}" class="btn btn-white" tabindex="0">{{ $value['buildtech_card_more_details_button_text'] }} <svg
                                                        xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                                                        fill="none">
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
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </section>
            @endif
            <!-- [ built-technology ] end -->

            @if ($settings['is_package_details_section_active'] == 'on' && $value == 'is_package_details_section_active')
                <section class="package-detail-section">
                    <div class="container">
                        <div class="section-title text-center">
                            <h2>{{ $settings['packagedetails_heading'] }}</h2>
                            <p>{!! $settings['packagedetails_short_description'] !!}</p>
                            <div class="btn-group">
                                <a href="{{ $settings['packagedetails_link'] }}" class="btn-secondary">{{ $settings['packagedetails_button_text'] }} <svg xmlns="http://www.w3.org/2000/svg"
                                        width="16" height="16" viewBox="0 0 16 16" fill="none">
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
                            </div>
                        </div>
                        <div class="description">
                            {!! $settings['packagedetails_long_description'] !!}
                        </div>
                    </div>
                </section>
            @endif

        @endforeach

    </div>

    <!-- Footer start here -->
    @if ($settings['footer_status'] == 'on')
        <footer class="site-footer">
            <div class="container">
                <div class="footer-top">
                    <div class="footer-logo">
                        <a href="{{ route('start') }}">
                            <img src="{{ check_file($settings['footer_logo']) ? get_file($settings['footer_logo']) : get_file('uploads/logo/logo_light.png') }}" alt="logo">
                        </a>
                    </div>
                    <div class="divider-line"></div>
                    <div class="footer-top-right">
                        <ul>
                            <li>
                                <a href="{{ $settings['footer_live_demo_link'] }}" class="btn-secondary">{{ __('Go to Shop')}}<svg
                                        xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18"
                                        fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.25 13.5V7.5H2.75V13.5C2.75 15.1569 4.09315 16.5 5.75 16.5H13.25C14.9069 16.5 16.25 15.1569 16.25 13.5V7.5H14.75V13.5C14.75 14.3284 14.0784 15 13.25 15H11.75V12.75C11.75 11.5074 10.7426 10.5 9.5 10.5C8.25736 10.5 7.25 11.5074 7.25 12.75V15H5.75C4.92157 15 4.25 14.3284 4.25 13.5ZM8.75 15H10.25V12.75C10.25 12.3358 9.91421 12 9.5 12C9.08579 12 8.75 12.3358 8.75 12.75V15Z"
                                            fill="white" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M6.20416 1.5C4.96424 1.5 3.74225 2.1179 3.07037 3.26266C2.85516 3.62935 2.61186 4.0677 2.41774 4.48653C2.32087 4.69556 2.22534 4.92295 2.15147 5.14939C2.0852 5.35254 2 5.66136 2 6C2 6.59786 2.14656 7.35644 2.67579 7.99153C3.24513 8.67473 4.07678 9 5 9C5.87645 9 6.55566 8.5913 6.99156 8.22643C7.1402 8.10201 7.3598 8.10201 7.50844 8.22643C7.94434 8.5913 8.62355 9 9.5 9C10.3765 9 11.0557 8.5913 11.4916 8.22643C11.6402 8.10201 11.8598 8.10201 12.0084 8.22643C12.4443 8.5913 13.1235 9 14 9C14.9232 9 15.7549 8.67473 16.3242 7.99153C16.8534 7.35644 17 6.59786 17 6C17 5.66136 16.9148 5.35254 16.8485 5.14939C16.7747 4.92296 16.6791 4.69556 16.5823 4.48653C16.3881 4.0677 16.1448 3.62936 15.9296 3.26266C15.2578 2.1179 14.0358 1.5 12.7958 1.5H6.20416ZM6.20416 3C5.45187 3 4.74481 3.37312 4.36402 4.02192C3.95516 4.71856 3.5 5.58686 3.5 6C3.5 6.75 3.875 7.5 5 7.5C5.78879 7.5 6.39323 6.76259 6.68405 6.32183C6.81063 6.13001 7.02018 6 7.25 6C7.47982 6 7.68937 6.13001 7.81595 6.32183C8.10677 6.76259 8.71121 7.5 9.5 7.5C10.2888 7.5 10.8932 6.76259 11.1841 6.32183C11.3106 6.13001 11.5202 6 11.75 6C11.9798 6 12.1894 6.13001 12.3159 6.32183C12.6068 6.76259 13.2112 7.5 14 7.5C15.125 7.5 15.5 6.75 15.5 6C15.5 5.58686 15.0448 4.71856 14.636 4.02192C14.2552 3.37312 13.5481 3 12.7958 3H6.20416Z"
                                            fill="white" />
                                    </svg></a>
                            </li>
                            <li>
                                <a href="{{ $settings['footer_support_link'] }}" class="btn-link"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                        viewBox="0 0 14 14" fill="none">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M11.6658 12.3335C12.034 12.3335 12.3325 12.035 12.3325 11.6668C12.3325 11.2986 12.034 11.0002 11.6658 11.0002C11.2976 11.0002 10.9992 11.2986 10.9992 11.6668C10.9992 12.035 11.2976 12.3335 11.6658 12.3335ZM11.6658 13.6668C12.7704 13.6668 13.6658 12.7714 13.6658 11.6668C13.6658 10.5623 12.7704 9.66682 11.6658 9.66682C10.5613 9.66682 9.66583 10.5623 9.66583 11.6668C9.66583 12.7714 10.5613 13.6668 11.6658 13.6668Z"
                                            fill="#6FD943" />
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M9.3325 1.66682C8.41203 1.66682 7.66583 2.41301 7.66583 3.33349V10.6668C7.66583 12.3237 6.32269 13.6668 4.66583 13.6668C3.00898 13.6668 1.66583 12.3237 1.66583 10.6668V5.66682C1.66583 5.29863 1.96431 5.00015 2.3325 5.00015C2.70069 5.00015 2.99917 5.29863 2.99917 5.66682V10.6668C2.99917 11.5873 3.74536 12.3335 4.66583 12.3335C5.58631 12.3335 6.3325 11.5873 6.3325 10.6668V3.33349C6.3325 1.67663 7.67565 0.333486 9.3325 0.333486C10.9894 0.333486 12.3325 1.67663 12.3325 3.33349V7.66682C12.3325 8.03501 12.034 8.33349 11.6658 8.33349C11.2976 8.33349 10.9992 8.03501 10.9992 7.66682V3.33349C10.9992 2.41301 10.253 1.66682 9.3325 1.66682Z"
                                            fill="#6FD943" />
                                        <path
                                            d="M1.75665 0.653994C2.0139 0.212991 2.6511 0.212992 2.90835 0.653995L4.081 2.66424C4.34025 3.10868 4.01967 3.66682 3.50514 3.66682H1.15986C0.645331 3.66682 0.32475 3.10868 0.584006 2.66424L1.75665 0.653994Z"
                                            fill="#6FD943" />
                                    </svg>{{ __('Support')}}</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="footer-row">
                    <div class="footer-col  about-site">
                        <p>{!! $settings['footer_description'] !!}</p>
                        <p>{!! $settings['all_rights_reserve_text'] !!}<br>
                            <a href="{!! $settings['all_rights_reserve_website_url'] !!}">{!! $settings['all_rights_reserve_website_name'] !!}</a>
                        </p>
                    </div>
                    <!-- Footer section start here -->
                    @if (is_array(json_decode($settings['footer_sections_details'], true)) || is_object(json_decode($settings['footer_sections_details'], true)))
                            @foreach (json_decode($settings['footer_sections_details'], true) as $key => $value)
                                <div class="footer-col footer-link footer-link-1">
                                    <div class="footer-widget">
                                        <h4>{{ $value['footer_section_heading']}}</h4>
                                        <ul>
                                            @if (isset($value['footer_section_text']) || !empty($value['footer_section_text']))
                                                @foreach ($value['footer_section_text'] as $key => $text)
                                                        <li><a href="{{ $text['link'] }}">{{ $text['title'] }}</a></li>
                                                @endforeach
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                    @endif
                    <!-- Footer section start here -->
                    @if ( isset($settings['is_newsletter_enabled']) && $settings['is_newsletter_enabled'] == 'on')
                        <div class="footer-col  footer-subscribe-col">
                            <div class="footer-widget">
                                <h4><b>{!! $settings['joinus_heading'] !!}</b></h4>
                                <p> {!! $settings['joinus_description'] !!} </p>
                                <form method="post" action="{{ route('join_us_store') }}" class="footer-subscribe-form">
                                    @csrf
                                    <div class="input-wrapper">
                                        <input type="email" name="email" placeholder="Type your email address...">
                                        <button type="submit" class="btn-subscibe">{{ __('Join Us!')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </footer>
    @endif
    <!-- Footer end here -->
    <!-- Mobile start here -->
    <div class="mobile-menu-wrapper">
        <div class="menu-close-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="18" viewBox="0 0 20 18">
                <path fill="#24272a"
                    d="M19.95 16.75l-.05-.4-1.2-1-5.2-4.2c-.1-.05-.3-.2-.6-.5l-.7-.55c-.15-.1-.5-.45-1-1.1l-.1-.1c.2-.15.4-.35.6-.55l1.95-1.85 1.1-1c1-1 1.7-1.65 2.1-1.9l.5-.35c.4-.25.65-.45.75-.45.2-.15.45-.35.65-.6s.3-.5.3-.7l-.3-.65c-.55.2-1.2.65-2.05 1.35-.85.75-1.65 1.55-2.5 2.5-.8.9-1.6 1.65-2.4 2.3-.8.65-1.4.95-1.9 1-.15 0-1.5-1.05-4.1-3.2C3.1 2.6 1.45 1.2.7.55L.45.1c-.1.05-.2.15-.3.3C.05.55 0 .7 0 .85l.05.35.05.4 1.2 1 5.2 4.15c.1.05.3.2.6.5l.7.6c.15.1.5.45 1 1.1l.1.1c-.2.15-.4.35-.6.55l-1.95 1.85-1.1 1c-1 1-1.7 1.65-2.1 1.9l-.5.35c-.4.25-.65.45-.75.45-.25.15-.45.35-.65.6-.15.3-.25.55-.25.75l.3.65c.55-.2 1.2-.65 2.05-1.35.85-.75 1.65-1.55 2.5-2.5.8-.9 1.6-1.65 2.4-2.3.8-.65 1.4-.95 1.9-1 .15 0 1.5 1.05 4.1 3.2 2.6 2.15 4.3 3.55 5.05 4.2l.2.45c.1-.05.2-.15.3-.3.1-.15.15-.3.15-.45z">
                </path>
            </svg>
        </div>
        <div class="mobile-menu-bar">
            <ul>
                <li class="mobile-item">
                    <a href="{{  url('/')  }}">{{ $settings['home_title'] }}</a>
                </li>
                <li class="mobile-item">
                    <a href="{{ route('apps.software') }}">{{ __('Add-on')}}</a>
                </li>
                <li class="mobile-item">
                    <a href="{{ route('apps.pricing') }}">{{ __('Pricing')}}</a>
                </li>
                @if (is_array(json_decode($settings['menubar_page'])) || is_object(json_decode($settings['menubar_page'])))
                    @foreach (json_decode($settings['menubar_page']) as $key => $value)
                        @if ($value->header == 'on')
                            <li class="menu-lnk">
                                <a class="nav-link" href="{{ route('custom.page',$value->page_slug) }}">{{ $value->menubar_page_name }}</a>
                            </li>
                        @endif
                    @endforeach
                @endif
                <li class="mobile-item">
                    <a href="{{ (Auth::check()) ? route('home') :route('login') }}">{{ (Auth::check()) ? __('Home') :__('Login') }}<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                        viewBox="0 0 16 16" fill="none">
                        <g clip-path="url(#clip0_14_726)">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M5.88967 10.9856C6.96087 11.2611 7.75238 12.233 7.75238 13.3897C7.75238 14.7607 6.64043 15.8721 5.26877 15.8721C3.89711 15.8721 2.78516 14.7607 2.78516 13.3897C2.78516 12.233 3.57667 11.2611 4.64787 10.9856V10.5959C4.64787 8.7099 6.1768 7.18097 8.06283 7.18097C9.26304 7.18097 10.236 6.20801 10.236 5.00781V3.09158L8.81233 4.51524C8.56985 4.75772 8.17672 4.75772 7.93424 4.51524C7.69176 4.27276 7.69176 3.87963 7.93424 3.63715L10.4179 1.15354C10.6603 0.91106 11.0535 0.91106 11.2959 1.15354L13.7796 3.63715C14.022 3.87962 14.022 4.27276 13.7796 4.51524C13.5371 4.75771 13.1439 4.75772 12.9015 4.51524L11.4778 3.09158V5.00781C11.4778 6.89384 9.94887 8.42278 8.06283 8.42278C6.86263 8.42278 5.88967 9.39573 5.88967 10.5959V10.9856ZM6.51058 13.3897C6.51058 14.0743 5.95517 14.6303 5.26877 14.6303C4.58237 14.6303 4.02696 14.0743 4.02696 13.3897C4.02696 12.7052 4.58237 12.1492 5.26877 12.1492C5.95517 12.1492 6.51058 12.7052 6.51058 13.3897Z"
                                fill="white" />
                        </g>
                        <defs>
                            <clipPath id="clip0_14_726">
                                <rect width="14.9017" height="14.9017" fill="white"
                                    transform="translate(0.921875 0.97168)" />
                            </clipPath>
                        </defs>
                    </svg></a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Mobile start here -->
    <div class="overlay"></div>

    <!--scripts start here-->
    <script src="{{ asset('market_assets/js/jquery.min.js')}}"></script>
    <script src="{{ asset('market_assets/js/slick.min.js')}}" defer="defer"></script>
    @if (admin_setting('site_rtl') == 'on')
        <script src="{{ asset('market_assets/js/custom-rtl.js')}}" defer="defer"></script>
    @else
        <script src="{{ asset('market_assets/js/custom.js')}}" defer="defer"></script>
    @endif
    @if( isset($settings['enable_cookie']) && $settings['enable_cookie'] == 'on')
        @include('layouts.cookie_consent')
    @endif

    <script>
         {{ isset($settings['landingpage_custom_js']) ? $settings['landingpage_custom_js'] : '' }}
    </script>

    @foreach (\Modules\LandingPage\Entities\Pixel::getPixelScript() as $script)
        <?= $script ?>
    @endforeach
</body>
