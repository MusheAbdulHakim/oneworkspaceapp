@props(['events' => $events])
@push('scripts')
    <script src="{{ asset('js/fullcalendar/index.global.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('multi_month_calendar_component');

            var myCalendar = new FullCalendar.Calendar(calendarEl, {
                initialDate: "{{ now()->format('Y-m-d') }}",
                editable: true,
                selectable: true,
                dayMaxEvents: true, // allow "more" link when too many events
                events: @json($events),
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    if (info.event.url) {
                        window.open(info.event.url);
                    }
                }
            }).render();

        });
    </script>
@endpush

@push('css')
    <style>
        #multi_month_calendar_component {
            height: 50vh;
            box-shadow: 1rem;
        }

        .fc-bg-event {
            background-color: rgb(55, 55, 229) !important;
        }
    </style>
@endpush

<div id='multi_month_calendar_component'></div>
