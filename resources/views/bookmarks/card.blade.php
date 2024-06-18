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
    @foreach($bookmarks as $i => $bookmark)
    <div class="col-md-4 col-12 product-card">
        @if ($bookmark->type == '1')
        <a href="{{ route('bookmark.iframe',['urlBookmark' => \Crypt::encrypt($bookmark)]) }}">
        @else
        <a href="{{ $bookmark->url }}" target="_blank">
        @endif
            <div class="card" style="background-color: {{ $bookmark->color ?? '#fff' }}">
                <div class="card-body">
                    @if (!empty($bookmark->image))
                    <div class="theme-avtar">
                        <img src="{{ get_file(('uploads/bookmarks/'.$bookmark->image)) }}"
                            alt="site image" style="max-width: 100%;">
                    </div>
                    @endif
                    <h6 class="card-header">{{ $bookmark->title }}</h6>
                    <span>{{ $bookmark->priority }}</span>
                    <span>{{ $bookmark->url }}</span>
                    <p>{{ $bookmark->description }}</p>
                </div>
            </div>
        </a>
    </div>
    @endforeach
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function(){

    })
</script>
@endpush
