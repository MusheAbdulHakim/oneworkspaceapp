{{ Form::open(['route' => 'mycalendar.store','enctype'=>'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-lg-12 col-xl-12">
            <div class="form-group">
                {{ Form::label('title', __('Event Title'), ['class' => 'col-form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control ', 'placeholder' => __('Enter Event Title'),'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-lg-6 col-xl-6">
            <div class="form-group">
                {{ Form::label('start', __('Event start Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('start', now()->format('Y-m-d'), ['class' => 'form-control datetime-local ', 'autocomplete'=>'off', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-lg-6 col-xl-6">
            <div class="form-group">
                {{ Form::label('end', __('Event End Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('end', now()->format('Y-m-d'), ['class' => 'form-control datetime-local ','autocomplete'=>'off', 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6 col-sm-12 col-lg-6 col-xl-6">
            <div class="form-group">
                {{ Form::label('url', __('Url'), ['class' => 'col-form-label']) }}
                {{ Form::url('url', '', ['class' => 'form-control','placeholder' => 'meet.google.com/gyw-xqov-qhb','autocomplete'=>'off']) }}
            </div>
        </div>

        <div class="col-md-6 col-sm-12 col-lg-6 col-xl-6">
            <div class="form-group">
                {{ Form::label('color', __('Pic Event Color'), ['class' => 'col-form-label']) }}
                {{ Form::color('color', '#1243A5', ['class' => 'form-control','autocomplete'=>'off']) }}
            </div>
        </div>

        <div class="form-group">
            {{ Form::label('description', __('Event Description'), ['class' => 'col-form-label']) }}
            {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Event Description'),'rows'=>'5']) }}
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="{{ __('Cancel') }}" class="btn  btn-light" data-bs-dismiss="modal">
    <input type="submit" value="{{ __('Submit') }}" class="btn  btn-primary">
</div>
{{ Form::close() }}
