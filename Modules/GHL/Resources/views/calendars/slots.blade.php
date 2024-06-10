<div class="modal-body">
    <h6 class="sub-title">{{__('Calender Time slots')}}</h6>
    @if (!empty($slots))
    <div class="table-responsive">
        <table id="calendars_table" class="table table-bordered dt-responsive pc-dt-simple">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                </tr>
            </thead>
            <tbody>
                @foreach($slots as $i => $slot)
                @if(!empty($slot) && is_array($slot))
                <tr>
                    <td>{{++$i}}</td>
                    <td>{{++$i}}</td>
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
