
@if(!empty($task))
{{ Form::model($task, array('route' => array('deals.tasks.update', $deal->id, $task->id), 'method' => 'PUT')) }}
@else
{{ Form::open(array('route' => ['deals.tasks.store',$deal->id])) }}
@endif
<div class="modal-body">
    <div class="text-end">
        @if (module_is_active('AIAssistant'))
            @include('aiassistant::ai.generate_ai_btn',['template_module' => 'deal task','module'=>'Lead'])
        @endif
    </div>
    <div class="row">
        <div class="col-12 form-group">
            {{ Form::label('name', __('Name'),['class'=>'col-form-label']) }}
            {{ Form::text('name', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('date', __('Date'),['class'=>'col-form-label']) }}
            {{ Form::date('date', null, array('class' => 'form-control','required'=>'required')) }}
        </div>
        <div class="col-6 form-group">
            {{ Form::label('time', __('Time'),['class'=>'col-form-label']) }}
            <div class="input-group timepicker">
                <input class="form-control timepicker" required="required" name="time" type="time" id="time">
            </div>
        </div>
        <div class="col-6 form-group">
            {{ Form::label('priority', __('Priority'),['class'=>'col-form-label']) }}
            <select class="form-control" name="priority" required id="priority">
                @foreach($priorities as $key => $priority)
                    <option value="{{$key}}" @if(isset($task) && $task->priority == $key) selected @endif>{{__($priority)}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-6 form-group">
            {{ Form::label('status', __('Status'),['class'=>'col-form-label']) }}
            <select class="form-control" name="status" required id="status">
                @foreach($status as $key => $st)
                    <option value="{{$key}}" @if(isset($task) && $task->status == $key) selected @endif>{{__($st)}}</option>
                @endforeach
            </select>
        </div>
        @if(empty($task))
            @if (module_is_active('Calender') && company_setting('google_calendar_enable') == 'on')
                @include('calender::setting.synchronize')
            @endif
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Close')}}</button>
    @if(isset($task))
        <button type="submit" class="btn  btn-primary">{{__('Edit')}}</button>
    @else
        <button type="submit" class="btn  btn-primary">{{__('Save')}}</button>
    @endif
</div>

{{ Form::close() }}
