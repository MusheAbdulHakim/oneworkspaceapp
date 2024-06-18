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
        <a href="{{ route('bookmark.cardview') }}"
            data-bs-toggle="tooltip" data-bs-original-title="{{ __('Card View') }}"
                class="btn btn-sm btn-primary btn-icon ">
                <i class="ti ti-box"></i>
        </a>
    </div>
@endsection


@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="users" class="table table-bordered dt-responsive pc-dt-simple">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Url</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Note</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookmarks as $i => $bookmark)
                            <tr>
                                <td>{{++$i}}</td>
                                <td>
                                    @if ($bookmark->type == '1')
                                    <a href="{{ route('bookmark.iframe',['urlBookmark' => \Crypt::encrypt($bookmark)]) }}">
                                    @else
                                    <a href="{{ $bookmark->url }}" target="_blank">
                                    @endif
                                    {{ $bookmark->url  }}
                                    </a>
                                </td>
                                <td>{{ $bookmark->title }}</td>
                                <td>{{ $bookmark->description }}</td>
                                <td>{{ $bookmark->note }}</td>
                                <td>{{ (!empty($bookmark->status)) ? 'Active': 'Archive' }}</td>
                                <td>
                                    @if (!empty($bookmark->image))
                                    <img src="{{ get_file(('uploads/bookmarks/'.$bookmark->image)) }}" class="img-fluid" alt="{{ $bookmark->title }}">
                                    @endif
                                </td>
                                <td>
                                    <div class="action-btn bg-success ms-2">
                                        <a data-ajax-popup="true"
                                            data-size="lg"
                                            data-title="{{ __('View Bookmark') }}"
                                            data-url="{{ route('bookmark.show', $bookmark->id) }}"
                                            data-toggle="tooltip" href="#">
                                            <span class="text-white"><i class="ti ti-eye"></i></span>
                                        </a>
                                    </div>
                                    <div class="action-btn bg-info ms-2">
                                        <a
                                            data-title="{{ __('Edit Bookmark') }}"
                                            href="{{ route('bookmark.edit', $bookmark->id) }}"
                                            data-toggle="tooltip">
                                            <span class="text-white"><i class="ti ti-edit"></i></span>
                                        </a>
                                    </div>

                                    <div class="action-btn bg-danger  ms-2">
                                        {{ Form::open(['route' => ['bookmark.destroy', $bookmark->id], 'class' => 'm-0']) }}
                                        @method('DELETE')
                                        <a href="javascript:void(0)" class="bs-pass-para show_confirm" aria-label="Delete"
                                            data-confirm="{{ __('Are You Sure?') }}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                            data-confirm-yes="delete-form-{{ $bookmark->id }}">
                                            <span class="text-white"><i class="ti ti-trash"></i></span>
                                        </a>
                                        {{ Form::close() }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){
        if($('#commonModal').hasClass('show')){
            console.log('hello');

        }
    })
</script>
@endpush
