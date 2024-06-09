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
            {{ Form::open(['route' => ['landingpage.cookie.setting.store'],'method'=>'post']) }}
                <div class="card">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5>{{ __('Custom') }}</h5>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-2 text-end">
                                <div class="form-check form-switch custom-switch-v1 float-end">
                                    <input type="checkbox" name="enable_cookie" class="form-check-input input-primary" id="enable_cookie"
                                        {{  (isset($settings['enable_cookie']) ? $settings['enable_cookie'] :'off') == 'on' ? ' checked ' : '' }}>
                                    <label class="form-check-label" for="enable_cookie"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row border p-2">
                            <div class="col-md-6">
                                <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                    <input type="checkbox" name="cookie_logging" class="form-check-input input-primary cookie_setting"
                                        id="cookie_logging" {{ (isset($settings['cookie_logging']) ? $settings['cookie_logging'] :'off') == 'on' ? ' checked ' : '' }}>
                                    <label class="form-check-label" for="cookie_logging">{{__('Enable logging')}}</label>
                                    <small class="text-danger">{{ __('After enabling logging, user cookie data will be stored in CSV file.')}}</small>
                                </div>
                                <div class="form-group" >
                                    {{ Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label' ]) }}
                                    {{ Form::text('cookie_title',!empty($settings['cookie_title']) ? $settings['cookie_title'] : null , ['class' => 'form-control cookie_setting'] ) }}
                                </div>
                                <div class="form-group ">
                                    {{ Form::label('cookie_description', __('Cookie Description'), ['class' => ' form-label']) }}
                                    {!! Form::textarea('cookie_description',!empty($settings['cookie_description']) ? $settings['cookie_description'] : null , ['class' => 'form-control cookie_setting', 'rows' => '3']) !!}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check form-switch custom-switch-v1 ">
                                    <input type="checkbox" name="necessary_cookies" class="form-check-input input-primary cookie_setting"
                                        id="necessary_cookies" checked onclick="return false">
                                    <label class="form-check-label" for="necessary_cookies">{{__('Strictly necessary cookies')}}</label>
                                </div>
                                <div class="form-group ">
                                    {{ Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('strictly_cookie_title',!empty($settings['strictly_cookie_title']) ? $settings['strictly_cookie_title'] : null , ['class' => 'form-control cookie_setting']) }}
                                </div>
                                <div class="form-group ">
                                    {{ Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label']) }}
                                    {!! Form::textarea('strictly_cookie_description',!empty($settings['strictly_cookie_description']) ? $settings['strictly_cookie_description'] : null , ['class' => 'form-control cookie_setting ', 'rows' => '3']) !!}
                                </div>
                            </div>
                            <div class="col-12">
                                <h5>{{__('More Information')}}</h5>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('more_information_description', __('Contact Us Description'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('more_information_description',!empty($settings['more_information_description']) ? $settings['more_information_description'] : null , ['class' => 'form-control cookie_setting']) }}
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group ">
                                    {{ Form::label('contactus_url', __('Contact Us URL'), ['class' => 'col-form-label']) }}
                                    {{ Form::text('contactus_url',!empty($settings['contactus_url']) ? $settings['contactus_url'] : null , ['class' => 'form-control cookie_setting']) }}
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button type="submit" class="btn btn-primary">{{ __('Save Changes') }}</button>
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

