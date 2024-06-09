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
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5>{{ __('Details') }}</h5>
                        </div>
                        <div id="p1" class="col-auto text-end text-primary h3">
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="justify-content-center">
                        <div class="col-sm-12 col-md-10 col-xxl-12">
                            <div class="accordion accordion-flush setting-accordion" id="accordionExample">
                                {{-- top bar --}}
                                <div class="accordion-item ">
                                    <h2 class="accordion-header" id="heading-1">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse1"
                                            aria-expanded="false" aria-controls="collapse1">
                                            <span class="d-flex align-items-center">
                                                
                                                {{ __('Top Bar') }}
                                            </span>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{__('On/Off')}}:</span>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden" name="is_top_bar_active" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" name="is_top_bar_active" id="is_top_bar_active"
                                                    {{ !empty($settings['is_top_bar_active']) && $settings['is_top_bar_active'] == 'on' ? 'checked="checked"' : '' }} >
                                                    <label class="form-check-label" for="customswitchv1-1"></label>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse1" class="accordion-collapse collapse" aria-labelledby="heading-1"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row gy-4">
                                                <div class="col-md-12 mt-3">
                                                    <div class="form-group">
                                                        @include('landingpage::landingpage.details.topbar.index')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Banner --}}
                                <div class="accordion-item ">
                                    <h2 class="accordion-header" id="heading-2">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse2"
                                            aria-expanded="false" aria-controls="collapse2">
                                            <span class="d-flex align-items-center">
                                                
                                                {{ __('Banner') }}
                                            </span>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{__('On/Off')}}:</span>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden" name="is_banner_section_active" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" name="is_banner_section_active" id="is_banner_section_active" 
                                                        {{ !empty($settings['is_banner_section_active']) && $settings['is_banner_section_active'] == 'on' ? 'checked="checked"' : '' }} >
                                                    <label class="form-check-label" for="customswitchv1-1"></label>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse2" class="accordion-collapse collapse" aria-labelledby="heading-2"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row gy-4">
                                                <div class="col-md-12 mt-3">
                                                    <div class="form-group">
                                                        @include('landingpage::landingpage.details.banner.index')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Features --}}
                                <div class="accordion-item ">
                                    <h2 class="accordion-header" id="heading-3">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse3"
                                            aria-expanded="false" aria-controls="collapse3">
                                            <span class="d-flex align-items-center">
                                                
                                                {{ __('Features') }}
                                            </span>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{__('On/Off')}}:</span>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden" name="is_features_section_active" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" name="is_features_section_active" id="is_features_section_active"
                                                    {{ !empty($settings['is_features_section_active']) && $settings['is_features_section_active'] == 'on' ? 'checked="checked"' : '' }} >
                                                    <label class="form-check-label" for="customswitchv1-1"></label>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse3" class="accordion-collapse collapse" aria-labelledby="heading-3"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row gy-4">
                                                <div class="col-md-12 mt-3">
                                                    <div class="form-group">
                                                        @include('landingpage::landingpage.details.features.index')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Reviews --}}
                                <div class="accordion-item ">
                                    <h2 class="accordion-header" id="heading-4">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse4"
                                            aria-expanded="false" aria-controls="collapse4">
                                            <span class="d-flex align-items-center">
                                                
                                                {{ __('Reviews') }}
                                            </span>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{__('On/Off')}}:</span>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden" name="is_reviews_section_active" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" name="is_reviews_section_active" id="is_reviews_section_active" 
                                                        {{ !empty($settings['is_reviews_section_active']) && $settings['is_reviews_section_active'] == 'on' ? 'checked="checked"' : '' }} >
                                                    <label class="form-check-label" for="customswitchv1-1"></label>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse4" class="accordion-collapse collapse" aria-labelledby="heading-4"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row gy-4">
                                                <div class="col-md-12 mt-3">
                                                    <div class="form-group">
                                                        @include('landingpage::landingpage.details.reviews.index')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Screenshots --}}
                                <div class="accordion-item ">
                                    <h2 class="accordion-header" id="heading-5">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse5"
                                            aria-expanded="false" aria-controls="collapse5">
                                            <span class="d-flex align-items-center">
                                                
                                                {{ __('Screenshots') }}
                                            </span>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{__('On/Off')}}:</span>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden" name="is_screenshots_section_active" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" name="is_screenshots_section_active" id="is_screenshots_section_active" 
                                                    {{ !empty($settings['is_screenshots_section_active']) && $settings['is_screenshots_section_active'] == 'on' ? 'checked="checked"' : '' }} >
                                                    <label class="form-check-label" for="customswitchv1-1"></label>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse5" class="accordion-collapse collapse" aria-labelledby="heading-5"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row gy-4">
                                                <div class="col-md-12 mt-3">
                                                    <div class="form-group">
                                                        @include('landingpage::landingpage.details.screenshots.index')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Dedicated --}}
                                <div class="accordion-item ">
                                    <h2 class="accordion-header" id="heading-6">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse6"
                                            aria-expanded="false" aria-controls="collapse6">
                                            <span class="d-flex align-items-center">
                                                
                                                {{ __('Dedicated') }}
                                            </span>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{__('On/Off')}}:</span>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden" name="is_dedicated_section_active" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" name="is_dedicated_section_active" id="is_dedicated_section_active"
                                                    {{ !empty($settings['is_dedicated_section_active']) && $settings['is_dedicated_section_active'] == 'on' ? 'checked="checked"' : '' }} >
                                                    <label class="form-check-label" for="customswitchv1-1"></label>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse6" class="accordion-collapse collapse" aria-labelledby="heading-6"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row gy-4">
                                                <div class="col-md-12 mt-3">
                                                    <div class="form-group">
                                                        @include('landingpage::landingpage.details.dedicated.index')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- PackageDetails --}}
                                <div class="accordion-item ">
                                    <h2 class="accordion-header" id="heading-7">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse7"
                                            aria-expanded="false" aria-controls="collapse7">
                                            <span class="d-flex align-items-center">
                                                
                                                {{ __('PackageDetails') }}
                                            </span>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{__('On/Off')}}:</span>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden" name="is_package_details_section_active" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" name="is_package_details_section_active" id="is_package_details_section_active" 
                                                    {{ !empty($settings['is_package_details_section_active']) && $settings['is_package_details_section_active'] == 'on' ? 'checked="checked"' : '' }} >
                                                    <label class="form-check-label" for="customswitchv1-1"></label>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse7" class="accordion-collapse collapse" aria-labelledby="heading-7"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row gy-4">
                                                <div class="col-md-12 mt-3">
                                                    <div class="form-group">
                                                        @include('landingpage::landingpage.details.packagedetails.index')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- FAQ --}}
                                <div class="accordion-item ">
                                    <h2 class="accordion-header" id="heading-8">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse8"
                                            aria-expanded="false" aria-controls="collapse8">
                                            <span class="d-flex align-items-center">
                                                
                                                {{ __('FAQ') }}
                                            </span>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{__('On/Off')}}:</span>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden" name="is_faq_section_active" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" name="is_faq_section_active" id="is_faq_section_active"
                                                    {{ !empty($settings['is_faq_section_active']) && $settings['is_faq_section_active'] == 'on' ? 'checked="checked"' : '' }} >
                                                    <label class="form-check-label" for="customswitchv1-1"></label>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse8" class="accordion-collapse collapse" aria-labelledby="heading-8"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row gy-4">
                                                <div class="col-md-12 mt-3">
                                                    <div class="form-group">
                                                        @include('landingpage::landingpage.details.faq.index')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- BuildTech --}}
                                <div class="accordion-item ">
                                    <h2 class="accordion-header" id="heading-9">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse9"
                                            aria-expanded="false" aria-controls="collapse9">
                                            <span class="d-flex align-items-center">
                                                
                                                {{ __('BuildTech') }}
                                            </span>
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">{{__('On/Off')}}:</span>
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="hidden" name="is_buildtech_section_active" value="off">
                                                    <input type="checkbox" class="form-check-input input-primary" name="is_buildtech_section_active"  id="is_buildtech_section_active" 
                                                    {{ !empty($settings['is_buildtech_section_active']) && $settings['is_buildtech_section_active'] == 'on' ? 'checked="checked"' : '' }} >
                                                    <label class="form-check-label" for="customswitchv1-1"></label>
                                                </div>
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse9" class="accordion-collapse collapse" aria-labelledby="heading-9"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="row gy-4">
                                                <div class="col-md-12 mt-3">
                                                    <div class="form-group">
                                                        @include('landingpage::landingpage.details.buildtech.index')
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--  End for all settings tab --}}
        </div>
    </div>

@endsection

@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>

    <script>
        $(document).ready(function () {
        $('.form-switch input[type="checkbox"]').on('change', function () {
            var checkbox = $(this);
            var formData = {};

            var checkboxName = checkbox.attr('name');
            var checkboxStatus = checkbox.prop('checked') ? 'on' : 'off';

            formData[checkboxName] = checkboxStatus;

            $.ajax({
                type: 'POST',
                url: '{{ route("change.blocks.store.ajax") }}',
                data: formData,
                success: function (data) {
                    // Handle success, if needed
                    toastrs('success', data.success , 'success');

                },
                error: function (error) {
                    // Handle errors, if needed
                    toastrs('Error', 'Something went wrong!!!', 'error');
                }
            });
        });
    });
    </script>
@endpush