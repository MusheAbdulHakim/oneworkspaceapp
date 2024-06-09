{{ Form::model($employee, ['route' => ['employee.salary.update', $employee->id], 'method' => 'POST']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('salary_type', __('Payslip Type'), ['class' => 'form-label']) }}
                {{ Form::select('salary_type', $payslip_type, null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Payslip Type']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('salary', __('Salary'), ['class' => 'form-label']) }}
                {{ Form::number('salary', null, ['class' => 'form-control ', 'required' => 'required', 'step' => '0.10']) }}
            </div>
        </div>
        @if (module_is_active('Account'))
            <div class="col-md-12">
                <div class="form-group">
                    {{ Form::label('account_type', __('Account Type'), ['class' => 'form-label']) }}
                    {{ Form::select('account_type', $bankAccount, null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Account Type']) }}
                    @if (empty($bankAccount->count()))
                        <div class="text-xs">
                            {{ __('Please add Account.') }}<a
                                href="{{ route('bank-account.index') }}"><b>{{ __('Add Account') }}</b></a>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Create'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
