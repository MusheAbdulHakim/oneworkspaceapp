@extends('layouts.auth')
@section('page-title')
    {{ __('Reset Password') }}
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="">
                <h2 class="mb-3 f-w-600">{{ __('New Password') }}</h2>
            </div>
            <form method="POST" action="{{ route('password.update') }}" class="" novalidate="">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">
                <div>
                    <div class="form-group mb-3">
                        <label for="email" class="form-label">{{ __('Email') }}</label>
                        <input id="email" type="email" class="form-control  @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email', $request->email) }}"
                            placeholder="{{ __('E-Mail Address') }}" required autofocus>
                        @error('email')
                            <span class="error invalid-email text-danger" role="alert">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control  @error('password') is-invalid @enderror"
                            name="password" placeholder="{{ __('Password') }}" required>
                        @error('password')
                            <span class="error invalid-password text-danger" role="alert">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                        <input id="password_confirmation" type="password"
                            class="form-control  @error('password_confirmation') is-invalid @enderror"
                            name="password_confirmation" placeholder="{{ __('Confirm Password') }}" required>
                        @error('password_confirmation')
                            <span class="error invalid-password_confirmation text-danger" role="alert">
                                <small>{{ $message }}</small>
                            </span>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-block mt-2"
                            tabindex="4">{{ __('Reset Password') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
