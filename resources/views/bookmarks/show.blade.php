<div class="modal-body">
    {{ Form::open(['route' => ['bookmark.update', $bookmark->id],'enctype'=>'multipart/form-data']) }}
    @method("PUT")
    <div class="modal-body">
        <div class="row">
            <div class="col-md-6 col-12">
                <div class="form-group">
                    {{ Form::label('url', __('Url'), ['class' => 'col-form-label']) }}
                    {{ Form::url('url', $bookmark->url ?? old('url'), ['class' => 'form-control','required' => 'required','placeholder' => 'http://app.oneworkspace.io','autocomplete'=>'off']) }}
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    {{ Form::label('title', __('Title'), ['class' => 'col-form-label']) }}
                    {{ Form::text('title',$bookmark->title ??  old('title'), ['class' => 'form-control ', 'placeholder' => __('Enter Title'),'required' => 'required']) }}
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
                    {{ Form::textarea('description', $bookmark->description ?? old('description'), ['class' => 'form-control', 'placeholder' => __('Enter Description'),'rows'=>'3']) }}
                </div>
            </div>
            <div class="col-12">
                <div class="form-group">
                    {{ Form::label('note', __('Note'), ['class' => 'col-form-label']) }}
                    {{ Form::textarea('note', $bookmark->note ?? old('note'), ['class' => 'form-control', 'placeholder' => __('Enter Note'),'rows'=>'6']) }}
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    {{ Form::label('order', __('Order / Bookmark Priority'), ['class' => 'col-form-label']) }}
                    {{ Form::text('order', $bookmark->order ?? old('order'), ['class' => 'form-control ', 'placeholder' => __('Prioritize Bookmark')]) }}
                </div>
            </div>
            <div class="col-md-6 col-12">
                <div class="form-group">
                    {{ Form::label('color', __('Color'), ['class' => 'col-form-label']) }}
                    {{ Form::color('color', $bookmark->color ?? '#1243A5', ['class' => 'form-control','autocomplete'=>'off']) }}
                </div>
            </div>

            <div class="col-md-6 col-12">
                <div class="form-group">
                    {!! Form::label('bookmark_type', __('Bookmark Type'), ['class' => 'form-label']) !!}
                    <div class="d-flex radio-check">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input type="radio" id="embed" value="1" name="bookmark_type"
                                class="form-check-input" {{ $bookmark->type == '1' ? 'checked': '' }}>
                            <label class="form-check-label "
                                for="embed">{{ __('Embed/Iframe') }}</label>
                        </div>
                        <div class="custom-control custom-radio ms-1 custom-control-inline">
                            <input type="radio" id="external" value="2" name="bookmark_type"
                                class="form-check-input" {{ $bookmark->type == '2' ? 'checked': '' }}>
                            <label class="form-check-label"
                                for="external">{{ __('External') }}</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label for="status">{{ __('Active?') }}</label>
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="status" class="form-check-input input-primary pointer" {{ !empty($bookmark->status) ? 'checked': '' }} id="status">
                    <label class="form-check-label" for="status"></label>
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    </div>
    {{ Form::close() }}
</div>
