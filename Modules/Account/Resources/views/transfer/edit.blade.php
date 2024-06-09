{{ Form::model($transfer, array('route' => array('bank-transfer.update',$transfer->id), 'method' => 'PUT')) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'transfer','module'=>'Account'])
        @endif
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <label class="require form-label">{{ __('From Type') }}</label>
            <select class="form-control"
                name="from_type" id="from_type">
                <option value="">{{ __('Select Type') }}</option>
                <option value="bank" @if ($transfer->from_type == 'bank') selected @endif>
                    {{ __('Bank') }}</option>
                <option value="wallet" @if ($transfer->from_type == 'wallet') selected @endif>
                    {{ __('Wallet') }}</option>
            </select>
        </div>

        <div class="form-group col-md-6">
            <label class="require form-label">{{ __('To Type') }}</label>
            <select class="form-control"
                name="to_type" id="to_type">
                <option value="">{{ __('Select Type') }}</option>
                <option value="bank" @if ($transfer->to_type == 'bank') selected @endif>{{ __('Bank') }}</option>
                <option value="wallet"  @if ($transfer->to_type == 'wallet') selected @endif >{{ __('Wallet') }}</option>
            </select>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('from_account', __('From Account'),['class'=>'form-label']) }}
                {{ Form::select('from_account', $bankAccount,null, array('class' => 'form-control ','required'=>'required','placeholder' => 'Select Account')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('to_account', __('To Account'),['class'=>'form-label']) }}
                {{ Form::select('to_account', $bankAccount,null, array('class' => 'form-control ','required'=>'required','placeholder' => 'Select Account')) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('amount', __('Amount'),['class'=>'form-label']) }}
                {{ Form::number('amount',null, array('class' => 'form-control','required'=>'required',"min"=>"0",'placeholder' => __('Enter Amount'))) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('date', __('Date'),['class'=>'form-label']) }}
                {{ Form::date('date',null, ['class' => 'form-control ','required'=>'required','placeholder' => 'Select Date']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('reference', __('Reference'),['class'=>'form-label']) }}
                {{ Form::text('reference',null, array('class' => 'form-control','placeholder' => 'Enter Enter')) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'form-label']) }}
                {{ Form::textarea('description',null, ['class' => 'form-control', 'placeholder' => __('Enter Description'),'rows'=>'3']) }}
            </div>
        </div>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
