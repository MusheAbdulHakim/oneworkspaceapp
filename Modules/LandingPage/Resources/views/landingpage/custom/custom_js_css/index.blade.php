<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5>{{ __('Custom JS and CSS') }}</h5>
            </div>
            <div id="p1" class="col-auto text-end text-primary h3">
            </div>
        </div>
    </div>
    <div class="card-body">
        {{--  Start for all settings tab --}}
        {{Form::model(null, array('route' => array('landingpage.custom-js-css.setting.save'), 'method' => 'POST')) }}
        @csrf
            <div class="border">
                <div class="">
                    <div class="row align-items-center justify-content-between p-3">
                        <div class="mb-5 col-6">
                            <div class="form-group">
                                {{ Form::label('landingpage_custom_js', __('Custom JS'), ['class' => 'col-form-label text-dark']) }}
                                {{ Form::textarea('landingpage_custom_js', isset($settings['landingpage_custom_js']) ? $settings['landingpage_custom_js'] : '', ['class' => 'form-control','id'=>'topbar_notification']) }}
                            </div>
                        </div>
                    
                        <div class="mb-5 col-6">
                            <div class="form-group">
                                {{ Form::label('landingpage_custom_css', __('Custom CSS'), ['class' => 'col-form-label text-dark']) }}
                                {{ Form::textarea('landingpage_custom_css', isset($settings['landingpage_custom_css']) ? $settings['landingpage_custom_css'] : '', ['class' => 'form-control','id'=>'topbar_notification']) }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer mt-3 text-end">
                    <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
                </div>
            </div>
        {{ Form::close() }}
        {{--  End for all settings tab --}}
    </div>
</div>
