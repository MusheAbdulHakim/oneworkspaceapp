@extends('vendor.installer.layouts.master')

@section('template_title')
    {{ trans('installer_messages.final.templateTitle') }}
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    {{ trans('installer_messages.final.title') }}
@endsection

@section('container')
    <p style="color:red;"><strong>Default Super Admin Created : superadmin@example.com / 1234</strong></p>
    <p style="color:red;"><strong>Default Company Created : company@example.com / 1234</strong></p>


    <p><strong><small>{{ trans('installer_messages.final.console') }}</small></strong></p>
    <pre><code>{{ $finalMessages }}</code></pre>

    <p><strong><small>{{ trans('installer_messages.final.log') }}</small></strong></p>
    <pre><code>{{ $finalStatusMessage }}</code></pre>

    <p><strong><small>{{ trans('installer_messages.final.env') }}</small></strong></p>
    <pre><code>{{ $finalEnvFile }}</code></pre>

    <div class="buttons">
        <a href="{{ route('users.index') }}" class="button">{{ trans('installer_messages.final.exit') }}</a>
    </div>

@endsection
