@extends('layouts.main')

@section('page-title')
    {{__('GoHighLevel Calendars')}}
@endsection

@section('page-breadcrumb')
    {{ __('GHL Calendars')}}
@endsection


@section('content')
    @if(!empty($calendars) && count($calendars) > 0)
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
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($calendars as $i => $calendar)
                                @if(!empty($calendar)  && is_array($calendar))
                                <tr>
                                    <th>{{++$i}}</th>
                                    <td>{{$calendar['name']}}</td>
                                    <td>{{$calendar['calendarType']}}</td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        (function() {
            
        });
    </script>
@endpush