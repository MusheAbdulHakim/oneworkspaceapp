@extends('layouts.main')
@section('page-title')
    {{ __('Settings') }}
@endsection
@section('page-breadcrumb')
    {{ __('Settings') }}
@endsection
@push('css')
<link href="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css') }}" rel="stylesheet">
@endpush
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xl-3">
                    <div class="card sticky-top setting-sidebar" style="top:30px">
                        <div class="list-group list-group-flush" id="useradd-sidenav">
                            {!! getSettingMenu() !!}
                        </div>
                    </div>
                </div>
                <div class="col-xl-9 setting-menu-div">
                    {{-- {!! getSettings() !!} --}}
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>

        <script>
            $(document).ready(function() {
                getSettingSection('Base');
            });
            $(document).on("click", ".setting-menu-nav", function() {
                var module = $(this).attr('data-module');
                var method = $(this).attr('data-method');
                getSettingSection(module,method);
            });

            function getSettingSection(module,method = null) {
                $.ajax({
                    url: '{{ url("setting/section") }}' + '/' + module + '/' + method,
                    type: 'get',
                    beforeSend: function() {
                        $(".loader-wrapper").removeClass('d-none');
                    },
                    success: function(data) {
                        $(".loader-wrapper").addClass('d-none');

                        if (data.status == 200) {
                            $('.setting-menu-div').empty();
                            $('.setting-menu-div').append(data.html);
                        } else {
                            // error code
                        }
                    },
                    error: function(xhr) {
                        $(".loader-wrapper").addClass('d-none');
                        toastrs('Error', xhr.responseJSON.error, 'error');
                    }
                });
            }
        </script>
    @endpush
