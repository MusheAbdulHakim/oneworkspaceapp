{{Form::model(null, array('route' => array('faq_update', $key), 'method' => 'POST','enctype' => "multipart/form-data")) }}
<div class="modal-body">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('questions', __('Questions'), ['class' => 'form-label']) }}
                {{ Form::text('faq_questions',$faq['faq_questions'], ['class' => 'form-control ', 'placeholder' => __('Enter Questions'),'required'=>'required']) }}
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('answer', __('Answer'), ['class' => 'form-label']) }}
                {{ Form::textarea('faq_answer', $faq['faq_answer'], ['class' => 'summernote form-control', 'placeholder' => __('Enter Answer'), 'id'=>'summernote','required'=>'required']) }}
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
