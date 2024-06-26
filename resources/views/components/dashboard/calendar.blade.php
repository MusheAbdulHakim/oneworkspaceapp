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
                events: @json($events),
                dateClick: function(info) {
                    document.getElementById("addEventbtn").click();
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault(); // don't let the browser navigate
                    let event = info.event;
                    if (event.url != '' && event.url != null && event.url != undefined) {
                        window.open(info.event.url);
                    }
                    document.getElementById("addEventbtn").click();
                }
            }).render();
            $('.fc-toolbar-title').addClass('text-capitalize')
            $('.fc-today-button').addClass('text-capitalize')
        });
    </script>
@endpush

@push('css')
    <style>
        #multi_month_calendar_component {
            height: 50vh;
            box-shadow: 1rem;
        }

        .fc-toolbar-title {
            font-size: 1.25rem !important;
        }

        .fc-bg-event {
            background-color: rgb(55, 55, 229) !important;
        }
    </style>
@endpush

<a data-url="{{ route('mycalendar.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
    data-title="{{ __('Add Event') }}" class="btn btn-sm btn-primary d-none btn-icon" id="addEventbtn"
    href="javascript:void(0)">
    <i class="ti ti-plus"></i>
</a>
<div id='multi_month_calendar_component'></div>
