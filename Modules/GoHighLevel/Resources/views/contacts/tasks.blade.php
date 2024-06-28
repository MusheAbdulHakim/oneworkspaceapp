<div class="modal-body">
    <h6 class="sub-title">{{__('Contact Tasks')}}</h6>
    @if (!empty($tasks))
    <div class="table-responsive">
        <table id="tasks_table" class="table table-bordered dt-responsive pc-dt-simple">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Body</th>
                    <th>Completed</th>
                    <th>Due Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($task as $i => $task)
                @if(!empty($appointment) && is_array($task))
                <tr>
                    <td>{{++$i}}</td>
                    <td>{{$task['title']}}</td>
                    <td>{{$task['body']}}</td>
                    <td>{{($task['completed'] == true) ? 'Yes': 'No'}}</td>
                    <td>{{\Carbon\Carbon::parse($task['dueDate'])->format('Y-m-d H:i')}}</td>
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
