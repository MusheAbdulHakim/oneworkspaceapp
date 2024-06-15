@push('scripts')
<script src="{{ asset('js/fullcalendar-6.1.14/dist/index.global.min.js') }}"></script>
<script>

    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('multi_month_calendar');

      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'multiMonthYear',
        initialDate: "{{ now()->format('Y-m-d') }}",
        editable: true,
        selectable: true,
        dayMaxEvents: true, // allow "more" link when too many events
        events: [
          {
            title: 'All Day Event',
            start: '2024-01-01'
          },
          {
            title: 'Long Event',
            start: '2024-01-07',
            end: '2024-01-10'
          },
          {
            groupId: 999,
            title: 'Repeating Event',
            start: '2024-01-09T16:00:00'
          },
          {
            groupId: 999,
            title: 'Repeating Event',
            start: '2024-01-16T16:00:00'
          },
          {
            title: 'Conference',
            start: '2024-01-11',
            end: '2023-01-13',
            display: 'background'
          },
          {
            title: 'Meeting',
            start: '2024-01-12T10:30:00',
            end: '2024-01-12T12:30:00',
            display: 'background'
          },
          {
            title: 'Lunch',
            start: '2024-01-12T12:00:00',
            display: 'background'
          },
          {
            title: 'Meeting',
            start: '2024-01-12T14:30:00',
            display: 'background'
          },
          {
            title: 'Happy Hour',
            start: '2024-01-12T17:30:00',
            display: 'background'
          },
          {
            title: 'Dinner',
            start: '2024-01-12T20:00:00',
            display: 'background'
          },
          {
            title: 'Birthday Party',
            start: '2024-01-13T07:00:00',
            display: 'background'
          },
          {
            title: 'Click for Google',
            url: 'http://meet.google.com/',
            start: '2024-01-28',
            display: 'background'
          }
        ]
      });

      calendar.render();
    });

  </script>
@endpush

@push('css')
<style>
#multi_month_calendar {
    height: 90vh;
    box-shadow: 1rem;
}
.fc-bg-event {
    background-color: rgb(55, 55, 229) !important;
}
</style>
@endpush

<div id='multi_month_calendar'></div>
