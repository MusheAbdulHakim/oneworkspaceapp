{{ Form::open(array('route' => 'landingpagePixel.store', 'method'=>'post', 'enctype' => "multipart/form-data")) }}
    <div class="modal-body">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('platform', __('Platform'),['class'=>'form-label']) }}
                    {!! Form::select('platform', $pixals_platforms, null,array('class' => 'form-control select2','required'=>'required')) !!}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('pixel_id',__('Pixel ID'))}}
                    {{Form::text('pixel_id',null,array('class'=>'form-control','placeholder'=>__('Enter Pixel ID')))}}
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
    </div>
{{ Form::close() }}

@push('css')
    <link href="{{  asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')  }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js') }}"></script>
@endpush
