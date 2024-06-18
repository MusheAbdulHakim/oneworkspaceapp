@extends('layouts.main')

@section('page-title')
    {{__('Url Bookmarks')}}
@endsection

@section('page-breadcrumb')
    {{ __('Bookmark List')}}
@endsection

@section('page-action')
    <div>
        <a
            href="{{ route('bookmark.create') }}"
            data-bs-toggle="tooltip"
            data-title="{{ __('Add Bookmark') }}"
            data-bs-original-title="{{ __('Add Bookmark') }}"
            class="btn btn-sm btn-primary btn-icon ">
            <i class="ti ti-plus"></i>
        </a>
        <a href="{{ route('bookmark.index') }}"
            data-bs-toggle="tooltip" data-bs-original-title="{{ __('Bookmark List') }}"
                class="btn btn-sm btn-primary btn-icon ">
                <i class="ti ti-list"></i>
        </a>
    </div>
@endsection


@section('content')
<div class="row">
    <x-dashboard.bookmarks :bookmarks="$bookmarks" />
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function(){

    })
</script>
@endpush
