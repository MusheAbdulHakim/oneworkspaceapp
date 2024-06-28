<div class="modal-lg">
    <div class="modal-body">
        <h6 class="sub-title">{{__('Calender Time slots')}}</h6>
        @if (!empty($appointments))
        <div class="table-responsive">
            <table id="calendars_table" class="table table-bordered dt-responsive pc-dt-simple">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Appointment Status</th>
                        <th>Notes</th>
                        <th>Status</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($appointments as $i => $appointment)
                    @if(!empty($appointment) && is_array($appointment))
                    <tr>
                        <td>{{++$i}}</td>
                        <td>{{$appointment['title']}}</td>
                        <td>{{$appointment['appoinmentStatus']}}</td>
                        <td>{{$appointment['notes']}}</td>
                        <td>{{$appointment['status']}}</td>
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
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Close') }}</button>
    </div>
</div>
