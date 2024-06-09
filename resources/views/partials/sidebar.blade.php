<nav class="dash-sidebar light-sidebar {{ empty($company_settings['site_transparent']) || $company_settings['site_transparent'] == 'on' ? 'transprent-bg' : '' }}">
    <div class="navbar-wrapper">
        <div class="m-header main-logo">
            <a href="{{ route('home') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="{{ get_file(sidebar_logo()) }}{{ '?' . time() }}" alt="" class="logo logo-lg" />
                {{-- <img src="{{ get_file(sidebar_logo()) }}{{ '?' . time() }}" alt="" class="logo logo-sm" /> --}}
            </a>
        </div>
        @if(!empty($company_settings['category_wise_sidemenu']) && $company_settings['category_wise_sidemenu'] == 'on')
          <div class="tab-container">
            <div class="tab-sidemenu">
              <ul class="dash-tab-link nav flex-column" role="tablist" id="dash-layout-submenus">
              </ul>
            </div>
            <div class="tab-link">
              <div class="navbar-content">
                <div class="tab-content" id="dash-layout-tab">
                </div>
                <ul class="dash-navbar">
                    {!! getMenu() !!}
                    @stack('custom_side_menu')
                </ul>
              </div>
            </div>
          </div>
        @else
          <div class="navbar-content">
              <ul class="dash-navbar">
                  {!! getMenu() !!}
                  @stack('custom_side_menu')
              </ul>
          </div>
        @endif  
        
    </div>
</nav>
