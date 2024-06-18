@extends('layouts.main')

@section('page-title')
    {{__('Url Bookmarks')}}
@endsection

@section('page-breadcrumb')
    {{ __('Bookmark List')}}
@endsection

@section('page-action')
    <div>
        <a
            href="{{ route('bookmark.index') }}"
            data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Bookmarks') }}"
            class="btn btn-sm btn-primary btn-icon ">
            <i class="fa fa-reply"></i>
        </a>
        <a href="{{ route('bookmark.cardview') }}"
            data-bs-toggle="tooltip" data-bs-original-title="{{ __('Card View') }}"
                class="btn btn-sm btn-primary btn-icon ">
                <i class="ti ti-box"></i>
        </a>
    </div>
@endsection


@section('content')
<div class="col-12 ">
    <div class="card">
        <div class="card-body">
            {{ Form::open(['route' => 'bookmark.store','enctype'=>'multipart/form-data']) }}
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {{ Form::label('url', __('Url'), ['class' => 'col-form-label']) }}
                                {{ Form::url('url', old('url'), ['class' => 'form-control','required' => 'required','placeholder' => 'http://app.oneworkspace.io','autocomplete'=>'off']) }}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }}
                                {{ Form::text('title', old('title'), ['class' => 'form-control ', 'placeholder' => __('Enter Title'),'required' => 'required']) }}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
                                {{ Form::textarea('description', old('description'), ['class' => 'form-control', 'placeholder' => __('Enter Description'),'rows'=>'3']) }}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                {{ Form::label('note', __('Note'), ['class' => 'col-form-label']) }}
                                {{ Form::textarea('note', old('note'), ['class' => 'form-control', 'placeholder' => __('Enter Note'),'rows'=>'6']) }}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {{ Form::label('order', __('Order / Bookmark Priority'), ['class' => 'col-form-label']) }}
                                {{ Form::text('order', old('order'), ['class' => 'form-control ', 'placeholder' => __('Prioritize Bookmark')]) }}
                            </div>
                        </div>
                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {{ Form::label('color', __('Color'), ['class' => 'col-form-label']) }}
                                {{ Form::color('color', '#ffffff', ['class' => 'form-control','autocomplete'=>'off']) }}
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="form-group">
                                {!! Form::label('bookmark_type', __('Bookmark Type'), ['class' => 'form-label']) !!}
                                <div class="d-flex radio-check">
                                    <div class="custom-control custom-radio custom-control-inline">
                                        <input type="radio" id="embed" value="1" name="bookmark_type"
                                            class="form-check-input">
                                        <label class="form-check-label "
                                            for="embed">{{ __('Embed/Iframe') }}</label>
                                    </div>
                                    <div class="custom-control custom-radio ms-1 custom-control-inline">
                                        <input type="radio" id="external" value="2" name="bookmark_type"
                                            class="form-check-input" checked>
                                        <label class="form-check-label"
                                            for="external">{{ __('External') }}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="status">{{ __('Active?') }}</label>
                            <div class="form-check form-switch custom-switch-v1 float-end">
                                <input type="checkbox" name="status" class="form-check-input input-primary pointer" id="status">
                                <label class="form-check-label" for="status"></label>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" value="{{ __('Submit') }}" class="btn  btn-primary">
                </div>
                {{ Form::close() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
       $('#url').blur(function(){
            let url = $(this).val();
            if(url){
                $.get(`{{ route("bookmark.getmeta") }}?url=${url}`, function(data, status){
                    $('#title').val(data.title)
                    $('#description').val(data.description)
                    if(data.error){
                        toastrs("Info",data.message)
                    }
                });
            }
       });
    })
</script>
@endpush



