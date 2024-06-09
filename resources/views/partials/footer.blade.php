

<footer class="mt-5 dash-footer">
    <div class="footer-wrapper">
        <div class="py-1">
            <span class="text-muted">
                @if (isset($company_settings['footer_text']))
                    {{ $company_settings['footer_text'] }}
                @elseif(isset($admin_settings['footer_text']))
                    {{ $admin_settings['footer_text'] }}
                @else
                    {{ __('Copyright') }} &copy; {{ config('app.name', 'WorkDo') }}
                @endif
                {{ date('Y') }}
            </span>
        </div>
    </div>
</footer>

@if (Route::currentRouteName() !== 'chatify')
<div id="commonModal" class="modal" tabindex="-1" aria-labelledby="exampleModalLongTitle"
    aria-modal="true" role="dialog" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="commonModalOver" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLongTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="body">
            </div>
        </div>
    </div>
</div>
@endif
<div class="loader-wrapper d-none">
<span class="site-loader"> </span>
</div>
<div class="top-0 p-3 position-fixed end-0" style="z-index: 99999">
<div id="liveToast" class="text-white toast fade" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body"> </div>
        <button type="button" class="m-auto btn-close btn-close-white me-2" data-bs-dismiss="toast"
            aria-label="Close"></button>
    </div>
</div>
</div>
<!-- Required Js -->


<script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/js/dash.js') }}"></script>
<script src="{{ asset('assets/js/plugins/simple-datatables.js') }}"></script>
<script src="{{ asset('assets/js/plugins/bootstrap-switch-button.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/datepicker-full.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/flatpickr.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/choices.min.js') }}"></script>
<script src="{{ asset('js/jquery.form.js') }}"></script>
@if(!empty($company_settings['category_wise_sidemenu']) && $company_settings['category_wise_sidemenu'] == 'on')
    <script src="{{ asset('assets/js/layout-tab.js') }}"></script>
@endif



<script src="{{ asset('js/custom.js') }}"></script>
@if ($message = Session::get('success'))
<script>
    toastrs('Success', '{!! $message !!}', 'success');
</script>
@endif
@if ($message = Session::get('error'))
<script>
    toastrs('Error', '{!! $message !!}', 'error');
</script>
@endif
@stack('scripts')
@include('Chatify::layouts.footerLinks')
@if (isset($admin_settings['enable_cookie']) && $admin_settings['enable_cookie'] == 'on')
@include('layouts.cookie_consent')
@endif
</body>

</html>
