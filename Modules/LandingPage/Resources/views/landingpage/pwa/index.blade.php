@extends('layouts.main')

@section('page-title')
    {{ __('Landing Page') }}
@endsection

@section('page-breadcrumb')
    {{__('Landing Page')}}
@endsection

@section('page-action')
    <div data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-original-title="{{ __('Qr Code') }}">
        <a class="btn btn-sm btn-primary btn-icon" data-bs-toggle="modal"  data-bs-target="#qrcodeModal" id="download-qr"
        target="_blanks" >
        <span class="text-white"><i class="fa fa-qrcode"></i></span>
    </a>
    <a class="btn btn-sm btn-primary btn-icon ml-0" data-bs-toggle="tooltip" data-bs-placement="bottom"
    data-bs-original-title="{{ __('Preview') }}" href="{{ url('/') }}" target="-blank" ><span
    class="text-white"><i class="ti ti-eye"></i></span></a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @include('landingpage::landingpage.sections')
            {{--  Start for all settings tab --}}
            <div class="card">
                {{ Form::model($settings, ['route' => ['landingpage.pwa.setting.save'], 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5>{{ __('PWA') }}</h5>
                        </div>
                        <div id="p1" class="col-auto text-end text-primary">
                            <div class="form-group col-md-4 ">
                                <label class="form-check-label"
                                    for="is_checkout_login_required"></label>
                                <div class="custom-control form-switch">
                                    <input type="checkbox"
                                        class="form-check-input is_pwa_store_active" name="is_pwa_store_active"
                                        id="pwa_store"
                                        {{ !empty($settings['is_pwa_store_active']) && $settings['is_pwa_store_active'] == 'on' ? 'checked=checked' : '' }}>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="border">
                        
                            <div class="p-3 justify-content-center">
                                
                                <div class="row">
                                    <div class="form-group col-md-6 pwa_is_enable">
                                        {{ Form::label('pwa_app_title', __('App Title'), ['class' => 'form-label']) }}
                                        {{ Form::text('pwa_app_title', !empty($pwa->name) ? $pwa->name : '', ['class' => 'form-control', 'placeholder' => __('App Title')]) }}
                                    </div>
        
                                    <div class="form-group col-md-6 pwa_is_enable">
                                        {{ Form::label('pwa_app_name', __('App Name'), ['class' => 'form-label']) }}
                                        {{ Form::text('pwa_app_name', !empty($pwa->short_name) ? $pwa->short_name : '', ['class' => 'form-control', 'placeholder' => __('App Name')]) }}
                                    </div>
        
                                    <div class="form-group input-width col-md-6 pwa_is_enable">
                                        {{ Form::label('pwa_app_background_color', __('App Background Color'), ['class' => 'form-label']) }}
                                        {{-- {{ Form::text('pwa_app_background_color', , ['class' => 'form-control', 'placeholder' => __('App Background Color')]) }} --}}
                                        {{ Form::color('pwa_app_background_color', !empty($pwa->background_color) ? $pwa->background_color : '', ['class' => 'form-control color-picker', 'placeholder' => __('18761234567')]) }}
                                    </div>
        
                                    <div class="form-group input-width col-md-6 pwa_is_enable">
                                        {{ Form::label('pwa_app_theme_color', __('App Theme Color'), ['class' => 'form-label']) }}
                                        {{-- {{ Form::text('pwa_app_theme_color', !empty($pwa->theme_color) ? $pwa->theme_color : '', ['class' => 'form-control', 'placeholder' => __('App Theme Color')]) }} --}}
                                        {{ Form::color('pwa_app_theme_color', !empty($pwa->theme_color) ? $pwa->theme_color : '', ['class' => 'form-control color-picker', 'placeholder' => __('18761234567')]) }}
                                    </div>
                                </div>
                                <div class="card-footer text-end">
                                    <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            {{--  End for all settings tab --}}
        </div>
    </div>
@endsection

@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush

