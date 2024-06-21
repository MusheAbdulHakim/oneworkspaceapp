@extends('layouts.main')

@section('page-title')
    {{ __('My Calendar') }}
@endsection

@section('page-action')
    <div>


        <a href="{{ route('mycalendar.index') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('List View') }}"
            class="btn btn-sm btn-primary btn-icon ">
            <i class="ti ti-list"></i>
        </a>
    </div>
@endsection
<a data-url="{{ route('mycalendar.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
    data-bs-original-title="{{ __('Add Event') }}" class="btn btn-sm btn-primary d-none btn-icon" id="addEventbtn"
    href="javascript:void(0)">
    <i class="ti ti-plus"></i>
</a>

@push('css')
    <style>
        #multi_month_calendar {
            height: 90vh;
        }
    </style>
@endpush

@section('content')
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id='multi_month_calendar'></div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/fullcalendar/index.global.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('multi_month_calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                initialDate: "{{ now()->format('Y-m-d') }}",
                editable: true,
                selectable: true,
                dayMaxEvents: true, // allow "more" link when too many events
                events: @json($events->all()),
                selectable: true,
                selectMirror: true,
                eventClick: function(info) {
                    if (info.event && info.event.url) {
                        info.jsEvent.preventDefault(); // don't let the browser navigate
                        window.open(info.event.url);
                    }

                },
                dateClick: function(info) {
                    document.getElementById("addEventbtn").click();
                },
                dayCellContent: function(arg) {
                    return {
                        html: `<a data-ajax-popup="true" class="btn float-start text-center" data-url="{{ route('mycalendar.create') }}" href="javascript:void(0)" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Add Event') }}">${arg.dayNumberText}</a>`
                    }
                },

            });

            calendar.render();
        });
    </script>
@endpush
