    @extends('layouts.auth')
    @section('page-title')
        {{ __('Register') }}
    @endsection
    @section('language-bar')
        <div class="lang-dropdown-only-desk">
            <li class="dropdown dash-h-item drp-language">
                <a class="dash-head-link dropdown-toggle btn" href="#" data-bs-toggle="dropdown" aria-expanded="false">
                    <span class="drp-text"> {{ Str::upper($lang) }}
                    </span>
                </a>
                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end">
                    @foreach (languages() as $key => $language)
                        <a href="{{ route('register', [$ref ,$key]) }}"
                            class="dropdown-item @if ($lang == $key) text-primary @endif">
                            <span>{{ Str::ucfirst($language) }}</span>
                        </a>
                    @endforeach
                </div>
            </li>
        </div>
    @endsection
    @php
        $admin_settings = getAdminAllSetting();
        $setting = \Modules\LandingPage\Entities\LandingPageSetting::settings();
    @endphp

    @section('content')
        <div class="card">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="card-body">
                    <div class="">
                        <h2 class="mb-3 f-w-600">{{ __('Register') }}</h2>
                    </div>
                    <div class="">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                                name="name" placeholder="Enter Name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            @error('name')
                                <span class="error invalid-name text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('WorkSpace Name') }}</label>
                            <input id="store_name" type="text" class="form-control @error('store_name') is-invalid @enderror"
                                name="workspace" placeholder="Enter WorkSpace Name" value="{{ old('store_name') }}" required autocomplete="store_name">
                            @error('workspace')
                                <span class="error invalid-name text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <input type="hidden" name = "type" value="register" id="type">
                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Email') }}</label>
                            <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror"
                                name="email" placeholder="Enter Email" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="error invalid-email text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">{{ __('Password') }}</label>
                            <input id="password" type="password" class="form-control  @error('password') is-invalid @enderror"
                                name="password" placeholder="Enter Password" required autocomplete="new-password">
                            @error('password')
                                <span class="error invalid-password text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label class="form-label">{{ __('Confirm password') }}</label>
                            <input id="password-confirm" type="password"
                                class="form-control @error('password_confirmation') is-invalid @enderror"
                                name="password_confirmation" placeholder="Enter Confirm password" required autocomplete="new-password">
                            @error('password_confirmation')
                                <span class="error invalid-password_confirmation text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-check ">
                            <input type="checkbox" class="form-check-input" id="termsCheckbox" name="terms" required>
                            <label class="form-check-label text-sm" for="termsCheckbox">{{ __('I agree to the ') }}
                                @if (is_array(json_decode($setting['menubar_page'])) || is_object(json_decode($setting['menubar_page'])))
                                    @foreach (json_decode($setting['menubar_page']) as $key => $value)
                                        @if (in_array($value->page_slug, ['terms_and_conditions']) && isset($value->template_name))
                                            @if(module_is_active('LandingPage'))
                                                <a href="{{ $value->template_name == 'page_content' ? route('custom.page', $value->page_slug) : $value->page_url }}"
                                                    target="_blank">{{ $value->menubar_page_name }}</a>
                                            @else
                                                <a href="{{ route('custompage', ['page'=>'terms_and_conditions']) }}"
                                                target="_blank">{{ __('Terms and Conditions') }}</a>
                                            @endif
                                        @endif
                                    @endforeach
                                    {{ __('and the ') }}
                                    @foreach (json_decode($setting['menubar_page']) as $key => $value)
                                        @if (in_array($value->page_slug, ['privacy_policy']) && isset($value->template_name))
                                            @if(module_is_active('LandingPage'))
                                                <a href="{{ $value->template_name == 'page_content' ? route('custom.page', $value->page_slug) : $value->page_url }}"
                                                target="_blank">{{ $value->menubar_page_name }}</a>
                                            @else
                                                <a href="{{ route('custompage', ['page'=>'privacy_policy']) }}"
                                                target="_blank">{{ __('Privacy Policy')}}</a>
                                            @endif
                                        @endif
                                    @endforeach
                                @endif
                            </label>
                        </div>
                        @if (module_is_active('GoogleCaptcha') &&
                                (isset($admin_settings['google_recaptcha_is_on']) ? $adminu_settings['google_recaptcha_is_on'] : 'off') == 'on')
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
                        <div class="d-grid">
                            <input type="hidden" name="ref_code" value="{{$ref}}">
                            <button class="btn btn-primary btn-block mt-2" type="submit">{{ __('Register') }}</button>
                        </div>

                    </div>
                    <p class="mb-0 my-4 text-center">{{ __('Already have an account?') }} <a
                            href="{{ route('login', $lang) }}" class="f-w-400 text-primary">{{ __('Login') }}</a></p>
                </div>
            </form>
        </div>
    @endsection
    @push('script')
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
