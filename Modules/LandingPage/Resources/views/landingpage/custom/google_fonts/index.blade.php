<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5>{{ __('Google Fonts') }}</h5>
            </div>
            <div id="p1" class="col-auto text-end text-primary h3">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="border">
            {{ Form::open(array('route' => 'landingpage.google.fonts', 'method'=>'post')) }}
                {{-- <div class="border-bottom p-3 accordion-header">
                    <h5>{{ __('Font Settings') }}</h5>
                </div> --}}
                <div class="card-body">
                    <!-- Body Settings -->
                    <div class="row mt-3 mb-3">
                        <div class="col-xl-6 col-sm-6 col-12">
                            {{ Form::label('body_fontfamily', __('Font Families'), ['class' => 'form-label']) }}
                            <select name="body_fontfamily"
                                class="form-control form-control-solid form-select mb-7">
                                @foreach ($font_familys as $key => $value)
                                    <option value="{{ $value }}"
                                        @if (isset($settings['body_fontfamily']) && $settings['body_fontfamily']  == $value) selected @endif>
                                        {{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="col-xl-6 col-sm-6 col-12">
                            {{ Form::label('body_fontweight', __('Font Weight'), ['class' => 'form-label']) }}
                            
                            {{ Form::select('body_fontweight', $fontweights, isset($settings['body_fontweight']) ? $settings['body_fontweight'] :'', ['class' => 'form-control form-control-solid mb-7']) }}
                        </div> --}}
                    </div>
                </div>
                <div class="card-footer text-end">
                    <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>


