@extends('layouts.main')

@section('page-title')
    {{ __('Landing Page') }}
@endsection

@push('scripts')
    <script>
        document.getElementById('site_logo').onchange = function () {
                var src = URL.createObjectURL(this.files[0])
                document.getElementById('image').src = src
            }
    </script>
@endpush

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
            @include('landingpage::landingpage.custom.sitesettings.index')
            @include('landingpage::landingpage.custom.footer.index')
            @include('landingpage::landingpage.custom.custom_js_css.index')
            @include('landingpage::landingpage.custom.google_fonts.index')
            {{-- @include('landingpage::landingpage.custom.branding.index') --}}
        </div>
    </div>
@endsection

@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush

