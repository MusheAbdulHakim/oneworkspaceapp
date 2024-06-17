@extends('layouts.main')

@section('page-breadcrumb')
{{ __('Strategy')}}
@endsection

@section('content')
<div class="row" id="iframe_container">
    <iframe id="page_iframe" src="https://pages.oneworkspace.io/business-strategy"
    seamless="seamless" frameborder="0"></iframe>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function(){
            var parent = document.getElementById('iframe_container');
            var child = document.getElementById('page_iframe');
            child.style.right = child.clientWidth - child.offsetWidth + "px";
        })
    </script>
@endpush


