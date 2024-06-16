@extends('layouts.main')

@section('page-title')
    {{__('My Calendar')}}
@endsection

@section('page-breadcrumb')
    {{ __('My Calendar Event List')}}
@endsection

@section('page-action')
    <div>
        <a href="{{ route('mycalendar.calendar') }}"
            data-bs-toggle="tooltip" data-bs-original-title="{{ __('Add Event') }}"
                class="btn btn-sm btn-primary btn-icon ">
                <i class="ti ti-plus"></i>
        </a>
        <a href="{{ route('mycalendar.calendar') }}"
            data-bs-toggle="tooltip" data-bs-original-title="{{ __('Calendar View') }}"
                class="btn btn-sm btn-primary btn-icon ">
                <i class="ti ti-calendar"></i>
        </a>
    </div>
@endsection


@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="calendars_table" class="table table-bordered dt-responsive pc-dt-simple">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Description</th>
                            <th>Url</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events as $i => $event)
                        <tr>
                            <td>{{++$i}}</td>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->startDate }}</td>
                            <td>{{ $event->endDate }}</td>
                            <td>{{ $event->description }}</td>
                            <td>{{ $event->url }}</td>
                            <td>
                                <div class="action-btn bg-info ms-2">
                                    <a data-ajax-popup="true"
                                        data-title="{{ __('Edit Event') }}"
                                        data-url="{{ route('mycalendar.edit', ['mycalendar' => $event->id]) }}"
                                        data-toggle="tooltip" href="#">
                                        <span class="text-white"><i class="ti ti-edit"></i></span>
                                    </a>
                                </div>
                                <div class="action-btn bg-danger  ms-2">
                                    {{ Form::open(['route' => ['mycalendar.destroy', ['mycalendar' => $event->id]], 'class' => 'm-0']) }}
                                    @method('DELETE')
                                    <a href="javascript:void(0)" class="bs-pass-para show_confirm" aria-label="Delete"
                                        data-confirm="{{ __('Are You Sure?') }}"
                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                        data-confirm-yes="delete-form-{{ $event->id }}">
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
        (function() {

        });
    </script>
@endpush
