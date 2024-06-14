@extends('layouts.main')

@section('page-title')
{{ __('App Integrations')}}
@endsection


@push('css')
    <style>
        iframe {
            display: block;
            border: none;
            height: calc(100vh - 30px);
            width: 100%;
        }
    </style>
@endpush
@section('content')
<div class="row">
    <iframe class="h-vh w-100" src="https://integrations.oneworkspace.io"
    seamless="seamless" frameborder="0"></iframe>
</div>
@endsection


