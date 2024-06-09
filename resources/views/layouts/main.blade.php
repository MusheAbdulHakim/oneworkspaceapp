@php
    $admin_settings = getAdminAllSetting();

    $company_settings = getCompanyAllSetting(creatorId());

    $color = !empty($company_settings['color']) ? $company_settings['color'] : 'theme-1';
      if (isset($company_settings['color_flag']) && $company_settings['color_flag'] == 'true') {
          $themeColor = 'custom-color';
      } else {
          $themeColor = $color;
      }
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ isset($company_settings['site_rtl']) && $company_settings['site_rtl'] == 'on' ? 'rtl' : '' }}">
<html lang="en">

@include('partials.head')
<body class="{{ isset($themeColor) ? $themeColor : 'theme-1' }}">
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill">

            </div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ auth-signup ] end -->
    @include('partials.sidebar')
    @include('partials.header')
    <section class="dash-container">
        <div class="dash-content">
            <!-- [ breadcrumb ] start -->
            <div class="page-header">
                <div class="page-block">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-auto">
                            <div class="page-header-title">
                                <h4 class="m-b-10">@yield('page-title')</h4>
                            </div>
                            <ul class="breadcrumb">
                                @php
                                    if (isset(app()->view->getSections()['page-breadcrumb'])) {
                                        $breadcrumb = explode(',', app()->view->getSections()['page-breadcrumb']);
                                    } else {
                                        $breadcrumb = [];
                                    }
                                @endphp
                                @if (!empty($breadcrumb))
                                    <li class="breadcrumb-item"><a
                                            href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
                                    @foreach ($breadcrumb as $item)
                                        <li class="breadcrumb-item {{ $loop->last ? 'active' : '' }}">
                                            {{ $item }}</li>
                                    @endforeach
                                @endif

                            </ul>
                        </div>
                        <div class="col-auto row">
                            @yield('page-action')
                        </div>
                    </div>
                </div>
            </div>
            @yield('content')
        </div>
    </section>
@include('partials.footer')
