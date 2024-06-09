@extends('layouts.main')
@section('page-title')
    {{ __('Dashboard') }}
@endsection
@section('page-breadcrumb')
    {{ __('Hrm') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Hrm/Resources/assets/css/main.css') }}">
@endpush
@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif
    <div class="row">
        @if (!in_array(Auth::user()->type, Auth::user()->not_emp_type))
            <div class="col-xxl-12">
                <div class="row">
                    <div class="col-xxl-7">
                        <div class="card">
                            <div class="card-header">
                                <h5>{{ __("Holiday's ") }}</h5>
                            </div>
                            <div class="card-body">
                                <div id='calendar' class='calendar'></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-5">
                        <div class="card" style="height: 232px;">
                            <div class="card-header">
                                <h5>{{ __('Mark Attandance ') }}<span>{{ company_date_formate(date('Y-m-d')) }}</span></h5>
                            </div>
                            <div class="card-body">
                                <p class="text-muted pb-0-5">
                                    {{ __('My Office Time: ' . $officeTime['startTime'] . ' to ' . $officeTime['endTime']) }}
                                </p>
                                <div class="row">
                                    <div class="col-md-6 float-right border-right">
                                        {{ Form::open(['url' => 'attendance/attendance', 'method' => 'post']) }}

                                        @if (empty($employeeAttendance) || $employeeAttendance->clock_out != '00:00:00')
                                            <button type="submit" value="0" name="in" id="clock_in"
                                                class="btn btn-primary">{{ __('CLOCK IN') }}</button>
                                        @else
                                            <button type="submit" value="0" name="in" id="clock_in"
                                                class="btn btn-primary disabled" disabled>{{ __('CLOCK IN') }}</button>
                                        @endif
                                        {{ Form::close() }}
                                    </div>
                                    <div class="col-md-6 float-left">
                                        @if (!empty($employeeAttendance) && $employeeAttendance->clock_out == '00:00:00')
                                            {{ Form::model($employeeAttendance, ['route' => ['attendance.update', $employeeAttendance->id], 'method' => 'PUT']) }}
                                            <button type="submit" value="1" name="out" id="clock_out"
                                                class="btn btn-danger">{{ __('CLOCK OUT') }}</button>
                                        @else
                                            <button type="submit" value="1" name="out" id="clock_out"
                                                class="btn btn-danger disabled" disabled>{{ __('CLOCK OUT') }}</button>
                                        @endif
                                        {{ Form::close() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header card-body table-border-style">
                                <h5>{{ __('Announcement List') }}</h5>
                            </div>
                            <div class="card-body" style="height: 270px; overflow:auto">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Title') }}</th>
                                                <th>{{ __('Start Date') }}</th>
                                                <th>{{ __('End Date') }}</th>
                                                <th>{{ __('Description') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list">
                                            @forelse ($announcements as $announcement)
                                                <tr>
                                                    <td>{{ $announcement->title }}</td>
                                                    <td>{{ company_date_formate($announcement->start_date) }}</td>
                                                    <td>{{ company_date_formate($announcement->end_date) }}</td>
                                                    <td>{{ $announcement->description }}</td>
                                                </tr>
                                            @empty
                                                @include('layouts.nodatafound')
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="col-xxl-12">
                <div class="col-xxl-12">
                    <div class="row">
                        <div class="col-xl-5">
                            <div class="card">
                                <div class="card-header card-body table-border-style">
                                    <h5>{{ __("Today's Not Clock In") }}</h5>
                                </div>
                                <div class="card-body" style="height: 290px; overflow:auto">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                @foreach ($notClockIns as $notClockIn)
                                                    <tr>
                                                        <td>{{ $notClockIn->name }}</td>
                                                        <td><span class="absent-btn">{{ __('Absent') }}</span></td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header card-body table-border-style">
                                    <h5>{{ __('Announcement List') }}</h5>
                                </div>
                                <div class="card-body" style="height: 270px; overflow:auto">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Title') }}</th>
                                                    <th>{{ __('Start Date') }}</th>
                                                    <th>{{ __('End Date') }}</th>
                                                    <th>{{ __('Description') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">
                                                @forelse ($announcements as $announcement)
                                                    <tr>
                                                        <td>{{ $announcement->title }}</td>
                                                        <td>{{ company_date_formate($announcement->start_date) }}</td>
                                                        <td>{{ company_date_formate($announcement->end_date) }}</td>
                                                        <td>{{ $announcement->description }}</td>
                                                    </tr>
                                                @empty
                                                    @include('layouts.nodatafound')
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-7">
                            <div class="card">
                                <div class="card-header">
                                    <h5>{{ __("Holiday's & Event's") }}</h5>
                                </div>
                                <div class="card-body card-635 ">
                                    <div id='calendar' class='calendar'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('Modules/Hrm/Resources/assets/js/main.min.js') }}"></script>
    <script type="text/javascript">
        (function() {
            var etitle;
            var etype;
            var etypeclass;
            var calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    timeGridDay: "{{ __('Day') }}",
                    timeGridWeek: "{{ __('Week') }}",
                    dayGridMonth: "{{ __('Month') }}"
                },
                themeSystem: 'bootstrap',
                slotDuration: '00:10:00',
                navLinks: true,
                droppable: true,
                selectable: true,
                selectMirror: true,
                editable: true,
                dayMaxEvents: true,
                handleWindowResize: true,
                events: {!! json_encode($events) !!},
            });
            calendar.render();
        })();
    </script>
@endpush
