{{ Form::model($partslogtime, ['route' => ['productslogtime.update', $partslogtime->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'parts_logtime','module'=>'CMMS'])
        @endif
    </div>
    <input type="hidden" name="product_id" value="{{ $partslogtime->product_id }}">
    <div class="row">

        @if (Auth::user()->id == $partslogtime->created_by)
            <div class="col-md-12 form-group">
                {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('date', null, ['class' => 'form-control', 'placeholder' => __('Enter Date'), 'required' => 'required']) }}
            </div>

            <div class="col-md-12 form-group">
                {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required' => 'required', 'row' => 3]) }}
            </div>
    </div>
</div>

    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
    </div>
@else

    <div class="col-md-12 form-group">
        {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
        {{ Form::date('date', null, ['class' => 'form-control', 'placeholder' => __('Enter Date'), 'required' => 'required', 'disabled']) }}
    </div>

    <div class="col-md-12 form-group">
        {{ Form::label('description', __('Description'), ['class' => 'col-form-label']) }}
        {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'required' => 'required', 'disabled', 'row' => 3]) }}
    </div>

    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
        {{ Form::submit(__('Update'), ['class' => 'btn btn-primary']) }}
    </div>
    @endif
{{ Form::close() }}
