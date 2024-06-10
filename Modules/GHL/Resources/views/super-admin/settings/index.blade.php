<div class="card" id="paypal-sidenav">
    <!--{{ Form::open(['route' => ['ghl.setting.store'], 'enctype' => 'multipart/form-data', 'id' => 'payment-form']) }}-->

    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5 class="">{{ __('GoHighLevel') }}</h5>
                <small>{{ __('Authenticate our app with your GHL Account. This will enable you to start using GoHighLevel features inside our application') }}</small>
            </div>
            <!--<div class="col-lg-2 col-md-2 col-sm-2 text-end">-->
            <!--    <div class="form-check form-switch custom-switch-v1 float-end">-->
            <!--        <input type="checkbox" name="ghl_integration_is_on" class="form-check-input input-primary"-->
            <!--            id="ghl_integration_is_on"-->
            <!--            {{ isset($settings['ghl_integration_is_on']) && $settings['ghl_integration_is_on'] == 'on' ? ' checked ' : '' }}>-->
            <!--        <label class="form-check-label" for="ghl_integration_is_on"></label>-->
            <!--    </div>-->
            <!--</div>-->
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            @if(isset($settings['ghl_integration_is_on']) && $settings['ghl_integration_is_on'] == 'on')
            <h4>GHL is connected</h4>
            @else
            <a href="{{ route('ghl.redirect') }}" target="_blank"><button class="btn btn-primary">Authenticate GHL</button></a>
            @endif
        </div>
    </div>
    <div class="card-footer text-end">
        <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
    </div>
    <!--{{ Form::close() }}-->

</div>

    <script>
        $(document).on('click', '#ghl_integration_is_on', function() {
            if ($('#ghl_integration_is_on').prop('checked')) {
                $("#company_paypal_client_id").removeAttr("disabled");
                $("#company_paypal_secret_key").removeAttr("disabled");
            } 
        });
    </script>
