<div class="card">
    <div class="card-header">
        <div class="row align-items-center">
            <div class="col">
                <h5>{{ __('Footer') }}</h5>
            </div>
            <div id="p1" class="col-auto text-end text-primary h3">
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="border">
            {{ Form::open(array('route' => 'footer_store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
                    <div class="border-bottom p-3 accordion-header">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5>{{ __('Footer Details') }}</h5>
                            </div>
                            <div id="p1" class="col-auto text-end text-primary h3">
                                <a image-url="{{ get_file('Modules/LandingPage/Resources/assets/infoimages/footersection.png') }}"
                                   data-url="{{ route('info.image.view',['landingpage','footer']) }}" class="view-images pt-2">
                                    <i class="ti ti-info-circle pointer"></i>
                                </a>
                            </div>
                        </div>
                    </div>
        
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('All Rights Reserve text', __('All Rights Reserve text'), ['class' => 'form-label']) }}
                                    {{ Form::text('all_rights_reserve_text', $settings['all_rights_reserve_text'], ['class' => 'form-control', 'placeholder' => __('All Rights Reserve text')]) }}
                                    @error('mail_port')
                                    <span class="invalid-mail_port" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('All Rights Reserve Website Name', __('All Rights Reserve Website Name'), ['class' => 'form-label']) }}
                                    {{ Form::text('all_rights_reserve_website_name', $settings['all_rights_reserve_website_name'], ['class' => 'form-control', 'placeholder' => __('All Rights Reserve Website Name')]) }}
                                    @error('mail_port')
                                    <span class="invalid-mail_port" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('All Rights Reserve Website URL', __('All Rights Reserve Website URL'), ['class' => 'form-label']) }}
                                    {{ Form::text('all_rights_reserve_website_url', $settings['all_rights_reserve_website_url'], ['class' => 'form-control', 'placeholder' => __('All Rights Reserve Website URL')]) }}
                                    @error('mail_port')
                                    <span class="invalid-mail_port" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    {{ Form::label('Go To Shop Link', __('Go To Shop Link'), ['class' => 'form-label']) }}
                                    {{ Form::text('footer_live_demo_link', $settings['footer_live_demo_link'], ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
                                    @error('mail_port')
                                    <span class="invalid-mail_port" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('Go To Shop Button Text', __('Go To Shop Button Text'), ['class' => 'form-label']) }}
                                    {{ Form::text('footer_gotoshop_button_text',$settings['footer_gotoshop_button_text'], ['class' => 'form-control', 'placeholder' => __('Enter Button Text')]) }}
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    {{ Form::label('Support Button Link', __('Support Button Link'), ['class' => 'form-label']) }}
                                    {{ Form::text('footer_support_link', $settings['footer_support_link'], ['class' => 'form-control', 'placeholder' => __('Enter Link')]) }}
                                    @error('mail_port')
                                    <span class="invalid-mail_port" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    {{ Form::label('Support Button Text', __('Support Button Text'), ['class' => 'form-label']) }}
                                    {{ Form::text('footer_support_button_text',$settings['footer_support_button_text'], ['class' => 'form-control', 'placeholder' => __('Enter Button Text')]) }}
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    {{ Form::label('Footer Description', __('Footer Description'), ['class' => 'form-label']) }}
                                    {{ Form::text('footer_description', $settings['footer_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                                    @error('mail_port')
                                    <span class="invalid-mail_port" role="alert">
                                            <strong class="text-danger">{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    {{ Form::label('Footer Logo', __('Footer Logo'), ['class' => 'form-label']) }}
                                    <div class="logo-content mt-4">
                                        <img id="image11" src="{{ check_file($settings['footer_logo']) ? get_file($settings['footer_logo']) : get_file('uploads/logo/logo_light.png') }}" class="small-logo"  style="filter: drop-shadow(2px 3px 7px #011C4B);">
                                    </div>
                                    <div class="choose-files mt-5">
                                        <label for="footer_logo">
                                            <div class=" bg-primary" style="cursor: pointer;transform: translateY(+110%);">
                                                <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                            </div>
                                            <input type="file" name="footer_logo" id="footer_logo" class="form-control choose_file_custom" data-filename="footer_logo">
                                        </label>
                                    </div>
                                    @error('footer_logo')
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
        
        <div class="border mt-5">
            <div class="border-bottom p-3 accordion-header">
                <div class="row align-items-center">
                    <div class="col">
                        <h5>{{ __('Footer Sections') }}</h5>
                    </div>
                    <div id="p1" class="col-auto text-end text-primary h3">
                        <a image-url="{{ get_file('Modules/LandingPage/Resources/assets/infoimages/footersection.png') }}" data-id="1"
                           data-url="{{ route('info.image.view',['landingpage','footer']) }}" class="view-images pt-2">
                            <i class="ti ti-info-circle pointer"></i>
                        </a>
                    </div>
                    <div class="col-auto justify-content-end d-flex">
                        <a data-size="lg" data-url="{{ route('footer_section_create') }}" data-ajax-popup="true" title="{{__('Create')}}" data-bs-toggle="tooltip" data-title="{{__('Create New Section')}}"  class="btn btn-sm btn-primary">
                            <i class="ti ti-plus text-light"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
        
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>{{__('No')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (is_array($footer_sections_details) || is_object($footer_sections_details))
                            @php
                                $of_no = 1
                            @endphp
                                @foreach ($footer_sections_details as $key => $value)
                                    <tr>
                                        <td>{{ $of_no++ }}</td>
                                        <td>{{ $value['footer_section_heading'] }}</td>
                                        <td>
                                            <span>
                                                <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('footer_section_edit',$key) }}" data-ajax-popup="true" data-title="{{__('Edit Page')}}" data-size="lg" data-bs-toggle="tooltip"  title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
        
                                                <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'GET', 'route' => ['footer_section_delete', $key],'id'=>'delete-form-'.$key]) !!}
        
                                                    <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm-yes="{{'delete-form-'.$key}}">
                                                    <i class="ti ti-trash text-white"></i>
                                                </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
        
        
            </div>
        
        
        </div>
    </div>
</div>

