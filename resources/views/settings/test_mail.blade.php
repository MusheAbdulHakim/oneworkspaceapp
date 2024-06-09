<form action="{{ $data['route'] }}" enctype="multipart/form-data" method="POST" id="test-mail-form">
    @csrf
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="name" class="form-label">{{ __('Email') }}</label>
                    <input class="form-control" placeholder="{{ __('Enter Email') }}" required="required" name="email" type="text" id="name">
                    <input type="hidden" name="mail_driver" value="{{ $data['mail_driver'] }}" />
                    <input type="hidden" name="mail_host" value="{{ $data['mail_host'] }}" />
                    <input type="hidden" name="mail_port" value="{{ $data['mail_port'] }}" />
                    <input type="hidden" name="mail_username" value="{{ $data['mail_username'] }}" />
                    <input type="hidden" name="mail_password" value="{{ $data['mail_password'] }}" />
                    <input type="hidden" name="mail_from_address" value="{{ $data['mail_from_address'] }}" />
                    <input type="hidden" name="mail_encryption" value="{{ $data['mail_encryption'] }}" />
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <button class="btn btn-primary pull-right" type="button" id="test-send-mail">{{ __('Send') }}</button>
    </div>
</form>
