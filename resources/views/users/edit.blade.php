@php
    if(Auth::user()->type=='super admin')
    {
        $name = __('Customer');
    }
    else{

        $name =__('User');
    }
@endphp
    {{Form::model($user,array('route' => array('users.update', $user->id), 'method' => 'PUT')) }}
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('name',__('Name'),['class'=>'form-label']) }}
                    {{Form::text('name',null,array('class'=>'form-control','placeholder'=>__('Enter '.($name).' Name'),'required'=>'required'))}}
                    @error('name')
                    <small class="invalid-name" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                    @enderror
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-group">
                    {{Form::label('email',__('Email'),['class'=>'form-label'])}}
                    {{Form::email('email',null,array('class'=>'form-control','placeholder'=>__('Enter '.($name).' Email'),'required'=>'required'))}}
                    @error('email')
                    <small class="invalid-email" role="alert">
                        <strong class="text-danger">{{ $message }}</strong>
                    </small>
                    @enderror
                </div>
            </div>
            @if(Auth::user()->type != 'super admin')
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('roles', __('Roles'),['class'=>'form-label']) }}
                        {{ Form::select('roles',$roles, null, ['class' => 'form-control', 'id' => 'user_id', 'data-toggle' => 'select']) }}
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        {{Form::label('mobile_no',__('Mobile No'),['class'=>'form-label'])}}
                        {{Form::text('mobile_no',null,array('class'=>'form-control','placeholder'=>__('Enter User Mobile No'),'required'=>'required'))}}
                        <div class=" text-xs text-danger">
                            {{ __('Please add mobile number with country code. (ex. +91)') }}
                        </div>
                        @error('mobile_no')
                        <small class="invalid-mobile" role="alert">
                            <strong class="text-danger">{{ $message }}</strong>
                        </small>
                        @enderror
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
        {{Form::submit(__('Update'),array('class'=>'btn  btn-primary'))}}
    </div>
    {{Form::close()}}
