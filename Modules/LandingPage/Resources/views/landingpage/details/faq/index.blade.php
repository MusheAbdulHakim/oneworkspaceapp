
@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush

<div class="border mb-5">
    {{ Form::open(array('route' => 'faq.store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
    @csrf
        <div class="p-3 border-bottom accordion-header">
            <div class="row align-items-center">
                <div class="col-6">
                    <h5 class="mb-2">{{ __('Main') }}</h5>
                    <small class="text-danger">{{ __('Note: This section is for Pricing page ') }}</small>
                </div>
                {{-- <div class="col switch-width text-end">
                    <div class="form-group mb-0">
                        <div class="custom-control custom-switch">
                            <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class="" name="faq_status"
                                id="faq_status"  {{ $settings['faq_status'] == 'on' ? 'checked="checked"' : '' }}>
                            <label class="custom-control-label" for="faq_status"></label>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>


        <div class="card-body">
            <div class="row">

                {{-- <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('Title', __('Title'), ['class' => 'form-label']) }}
                        {{ Form::text('faq_title',$settings['faq_title'], ['class' => 'form-control ', 'placeholder' => __('Enter Title')]) }}
                        @error('mail_host')
                        <span class="invalid-mail_driver" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div> --}}

                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                        {{ Form::text('faq_heading',$settings['faq_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading')]) }}
                        @error('mail_host')
                        <span class="invalid-mail_driver" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                        {{-- {{ Form::text('faq_description', $settings['faq_description'], ['class' => 'form-control', 'placeholder' => __('Enter Description')]) }} --}}
                        {{ Form::textarea('faq_description', $settings['faq_description'], ['class' => 'summernote form-control', 'placeholder' => __('Enter Description'),'required'=>'required']) }}
                        @error('mail_port')
                        <span class="invalid-mail_port" role="alert">
                                <strong class="text-danger">{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>



            </div>
        </div>
        <div class="card-footer text-end">
            <button class="btn btn-print-invoice btn-primary m-r-10" type="submit" >{{ __('Save Changes') }}</button>
        </div>
    {{ Form::close() }}

</div>


<div class="border mb-5">
    <div class="p-3 border-bottom accordion-header">
        <div class="row align-items-center">
            <div class="col-lg-9 col-md-9 col-sm-9">
                <div class="col-6">
                    <h5 class="mb-2">{{ __('Info') }}</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 justify-content-end d-flex">
                <a data-size="lg" data-url="{{ route('faq_create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
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
                   @if (is_array($faqs) || is_object($faqs))
                    @php
                        $no = 1
                    @endphp
                        @foreach ($faqs as $key => $value)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $value['faq_questions'] }}</td>
                                <td>
                                    <span>
                                        <div class="action-btn bg-info ms-2">
                                            <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('faq_edit',$key) }}" data-ajax-popup="true" title="{{__('Edit Page')}}" data-size="lg" data-bs-toggle="tooltip"  title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>

                                            <div class="action-btn bg-danger ms-2">
                                            {!! Form::open(['method' => 'GET', 'route' => ['faq_delete', $key],'id'=>'delete-form-'.$key]) !!}

                                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm-yes="{{'delete-form-'.$key}}">
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