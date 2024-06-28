@extends('layouts.main')

@section('page-title')
    {{__('GoHighLevel Calendars')}}
@endsection

@section('page-breadcrumb')
    {{ __('GoHighLevel Calendars')}}
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
                                <th>Name</th>
                                <th>Calendar Type</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($calendars) && count($calendars) > 0)
                            @foreach($calendars as $i => $calendar)
                            @if(!empty($calendar)  && is_array($calendar))
                            <tr>
                                <th>{{++$i}}</th>
                                <td>{{$calendar['name']}}</td>
                                <td>{{$calendar['calendarType']}}</td>
                                <td>
                                    <div class="action-btn bg-warning  ms-2">
                                        <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center"
                                            data-url="{{ route('gohighlevel.calendar.slots',($calendar['id'])) }}"
                                            data-ajax-popup="true"  data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Time Slots')}}">
                                            <span class="text-white"><i class="ti ti-adjustments"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            @endforeach
                            @endif

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
