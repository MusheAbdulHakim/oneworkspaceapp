 <!--Brand Settings-->
 <div id="site-settings" class="">
     {{ Form::open(['route' => ['super.admin.settings.save'], 'enctype' => 'multipart/form-data', 'id' => 'setting-form']) }}
     @method('post')
     <div class="card">
         <div class="card-header">
             <h5>{{ __('Brand Settings') }}</h5>
         </div>
         <div class="card-body pb-0">
             <div class="row">
                 <div class="col-lg-4 col-12 d-flex">
                     <div class="card w-100">
                         <div class="card-header">
                             <h5 class="small-title">{{ __('Logo Dark') }}</h5>
                         </div>
                         <div class="card-body setting-card setting-logo-box p-3">
                             <div class="d-flex flex-column justify-content-between align-items-center h-100">
                                 <div class="logo-content img-fluid logo-set-bg  text-center py-2">
                                     @php
                                         $logo_dark = isset($settings['logo_dark']) ? (check_file($settings['logo_dark']) ? $settings['logo_dark'] : 'uploads/logo/logo_dark.png') : 'uploads/logo/logo_dark.png';
                                     @endphp
                                     <img alt="image" src="{{ get_file($logo_dark) }}{{ '?' . time() }}"
                                         class="small-logo" id="pre_default_logo">
                                 </div>
                                 <div class="choose-files mt-3">
                                     <label for="logo_dark">
                                         <div class=" bg-primary "> <i
                                                 class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                         <input type="file" class="form-control file" name="logo_dark" id="logo_dark"
                                             data-filename="logo_dark"
                                             onchange="document.getElementById('pre_default_logo').src = window.URL.createObjectURL(this.files[0])">
                                     </label>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-4 col-12 d-flex">
                     <div class="card w-100">
                         <div class="card-header">
                             <h5 class="small-title">{{ __('Logo Light') }}</h5>
                         </div>
                         <div class="card-body setting-card setting-logo-box p-3">
                             <div class="d-flex flex-column justify-content-between align-items-center h-100">
                                 <div class="logo-content img-fluid logo-set-bg text-center py-2">
                                     @php
                                         $logo_light = isset($settings['logo_light']) ? (check_file($settings['logo_light']) ? $settings['logo_light'] : 'uploads/logo/logo_light.png') : 'uploads/logo/logo_light.png';
                                     @endphp
                                     <img alt="image" src="{{ get_file($logo_light) }}{{ '?' . time() }}"
                                         class="img_setting small-logo" id="landing_page_logo">
                                 </div>
                                 <div class="choose-files mt-3">
                                     <label for="logo_light">
                                         <div class=" bg-primary "> <i
                                                 class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                         <input type="file" class="form-control file" name="logo_light"
                                             id="logo_light" data-filename="logo_light"
                                             onchange="document.getElementById('landing_page_logo').src = window.URL.createObjectURL(this.files[0])">

                                     </label>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
                 <div class="col-lg-4 col-12 d-flex">
                     <div class="card w-100">
                         <div class="card-header">
                             <h5 class="small-title">{{ __('Favicon') }}</h5>
                         </div>
                         <div class="card-body setting-card setting-logo-box p-3">
                             <div class="d-flex flex-column justify-content-between align-items-center h-100">
                                 <div class="logo-content img-fluid logo-set-bg text-center py-2">
                                     @php
                                         $favicon = isset($settings['favicon']) ? (check_file($settings['favicon']) ? $settings['favicon'] : 'uploads/logo/favicon.png') : 'uploads/logo/favicon.png';
                                     @endphp
                                     <img src="{{ get_file($favicon) }}{{ '?' . time() }}" class="setting-img"
                                         width="40px" id="img_favicon" />
                                 </div>
                                 <div class="choose-files mt-3">
                                     <label for="favicon">
                                         <div class=" bg-primary "> <i
                                                 class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                         <input type="file" class="form-control file" name="favicon" id="favicon"
                                             data-filename="favicon"
                                             onchange="document.getElementById('img_favicon').src = window.URL.createObjectURL(this.files[0])">
                                     </label>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="row">
                 <div class="col-sm-6 col-12">
                     <div class="form-group">
                         <label for="title_text" class="form-label">{{ __('Title Text') }}</label>
                         {{ Form::text('title_text', !empty($settings['title_text']) ? $settings['title_text'] : null, ['class' => 'form-control', 'placeholder' => __('Enter Title Text')]) }}
                     </div>
                 </div>
                 <div class="col-sm-6 col-12">
                     <div class="form-group">
                         <label for="footer_text" class="form-label">{{ __('Footer Text') }}</label>
                         {{ Form::text('footer_text', !empty($settings['footer_text']) ? $settings['footer_text'] : null, ['class' => 'form-control', 'placeholder' => __('Enter Footer Text')]) }}
                     </div>
                 </div>
                 <div class="col-sm-3 col-12">
                     <div class="form-check form-switch mt-2">
                         <input type="checkbox" class="form-check-input" id="landing_page" name="landing_page"
                             {{ isset($settings['landing_page']) && $settings['landing_page'] == 'on' ? 'checked' : '' }} />
                         <label class="form-check-label f-w-600 pl-1"
                             for="landing_page">{{ __('Enable Landing Page') }}</label>

                     </div>
                 </div>
                 <div class="col-sm-3 col-12">
                     <div class="form-check form-switch mt-2">
                         <input type="checkbox" class="form-check-input" id="signup" name="signup"
                             {{ isset($settings['signup']) && $settings['signup'] == 'on' ? 'checked' : '' }} />
                         <label class="form-check-label f-w-600 pl-1" for="signup">{{ __('Enable Signup') }}</label>

                     </div>
                 </div>
                 <div class="col-auto ">
                     <div class="form-check form-switch mt-2">
                         <input type="checkbox" class="form-check-input" id="email_verification"
                             name="email_verification"
                             {{ isset($settings['email_verification']) && $settings['email_verification'] == 'on' ? 'checked' : '' }} />
                         <label class="form-check-label f-w-600 pl-1"
                             for="email_verification">{{ __('Email Verification') }}</label>

                     </div>
                 </div>
                 <div class="row mt-3">
                     <h4 class="small-title">{{ __('Theme Customizer') }}</h4>
                     <div class="setting-card setting-logo-box p-3">
                         <div class="row">
                            <div class="col-xxl-3 col-md-4 col-sm-6 col-12">
                                 <h6 class="">
                                     <i class="ti ti-credit-card me-2 h5"></i>{{ __('Primary color settings') }}
                                 </h6>

                                 <hr class="my-2" />
                                 <div class="color-wrp">
                                     <div class="theme-color themes-color">
                                         <a href="#!"
                                             class="themes-color-change {{ isset($settings['color']) && $settings['color'] == 'theme-1' ? 'active_color' : '' }}"
                                             data-value="theme-1"></a>
                                         <input type="radio" class="theme_color d-none" name="color"
                                             value="theme-1"{{ isset($settings['color']) && $settings['color'] == 'theme-1' ? 'checked' : '' }}>
                                         <a href="#!"
                                             class="themes-color-change {{ isset($settings['color']) && $settings['color'] == 'theme-2' ? 'active_color' : '' }}"
                                             data-value="theme-2"></a>
                                         <input type="radio" class="theme_color d-none" name="color"
                                             value="theme-2"{{ isset($settings['color']) && $settings['color'] == 'theme-2' ? 'checked' : '' }}>
                                         <a href="#!"
                                             class="themes-color-change {{ isset($settings['color']) && $settings['color'] == 'theme-3' ? 'active_color' : '' }}"
                                             data-value="theme-3"></a>
                                         <input type="radio" class="theme_color d-none" name="color"
                                             value="theme-3"{{ isset($settings['color']) && $settings['color'] == 'theme-3' ? 'checked' : '' }}>
                                            
                                         <a href="#!"
                                             class="themes-color-change {{ isset($settings['color']) && $settings['color'] == 'theme-4' ? 'active_color' : '' }}"
                                             data-value="theme-4"></a>
                                         <input type="radio" class="theme_color d-none" name="color"
                                             value="theme-4"{{ isset($settings['color']) && $settings['color'] == 'theme-4' ? 'checked' : '' }}>
                                             <br>
                                         <a href="#!"
                                             class="themes-color-change {{ isset($settings['color']) && $settings['color'] == 'theme-5' ? 'active_color' : '' }}"
                                             data-value="theme-5"></a>
                                         <input type="radio" class="theme_color d-none" name="color"
                                             value="theme-5"{{ isset($settings['color']) && $settings['color'] == 'theme-5' ? 'checked' : '' }}>
                                         
                                         <a href="#!"
                                             class="themes-color-change {{ isset($settings['color']) && $settings['color'] == 'theme-6' ? 'active_color' : '' }}"
                                             data-value="theme-6"></a>
                                         <input type="radio" class="theme_color d-none" name="color"
                                             value="theme-6"{{ isset($settings['color']) && $settings['color'] == 'theme-6' ? 'checked' : '' }}>
                                             
                                         <a href="#!"
                                             class="themes-color-change {{ isset($settings['color']) && $settings['color'] == 'theme-7' ? 'active_color' : '' }}"
                                             data-value="theme-7"></a>
                                         <input type="radio" class="theme_color d-none" name="color"
                                             value="theme-7"{{ isset($settings['color']) && $settings['color'] == 'theme-7' ? 'checked' : '' }}>
                                         <a href="#!"
                                             class="themes-color-change {{ isset($settings['color']) && $settings['color'] == 'theme-8' ? 'active_color' : '' }}"
                                             data-value="theme-8"></a>
                                         <input type="radio" class="theme_color d-none" name="color"
                                             value="theme-8"{{ isset($settings['color']) && $settings['color'] == 'theme-8' ? 'checked' : '' }}>
                                             <br>
                                         <a href="#!"
                                             class="themes-color-change {{ isset($settings['color']) && $settings['color'] == 'theme-9' ? 'active_color' : '' }}"
                                             data-value="theme-9"></a>
                                         <input type="radio" class="theme_color d-none" name="color"
                                             value="theme-9"{{ isset($settings['color']) && $settings['color'] == 'theme-9' ? 'checked' : '' }}>
                                            
                                         <a href="#!"
                                             class="themes-color-change {{ isset($settings['color']) && $settings['color'] == 'theme-10' ? 'active_color' : '' }}"
                                             data-value="theme-10"></a>
                                         <input type="radio" class="theme_color d-none" name="color"
                                             value="theme-10"{{ isset($settings['color']) && $settings['color'] == 'theme-10' ? 'checked' : '' }}>
                                             <div class="color-picker-wrp ">
                                                 <input type="color"
                                                     value="{{ isset($settings['color']) ? $settings['color'] : '' }}"
                                                     class="colorPicker {{ isset($settings['color_flag']) && $settings['color_flag'] == 'true' ? 'active_color' : '' }}"
                                                     name="custom_color" id="color-picker">
                                                 <input type='hidden' name="color_flag"
                                                     value={{ isset($settings['color_flag']) && $settings['color_flag'] == 'true' ? 'true' : 'false' }}>
                                             </div>
                                     </div>
                                 </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 col-12">
                                 <h6>
                                     <i class="ti ti-layout-sidebar me-2 h5"></i> {{ __('Sidebar settings') }}
                                 </h6>
                                 <hr class="my-2" />
                                 <div class="form-check form-switch">
                                     <input type="checkbox" class="form-check-input" id="site_transparent"
                                         name="site_transparent"
                                         {{ isset($settings['site_transparent']) && $settings['site_transparent'] == 'on' ? 'checked' : '' }} />

                                     <label class="form-check-label f-w-600 pl-1"
                                         for="site_transparent">{{ __('Transparent layout') }}</label>
                                 </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 col-12">
                                 <h6 class="">
                                     <i class="ti ti-sun me-2 h5"></i>{{ __('Layout settings') }}
                                 </h6>
                                 <hr class=" my-2 " />
                                 <div class="form-check form-switch mt-2">

                                     <input type="checkbox" class="form-check-input" id="cust-darklayout"
                                         name="cust_darklayout"
                                         {{ isset($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on' ? 'checked' : '' }} />
                                     <label class="form-check-label f-w-600 pl-1"
                                         for="cust-darklayout">{{ __('Dark Layout') }}</label>

                                 </div>
                            </div>
                            <div class="col-xxl-2 col-md-4 col-sm-6 col-12">
                                 <h6 class="">
                                     <i class="ti ti-align-right me-2 h5"></i>{{ __('Enable RTL') }}
                                 </h6>
                                 <hr class=" my-2 " />
                                 <div class="form-check form-switch mt-2">

                                     <input type="checkbox" class="form-check-input" id="site_rtl" name="site_rtl"
                                         {{ isset($settings['site_rtl']) && $settings['site_rtl'] == 'on' ? 'checked' : '' }} />
                                     <label class="form-check-label f-w-600 pl-1"
                                         for="site_rtl">{{ __('RTL Layout') }}</label>

                                 </div>
                            </div>
                            <div class="col-xxl-3 col-md-4 col-sm-6 col-12">
                                <h6 class="">
                                    <i class="ti ti-align-right me-2 h5"></i>{{ __('Category Wise Sidemenu') }}
                                </h6>
                                <hr class=" my-2 " />
                                <div class="form-check form-switch mt-2">

                                    <input type="checkbox" class="form-check-input" id="category_wise_sidemenu" name="category_wise_sidemenu"
                                        {{ isset($settings['category_wise_sidemenu']) && $settings['category_wise_sidemenu'] == 'on' ? 'checked' : '' }} />
                                    <label class="form-check-label f-w-600 pl-1" 
                                        for="category_wise_sidemenu">{{ __('Category Wise Sidemenu') }}</label>

                                </div>
                            </div>
                         </div>
                     </div>
                 </div>
             </div>

         </div>
         <div class="card-footer text-end">
             <input class="btn btn-print-invoice  btn-primary " type="submit" value="{{ __('Save Changes') }}">
         </div>
         {{ Form::close() }}
     </div>
 </div>

 <!--system settings-->
 <div class="row">
     <div class="col-sm-12 col-md-12">
         <div class="card" id="system-settings">
             <div class="card-header">
                 <h5 class="small-title">{{ __('System Settings') }}</h5>
             </div>
             {{ Form::open(['route' => ['super.admin.system.setting.store'], 'id' => 'setting-system-form']) }}
             @method('post')
             <div class="card-body pb-0">
                 <div class="row">
                     <div class="col-6">
                         <div class="form-group col switch-width">
                             {{ Form::label('defult_language', __('Default Language'), ['class' => ' col-form-label']) }}
                             <select class="form-control" data-trigger name="defult_language" id="defult_language"
                                 placeholder="This is a search placeholder">
                                 @foreach (languages() as $key => $language)
                                     <option value="{{ $key }}"
                                         {{ isset($settings['defult_language']) && $settings['defult_language'] == $key ? 'selected' : '' }}>
                                         {{ Str::ucfirst($language) }} </option>
                                 @endforeach
                             </select>
                         </div>
                     </div>
                     <div class="col-sm-6 col-6">
                         <div class="form-group col switch-width">
                             {{ Form::label('defult_timezone', __('Default Timezone'), ['class' => ' col-form-label']) }}
                             {{ Form::select('defult_timezone', $timezones, isset($settings['defult_timezone']) ? $settings['defult_timezone'] : null, ['id' => 'timezone', 'class' => 'form-control choices', 'searchEnabled' => 'true']) }}
                         </div>
                     </div>

                     <div class="col-6">
                         <div class="form-group">
                             <label for="site_date_format" class="form-label">{{ __('Date Format') }}</label>
                             <select type="text" name="site_date_format" class="form-control selectric"
                                 id="site_date_format">
                                 <option value="d-m-Y" @if (isset($settings['site_date_format']) && $settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>
                                     DD-MM-YYYY</option>
                                 <option value="m-d-Y" @if (isset($settings['site_date_format']) && $settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>
                                     MM-DD-YYYY</option>
                                 <option value="Y-m-d" @if (isset($settings['site_date_format']) && $settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>
                                     YYYY-MM-DD</option>
                             </select>
                         </div>
                     </div>
                     <div class="col-6">
                         <div class="form-group">
                             <label for="site_time_format" class="form-label">{{ __('Time Format') }}</label>
                             <select type="text" name="site_time_format" class="form-control selectric"
                                 id="site_time_format">
                                 <option value="g:i A" @if (isset($settings['site_time_format']) && $settings['site_time_format'] == 'g:i A') selected="selected" @endif>
                                     10:30 PM</option>
                                 <option value="H:i" @if (isset($settings['site_time_format']) && $settings['site_time_format'] == 'H:i') selected="selected" @endif>
                                     22:30</option>
                             </select>
                         </div>
                     </div>
                 </div>
             </div>
             <div class="card-footer text-end">
                 <input class="btn btn-print-invoice  btn-primary " type="submit" value="{{ __('Save Changes') }}">
             </div>
             {{ Form::close() }}
         </div>
     </div>
 </div>

 <!--currency settings-->
 <div class="row">
     <div class="col-sm-12 col-md-12">
         <div class="card" id="currency-settings">
             <div class="card-header">
                 <h5 class="small-title">{{ __('Currency Settings') }}</h5>
             </div>
             {{ Form::open(['route' => ['super.admin.currency.settings'], 'method' => 'post', 'id' => 'setting-currency-form']) }}
             <div class="card-body pb-0">
                 <div class="row">
                     <div class="col-6">
                         <div class="form-group col switch-width">
                             {{ Form::label('currency_format', __('Decimal Format'), ['class' => ' col-form-label']) }}
                             <select class="form-control currency_note" data-trigger name="currency_format"
                                 id="currency_format" placeholder="This is a search placeholder">
                                 <option value="0"
                                     {{ isset($settings['currency_format']) && $settings['currency_format'] == '0' ? 'selected' : '' }}>
                                     1</option>
                                 <option value="1"
                                     {{ isset($settings['currency_format']) && $settings['currency_format'] == '1' ? 'selected' : '' }}>
                                     1.0</option>
                                 <option value="2"
                                     {{ isset($settings['currency_format']) && $settings['currency_format'] == '2' ? 'selected' : '' }}>
                                     1.00</option>
                                 <option value="3"
                                     {{ isset($settings['currency_format']) && $settings['currency_format'] == '3' ? 'selected' : '' }}>
                                     1.000</option>
                                 <option value="4"
                                     {{ isset($settings['currency_format']) && $settings['currency_format'] == '4' ? 'selected' : '' }}>
                                     1.0000</option>
                             </select>
                         </div>
                     </div>
                     <div class="col-6">
                         <div class="form-group col switch-width">
                             {{ Form::label('defult_currancy', __('Default Currancy'), ['class' => ' col-form-label']) }}
                             <select class="form-control currency_note" data-trigger name="defult_currancy"
                                 id="defult_currancy" placeholder="This is a search placeholder">
                                 @foreach (currency() as $c)
                                     <option value="{{ $c->symbol }}-{{ $c->code }}"
                                         data-symbol="{{ $c->symbol }}"
                                         {{ isset($settings['defult_currancy']) && $settings['defult_currancy'] == $c->code ? 'selected' : '' }}>
                                         {{ $c->symbol }} - {{ $c->code }} </option>
                                 @endforeach
                             </select>
                         </div>
                     </div>
                     <div class="form-group col-6">
                         <label for="decimal_separator" class="form-label">{{ __('Decimal Separator') }}</label>
                         <select type="text" name="decimal_separator" class="form-control selectric currency_note"
                             id="decimal_separator">
                             <option value="dot" @if (@$settings['decimal_separator'] == 'dot') selected="selected" @endif>
                                 {{ __('Dot') }}</option>
                             <option value="comma" @if (@$settings['decimal_separator'] == 'comma') selected="selected" @endif>
                                 {{ __('Comma') }}</option>
                         </select>
                     </div>
                     <div class="form-group col-6">
                         <label for="thousand_separator" class="form-label">{{ __('Thousands Separator') }}</label>
                         <select type="text" name="thousand_separator"
                             class="form-control selectric currency_note" id="thousand_separator">
                             <option value="dot" @if (@$settings['thousand_separator'] == 'dot') selected="selected" @endif>
                                 {{ __('Dot') }}</option>
                             <option value="comma" @if (@$settings['thousand_separator'] == 'comma') selected="selected" @endif>
                                 {{ __('Comma') }}</option>
                         </select>
                     </div>
                     <div class="form-group col-6">
                         {{ Form::label('currency_space', __('Currency Symbol Space'), ['class' => 'form-label']) }}
                         <div class="row ms-1">
                             <div class="form-check col-md-6">
                                 <input class="form-check-input currency_note" type="radio"
                                     name="currency_space" value="withspace"
                                     @if (!isset($settings['currency_space']) || $settings['currency_space'] == 'withspace') checked @endif id="flexCheckDefault">
                                 <label class="form-check-label" for="flexCheckDefault">
                                     {{ __('With space') }}
                                 </label>
                             </div>
                             <div class="form-check col-6">
                                 <input class="form-check-input currency_note" type="radio"
                                     name="currency_space" value="withoutspace"
                                     @if (!isset($settings['currency_space']) || $settings['currency_space'] == 'withoutspace') checked @endif id="flexCheckChecked">
                                 <label class="form-check-label" for="flexCheckChecked">
                                     {{ __('Without space') }}
                                 </label>
                             </div>
                         </div>
                         @error('currency_space')
                             <span class="invalid-currency_space" role="alert">
                                 <strong class="text-danger">{{ $message }}</strong>
                             </span>
                         @enderror
                     </div>
                     <div class="col-6">
                         <div class="form-group">
                             <label class="form-label"
                                 for="example3cols3Input">{{ __('Currency Symbol Position') }}</label>
                             <div class="row ms-1">
                                 <div class="form-check col-md-6">
                                     <input class="form-check-input currency_note" type="radio"
                                         name="site_currency_symbol_position" value="pre"
                                         @if (!isset($settings['site_currency_symbol_position']) || $settings['site_currency_symbol_position'] == 'pre') checked @endif
                                         id="currencySymbolPosition">
                                     <label class="form-check-label" for="currencySymbolPosition">
                                         {{ __('Pre') }}
                                     </label>
                                 </div>
                                 <div class="form-check col-md-6">
                                     <input class="form-check-input currency_note" type="radio"
                                         name="site_currency_symbol_position" value="post"
                                         @if (isset($settings['site_currency_symbol_position']) && $settings['site_currency_symbol_position'] == 'post') checked @endif id="currencySymbolPost">
                                     <label class="form-check-label" for="currencySymbolPost">
                                         {{ __('Post') }}
                                     </label>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-6">
                         <div class="form-group">
                             <label class="form-label"
                                 for="example3cols3Input">{{ __('Currency Symbol & Name') }}</label>
                             <div class="row ms-1">
                                 <div class="form-check col-md-6">
                                     <input class="form-check-input currency_note" type="radio"
                                         name="site_currency_symbol_name" value="symbol"
                                         @if (!isset($settings['site_currency_symbol_name']) || $settings['site_currency_symbol_name'] == 'symbol') checked @endif id="currencySymbol">
                                     <label class="form-check-label" for="currencySymbol">
                                         {{ __('With Currency Symbol') }}
                                     </label>
                                 </div>
                                 <div class="form-check col-md-6">
                                     <input class="form-check-input currency_note" type="radio"
                                         name="site_currency_symbol_name" value="symbolname"
                                         @if (isset($settings['site_currency_symbol_name']) && $settings['site_currency_symbol_name'] == 'symbolname') checked @endif id="currencySymbolName">
                                     <label class="form-check-label" for="currencySymbolName">
                                         {{ __('With Currency Name') }}
                                     </label>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="col-6">
                        <div class="form-group">
                            <label class="form-label" for="new_note_value">{{ __('Preview :') }}</label>
                            <span id="formatted_price_span"></span>
                        </div>
                    </div>
                 </div>
             </div>
             <div class="card-footer text-end">
                 <input class="btn btn-print-invoice  btn-primary " type="submit"
                     value="{{ __('Save Changes') }}">
             </div>
             {{ Form::close() }}
         </div>
     </div>
 </div>

 {{-- Cookie settings --}}
 <div class="card" id="cookie-sidenav">
     {{ Form::open(['route' => ['cookie.setting.store'], 'method' => 'post']) }}
     <div class="card-header">
         <div class="row">
             <div class="col-lg-10 col-md-10 col-sm-10">
                 <h5 class="">{{ __('Cookie Settings') }}</h5>
             </div>
             <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                 <div class="form-check form-switch custom-switch-v1 float-end">
                     <input type="checkbox" name="enable_cookie" class="form-check-input input-primary"
                         id="enable_cookie"
                         {{ (isset($settings['enable_cookie']) ? $settings['enable_cookie'] : 'off') == 'on' ? ' checked ' : '' }}>
                     <label class="form-check-label" for="enable_cookie"></label>
                 </div>
             </div>
         </div>
     </div>
     <div class="card-body">
         <div class="row ">
             <div class="col-md-6">
                 <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                     <input type="checkbox" name="cookie_logging"
                         class="form-check-input input-primary cookie_setting" id="cookie_logging"
                         {{ (isset($settings['cookie_logging']) ? $settings['cookie_logging'] : 'off') == 'on' ? ' checked ' : '' }}>
                     <label class="form-check-label" for="cookie_logging">{{ __('Enable logging') }}</label>
                     <small
                         class="text-danger">{{ __('After enabling logging, user cookie data will be stored in CSV file.') }}</small>
                 </div>
                 <div class="form-group">
                     {{ Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label']) }}
                     {{ Form::text('cookie_title', !empty($settings['cookie_title']) ? $settings['cookie_title'] : null, ['class' => 'form-control cookie_setting']) }}
                 </div>
                 <div class="form-group ">
                     {{ Form::label('cookie_description', __('Cookie Description'), ['class' => ' form-label']) }}
                     {!! Form::textarea(
                         'cookie_description',
                         !empty($settings['cookie_description']) ? $settings['cookie_description'] : null,
                         ['class' => 'form-control cookie_setting', 'rows' => '3'],
                     ) !!}
                 </div>
             </div>
             <div class="col-md-6">
                 <div class="form-check form-switch custom-switch-v1 ">
                     <input type="checkbox" name="necessary_cookies"
                         class="form-check-input input-primary cookie_setting" id="necessary_cookies" checked
                         onclick="return false">
                     <label class="form-check-label"
                         for="necessary_cookies">{{ __('Strictly necessary cookies') }}</label>
                 </div>
                 <div class="form-group ">
                     {{ Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label']) }}
                     {{ Form::text('strictly_cookie_title', !empty($settings['strictly_cookie_title']) ? $settings['strictly_cookie_title'] : null, ['class' => 'form-control cookie_setting']) }}
                 </div>
                 <div class="form-group ">
                     {{ Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label']) }}
                     {!! Form::textarea(
                         'strictly_cookie_description',
                         !empty($settings['strictly_cookie_description']) ? $settings['strictly_cookie_description'] : null,
                         ['class' => 'form-control cookie_setting ', 'rows' => '3'],
                     ) !!}
                 </div>
             </div>
             <div class="col-12">
                 <h5>{{ __('More Information') }}</h5>
             </div>
             <div class="col-md-6">
                 <div class="form-group">
                     {{ Form::label('more_information_description', __('Contact Us Description'), ['class' => 'col-form-label']) }}
                     {{ Form::text('more_information_description', !empty($settings['more_information_description']) ? $settings['more_information_description'] : null, ['class' => 'form-control cookie_setting']) }}
                 </div>
             </div>
             <div class="col-md-6">
                 <div class="form-group ">
                     {{ Form::label('contactus_url', __('Contact Us URL'), ['class' => 'col-form-label']) }}
                     {{ Form::text('contactus_url', !empty($settings['contactus_url']) ? $settings['contactus_url'] : null, ['class' => 'form-control cookie_setting']) }}
                 </div>
             </div>
         </div>
     </div>
     <div class="card-footer">
         <div class="row">
             <div class="col-6">
                 @if ((isset($settings['cookie_logging']) ? $settings['cookie_logging'] : 'off') == 'on')
                     @if (check_file('uploads/sample/cookie_data.csv'))
                         <label for="file" class="form-label">{{ __('Download cookie accepted data') }}</label>
                         <a href="{{ asset('uploads/sample/cookie_data.csv') }}" class="btn btn-primary mr-3">
                             <i class="ti ti-download"></i>
                         </a>
                     @endif
                 @endif
             </div>
             <div class="col-6 text-end ">
                 <input class="btn btn-print-invoice btn-primary" type="submit" value="{{ __('Save Changes') }}">
             </div>
         </div>
     </div>
     {{ Form::close() }}
 </div>

 <!--Pusher Setting-->
 <div id="pusher-sidenav" class="card">
     <div class="card-header">
         <h5>{{ __('Pusher Settings') }}</h5>
     </div>
     {{ Form::open(['route' => ['pusher.setting'], 'method' => 'post', 'id' => 'pusher-form']) }}
     <div class="card-body">
         <div class="row">
             <div class="col-md-6">
                 <div class="form-group">
                     {{ Form::label('pusher_app_id', __('Pusher App Id'), ['class' => 'form-label']) }}
                     {{ Form::text('pusher_app_id', !empty($settings['PUSHER_APP_ID']) ? $settings['PUSHER_APP_ID'] : null, ['class' => 'form-control font-style', 'required' => 'required', 'placeholder' => 'Enter Pusher App Id']) }}
                 </div>
             </div>
             <div class="col-md-6">
                 <div class="form-group">
                     {{ Form::label('pusher_app_key', __('Pusher App Key'), ['class' => 'form-label']) }}
                     {{ Form::text('pusher_app_key', !empty($settings['PUSHER_APP_KEY']) ? $settings['PUSHER_APP_KEY'] : null, ['class' => 'form-control font-style', 'required' => 'required', 'placeholder' => 'Enter Pusher App Key']) }}
                 </div>
             </div>

         </div>
         <div class="row">
             <div class="col-md-6">
                 <div class="form-group">
                     {{ Form::label('pusher_app_secret', __('Pusher App Secret'), ['class' => 'form-label']) }}
                     {{ Form::text('pusher_app_secret', !empty($settings['PUSHER_APP_SECRET']) ? $settings['PUSHER_APP_SECRET'] : null, ['class' => 'form-control font-style', 'required' => 'required', 'placeholder' => 'Enter Pusher App Secret']) }}
                 </div>
             </div>
             <div class="col-md-6">
                 <div class="form-group">
                     {{ Form::label('pusher_app_cluster', __('Pusher App Cluster'), ['class' => 'form-label']) }}
                     {{ Form::text('pusher_app_cluster', !empty($settings['PUSHER_APP_CLUSTER']) ? $settings['PUSHER_APP_CLUSTER'] : null, ['class' => 'form-control font-style', 'required' => 'required', 'placeholder' => 'Enter Pusher App Cluster']) }}
                 </div>
             </div>
         </div>
     </div>
     <div class="card-footer text-end">
         <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
     </div>

     {{ Form::close() }}
 </div>
 {{-- SEO settings --}}

 <div id="seo-sidenav" class="card">
     <div class="card-header">
         <div class="row">
             <div class="col-lg-10 col-md-10 col-sm-10">
                 <h5>{{ __('SEO Settings') }}</h5>
             </div>
         </div>
     </div>
     {{ Form::open(['url' => route('seo.setting.save'), 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
     @csrf
     <div class="card-body">
         <div class="row">
             <div class="col-md-7">
                 <div class="form-group">
                     {{ Form::label('meta_title', __('Meta Title'), ['class' => 'col-form-label']) }}
                     {{ Form::text('meta_title', !empty($settings['meta_title']) ? $settings['meta_title'] : null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Meta Title']) }}
                 </div>
                 <div class="form-group">
                     {{ Form::label('meta_keywords', __('Meta Keywords'), ['class' => 'col-form-label']) }}
                     {{ Form::textarea('meta_keywords', !empty($settings['meta_keywords']) ? $settings['meta_keywords'] : null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Meta Keywords', 'rows' => 2]) }}
                 </div>
                 <div class="form-group">
                     {{ Form::label('meta_description', __('Meta Description'), ['class' => 'col-form-label']) }}
                     {{ Form::textarea('meta_description', !empty($settings['meta_description']) ? $settings['meta_description'] : null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Meta Description', 'rows' => 3]) }}
                 </div>
             </div>
             <div class="col-md-5">
                 <div class="form-group mb-0">
                     {{ Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label']) }}
                 </div>
                 <div class="setting-card">
                     <div class="logo-content">
                         <img id="image2"
                             src="{{ get_file(!empty($settings['meta_image']) ? (check_file($settings['meta_image']) ? $settings['meta_image'] : 'uploads/meta/meta_image.png') : 'uploads/meta/meta_image.png') }}{{ '?' . time() }}"
                             class="img_setting seo_image">
                     </div>
                     <div class="choose-files mt-4">
                         <label for="meta_image">
                             <div class="bg-primary company_favicon_update"> <i
                                     class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                             </div>
                             <input type="file" class="form-control file"
                                 accept="image/png, image/gif, image/jpeg,image/jpg" id="meta_image"
                                 name="meta_image"
                                 onchange="document.getElementById('image2').src = window.URL.createObjectURL(this.files[0])"
                                 data-filename="meta_image">
                         </label>
                     </div>
                     @error('meta_image')
                         <div class="row">
                             <span class="invalid-logo" role="alert">
                                 <strong class="text-danger">{{ $message }}</strong>
                             </span>
                         </div>
                     @enderror
                 </div>
             </div>
         </div>
     </div>
     <div class="card-footer text-end">
         <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
     </div>
     {{ Form::close() }}
 </div>

 {{-- Cache settings --}}
 <div class="card" id="cache-sidenav">
     <div class="card-header">
         <h5>{{ 'Cache Settings' }}</h5>
         <small class="text-secondary font-weight-bold">
             {{ __("This is a page meant for more advanced users, simply ignore it if you don't understand what cache is.") }}
         </small>
     </div>
     <form method="GET" action="{{ route('config.cache') }}" accept-charset="UTF-8">
         <div class="card-body">
             <div class="row">
                 <div class="col-12 form-group">
                     {{ Form::label('Current cache size', __('Current cache size'), ['class' => 'col-form-label']) }}
                     <div class="input-group">
                         <input type="text" class="form-control" value="{{ CacheSize() }}" readonly>
                         <div class="input-group-append">
                             <span class="input-group-text">{{ __('MB') }}</span>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
         <div class="card-footer text-end">
             <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                 value="{{ __('Cache Clear') }}">
         </div>
         {{ Form::close() }}
 </div>

 {{-- storage setting --}}
 <div class="card" id="storage-sidenav">
     {{ Form::open(['route' => 'storage.setting.store', 'enctype' => 'multipart/form-data']) }}
     <div class="card-header">
         <div class="row">
             <div class="col-lg-10 col-md-10 col-sm-10">
                 <h5 class="">{{ __('Storage Settings') }}</h5>
             </div>
         </div>
     </div>
     <div class="card-body">
         <div class="d-flex">
             <div class="pe-2">
                 <input type="radio" class="btn-check" name="storage_setting" id="local-outlined"
                     autocomplete="off"
                     {{ isset($settings['storage_setting']) && $settings['storage_setting'] == 'local' ? 'checked' : '' }}
                     value="local">
                 <label class="btn btn-outline-primary" for="local-outlined">{{ __('Local') }}</label>
             </div>
             <div class="pe-2">
                 <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined" autocomplete="off"
                     {{ isset($settings['storage_setting']) && $settings['storage_setting'] == 's3' ? 'checked' : '' }}
                     value="s3">
                 <label class="btn btn-outline-primary" for="s3-outlined"> {{ __('AWS S3') }}</label>
             </div>

             <div class="pe-2">
                 <input type="radio" class="btn-check" name="storage_setting" id="wasabi-outlined"
                     autocomplete="off"
                     {{ isset($settings['storage_setting']) && $settings['storage_setting'] == 'wasabi' ? 'checked' : '' }}
                     value="wasabi">
                 <label class="btn btn-outline-primary" for="wasabi-outlined">{{ __('Wasabi') }}</label>
             </div>
         </div>
         <hr class="mt-4">
         <div
             class="local-setting row {{ isset($settings['storage_setting']) && $settings['storage_setting'] == 'local' ? ' ' : 'd-none' }}">
             <h4 class="small-title">{{ __('Local Settings') }}</h4>
             <div class="form-group col-12 switch-width">
                 {{ Form::label('local_storage_validation', __('Only Upload Files'), ['class' => ' col-form-label']) }}
                 {{ Form::select('local_storage_validation[]', array_flip($file_type), isset($settings['local_storage_validation']) ? explode(',', $settings['local_storage_validation']) : null, ['id' => 'local_storage_validation', 'class' => ' choices', 'multiple' => '', 'searchEnabled' => 'true']) }}
             </div>
             <div class="col-lg-4">
                 <div class="form-group">
                     <label class="form-label"
                         for="local_storage_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                     <input type="number" name="local_storage_max_upload_size" class="form-control"
                         value="{{ isset($settings['local_storage_max_upload_size']) ? $settings['local_storage_max_upload_size'] : 2024 }}"
                         placeholder="{{ __('Max upload size') }}">
                 </div>
             </div>
         </div>
         <div
             class="s3-setting row {{ isset($settings['storage_setting']) && $settings['storage_setting'] == 's3' ? ' ' : 'd-none' }}">
             <h4 class="small-title mb-3">{{ __('AWS S3 Settings') }}</h4>

             <div class=" row ">
                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="s3_key">{{ __('S3 Key') }}</label>
                         <input type="text" name="s3_key" class="form-control"
                             value="{{ isset($settings['s3_key']) ? $settings['s3_key'] : null }}"
                             placeholder="{{ __('S3 Key') }}">
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="s3_secret">{{ __('S3 Secret') }}</label>
                         <input type="text" name="s3_secret" class="form-control"
                             value="{{ isset($settings['s3_secret']) ? $settings['s3_secret'] : null }}"
                             placeholder="{{ __('S3 Secret') }}">
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="s3_region">{{ __('S3 Region') }}</label>
                         <input type="text" name="s3_region" class="form-control"
                             value="{{ isset($settings['s3_region']) ? $settings['s3_region'] : null }}"
                             placeholder="{{ __('S3 Region') }}">
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="s3_bucket">{{ __('S3 Bucket') }}</label>
                         <input type="text" name="s3_bucket" class="form-control"
                             value="{{ isset($settings['s3_bucket']) ? $settings['s3_bucket'] : null }}"
                             placeholder="{{ __('S3 Bucket') }}">
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="s3_url">{{ __('S3 URL') }}</label>
                         <input type="text" name="s3_url" class="form-control"
                             value="{{ isset($settings['s3_url']) ? $settings['s3_url'] : null }}"
                             placeholder="{{ __('S3 URL') }}">
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="s3_endpoint">{{ __('S3 Endpoint') }}</label>
                         <input type="text" name="s3_endpoint" class="form-control"
                             value="{{ isset($settings['s3_endpoint']) ? $settings['s3_endpoint'] : null }}"
                             placeholder="{{ __('S3 Endpoint') }}">
                     </div>
                 </div>
                 <div class="col-lg-4">
                     <div class="form-group">
                         <label class="form-label"
                             for="s3_max_upload_size">{{ __('Max upload size ( In KB)') }}</label>
                         <input type="number" name="s3_max_upload_size" class="form-control"
                             value="{{ isset($settings['s3_max_upload_size']) ? $settings['s3_max_upload_size'] : 2024 }}"
                             placeholder="{{ __('Max upload size') }}">
                     </div>
                 </div>
             </div>
             <div class="form-group col-12 switch-width">
                 {{ Form::label('s3_storage_validation', __('Only Upload Files'), ['class' => ' col-form-label']) }}
                 {{ Form::select('s3_storage_validation[]', array_flip($file_type), isset($settings['s3_storage_validation']) ? explode(',', $settings['s3_storage_validation']) : null, ['id' => 's3_storage_validation', 'class' => ' choices', 'multiple' => '']) }}
             </div>
         </div>

         <div
             class="wasabi-setting row {{ isset($settings['storage_setting']) && $settings['storage_setting'] == 'wasabi' ? ' ' : 'd-none' }}">
             <h4 class="small-title mb-3">{{ __('Wasabi Settings') }}</h4>
             <div class=" row ">

                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="s3_key">{{ __('Wasabi Key') }}</label>
                         <input type="text" name="wasabi_key" class="form-control"
                             value="{{ isset($settings['wasabi_key']) ? $settings['wasabi_key'] : null }}"
                             placeholder="{{ __('Wasabi Key') }}">
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="s3_secret">{{ __('Wasabi Secret') }}</label>
                         <input type="text" name="wasabi_secret" class="form-control"
                             value="{{ isset($settings['wasabi_secret']) ? $settings['wasabi_secret'] : null }}"
                             placeholder="{{ __('Wasabi Secret') }}">
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="s3_region">{{ __('Wasabi Region') }}</label>
                         <input type="text" name="wasabi_region" class="form-control"
                             value="{{ isset($settings['wasabi_region']) ? $settings['wasabi_region'] : null }}"
                             placeholder="{{ __('Wasabi Region') }}">
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="wasabi_bucket">{{ __('Wasabi Bucket') }}</label>
                         <input type="text" name="wasabi_bucket" class="form-control"
                             value="{{ isset($settings['wasabi_bucket']) ? $settings['wasabi_bucket'] : null }}"
                             placeholder="{{ __('Wasabi Bucket') }}">
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="wasabi_url">{{ __('Wasabi URL') }}</label>
                         <input type="text" name="wasabi_url" class="form-control"
                             value="{{ isset($settings['wasabi_url']) ? $settings['wasabi_url'] : null }}"
                             placeholder="{{ __('Wasabi URL') }}">
                     </div>
                 </div>
                 <div class="col-lg-6">
                     <div class="form-group">
                         <label class="form-label" for="wasabi_root">{{ __('Wasabi Root') }}</label>
                         <input type="text" name="wasabi_root" class="form-control"
                             value="{{ isset($settings['wasabi_root']) ? $settings['wasabi_root'] : null }}"
                             placeholder="{{ __('Wasabi Sub Folder') }}">
                         <small
                             class="text-danger">{{ __('If a folder has been created under the bucket then enter the folder name otherwise blank') }}
                         </small>
                     </div>
                 </div>
                 <div class="col-lg-4">
                     <div class="form-group">
                         <label class="form-label" for="wasabi_root">{{ __('Max upload size ( In KB)') }}</label>
                         <input type="number" name="wasabi_max_upload_size" class="form-control"
                             value="{{ isset($settings['wasabi_max_upload_size']) ? $settings['wasabi_max_upload_size'] : 2024 }}"
                             placeholder="{{ __('Max upload size') }}">
                     </div>
                 </div>
                 <div class="form-group col-12 switch-width">
                     {{ Form::label('wasabi_storage_validation', __('Only Upload Files'), ['class' => ' col-form-label']) }}
                     {{ Form::select('wasabi_storage_validation[]', array_flip($file_type), isset($settings['wasabi_storage_validation']) ? explode(',', $settings['wasabi_storage_validation']) : null, ['id' => 'wasabi_storage_validation', 'class' => ' choices', 'multiple' => '']) }}
                 </div>
             </div>

         </div>

     </div>
     <div class="card-footer text-end">
         <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
     </div>
     {{ Form::close() }}

 </div>

 {{-- GPT Key setting --}}
 <div class="card" id="chat-gpt-setting-sidenav">
     {{ Form::open(['route' => 'ai.key.setting.save']) }}
     <div class="card-header">
         <div class="row">
             <div class="col-lg-10 col-md-10 col-sm-10">
                 <h5>{{ __('Chat GPT Key Settings') }}</h5>
                 <small class="text-muted">{{ __('Edit your key details') }}</small>
             </div>
         </div>
     </div>
     <div class="card-body">
         <div class="row mt-2">
             <div class="form-group">
                 <div class="field_wrapper">
                     @if (count($ai_key_settings) > 0)
                         <?php $i = 1; ?>
                         @foreach ($ai_key_settings as $key_data)
                             <div class="d-flex gap-1 mb-4">
                                 <input type="text" class="form-control" name="api_key[]"
                                     value="{{ $key_data->key }}" />
                                 @if ($i == 1)
                                     <a href="javascript:void(0);" class="add_button btn btn-primary"
                                         title="Add field"><i class="ti ti-plus"></i></a>
                                 @else
                                     <a href="javascript:void(0);" class="remove_button btn btn-danger"><i
                                             class="ti ti-trash"></i></a>
                                 @endif
                             </div>
                             <?php $i++; ?>
                         @endforeach
                     @else
                         <div class="d-flex gap-1 mb-4">
                             <input type="text" class="form-control " name="api_key[]" value="" />

                             <a href="javascript:void(0);" class="add_button btn btn-primary" title="Add field"><i
                                     class="ti ti-plus"></i></a>

                         </div>
                     @endif
                 </div>
             </div>
             <div class="form-group">
                 {{ Form::label('chatgpt_model', __('Chatgpt Model'), ['class' => 'col-form-label']) }}
                 {{ Form::text('chatgpt_model', isset($settings['chatgpt_model']) ? $settings['chatgpt_model'] : '', ['class' => 'form-control', 'placeholder' => 'Enter Chatgpt Model name']) }}
             </div>
         </div>
     </div>
     <div class="card-footer text-end">
         <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
     </div>
     {{ Form::close() }}
 </div>

 <script>
     $(document).ready(function() {
         choices();
     });
     $(document).on('change', '[name=storage_setting]', function() {
         if ($(this).val() == 's3') {
             $('.s3-setting').removeClass('d-none');
             $('.wasabi-setting').addClass('d-none');
             $('.local-setting').addClass('d-none');
         } else if ($(this).val() == 'wasabi') {
             $('.s3-setting').addClass('d-none');
             $('.wasabi-setting').removeClass('d-none');
             $('.local-setting').addClass('d-none');
         } else {
             $('.s3-setting').addClass('d-none');
             $('.wasabi-setting').addClass('d-none');
             $('.local-setting').removeClass('d-none');
         }
     });

     function check_theme(color_val) {
         $('input[value="' + color_val + '"]').prop('checked', true);
         $('a[data-value]').removeClass('active_color');
         $('a[data-value="' + color_val + '"]').addClass('active_color');
     }
     var themescolors = document.querySelectorAll(".themes-color > a");
     for (var h = 0; h < themescolors.length; h++) {
         var c = themescolors[h];

         c.addEventListener("click", function(event) {
             var targetElement = event.target;
             if (targetElement.tagName == "SPAN") {
                 targetElement = targetElement.parentNode;
             }
             var temp = targetElement.getAttribute("data-value");
             removeClassByPrefix(document.querySelector("body"), "theme-");
             document.querySelector("body").classList.add(temp);
         });
     }

     function removeClassByPrefix(node, prefix) {
         for (let i = 0; i < node.classList.length; i++) {
             let value = node.classList[i];
             if (value.startsWith(prefix)) {
                 node.classList.remove(value);
             }
         }
     }
     if ($('#useradd-sidenav').length > 0) {
         var scrollSpy = new bootstrap.ScrollSpy(document.body, {
             target: '#useradd-sidenav',
             offset: 300,
         });
     }
     $(document).on('change', '#defult_currancy', function() {
         var sy = $('#defult_currancy option:selected').attr('data-symbol');
         $('#defult_currancy_symbol').val(sy);

     });
 </script>

 {{-- Dark Mod --}}
 <script>
     var custdarklayout = document.querySelector("#cust-darklayout");
     custdarklayout.addEventListener("click", function() {
         if (custdarklayout.checked) {
             document.querySelector(".m-header > .b-brand > .logo-lg").setAttribute("src",
                 "{{ $logo_light }}");
             document.querySelector("#main-style-link").setAttribute("href",
                 "{{ asset('assets/css/style-dark.css') }}");
         } else {
             document.querySelector(".m-header > .b-brand > .logo-lg").setAttribute("src",
                 "{{ $logo_dark }}");
             document.querySelector("#main-style-link").setAttribute("href",
                 "{{ asset('assets/css/style.css') }}");
         }
     });

     function removeClassByPrefix(node, prefix) {
         for (let i = 0; i < node.classList.length; i++) {
             let value = node.classList[i];
             if (value.startsWith(prefix)) {
                 node.classList.remove(value);
             }
         }
     }
 </script>

 {{-- cookie setting --}}
 @if (isset($settings['enable_cookie']) && $settings['enable_cookie'] != 'on')
     <script>
         $(document).ready(function() {
             $('.cookie_setting').attr("disabled", "disabled");
         });
     </script>
 @endif
 <script>
     $(document).on('click', '#enable_cookie', function() {
         if ($('#enable_cookie').prop('checked')) {
             $(".cookie_setting").removeAttr("disabled");
         } else {
             $('.cookie_setting').attr("disabled", "disabled");
         }
     });
 </script>
 <script>
     function cust_theme_bg(params) {
         var custthemebg = document.querySelector("#site_transparent");
         var val = "checked";
         if (val) {
             document.querySelector(".dash-sidebar").classList.add("transprent-bg");
             document
                 .querySelector(".dash-header:not(.dash-mob-header)")
                 .classList.add("transprent-bg");
         } else {
             document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
             document
                 .querySelector(".dash-header:not(.dash-mob-header)")
                 .classList.remove("transprent-bg");
         }
     }
     if ($('#site_transparent').length > 0) {
         var custthemebg = document.querySelector("#site_transparent");
         custthemebg.addEventListener("click", function() {
             if (custthemebg.checked) {
                 document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                 document
                     .querySelector(".dash-header:not(.dash-mob-header)")
                     .classList.add("transprent-bg");
             } else {
                 document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                 document
                     .querySelector(".dash-header:not(.dash-mob-header)")
                     .classList.remove("transprent-bg");
             }
         });
     }
 </script>

 {{-- theme color --}}
 <script>
     $('.colorPicker').on('click', function(e) {
         $('body').removeClass('custom-color');
         if (/^theme-\d+$/) {
             $('body').removeClassRegex(/^theme-\d+$/);
         }
         $('body').addClass('custom-color');
         $('.themes-color-change').removeClass('active_color');
         $(this).addClass('active_color');
         const input = document.getElementById("color-picker");
         setColor();
         input.addEventListener("input", setColor);

         function setColor() {
             document.documentElement.style.setProperty('--color-customColor', input.value);
         }

         $(`input[name='color_flag`).val('true');
     });

     $('.themes-color-change').on('click', function() {

         $(`input[name='color_flag`).val('false');

         var color_val = $(this).data('value');
         $('body').removeClass('custom-color');
         if (/^theme-\d+$/) {
             $('body').removeClassRegex(/^theme-\d+$/);
         }
         $('body').addClass(color_val);
         $('.theme-color').prop('checked', false);
         $('.themes-color-change').removeClass('active_color');
         $('.colorPicker').removeClass('active_color');
         $(this).addClass('active_color');
         $(`input[value=${color_val}]`).prop('checked', true);
     });

     $.fn.removeClassRegex = function(regex) {
         return $(this).removeClass(function(index, classes) {
             return classes.split(/\s+/).filter(function(c) {
                 return regex.test(c);
             }).join(' ');
         });
     };
 </script>
 <script>
     $(document).ready(function() {
        sendData();

         $('.currency_note').on('change', function() {
             sendData();
         });

         function sendData() {
             var formData = $('#setting-currency-form').serialize();
             $.ajax({
                 type: 'POST',
                 url: '{{ route('admin.update.note.value') }}',
                 data: formData,
                 success: function(response) {
                     var formattedPrice = response.formatted_price;
                      $('#formatted_price_span').text(formattedPrice);
                 }
             });
         }
     });
 </script>

<script>
    $(document).ready(function() {
        var maxField = 100; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.field_wrapper'); //Input field wrapper
        var fieldHTML =
            '<div class="d-flex gap-1 mb-4"><input type="text" class="form-control " name="api_key[]" value=""/><a href="javascript:void(0);" class="remove_button btn btn-danger"><i class="ti ti-trash"></i></a></div>'; //New input field html
        var x = 1; //Initial field counter is 1

        //Once add button is clicked
        $(addButton).click(function() {
            //Check maximum number of input fields
            if (x < maxField) {
                x++; //Increment field counter
                $(wrapper).append(fieldHTML); //Add field html
            }
        });

        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e) {
            e.preventDefault();
            $(this).parent('div').remove(); //Remove field html
            x--; //Decrement field counter
        });
    });
</script>
