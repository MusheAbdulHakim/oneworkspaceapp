
{{ Form::open(array('route' => 'homesection.store', 'method'=>'post', 'enctype' => "multipart/form-data",'id' => "imageUploadForm")) }}
    <div class="border mb-5">
        <div class="row p-3">
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Offer Text', __('Offer Text'), ['class' => 'form-label']) }}
                    {{ Form::text('home_offer_text', $settings['home_offer_text'], ['class' => 'form-control', 'placeholder' => __('70% Special Offer')]) }}
                    @error('home_offer_text')
                        <span class="invalid-home_offer_text" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                    {{ Form::text('home_heading',$settings['home_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading')]) }}
                    @error('home_heading')
                    <span class="invalid-home_heading" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Title', __('Title'), ['class' => 'form-label']) }}
                    {{ Form::text('home_title',$settings['home_title'], ['class' => 'form-control ', 'placeholder' => __('Enter Title')]) }}
                    @error('home_title')
                    <span class="invalid-home_title" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Trusted by', __('Trusted by'), ['class' => 'form-label']) }}
                    {{ Form::text('home_trusted_by', $settings['home_trusted_by'], ['class' => 'form-control', 'placeholder' => __('1,000+ customers')]) }}
                    @error('mail_port')
                    <span class="invalid-mail_port" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                    {{ Form::text('home_description', $settings['home_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                    @error('mail_port')
                    <span class="invalid-mail_port" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>


            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Live Demo Link', __('Live Demo Link'), ['class' => 'form-label']) }}
                    {{ Form::text('home_live_demo_link', $settings['home_live_demo_link'], ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
                    @error('home_live_demo_link')
                    <span class="invalid-mail_port" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Banner', __('Banner'), ['class' => 'form-label']) }}
                    <div class="logo-content mt-4">
                        <img id="image" src="{{ check_file($settings['home_banner']) ? get_file($settings['home_banner']) : get_file('market_assets/images/images1.png') }}"
                            class="big-logo" width="100%">
                    </div>
                    <div class="choose-files mt-5">
                        <label for="home_banner">
                            <div class=" bg-primary " style="cursor: pointer;">
                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                            </div>
                            <input type="file" name="home_banner" id="home_banner" class="form-control choose_file_custom" data-filename="home_banner">
                        </label>
                    </div>
                    @error('home_banner')
                        <div class="row">
                        <span class="invalid-logo" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </span>
                        </div>
                    @enderror
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('Live Link Button Text', __('Live Demo Button Text'), ['class' => 'form-label']) }}
                    {{ Form::text('home_link_button_text',$settings['home_link_button_text'], ['class' => 'form-control', 'placeholder' => __('Enter Button Text')]) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="row pb-3">
                    <div class="col">
                        <h6>{{ __('Logo') }}</h6>
                    </div>
                    <div class="col-auto text-end">
                        <button class="btn btn-sm btn-primary btn-icon m-1 " data-repeater-create type="button"><i class="ti ti-plus"></i></button>
                    </div>
                </div>
                <div data-repeater-list="home_logo">
                    <div data-repeater-item class="text-end">
                        <div class="card mb-3 border shadow-none product_Image" >
                            <div class="px-2 py-2">
                                <div class="row align-items-center ">
                                    <div class="col">
                                        <input type="file"  class="form-control" name="home_logo" accept="image/*" onchange="updateImagePreview(this)">
                                    </div>
                                    <div class="col-auto">
                                        <p class="card-text small text-muted">
                                            <img class="rounded" src="{{ check_file($settings['home_logo']) ? get_file($settings['home_logo']) : get_file('uploads/logo/logo_dark.png') }}" width="70px" alt="Image placeholder" data-dz-thumbnail="">

                                        </p>
                                    </div>
                                    <div class="col-auto actions">
                                        <a data-repeater-delete href="javascript:void(0)" class="action-item btn btn-sm btn-icon btn-light-secondary  ms-2">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if($settings['home_logo'] !="")
                <div id="imageContainer">
                    @foreach (explode(',', $settings['home_logo'])  as $k => $home_logo)
                        <div class="card mb-3 border shadow-none product_Image">
                            <div class="px-2 py-2">
                                <div class="row align-items-center">
                                    <div class="col ml-n2">
                                        <p class="card-text small text-muted">
                                            <img class="rounded" src="{{ check_file('uploads/landing_page_image/'.$home_logo) ? get_file('uploads/landing_page_image/'.$home_logo) : get_file('uploads/logo/logo_dark.png')}}" width="70px" alt="Image placeholder" data-dz-thumbnail="">
                                        </p>
                                    </div>
                                    <div class="col-auto actions">
                                        <a class="action-item btn btn-sm btn-icon btn-light-secondary" href="{{ check_file('uploads/landing_page_image/'.$home_logo) ? get_file('uploads/landing_page_image/'.$home_logo) : get_file('uploads/logo/logo_dark.png') }}" download="" data-toggle="tooltip" data-original-title="Download">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    </div>
                                    <div class="col-auto actions">
                                        <a class="action-item btn btn-sm btn-icon btn-light-secondary delete-button" data-image="{{ $home_logo }}">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @endif
                <input type="hidden" class="form-control" id="imageNames" name="savedlogo" value="{{ $settings['home_logo'] }}">
            </div>
            <div class="mt-3 text-end">
                <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
            </div>
        </div>
    </div>
{{ Form::close() }}
