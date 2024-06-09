{{ Form::model($award, ['route' => ['award.update', $award->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'award','module'=>'Hrm'])
        @endif
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('employee_id', __('Employee'), ['class' => 'col-form-label']) }}
                {{ Form::select('employee_id', $employees, !empty($award->user_id) ? $award->user_id : null, ['class' => 'form-control ', 'placeholder' => __('Select Employee'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('award_type', __('Award Type'), ['class' => 'col-form-label']) }}
                {{ Form::select('award_type', $awardtypes, null, ['class' => 'form-control ', 'placeholder' => __('Select Award Type'), 'required' => 'required']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('date', __('Date'), ['class' => 'col-form-label']) }}
                {{ Form::date('date', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Date']) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('gift', __('Gift'), ['class' => 'col-form-label']) }}
                {{ Form::text('gift', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Gift')]) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('description', __('Description'), ['class' => 'col-form-label ']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'), 'rows' => '3', 'required' => 'required']) }}
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
</div>
{{ Form::close() }}
