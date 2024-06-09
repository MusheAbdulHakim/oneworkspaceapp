@extends('layouts.main')
@section('page-title')
{{ __('Profile')}}
@endsection
@section('page-breadcrumb')
{{ __('Profile') }}
@endsection
@push('scripts')
    <script>
        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300
        })
    </script>
@endpush
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-xl-3">
                <div class="card sticky-top" style="top:30px">
                    <div class="list-group list-group-flush" id="useradd-sidenav">
                        <a href="#useradd-1"
                            class="list-group-item list-group-item-action border-0">{{ __('Personal Info') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        <a href="#useradd-2"
                            class="list-group-item list-group-item-action border-0">{{ __('Change Password') }} <div
                                class="float-end"><i class="ti ti-chevron-right"></i></div></a>
                        @stack('profile_setting_sidebar')
                    </div>
                </div>
            </div>


            <div class="col-xl-9">
                <div id="useradd-1">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">{{ __('Personal Information') }}</h5>
                            <small> {{ __('Details about your personal information') }}</small>
                        </div>
                        {{ Form::model($userDetail, ['route' => ['edit.profile'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}
                        <div class="card-body">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <img src="{{!empty($userDetail->avatar) ? get_file($userDetail->avatar) : get_file('uploads/users-avatar/avatar.png')}}" id="myAvatar"  alt="user-image" class="rounded-circle img-thumbnail m-2 w-25">
                                        <div class="choose-files mt-3">
                                            <label for="avatar">
                                                <div class=" bg-primary "> <i class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                                <input type="file" accept="image/png, image/gif, image/jpeg,  image/jpg"  class="form-control" name="profile" id="avatar" data-filename="avatar-logo">
                                            </label>
                                        </div>
                                        @error('avatar')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <small class="">{{ __('Please upload a valid image file. Size of image should not be more than 2MB.') }}</small>
                                </div>
                                <div class="col-lg-6 mb-3">
                                    <div class="form-group">
                                        <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                        <input class="form-control @error('name') is-invalid @enderror" name="name" type="text" id="fullname" placeholder="{{ __('Enter Your Name') }}" value="{{ $userDetail->name }}" required autocomplete="name">
                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="email" class="form-label">{{ __('Email') }}</label>
                                        <input class="form-control @error('email') is-invalid @enderror" name="email" type="text" id="email" placeholder="{{ __('Enter Your Email Address') }}" value="{{ $userDetail->email }}" required autocomplete="email">
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                         </div>
                        {{ Form::close() }}
                    </div>
                </div>

                <div id="useradd-2">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-2">{{ __('Change Password') }}</h5>
                            <small> {{ __('Details about your account password change') }}</small>
                        </div>
                        {{ Form::model($userDetail, ['route' => ['update.password', $userDetail->id], 'method' => 'post']) }}
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('current_password', __('Current Password'), ['class' => 'col-form-label text-dark']) }}
                                        {{ Form::password('current_password', ['class' => 'form-control','required'=>'required', 'placeholder' => __('Enter Current Password')]) }}
                                        @error('current_password')
                                            <span class="invalid-current_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('new_password', __('New Password'), ['class' => 'col-form-label text-dark']) }}
                                        {{ Form::password('new_password', ['class' => 'form-control','required'=>'required', 'placeholder' => __('Enter New Password')]) }}
                                        @error('new_password')
                                            <span class="invalid-new_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        {{ Form::label('confirm_password', __('New Confirm Password'), ['class' => 'col-form-label text-dark']) }}
                                        {{ Form::password('confirm_password', ['class' => 'form-control','required'=>'required', 'placeholder' => __('Enter Confirm Password')]) }}
                                        @error('confirm_password')
                                            <span class="invalid-confirm_password" role="alert">
                                                <strong class="text-danger">{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                         </div>
                        {{ Form::close() }}
                    </div>
                </div>
                @stack('profile_setting_sidebar_div')
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script type="text/javascript">
    $('#avatar').change(function(){

    let reader = new FileReader();
    reader.onload = (e) => {
        $('#myAvatar').attr('src', e.target.result);
    }
    reader.readAsDataURL(this.files[0]);

    });
</script>
@endpush
