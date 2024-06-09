@extends('layouts.main')

@section('page-title')
    {{ __('Landing Page') }}
@endsection

@section('page-breadcrumb')
    {{__('Landing Page')}}
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        @include('landingpage::marketplace.modules')
                <div class="row">
                    <div class="col-xl-3">
                        <div class="card sticky-top" style="top:30px">
                            <div class="list-group list-group-flush" id="useradd-sidenav">
                                @include('landingpage::marketplace.tab')
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9">
                    {{--  Start for all settings tab --}}
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col">
                                    <h5>{{ __('Add On Head details') }}</h5>
                                </div>
                                <div id="p1" class="col-auto text-end text-primary h3">
                                    <a image-url="{{ get_file('Modules/LandingPage/Resources/assets/infoimages/addon.png') }}"
                                       data-url="{{ route('info.image.view',['marketplace','addon']) }}" class="view-images pt-2">
                                        <i class="ti ti-info-circle pointer"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{ Form::open(array('route' => array('addon_store',$slug), 'method'=>'post', 'enctype' => "multipart/form-data")) }}
                            <div class="card-body">
                                @csrf
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                                            {{ Form::text('addon_heading',$settings['addon_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading')]) }}
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                                            {{ Form::textarea('addon_description', $settings['addon_description'], ['class' => 'summernote form-control', 'placeholder' => __('Enter Description'), 'id'=>'addon_description','required'=>'required']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
                            </div>
                        {{ Form::close() }}
                    </div>
                    {{--  End for all settings tab --}}
                    </div>
                </div>
        </div>
    </div>
@endsection

@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush