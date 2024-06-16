@extends('layouts.main')

@section('page-title')
    {{__('My Calendar')}}
@endsection

@section('page-action')
    <div>
        <a data-url="{{ route('mycalendar.create') }}"
            data-ajax-popup="true"
            data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Add Event') }}"
            class="btn btn-sm btn-primary btn-icon"
            href="javascript:void(0)">
            <i class="ti ti-plus"></i>
        </a>

        <a href="{{ route('mycalendar.index') }}"
            data-bs-toggle="tooltip" data-bs-original-title="{{ __('List View') }}"
                class="btn btn-sm btn-primary btn-icon ">
                <i class="ti ti-list"></i>
        </a>
    </div>
@endsection

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
        initialDate: "{{ now()->format('Y-m-d') }}",
        editable: true,
        selectable: true,
        dayMaxEvents: true, // allow "more" link when too many events
        events: @json($events->all()),
        eventClick: function(info) {
            info.jsEvent.preventDefault(); // don't let the browser navigate
            if (info.event.url) {
                window.open(info.event.url);
            }
        }

      });

      calendar.render();
    });

  </script>
@endpush



