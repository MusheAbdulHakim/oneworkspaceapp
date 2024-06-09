    {{ Form::open(['route' => ['vendor.bill.send.mail', $bill_id]]) }}
    <div class="modal-body">
        <div class="row">
            <div class="form-group col-md-12">
                {{ Form::label('email', __('Email')) }}
                {{ Form::text('email', '', ['class' => 'form-control', 'required' => 'required']) }}
                @error('email')
                    <span class="invalid-email" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </span>
                @enderror
            </div>
        </div>
    </div>
    <div class="modal-footer">
        {{ Form::submit(__('Send'), ['class' => 'btn  btn-primary']) }}
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
    </div>
    {{ Form::close() }}
