@php
    $user = Auth::user();
    $events = [];
    $holidays = \Modules\Hrm\Entities\Holiday::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace());
    if (!empty($request->date)) {
        $date_range = explode(' to ', $request->date);
        $holidays->where('start_date', '>=', $date_range[0]);
        $holidays->where('end_date', '<=', $date_range[1]);
    }
    $holidays = $holidays->get();
    foreach ($holidays as $key => $holiday) {
        $data = [
            'title' => $holiday->occasion,
            'start' => $holiday->start_date,
            'end' => $holiday->end_date,
            'className' => 'event-danger'
        ];
        array_push($events, $data);
    }
    $hrm_events = \Modules\Hrm\Entities\Event::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace());
    if (!empty($request->date)) {
        $date_range = explode(' to ', $request->date);
        $hrm_events->where('start_date', '>=', $date_range[0]);
        $hrm_events->where('end_date', '<=', $date_range[1]);
    }
    $hrm_events = $hrm_events->get();
    foreach ($hrm_events as $key => $hrm_event) {
        $data = [
            'id'    => $hrm_event->id,
            'title' => $hrm_event->title,
            'start' => $hrm_event->start_date,
            'end' => $hrm_event->end_date,
            'className' => $hrm_event->color
        ];
        array_push($events, $data);
    }

    if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
        $emp = \Modules\Hrm\Entities\Employee::where('user_id', '=', $user->id)->first();
        if (!empty($emp)) {
            $announcements = \Modules\Hrm\Entities\Announcement::orderBy('announcements.id', 'desc')->take(5)->leftjoin('announcement_employees', 'announcements.id', '=', 'announcement_employees.announcement_id')->where('announcement_employees.employee_id', '=', $emp->id)->orWhere(
                function ($q) {
                    $q->where('announcements.department_id', 0)->where('announcements.employee_id', 0);
                }
            )->get();
        } else {
            $announcements = [];
        }

        $date               = date("Y-m-d");
        $time               = date("H:i:s");
        $employeeAttendance = \Modules\Hrm\Entities\Attendance::orderBy('id', 'desc')->where('employee_id', '=', Auth::user()->id)->where('date', '=', $date)->first();
        $company_settings = getCompanyAllSetting();
        $officeTime['startTime'] = !empty($company_settings['company_start_time']) ? $company_settings['company_start_time'] : '09:00';
        $officeTime['endTime']  = !empty($company_settings['company_end_time']) ? $company_settings['company_end_time'] : '18:00';

    } else {
        $announcements = \Modules\Hrm\Entities\Announcement::orderBy('announcements.id', 'desc')->take(5)->where('workspace', getActiveWorkSpace())->get();

        $emp           = \App\Models\User::where('created_by', '=', Auth::user()->id)->emp()->where('workspace_id', getActiveWorkSpace())->get()->toArray();
        $countEmployee = count($emp);
        $emp_id = array_column($emp, 'id');

        $user      = \App\Models\User::whereNotIn('id', $emp_id)->where('created_by', '=', Auth::user()->id)->where('workspace_id', getActiveWorkSpace())->get();
        $countUser = count($user);

        $currentDate = date('Y-m-d');

        $notClockIn    = \Modules\Hrm\Entities\Attendance::where('date', '=', $currentDate)->get()->pluck('employee_id');

        $notClockIns = \App\Models\User::where('created_by', '=', Auth::user()->id)->where('workspace_id', getActiveWorkSpace())->whereNotIn('id', $notClockIn)->emp()->get();

    }

@endphp

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
