{{Form::model(null, array('route' => array('marketplace_screenshots_update',[$slug, $key]), 'method' => 'POST','enctype' => "multipart/form-data")) }}
<div class="modal-body">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('Heading', __('Heading'), ['class' => 'form-label']) }}
                {{ Form::text('screenshots_heading',$screenshot['screenshots_heading'], ['class' => 'form-control ', 'placeholder' => __('Enter Heading'),'required'=>'required'])  }}
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('screenshot', __('Screenshot'), ['class' => 'form-label']) }}
                @if (check_file($screenshot['screenshots']))
                    <div class="logo-content my-4 ">
                        <img id="image" class="w-100 logo" src="{{get_file($screenshot['screenshots'])}}"
                        style="filter: drop-shadow(2px 3px 7px #011C4B);">
                    </div>
                @endif
                <input type="file" name="screenshots" class="form-control">
            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{__('Update')}}" class="btn  btn-primary">
</div>
{{ Form::close() }}
