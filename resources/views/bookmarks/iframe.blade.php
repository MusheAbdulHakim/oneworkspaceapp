@extends('layouts.main')

@section('page-breadcrumb')
{{ __('Bookmark '.$bookmark->url)}}
@endsection

@section('page-action')
    <div>

        <a href="{{ route('bookmark.index') }}"
            data-bs-toggle="tooltip" data-bs-original-title="{{ __('List View') }}"
                class="btn btn-sm btn-primary btn-icon ">
                <i class="ti ti-list"></i>
        </a>
        <a href="{{ route('bookmark.cardview') }}"
            data-bs-toggle="tooltip" data-bs-original-title="{{ __('Card View') }}"
                class="btn btn-sm btn-primary btn-icon ">
                <i class="ti ti-box"></i>
        </a>
    </div>
@endsection

@section('content')
<div class="row" id="iframe_container">
    <iframe id="page_iframe" src="{{ $bookmark->url }}" seamless="seamless" frameborder="0"></iframe>
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




