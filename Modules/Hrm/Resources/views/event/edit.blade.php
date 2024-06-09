<style>
    .event_color_active {
    box-shadow: inset 0 0 0 2px #000;
}
</style>
@if (Auth::user()->type == 'company')
    {{ Form::model($event, ['route' => ['event.update', $event->id], 'method' => 'PUT']) }}
    <div class="modal-body">
        <div class="text-end">
            @if (module_is_active('AIAssistant'))
                @include('aiassistant::ai.generate_ai_btn',['template_module' => 'event','module'=>'Hrm'])
            @endif
        </div>
        <div class="row">
            <div class="form-group">
                {{ Form::label('title', __('Event Title'), ['class' => 'col-form-label']) }}
                {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Event Title'),'required' => 'required']) }}
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('start_date', __('Event start Date'), ['class' => 'col-form-label']) }}
                    {{ Form::date('start_date', null, ['class' => 'form-control ', 'autocomplete' => 'off','required' => 'required']) }}
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {{ Form::label('end_date', __('Event End Date'), ['class' => 'col-form-label']) }}
                    {{ Form::date('end_date', null, ['class' => 'form-control ', 'autocomplete' => 'off','required' => 'required'   ]) }}
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('color', __('Event Select Color'), ['class' => 'col-form-label d-block mb-3']) }}
                <div class=" btn-group-toggle btn-group-colors event-tag" data-toggle="buttons">
                    <label
                        class="btn bg-info p-3 {{ $event->color == 'event-info'
                            ? 'custom_color_radio_button event_color_active
                                                                                                                        '
                            : '' }} "><input
                            type="radio" name="color" class="d-none event_color" value="event-info"
                            {{ $event->color == 'event-info' ? 'checked' : '' }}></label>

                    <label
                        class="btn bg-warning p-3 {{ $event->color == 'event-warning' ? 'custom_color_radio_button event_color_active' : '' }}"><input
                            type="radio" class="d-none event_color" name="color" value="event-warning"
                            {{ $event->color == 'event-warning' ? 'checked' : '' }}></label>

                    <label
                        class="btn bg-danger p-3 {{ $event->color == 'event-danger' ? 'custom_color_radio_button event_color_active' : '' }}"><input
                            type="radio" name="color" class="d-none event_color" value="event-danger"
                            {{ $event->color == 'event-danger' ? 'checked' : '' }}></label>


                    <label
                        class="btn bg-success p-3 {{ $event->color == 'event-success' ? 'custom_color_radio_button event_color_active' : '' }}"><input
                            type="radio" class="d-none event_color" name="color" value="event-success"
                            {{ $event->color == 'event-success' ? 'checked' : '' }}></label>

                    <label class="btn p-3 {{ $event->color == 'event-primary' ? 'custom_color_radio_button event_color_active' : '' }}"
                        style="background-color: #51459d !important"><input type="radio" class="d-none event_color"
                            name="color" value="event-primary"
                            {{ $event->color == 'event-primary' ? 'checked' : '' }}></label>
                </div>
            </div>
            <div class="form-group">
                {{ Form::label('description', __('Event Description'), ['class' => 'col-form-label']) }}
                {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '5', 'placeholder' => __('Enter Event Description')]) }}
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <input type="submit" value="{{ __('Save Changes') }}" class="btn  btn-primary">

    </div>
    {{ Form::close() }}
@endif

@if (!in_array(\Auth::user()->type, \Auth::user()->not_emp_type))
        <div class="card">

            <div class="tab-content tab-bordered">
                <div class="tab-pane fade show active" id="tab-1" role="tabpanel">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="">
                                <div class="card-body">
                                    <dl class="row">
                                        <dt class="col-sm-4"><span
                                                class="h6 text-sm mb-0">{{ __('Title') }}</span>
                                        </dt>
                                        <dd class="col-sm-8"><span
                                                class="text-sm">{{ $event->title }}</span></dd>

                                        <dt class="col-sm-4"><span
                                                class="h6 text-sm mb-0">{{ __('Start Date') }}</span>
                                        </dt>
                                        <dd class="col-sm-8"><span
                                                class="text-sm">{{ company_date_formate($event->start_date) }}</span>
                                        </dd>
                                        <dt class="col-sm-4"><span
                                                class="h6 text-sm mb-0">{{ __('End Date') }}</span>
                                        </dt>
                                        <dd class="col-sm-8"><span
                                                class="text-sm">{{ company_date_formate($event->end_date) }}</span>
                                        </dd>
                                        <dt class="col-sm-4"><span
                                                class="h6 text-sm mb-0">{{ __('Description') }}</span></dt>
                                        <dd class="col-sm-8"><span
                                                class="text-sm">{{ $event->description }}</span></dd>
                                    </dl>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
    </div>
@endif

@if (Auth::user()->type == 'hr')
    {{ Form::model($event, ['route' => ['event.update', $event->id], 'method' => 'PUT']) }}
        <div class="col-form-label">
            <div class="text-end">
                @if (module_is_active('AIAssistant'))
                    @include('aiassistant::ai.generate_ai_btn',['template_module' => 'event','module'=>'Hrm'])
                @endif
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('title', __('Event Title'), ['class' => 'col-form-label']) }}
                        {{ Form::text('title', null, ['class' => 'form-control', 'placeholder' => __('Enter Event Title')]) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('start_date', __('Event start Date'), ['class' => 'col-form-label']) }}
                        {{ Form::date('start_date', null, ['class' => 'form-control ', 'autocomplete' => 'off']) }}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        {{ Form::label('end_date', __('Event End Date'), ['class' => 'col-form-label']) }}
                        {{ Form::date('end_date', null, ['class' => 'form-control ', 'autocomplete' => 'off']) }}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        {{ Form::label('color', __('Event Select Color'), ['class' => 'col-form-label d-block mb-3']) }}

                        <div class=" btn-group-toggle btn-group-colors event-tag" data-toggle="buttons">
                            <label
                                class="btn bg-info p-3 {{ $event->color == 'event-info'
                                    ? 'custom_color_radio_button
                                                        '
                                    : '' }} "><input
                                    type="radio" name="color" class="d-none event_color" value="event-info"
                                    {{ $event->color == 'event-info' ? 'checked' : '' }}></label>

                            <label
                                class="btn bg-warning p-3 {{ $event->color == 'event-warning' ? 'custom_color_radio_button' : '' }}"><input
                                    type="radio" class="d-none event_color" name="color" value="event-warning"
                                    {{ $event->color == 'event-warning' ? 'checked' : '' }}></label>

                            <label
                                class="btn bg-danger p-3 {{ $event->color == 'event-danger' ? 'custom_color_radio_button' : '' }}"><input
                                    type="radio" name="color" class="d-none event_color" value="event-danger"
                                    {{ $event->color == 'event-danger' ? 'checked' : '' }}></label>


                            <label
                                class="btn bg-success p-3 {{ $event->color == 'event-success' ? 'custom_color_radio_button' : '' }}"><input
                                    type="radio" class="d-none event_color" name="color" value="event-success"
                                    {{ $event->color == 'event-success' ? 'checked' : '' }}></label>

                            <label class="btn p-3 {{ $event->color == 'event-primary' ? 'custom_color_radio_button' : '' }}"
                                style="background-color: #51459d !important"><input type="radio" class="d-none event_color"
                                    name="color" value="event-primary"
                                    {{ $event->color == 'event-primary' ? 'checked' : '' }}></label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        {{ Form::label('description', __('Event Description'), ['class' => 'col-form-label']) }}
                        {{ Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Event Description')]) }}
                    </div>
                </div>
            </div>
    </div>
    <div class="text-end mb-4">
        <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
        <input type="submit" value="{{ __('Save Changes') }}" class="btn  btn-primary">

    </div>
    {{ Form::close() }}
@endif
