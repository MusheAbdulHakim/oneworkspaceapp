<div class="border mb-5">

    {{ Form::open(array('route' => 'dedicated_store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
        @csrf
        <div class="p-3 border-bottom accordion-header">
            <div class="row align-items-center">
                <div class="col">
                    <h5>{{ __('Main') }}</h5>
                </div>
                <div id="p1" class="col-auto text-end text-primary h3">
                    <a image-url="{{ get_file('Modules/LandingPage/Resources/assets/infoimages/dedicationsectiondetails.png') }}"
                       data-url="{{ route('info.image.view',['landingpage','dedicated']) }}" class="view-images pt-2">
                        <i class="ti ti-info-circle pointer"></i>
                    </a>
                </div>
                {{-- <div class="col-auto switch-width text-end">
                    <div class="form-group mb-0">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class="" name="dedicated_section_status"
                                id="dedicated_section_status"  {{ $settings['dedicated_section_status'] == 'on' ? 'checked="checked"' : '' }}>
                            <label class="custom-control-label" for="dedicated_section_status"></label>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>


        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('Dedicated heading', __('Heading'), ['class' => 'form-label']) }}
                        {{ Form::text('dedicated_heading', $settings['dedicated_heading'], ['class' => 'form-control', 'placeholder' => __('Enter Heading')]) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('Dedicated heading', __('Description'), ['class' => 'form-label']) }}
                        {{ Form::text('dedicated_description', $settings['dedicated_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('Live', __('Live Demo Button Link'), ['class' => 'form-label']) }}
                        {{ Form::text('dedicated_live_demo_link',$settings['dedicated_live_demo_link'], ['class' => 'form-control ', 'placeholder' => __('Enter Details Link'),'required'=>'required']) }}
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('Live Link Button Text', __('Live Demo Button Text'), ['class' => 'form-label']) }}
                        {{ Form::text('dedicated_link_button_text',$settings['dedicated_link_button_text'], ['class' => 'form-control', 'placeholder' => __('Enter Button Text')]) }}
                    </div>
                </div>

            </div>
        </div>
        <div class="card-footer text-end">
            <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="{{ __('Save Changes') }}">
        </div>
    {{ Form::close() }}



</div>

<div class="border">
    <div class="p-3 border-bottom accordion-header">
        <div class="row align-items-center">
            <div class="col">
                <h5>{{ __('Info') }}</h5>
            </div>
            <div id="p1" class="col-auto text-end text-primary h3">
                <a image-url="{{ get_file('Modules/LandingPage/Resources/assets/infoimages/dedicatedsectioncards.png') }}" data-id="1"
                   data-url="{{ route('info.image.view',['landingpage','dedicated']) }}" class="view-images pt-2">
                    <i class="ti ti-info-circle pointer"></i>
                </a>
            </div>
            <div class="col-auto justify-content-end d-flex">
                <a data-size="lg" data-url="{{ route('dedicated_card_create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" data-title="{{__('Create New Card')}}" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
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
                        <th class="text-center">{{__('Action')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @if (is_array($dedicated_card_details) || is_object($dedicated_card_details))
                    @php
                        $ff_no = 1
                    @endphp
                        @foreach ($dedicated_card_details as $key => $value)
                            <tr>
                                <td>{{ $ff_no++ }}</td>
                                <td>{{ $value['dedicated_card_heading'] }}</td>
                                <td class="text-center">
                                    <span>
                                        <div class="action-btn bg-info ms-2">
                                                <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('dedicated_card_edit',$key) }}" data-ajax-popup="true" data-title="{{__('Edit Card Detail')}}" data-size="lg" data-bs-toggle="tooltip"  title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>

                                        <div class="action-btn bg-danger ms-2">
                                        {!! Form::open(['method' => 'GET', 'route' => ['dedicated_card_delete', $key],'id'=>'delete-form-'.$key]) !!}
                                            <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm-yes="{{ 'delete-form-'.$key}}">
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
