<div class="card" id="account-sidenav">
    {{ Form::open(['route' => 'accounts.setting.save', 'method' => 'post']) }}
    <div class="card-header">
        <div class="row">
            <div class="col-lg-10 col-md-10 col-sm-10">
                <h5 class="">{{ __('Account Settings') }}</h5>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row mt-2">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('customer_prefix', __('Customer Prefix'), ['class' => 'form-label']) }}
                    {{ Form::text('customer_prefix', !empty($settings['customer_prefix']) ? $settings['customer_prefix'] : '#CUST00000', ['class' => 'form-control', 'placeholder' => 'Enter Customer Prefix']) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('vendor_prefix', __('Vendor Prefix'), ['class' => 'form-label']) }}
                    {{ Form::text('vendor_prefix', !empty($settings['vendor_prefix']) ? $settings['vendor_prefix'] : '#VEND', ['class' => 'form-control', 'placeholder' => 'Enter Vendor Prefix']) }}
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer text-end">
        <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
    </div>
    {{ Form::close() }}
</div>
<!--Bill Setting-->
<div id="bill-print-sidenav" class="card">
    <div class="card-header">
        <h5>{{ __('Bill Print Settings') }}</h5>
        <small class="text-muted">{{ __('Edit your Company Bill details') }}</small>
    </div>
    <div class="bg-none">
        <div class="row company-setting">
            <form id="setting-form" method="post" action="{{ route('bill.template.setting') }}"
                enctype ="multipart/form-data">
                @csrf
                <div class="card-header card-body ">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('bill_prefix', __('Prefix'), ['class' => 'form-label']) }}
                                {{ Form::text('bill_prefix', !empty($settings['bill_prefix']) ? $settings['bill_prefix'] : '#BILL', ['class' => 'form-control', 'placeholder' => 'Enter Bill Prefix']) }}
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                {{ Form::label('bill_starting_number', __('Starting Number'), ['class' => 'form-label']) }}
                                {{ Form::number('bill_starting_number', !empty($settings['bill_starting_number']) ? $settings['bill_starting_number'] : 1, ['class' => 'form-control', 'placeholder' => 'Enter Bill Starting Number']) }}
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                {{ Form::label('bill_footer_title', __('Footer Title'), ['class' => 'form-label']) }}
                                {{ Form::text('bill_footer_title', !empty($settings['bill_footer_title']) ? $settings['bill_footer_title'] : '', ['class' => 'form-control', 'placeholder' => 'Enter Footer Title']) }}
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                {{ Form::label('bill_footer_notes', __('Footer Notes'), ['class' => 'form-label']) }}
                                {{ Form::textarea('bill_footer_notes', !empty($settings['bill_footer_notes']) ? $settings['bill_footer_notes'] : '', ['class' => 'form-control', 'rows' => '1', 'placeholder' => 'Enter Bill Footer Notes']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <div class="card-header card-body">
                            <div class="form-group d-flex align-items-center justify-content-between">
                                {{ Form::label('bill_shipping_display', __('Shipping Display?'), ['class' => 'form-label']) }}
                                <div class="text-end form-check form-switch d-inline-block">
                                    <input type="checkbox" class="form-check-input"
                                    name="bill_shipping_display" id="bill_shipping_display"
                                    {{ (isset($settings['bill_shipping_display']) ? $settings['bill_shipping_display'] : 'off') == 'on' ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="form-group d-flex align-items-center justify-content-between">
                                {{ Form::label('bill_qr_display', __('QR Display?'), ['class' => 'form-label']) }}
                                <div class="text-end form-check form-switch d-inline-block">
                                    <input type="checkbox" class="form-check-input"
                                    name="bill_qr_display" id="bill_qr_display"
                                    {{ (isset($settings['bill_qr_display']) ? $settings['bill_qr_display'] : 'off') == 'on' ? 'checked' : '' }}>
                                </div>
                            </div>
                            <div class="form-group">
                                {{ Form::label('bill_template', __('Bill Template'), ['class' => 'form-label']) }}
                                {{ Form::select('bill_template', Modules\Account\Entities\AccountUtility::templateData()['templates'], !empty($settings['bill_template']) ? $settings['bill_template'] : null, ['class' => 'form-control', 'required' => 'required']) }}
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{ __('Color Input') }}</label>
                                <div class="row gutters-xs">
                                    @foreach (Modules\Account\Entities\AccountUtility::templateData()['colors'] as $key => $color)
                                        <div class="col-auto">
                                            <label class="colorinput">
                                                <input name="bill_color" type="radio" value="{{ $color }}"
                                                    class="colorinput-input"
                                                    {{ !empty($settings['bill_color']) && $settings['bill_color'] == $color ? 'checked' : '' }}>
                                                <span class="colorinput-color"
                                                    style="background: #{{ $color }}"></span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-form-label">{{ __('Bill Logo') }}</label>
                                <div class="choose-files mt-5 ">
                                    <label for="bill_logo">
                                        <div class=" bg-primary "> <i
                                                class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                        <img id="blah7" class="mt-3" src="" width="70%" />
                                        <input type="file" class="form-control file" name="bill_logo" id="bill_logo"
                                            data-filename="bill_logo_update"
                                            onchange="document.getElementById('blah7').src = window.URL.createObjectURL(this.files[0])">
                                    </label>
                                </div>
                            </div>
                            <div class="form-group mt-2 text-end">
                                <input type="submit" value="{{ __('Save Changes') }}"
                                    class="btn btn-print-invoice  btn-primary m-r-10">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        @if (!empty($settings['bill_template']) && !empty($settings['bill_color']))
                            <iframe id="bill_frame" class="w-100 h-100" frameborder="0"
                                src="{{ route('bill.preview', [$settings['bill_template'], $settings['bill_color']]) }}"></iframe>
                        @else
                            <iframe id="bill_frame" class="w-100 h-100" frameborder="0"
                                src="{{ route('bill.preview', ['template1', 'fffff']) }}"></iframe>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>



<script>
    $(document).on("change", "select[name='bill_template'], input[name='bill_color']", function() {
        var template = $("select[name='bill_template']").val();
        var color = $("input[name='bill_color']:checked").val();
        $('#bill_frame').attr('src', '{{ url('/bill/preview') }}/' + template + '/' + color);
    });

</script>

