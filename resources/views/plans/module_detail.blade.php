{{ Form::model($addon, ['route' => ['add-one.detail.save', $addon->id], 'method' => 'post','enctype'=>'multipart/form-data']) }}
<div class="modal-body">
    <div class="form-group mb-1">
        {{ Form::label('name', __('Name'), ['class' => 'col-form-label']) }}
        {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => __('Enter Permission Name')]) }}
        @error('name')
            <span class="invalid-name" role="alert">
                <strong class="text-danger">{{ $message }}</strong>
            </span>
        @enderror
    </div>
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12">
            {{ Form::label('monthly_price', __('Price/Month'), ['class' => 'col-form-label']) }}
            <div class="input-group">
                {{ Form::number('monthly_price', null, ['class' => 'form-control','step' => '0.1','placeholder' => __('Enter Price for Month')]) }}
                <span class="input-group-text">{{ company_setting('defult_currancy_symbol')}}</span>
            </div>
            <small>{{ __('0 To module is free') }}</small>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            {{ Form::label('yearly_price', __('Price/Year'), ['class' => 'col-form-label']) }}
            <div class="input-group">
                {{ Form::number('yearly_price', null, ['class' => 'form-control','step' => '0.1','placeholder' => __('Enter Price for Year')]) }}
                <span class="input-group-text">{{ company_setting('defult_currancy_symbol')}}</span>
            </div>
            <small>{{ __('0 To module is free') }}</small>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <div class="form-group">
                <label class="col-form-label">{{__('Logo')}}</label>
                <div class="choose-files">
                    <label for="module_logo">
                        <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                        <input type="file" class="form-control file" accept="image/png, image/jpeg, image/jpg" name="module_logo" id="module_logo" data-filename="module_logo_update" onchange="document.getElementById('blah6').src = window.URL.createObjectURL(this.files[0])">
                    </label>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12">
            <img id="blah6" class="mt-3" src="{{ get_module_img($addon->module) }}"  width="30%"/>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Save'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
