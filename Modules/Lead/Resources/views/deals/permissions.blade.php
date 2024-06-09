{{ Form::model($deal, ['route' => ['deals.client.permissions.store', $deal->id, $client->id], 'method' => 'PUT']) }}
<div class="modal-body">
    <ul class="list-group">
        <div class="row">
            @foreach ($permissions as $key => $permission)
                <div class="col-md-6 py-2 px-2">
                    <li class="list-group-item">
                        <div class="col-12 form-check mt-2 mb-2 ">
                            {{ Form::checkbox('permissions[]', $permission, in_array($permission, $selected) ? true : false, ['class' => 'form-check-input', 'id' => 'permissions_' . $key]) }}
                            {{ Form::label('permissions_' . $key, ucfirst($permission), ['class' => 'form-check-label ml-4']) }}
                        </div>
                    </li>
                </div>
            @endforeach
        </div>
    </ul>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    <button type="submit" class="btn  btn-primary">{{ __('Save') }}</button>
</div>
{{ Form::close() }}
