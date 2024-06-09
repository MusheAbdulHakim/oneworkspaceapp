@php
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
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

    <meta name="title" content="{{ !empty(admin_setting('meta_title')) ? admin_setting('meta_title') : 'WOrkdo Dash' }}">
    <meta name="keywords" content="{{ !empty(admin_setting('meta_keywords')) ? admin_setting('meta_keywords') : 'WorkDo Dash,SaaS solution,Multi-workspace' }}">
    <meta name="description" content="{{ !empty(admin_setting('meta_description')) ? admin_setting('meta_description') : 'Discover the efficiency of Dash, a user-friendly web application by Rajodiya Apps.'}}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ env('APP_URL') }}">
    <meta property="og:title" content="{{ !empty(admin_setting('meta_title')) ? admin_setting('meta_title') : 'WOrkdo Dash' }}">
    <meta property="og:description" content="{{ !empty(admin_setting('meta_description')) ? admin_setting('meta_description') : 'Discover the efficiency of Dash, a user-friendly web application by Rajodiya Apps.'}} ">
    <meta property="og:image" content="{{ get_file( (!empty(admin_setting('meta_image'))) ? (check_file(admin_setting('meta_image'))) ?  admin_setting('meta_image') : 'uploads/meta/meta_image.png' : 'uploads/meta/meta_image.png'  ) }}{{'?'.time() }}">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ env('APP_URL') }}">
    <meta property="twitter:title" content="{{ !empty(admin_setting('meta_title')) ? admin_setting('meta_title') : 'WOrkdo Dash' }}">
    <meta property="twitter:description" content="{{ !empty(admin_setting('meta_description')) ? admin_setting('meta_description') : 'Discover the efficiency of Dash, a user-friendly web application by Rajodiya Apps.'}} ">
    <meta property="twitter:image" content="{{ get_file( (!empty(admin_setting('meta_image'))) ? (check_file(admin_setting('meta_image'))) ?  admin_setting('meta_image') : 'uploads/meta/meta_image.png' : 'uploads/meta/meta_image.png'  ) }}{{'?'.time() }}">

    <meta name="author" content="Workdo.io">

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0">

    <link rel="icon" href="{{ (!empty(admin_setting('favicon')) && check_file(admin_setting('favicon'))) ? get_file(admin_setting('favicon')) : get_file('uploads/logo/favicon.png')}}{{'?'.time()}}" type="image/x-icon" />


    <link
        href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800&display=swap"
        rel="stylesheet">

    <style>
        :root {
            --color-customColor: <?= $color ?>;
        }
    </style>
    
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
        <link rel="stylesheet" href="{{ asset('market_assets/css/main-style.css') }}">
        <link rel="stylesheet" href="{{ asset('market_assets/css/responsive.css') }}">
    @endif

    @if(admin_setting('cust_darklayout') == 'on')
        <link rel="stylesheet" href="{{ asset('market_assets/css/main-style-dark.css') }}" id="main-style-link">
    @endif

    <link rel="stylesheet" href="{{ asset('market_assets/css/magnific-popup.css') }}">
</head>

<body class="{{ !empty($themeColor)?$themeColor:'theme-1' }}">
     <!-- header start here -->
     <header class="site-header header-style-one">
        @if ($settings['topbar_status'] == 'on')
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

    <!-- wrapper start -->
         @yield('content')
    <!-- wrapper end -->

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
                            {{-- <form method="post" action="{{ route('join_us_store') }}">
                                @csrf
                                <div class="input-wrapper border border-dark">
                                    <input type="text" name="email" placeholder="Type your email address...">
                                    <button type="submit" class="btn btn-dark rounded-pill">Join Us!</button>
                                </div>
                            </form> --}}
                        </div>
                    </div>
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
    @if(admin_setting('enable_cookie') == 'on')
        @include('layouts.cookie_consent')
    @endif

    <script src="{{ asset('market_assets/js/magnific-popup.min.js')}}" defer="defer"></script>
    <script>
        $(document).ready(function(){
            $('.img-zoom').magnificPopup({
                disableOn: 300,
                type: 'image',
                mainClass: 'mfp-fade',
                removalDelay: 160
            });
    });
    </script>

    @stack('scripts')

</body>

</html>
