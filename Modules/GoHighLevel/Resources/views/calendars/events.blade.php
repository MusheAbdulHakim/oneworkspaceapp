@extends('layouts.main')

@section('page-title')
    {{__('GoHighLevel Calendar Events')}}
@endsection

@section('page-breadcrumb')
    {{ __('Calendar Events')}}
@endsection


@push('css')

@endpush

@section('content')
@if (!empty($events))
<div class="table-responsive">
    <table id="calendars_table" class="table table-bordered dt-responsive pc-dt-simple">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Address</th>
                <th>Appointment Status</th>
                <th>Notes</th>
                <th>Status</th>
                <th>Start Time</th>
                <th>End Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($events as $i => $event)
            @if(!empty($event) && is_array($event))
            <tr>
                <td>{{++$i}}</td>
                <td>{{$event['title']}}</td>
                <td>{{$event['address']}}</td>
                <td>{{$event['appoinmentStatus']}}</td>
                <td>{{$event['notes']}}</td>
                <td>{{$event['status']}}</td>
                <td>
                    @php
                        $date = $contact['startTime'] ?? null;
                        if(!empty($date)){
                            $date = \Carbon\Carbon::parse($date)->format('d M, Y');
                        }
                    @endphp
                    {{$date}}
                </td>
                <td>
                    @php
                        $date = $contact['endTime'] ?? null;
                        if(!empty($date)){
                            $date = \Carbon\Carbon::parse($date)->format('d M, Y');
                        }
                    @endphp
                    {{$date}}
                </td>
            </tr>
            @endif
            @endforeach

        </tbody>
    </table>
</div>
@endif
<div class="row">
    <div id='calendar'></div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth'
            });
            calendar.render();
        });
    (function() {

    });
</script>
@endpush
