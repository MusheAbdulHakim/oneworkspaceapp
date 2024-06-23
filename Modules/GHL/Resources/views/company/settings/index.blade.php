<div class="card" id="paypal-sidenav">

    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5 class="">{{ __('GoHighLevel') }}</h5>
                <small>{{ __('Authenticate our app with your GHL Account. This will enable you to start using GoHighLevel features inside our application') }}</small>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            {{-- @if (!empty($userGhl))
            <h4>GHL is connected</h4>
            @else
            @endif --}}
            <a href="{{ route('ghl.redirect') }}" target="_blank"><button class="btn btn-primary">Authenticate
                    GHL</button></a>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '#ghl_integration_is_on', function() {
        if ($('#ghl_integration_is_on').prop('checked')) {
            $("#company_paypal_client_id").removeAttr("disabled");
            $("#company_paypal_secret_key").removeAttr("disabled");
        }
    });
</script>
