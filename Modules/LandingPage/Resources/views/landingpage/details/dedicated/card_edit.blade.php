{{ Form::open(array('route' => array('dedicated_card_update', $key), 'method'=>'post', 'enctype' => "multipart/form-data")) }}
    <div class="modal-body">
        @csrf
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                    {{ Form::text('dedicated_card_heading',$dedicated_card['dedicated_card_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading'),'required'=>'required']) }}
                </div>
            </div>

            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('Description', __('Description'), ['class' => 'form-label']) }}
                    {{ Form::textarea('dedicated_card_description', $dedicated_card['dedicated_card_description'], ['class' => 'summernote form-control', 'placeholder' => __('Enter Description'), 'id'=>'dedicated_card','required'=>'required']) }}
                </div>
            </div>

            <div class="col-md-8">
                <div class="form-group">
                    {{ Form::label('More', __('More Details Link'), ['class' => 'form-label']) }}
                    {{ Form::text('dedicated_card_more_details_link',$dedicated_card['dedicated_card_more_details_link'], ['class' => 'form-control ', 'placeholder' => __('Enter Details Link'),'required'=>'required']) }}
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    {{ Form::label('More Details Link Button Text', __('More Details Link Button Text'), ['class' => 'form-label']) }}
                    {{ Form::text('dedicated_card_more_details_button_text',$dedicated_card['dedicated_card_more_details_button_text'], ['class' => 'form-control', 'placeholder' => __('Enter Button Text'),'required'=>'required']) }}
                </div>
            </div>

            <div class="col-md-12">
                
                <div class="form-group">
                    {{ Form::label('Logo', __('Logo'), ['class' => 'form-label']) }}
                    <div class="logo-content mt-4 pb-5">
                        <img id="image" src="{{ get_file($dedicated_card['dedicated_card_logo'])}}"
                            class="small-logo"  style="filter: drop-shadow(2px 3px 7px #011C4B);">
                    </div>
                    <input type="file" name="dedicated_card_logo" class="form-control" >
                </div>
            </div>

        </div>
    </div>
    <div class="modal-footer">
        <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
        <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
    </div>
{{ Form::close() }}

