{{ Form::model($taxbracket, ['route' => ['taxbracket.update', $taxbracket->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('from', __('From'), ['class' => 'form-label']) }}
                {{ Form::number('from', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.10', 'placeholder' => __('Enter Amount')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('to', __('To'), ['class' => 'form-label']) }}
                {{ Form::number('to', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.10', 'placeholder' => __('Enter Amount')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('fixed_amount', __('Fixed Amount'), ['class' => 'form-label']) }}
                {{ Form::number('fixed_amount', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.10', 'placeholder' => __('Enter Fixed Amount')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('percentage', __('Percentage'), ['class' => 'form-label']) }}
                {{ Form::number('percentage', null, ['class' => 'form-control', 'required' => 'required', 'step' => '0.10', 'placeholder' => __('Enter Percentage')]) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Update'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
