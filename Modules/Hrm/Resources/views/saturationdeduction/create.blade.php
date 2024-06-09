{{ Form::open(['url' => 'saturationdeduction', 'method' => 'post']) }}
{{ Form::hidden('employee_id', $employee->id, []) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('title', __('Title'), ['class' => 'form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Enter Title']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('deduction_option', __('Deduction Options'), ['class' => 'form-label']) }}
                {{ Form::select('deduction_option', $deduction_options, null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Deduction Option']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
                {{ Form::select('type', $saturationdeduc, null, ['class' => 'form-control amount_type ', 'required' => 'required', 'placeholder' => 'Select Type']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'), ['class' => 'form-label amount_label']) }}
                {{ Form::number('amount', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Enter Amount', 'step' => '0.10']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
