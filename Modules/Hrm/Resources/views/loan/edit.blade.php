{{ Form::model($loan, ['route' => ['loan.update', $loan->id], 'method' => 'PUT']) }}
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
                {{ Form::label('loan_option', __('Loan Options'), ['class' => 'form-label']) }}
                {{ Form::select('loan_option', $loan_options, null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Loan Option']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
                {{ Form::select('type', $loans, null, ['class' => 'form-control amount_type ', 'required' => 'required', 'placeholder' => 'Select Type']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('amount', $loan->type == 'percentage' ? __('Percentage') : __('Loan Amount'), ['class' => 'form-label amount_label']) }}
                {{ Form::number('amount', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Enter Amount', 'step' => '0.10']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                {{ Form::date('start_date', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                {{ Form::date('end_date', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('reason', __('Reason'), ['class' => 'form-label']) }}
                {{ Form::textarea('reason', null, ['class' => 'form-control', 'rows' => 3, 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
