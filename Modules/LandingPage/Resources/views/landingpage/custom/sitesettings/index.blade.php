<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5>{{ __('Site Settings') }}</h5>
            </div>
            <div id="p1" class="col-auto text-end text-primary h3">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="faq justify-content-center">
            <div class="col-sm-12 col-md-10 col-xxl-12">
                <div class="border">
                    <div class="border-bottom p-3 accordion-header">
                        <div class="row">
                            <div class="col">
                                <h5>{{ __('Home Section') }}</h5>
                            </div>
                        </div>
                    </div>
                    {{ Form::open(array('route' => 'custom_store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('Site Logo', __('Site Logo'), ['class' => 'form-label']) }}
                                        <div class="logo-content mt-4">
                                            <img id="image" class="small-logo" src="{{check_file($settings['site_logo']) ? get_file($settings['site_logo']) : get_file('uploads/logo/logo_dark.png')}}" class="w-100 logo"  style="filter: drop-shadow(2px 3px 7px #011C4B);">
                                        </div>
                                        <div class="choose-files mt-5">
                                            <label for="site_logo">
                                                <div class=" bg-primary" style="cursor: pointer;transform: translateY(+110%);">
                                                    <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                </div>
                                                <input type="file" name="site_logo" id="site_logo" class="form-control choose_file_custom" data-filename="site_logo">
                                            </label>
                                        </div>
                                        @error('site_logo')
                                            <div class="row">
                                            <span class="invalid-logo" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
                        </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
