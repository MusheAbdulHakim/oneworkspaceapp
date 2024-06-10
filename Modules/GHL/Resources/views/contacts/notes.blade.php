<div class="modal-body">
    <h6 class="sub-title">{{__('Contact Notes')}}</h6>
    @if (!empty($notes))
    <div class="table-responsive">
        <table id="notes_table" class="table table-bordered dt-responsive pc-dt-simple">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Body</th>
                </tr>
            </thead>
            <tbody>
                @foreach($notes as $i => $note)
                @if(!empty($note) && is_array($note))
                <tr>
                    <td>{{++$i}}</td>
                    <td>{{$note['title']}}</td>
                    <td>{{$note['body']}}</td>
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
