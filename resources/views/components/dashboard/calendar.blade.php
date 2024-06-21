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
                dateClick: function(info) {
                    document.getElementById("addEventbtn").click();
                },
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

<a data-url="{{ route('mycalendar.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
    data-bs-original-title="{{ __('Add Event') }}" class="btn btn-sm btn-primary d-none btn-icon" id="addEventbtn"
    href="javascript:void(0)">
    <i class="ti ti-plus"></i>
</a>
<div id='multi_month_calendar_component'></div>
