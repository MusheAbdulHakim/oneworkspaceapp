@extends('layouts.main')
@php
    if ($project->type == 'project') {
        $name = 'Project';
    } else {
        $name = 'Project Template';
    }
@endphp
@section('page-title')
    {{ __($name . ' Detail') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('Modules/Taskly/Resources/assets/css/custom.css') }}" type="text/css" />
@endpush
@section('page-breadcrumb')
    {{ __($name . ' Detail') }}
@endsection

@section('page-action')
    @if ($project->type == 'project')
        @stack('addButtonHook')
    @else
        @stack('projectConvertButton')
    @endif
    <div class="col-md-auto col-sm-4 pb-3">
        <a href="#" class="btn btn-xs btn-primary btn-icon-only col-12 cp_link"
            data-link="{{ route('project.shared.link', [\Illuminate\Support\Facades\Crypt::encrypt($project->id)]) }}"
            data-toggle="tooltip" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Copy') }}">
            <span class="btn-inner--text text-white">
                <i class="ti ti-copy"></i></span>
        </a>
    </div>
    @permission('project setting')
        <div class="col-sm-auto">
            <a href="#" class="btn btn-xs btn-primary btn-icon-only col-12"
                data-title="{{ __('Shared Project Settings') }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Shared Project Setting') }}"
                data-url="{{ route('project.setting', [$project->id]) }}">
                <i class="ti ti-settings"></i>
            </a>
        </div>
    @endpermission
    <div class="col-sm-auto">
        <a href="{{ route('projects.gantt', [$project->id]) }}"
            class="btn btn-xs btn-primary btn-icon-only width-auto ">{{ __('Gantt Chart') }}</a>
    </div>
    @permission('task manage')
        <div class="col-sm-auto">
            <a href="{{ route('projects.task.board', [$project->id]) }}"
                class="btn btn-xs btn-primary btn-icon-only width-auto ">{{ __('Task Board') }}</a>
        </div>
    @endpermission
    @permission('bug manage')
        <div class="col-sm-auto">
            <a href="{{ route('projects.bug.report', [$project->id]) }}"
                class="btn btn-xs btn-primary btn-icon-only width-auto">{{ __('Bug Report') }}</a>
        </div>
    @endpermission
    @permission('project tracker manage')
        @if (module_is_active('TimeTracker'))
            <div class="col-sm-auto">
                <a href="{{ route('projecttime.tracker', [$project->id]) }}"
                    class="btn btn-xs btn-primary btn-icon-only width-auto ">{{ __('Tracker') }}</a>
            </div>
        @endif
    @endpermission
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.min.css') }}">
    <style>
        @media (max-width: 1300px) {
            .row1 {
                display: flex;
                flex-wrap: wrap;
            }
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="row">
                        <div class="col-xxl-8">
                            <div class="card bg-primary">
                                <div class="card-body">
                                    <div class="d-block d-sm-flex align-items-center justify-content-between">
                                        <h4 class="text-white"> {{ $project->name }}</h4>
                                        <div class="d-flex  align-items-center row1">
                                            @if ($project->type == 'project')
                                                <div class="px-3">
                                                    <span class="text-white text-sm">{{ __('Start Date') }}:</span>
                                                    <h5 class="text-white text-nowrap">
                                                        {{ company_date_formate($project->start_date) }}
                                                    </h5>
                                                </div>
                                                <div class="px-3">
                                                    <span class="text-white text-sm">{{ __('Due Date') }}:</span>
                                                    <h5 class="text-white text-nowrap">
                                                        {{ company_date_formate($project->end_date) }}
                                                    </h5>
                                                </div>
                                                <div class="px-3">
                                                    <span class="text-white text-sm">{{ __('Total Members') }}:</span>
                                                    <h5 class="text-white text-nowrap">
                                                        {{ (int) $project->users->count() + (int) $project->clients->count() }}
                                                    </h5>
                                                </div>
                                            @endif
                                            <div class="px-3 py-2">
                                                @if ($project->status == 'Finished')
                                                    <div class="badge  bg-success p-2 px-3 rounded"> {{ __('Finished') }}
                                                    </div>
                                                @elseif($project->status == 'Ongoing')
                                                    <div class="badge  bg-secondary p-2 px-3 rounded">{{ __('Ongoing') }}
                                                    </div>
                                                @else
                                                    <div class="badge bg-warning p-2 px-3 rounded">{{ __('OnHold') }}
                                                    </div>
                                                @endif

                                            </div>
                                        </div>

                                        @if (!$project->is_active)
                                            <button class="btn btn-light d">
                                                <a href="#" class="" title="{{ __('Locked') }}">
                                                    <i class="ti ti-lock"> </i></a>
                                            </button>
                                        @else
                                            <div class="d-flex align-items-center ">
                                                @permission('project edit')
                                                    <div class="btn btn-light d-flex align-items-between me-3">
                                                        <a href="#" class="" data-size="lg"
                                                            data-url="{{ route('projects.edit', [$project->id]) }}"
                                                            data-="" data-ajax-popup="true"
                                                            data-title="{{ __('Edit ') . $name }}" data-toggle="tooltip"
                                                            title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil"> </i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('project delete')
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['projects.destroy', $project->id],
                                                        'id' => 'delete-form-' . $project->id,
                                                    ]) !!}
                                                    <button class="btn btn-light d" type="button"><a href="#"
                                                            data-toggle="tooltip" title="{{ __('Delete') }}"
                                                            class="bs-pass-para show_confirm"><i class="ti ti-trash">
                                                            </i></a></button>
                                                    {!! Form::close() !!}
                                                @endpermission

                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if ($project->type == 'project')
                                    <div class="col-lg-3 col-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="theme-avtar bg-primary">
                                                        <i class="fas fas fa-calendar-day"></i>
                                                    </div>
                                                    <div class="col text-end">
                                                        <h6 class="text-muted">{{ __('Days left') }}</h6>
                                                        <span class="h6 font-weight-bold mb-0 ">{{ $daysleft }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-6">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="theme-avtar bg-info">
                                                        <i class="fas fa-money-bill-alt"></i>
                                                    </div>
                                                    <div class="col text-end">
                                                        <h6 class="text-muted">{{ __('Budget') }}</h6>
                                                        <span
                                                            class="h6 font-weight-bold mb-0 ">{{ company_setting('defult_currancy') }}
                                                            {{ number_format($project->budget) }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @php
                                    $class = $project->type == 'template' ? 'col-lg-6 col-6' : 'col-lg-3 col-6';
                                @endphp
                                <div class="{{ $class }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="fas fa-check-double"></i>
                                                </div>
                                                <div class="col text-end">
                                                    <h6 class="text-muted">{{ __('Total Task') }}</h6>
                                                    <span
                                                        class="h6 font-weight-bold mb-0 ">{{ $project->countTask() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="{{ $class }}">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="theme-avtar bg-success">
                                                    <i class="fas fa-comments"></i>
                                                </div>
                                                <div class="col text-end">
                                                    <h6 class="text-muted">{{ __('Comment') }}</h6>
                                                    <span
                                                        class="h6 font-weight-bold mb-0 ">{{ $project->countTaskComments() }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="col-xxl-4">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card" style="height: 239px">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-0">{{ __('Progress') }}<span class="text-end">
                                                            ({{ __('Last Week Tasks') }}) </span></h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-2">
                                            <div id="task-chart"></div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($project->type == 'project')
                    <div class="col-xxl-12">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card deta-card">
                                    <div class="card-header">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-0">{{ __('Team Members') }}
                                                    ({{ count($project->users) }})
                                                </h5>
                                            </div>
                                            <div class="float-end">
                                                <p class="text-muted d-sm-flex align-items-center mb-0">

                                                    <a href="#" class="btn btn-sm btn-primary"
                                                        data-ajax-popup="true" data-title="{{ __('Invite') }}"
                                                        data-bs-toggle="tooltip" data-bs-title="{{ __('Invite') }}"
                                                        data-url="{{ route('projects.invite.popup', [$project->id]) }}"><i
                                                            class="ti ti-brand-telegram"></i></a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body top-10-scroll">
                                        @foreach ($project->users as $user)
                                            <ul class="list-group list-group-flush" style="width: 100%;">
                                                <li class="list-group-item px-0">
                                                    <div class="row align-items-center justify-content-between">
                                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                                            <div class="d-flex align-items-center px-2">
                                                                <a href="#" class=" text-start">
                                                                    <img alt="image" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="{{ $user->name }}"
                                                                        @if ($user->avatar) src="{{ get_file($user->avatar) }}" @else src="{{ get_file('avatar.png') }}" @endif
                                                                        class="rounded-circle " width="40px"
                                                                        height="40px">
                                                                </a>
                                                                <div class="px-2">
                                                                    <h5 class="m-0">{{ $user->name }}</h5>
                                                                    <small class="text-muted">{{ $user->email }}<span
                                                                            class="text-primary "> -
                                                                            {{ (int) count($project->user_done_tasks($user->id)) }}/{{ (int) count($project->user_tasks($user->id)) }}</span></small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-auto text-sm-end d-flex align-items-center">
                                                            @auth('web')
                                                                @if ($user->id != Auth::id())
                                                                    @permission('team member remove')
                                                                        <form id="delete-user-{{ $user->id }}"
                                                                            action="{{ route('projects.user.delete', [$project->id, $user->id]) }}"
                                                                            method="POST" style="display: none;"
                                                                            class="d-inline-flex">
                                                                            <a href="#"
                                                                                class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para show_confirm"
                                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                                data-confirm-yes="delete-user-{{ $user->id }}"
                                                                                data-toggle="tooltip"
                                                                                title="{{ __('Delete') }}"><i
                                                                                    class="ti ti-trash"></i></a>

                                                                            @csrf
                                                                            @method('DELETE')
                                                                        </form>
                                                                    @endpermission
                                                                @endif
                                                            @endauth
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card deta-card">
                                    <div class="card-header ">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h5 class="mb-0">{{ __('Clients') }} ({{ count($project->clients) }})
                                                </h5>
                                            </div>
                                            <div class="float-end">
                                                <p class="text-muted d-none d-sm-flex align-items-center mb-0">
                                                    <a href="#" class="btn btn-sm btn-primary"
                                                        data-ajax-popup="true" data-title="{{ __('Share to Client') }}"
                                                        data-toggle="tooltip" title="{{ __('Share to Client') }}"
                                                        data-url="{{ route('projects.share.popup', [$project->id]) }}"><i
                                                            class="ti ti-share"></i></a>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body top-10-scroll">
                                        @foreach ($project->clients as $client)
                                            <ul class="list-group list-group-flush" style="width: 100%;">
                                                <li class="list-group-item px-0">
                                                    <div class="row align-items-center justify-content-between">
                                                        <div class="col-sm-auto mb-3 mb-sm-0">
                                                            <div class="d-flex align-items-center px-2">
                                                                <a href="#" class=" text-start">
                                                                    <img alt="image" data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"
                                                                        title="{{ $client->name }}"
                                                                        @if ($client->avatar) src="{{ get_file($client->avatar) }}" @else src="{{ get_file('avatar.png') }}" @endif
                                                                        class="rounded-circle " width="40px"
                                                                        height="40px">
                                                                </a>
                                                                <div class="px-2">
                                                                    <h5 class="m-0">{{ $client->name }}</h5>
                                                                    <small class="text-muted">{{ $client->email }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-auto text-sm-end d-flex align-items-center">
                                                            @if (\Auth::user()->hasRole('company'))
                                                                @permission('team client remove')
                                                                    <form id="delete-client-{{ $client->id }}"
                                                                        action="{{ route('projects.client.delete', [$project->id, $client->id]) }}"
                                                                        method="POST" style="display: none;"
                                                                        class="d-inline-flex">
                                                                        <a href="#"
                                                                            class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para show_confirm"
                                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                            data-confirm-yes="delete-client-{{ $client->id }}"
                                                                            data-toggle="tooltip"
                                                                            title="{{ __('Delete') }}"><i
                                                                                class="ti ti-trash"></i></a>
                                                                        @csrf
                                                                        @method('DELETE')

                                                                    </form>
                                                                @endpermission
                                                            @endif
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            @if (module_is_active('Account', $project->created_by) && $project->type == 'project')
                                <div class="col-md-4">
                                    <div class="card deta-card">
                                        <div class="card-header">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <h5 class="mb-0">{{ __('Activity') }}</h5>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-body p-3">
                                            <div class="timeline timeline-one-side top-10-scroll"
                                                data-timeline-content="axis" data-timeline-axis-style="dashed"
                                                style="max-height: 300px;">
                                                @foreach ($project->activities as $activity)
                                                    <div class="timeline-block px-2 pt-3">
                                                        @if ($activity->log_type == 'Upload File')
                                                            <span
                                                                class="timeline-step timeline-step-sm border border-primary text-white">
                                                                <i class="fas fa-file text-dark"></i></span>
                                                        @elseif($activity->log_type == 'Create Milestone')
                                                            <span
                                                                class="timeline-step timeline-step-sm border border-info text-white">
                                                                <i class="fas fa-cubes text-dark"></i></span>
                                                        @elseif($activity->log_type == 'Create Task')
                                                            <span
                                                                class="timeline-step timeline-step-sm border border-success text-white">
                                                                <i class="fas fa-tasks text-dark"></i></span>
                                                        @elseif($activity->log_type == 'Create Bug')
                                                            <span
                                                                class="timeline-step timeline-step-sm border border-warning text-white">
                                                                <i class="fas fa-bug text-dark"></i></span>
                                                        @elseif($activity->log_type == 'Move' || $activity->log_type == 'Move Bug')
                                                            <span
                                                                class="timeline-step timeline-step-sm border round border-danger text-white">
                                                                <i class="fas fa-align-justify text-dark"></i></span>
                                                        @elseif($activity->log_type == 'Create Invoice')
                                                            <span
                                                                class="timeline-step timeline-step-sm border border-bg-dark text-white">
                                                                <i class="fas fa-file-invoice text-dark"></i></span>
                                                        @elseif($activity->log_type == 'Invite User')
                                                            <span
                                                                class="timeline-step timeline-step-sm border border-success">
                                                                <i class="fas fa-plus text-dark"></i></span>
                                                        @elseif($activity->log_type == 'Share with Client')
                                                            <span
                                                                class="timeline-step timeline-step-sm border border-info text-white">
                                                                <i class="fas fa-share text-dark"></i></span>
                                                        @elseif($activity->log_type == 'Create Timesheet')
                                                            <span
                                                                class="timeline-step timeline-step-sm border border-success text-white">
                                                                <i class="fas fa-clock-o text-dark"></i></span>
                                                        @endif
                                                        <div class=" row last_notification_text">
                                                            <span class="col-4 m-0 h6 text-sm">
                                                                <span>{{ $activity->log_type }}</span>
                                                            </span>
                                                            <br>
                                                            <span class="col-auto text-start text-sm h6">
                                                                {!! $activity->getRemark() !!}
                                                            </span>
                                                            <div class="col-auto text-end notification_time_main">
                                                                <p class="text-muted">
                                                                    {{ $activity->created_at->diffForHumans() }}
                                                                </p>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    </div>
                @endif
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-xxl-8">
                            <div class="card milestone-card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-0">{{ __('Milestones') }} ({{ count($project->milestones) }})</h5>
                                        </div>
                                        <div class="float-end">
                                            @permission('milestone create')
                                                <p class="text-muted d-sm-flex align-items-center mb-0">
                                                    <a class="btn btn-sm btn-primary" data-size="lg" data-ajax-popup="true"
                                                        data-title="{{ __('Create Milestone') }}"
                                                        data-url="{{ route('projects.milestone', [$project->id]) }}"
                                                        data-toggle="tooltip" title="{{ __('Create Milestone') }}"><i
                                                            class="ti ti-plus"></i></a>
                                                </p>
                                            @endpermission
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body top-10-scroll">

                                    <div class="table-responsive">
                                        <table id="" class="table table-bordered px-2">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Status') }}</th>
                                                    <th>{{ __('Start Date') }}</th>
                                                    <th>{{ __('End Date') }}</th>
                                                    <th>{{ __('Cost') }}</th>
                                                    <th>{{ __('Progress') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($project->milestones as $key => $milestone)
                                                    <tr>
                                                        <td>
                                                            <a href="#" class="d-block font-weight-500 mb-0"
                                                                @permission('milestone delete') data-ajax-popup="true" data-title="{{ __('Milestone Details') }}"  data-url="{{ route('projects.milestone.show', [$milestone->id]) }}" @endpermission>
                                                                <h5 class="m-0"> {{ $milestone->title }} </h5>
                                                            </a>
                                                        </td>
                                                        <td>

                                                            @if ($milestone->status == 'complete')
                                                                <label
                                                                    class="badge bg-success p-2 px-3 rounded">{{ __('Complete') }}</label>
                                                            @else
                                                                <label
                                                                    class="badge bg-warning p-2 px-3 rounded">{{ __('Incomplete') }}</label>
                                                            @endif
                                                        </td>
                                                        <td>{{ $milestone->start_date }}</td>
                                                        <td>{{ $milestone->end_date }}</td>
                                                        <td>{{ company_setting('defult_currancy') }}{{ $milestone->cost }}
                                                        </td>
                                                        <td>
                                                            <div class="progress_wrapper">
                                                                <div class="progress">
                                                                    <div class="progress-bar" role="progressbar"
                                                                        style="width: {{ $milestone->progress }}px;"
                                                                        aria-valuenow="55" aria-valuemin="0"
                                                                        aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                                <div class="progress_labels">
                                                                    <div class="total_progress">

                                                                        <strong> {{ $milestone->progress }}%</strong>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="col-auto">
                                                            @permission('milestone edit')
                                                                <div class="action-btn btn-primary ms-2">
                                                                    <a class="action-btn btn-info mx-1  btn btn-sm d-inline-flex align-items-center"
                                                                        data-ajax-popup="true" data-size="lg"
                                                                        data-title="{{ __('Edit Milestone') }}"
                                                                        data-url="{{ route('projects.milestone.edit', [$milestone->id]) }}"
                                                                        data-toggle="tooltip" title="{{ __('Edit') }}"><i
                                                                            class="ti ti-pencil text-white"></i></a>
                                                                </div>
                                                            @endpermission
                                                            @permission('milestone delete')
                                                                <form id="delete-form1-{{ $milestone->id }}"
                                                                    action="{{ route('projects.milestone.destroy', [$milestone->id]) }}"
                                                                    method="POST" style="display: none;"
                                                                    class="d-inline-flex">
                                                                    <a href="#"
                                                                        class="action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center bs-pass-para show_confirm"
                                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                        data-confirm-yes="delete-form1-{{ $milestone->id }}"
                                                                        data-toggle="tooltip" title="{{ __('Delete') }}"><i
                                                                            class="ti ti-trash"></i></a>
                                                                    @csrf
                                                                    @method('DELETE')

                                                                </form>
                                                            @endpermission

                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xxl-4">
                            <div class="card">
                                <div class="card-header">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h5 class="mb-0">{{ __('Files') }}</h5>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-4">

                                    <div class="author-box-name form-control-label mb-4">

                                    </div>
                                    <div class="col-md-12 dropzone browse-file" id="dropzonewidget">
                                        <div class="dz-message" data-dz-message>
                                            <span>{{ __('Drop files here to upload') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if (module_is_active('Account', $project->created_by) && $project->type == 'project')
                    <div class="col-md-12">
                        <div class="card deta-card">
                            <div class="card-header">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-0">{{ __('Activity') }}</h5>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body p-3">
                                <div class="timeline timeline-one-side top-10-scroll" data-timeline-content="axis"
                                    data-timeline-axis-style="dashed" style="max-height: 300px;">
                                    @foreach ($project->activities as $activity)
                                        <div class="timeline-block px-2 pt-3">
                                            @if ($activity->log_type == 'Upload File')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-primary text-white">
                                                    <i class="fas fa-file text-dark"></i></span>
                                            @elseif($activity->log_type == 'Create Milestone')
                                                <span class="timeline-step timeline-step-sm border border-info text-white">
                                                    <i class="fas fa-cubes text-dark"></i></span>
                                            @elseif($activity->log_type == 'Create Task')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white">
                                                    <i class="fas fa-tasks text-dark"></i></span>
                                            @elseif($activity->log_type == 'Create Bug')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-warning text-white">
                                                    <i class="fas fa-bug text-dark"></i></span>
                                            @elseif($activity->log_type == 'Move' || $activity->log_type == 'Move Bug')
                                                <span
                                                    class="timeline-step timeline-step-sm border round border-danger text-white">
                                                    <i class="fas fa-align-justify text-dark"></i></span>
                                            @elseif($activity->log_type == 'Create Invoice')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-bg-dark text-white">
                                                    <i class="fas fa-file-invoice text-dark"></i></span>
                                            @elseif($activity->log_type == 'Invite User')
                                                <span class="timeline-step timeline-step-sm border border-success"> <i
                                                        class="fas fa-plus text-dark"></i></span>
                                            @elseif($activity->log_type == 'Share with Client')
                                                <span class="timeline-step timeline-step-sm border border-info text-white">
                                                    <i class="fas fa-share text-dark"></i></span>
                                            @elseif($activity->log_type == 'Create Timesheet')
                                                <span
                                                    class="timeline-step timeline-step-sm border border-success text-white">
                                                    <i class="fas fa-clock-o text-dark"></i></span>
                                            @endif
                                            <div class=" row last_notification_text">
                                                <span class="col-1 m-0 h6 text-sm"> <span>{{ $activity->log_type }}
                                                    </span> </span>
                                                <br>
                                                <span class="col text-start text-sm h6"> {!! $activity->getRemark() !!} </span>
                                                <div class="col-auto text-end notification_time_main">
                                                    <p class="text-muted">{{ $activity->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-md-12">
                    @stack('DocumentSection')
                </div>

            </div>
            <!-- [ sample-page ] end -->
        </div>
        <!-- [ Main Content ] end -->
    </div>
@endsection
@push('scripts')
    <script>
        $(document).ready(function() {
            var type = '{{ $project->type }}';
            if (type == 'template') {
                $('.pro_type').addClass('d-none');
            } else {
                $('.pro_type').removeClass('d-none');
            }
        });
    </script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('js/letter.avatar.js') }}"></script>
    <script src="{{ asset('Modules/Taskly/Resources/assets/js/dropzone.min.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {
            maxFiles: 20,
            maxFilesize: 20,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{ route('projects.file.upload', [$project->id]) }}",
            success: function(file, response) {
                if (response.is_success) {
                    dropzoneBtn(file, response);
                    toastrs('{{ __('Success') }}', 'File Successfully Uploaded', 'success');
                } else {
                    myDropzone.removeFile(response.error);
                    toastrs('Error', response.error, 'error');
                }
            },
            error: function(file, response) {
                myDropzone.removeFile(file);
                if (response.error) {
                    toastrs('Error', response.error, 'error');
                } else {
                    toastrs('Error', response, 'error');
                }
            }
        });
        myDropzone.on("sending", function(file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("project_id", {{ $project->id }});
        });

        @if (isset($permisions) && in_array('show uploading', $permisions))
            $(".dz-hidden-input").prop("disabled", true);
            myDropzone.removeEventListeners();
        @endif

        function dropzoneBtn(file, response) {

            var html = document.createElement('div');
            var download = document.createElement('a');
            download.setAttribute('href', response.download);
            download.setAttribute('class', "action-btn btn-primary mx-1  btn btn-sm d-inline-flex align-items-center");
            download.setAttribute('data-toggle', "tooltip");
            download.setAttribute('download', file.name);
            download.setAttribute('title', "{{ __('Download') }}");
            download.innerHTML = "<i class='ti ti-download'> </i>";
            html.appendChild(download);

            @if (isset($permisions) && in_array('show uploading', $permisions))
            @else
                var del = document.createElement('a');
                del.setAttribute('href', response.delete);
                del.setAttribute('class', "action-btn btn-danger mx-1  btn btn-sm d-inline-flex align-items-center");
                del.setAttribute('data-toggle', "popover");
                del.setAttribute('title', "{{ __('Delete') }}");
                del.innerHTML = "<i class='ti ti-trash '></i>";

                del.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (confirm("Are you sure ?")) {
                        var btn = $(this);
                        $.ajax({
                            url: btn.attr('href'),
                            type: 'DELETE',
                            success: function(response) {
                                if (response.is_success) {
                                    btn.closest('.dz-image-preview').remove();
                                    toastrs('{{ __('Success') }}', 'File Successfully Deleted',
                                        'success');
                                } else {
                                    toastrs('{{ __('Error') }}', 'Something Wents Wrong.', 'error');
                                }
                            },
                            error: function(response) {
                                response = response.responseJSON;
                                if (response.is_success) {
                                    toastrs('{{ __('Error') }}', 'Something Wents Wrong.', 'error');
                                } else {
                                    toastrs('{{ __('Error') }}', 'Something Wents Wrong.', 'error');
                                }
                            }
                        })
                    }
                });

                html.appendChild(del);
            @endif

            file.previewTemplate.appendChild(html);
        }

        @php($files = $project->files)
        @foreach ($files as $file)

            @php($storage_file = get_base_file($file->file_path))
            // Create the mock file:
            var mockFile = {
                name: "{{ $file->file_name }}",
                size: "{{ get_size(get_file($file->file_path)) }}"
            };
            // Call the default addedfile event handler
            myDropzone.emit("addedfile", mockFile);
            // And optionally show the thumbnail of the file:
            myDropzone.emit("thumbnail", mockFile, "{{ get_file($file->file_path) }}");
            myDropzone.emit("complete", mockFile);

            dropzoneBtn(mockFile, {
                download: "{{ get_file($file->file_path) }}",
                delete: "{{ route('projects.file.delete', [$project->id, $file->id]) }}"
            });
        @endforeach
    </script>
    <script>
        (function() {
            var options = {
                chart: {
                    height: 135,
                    type: 'line',
                    toolbar: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                series: [
                    @foreach ($chartData['stages'] as $id => $name)
                        {
                            name: "{{ __($name) }}",
                            // data:
                            data: {!! json_encode($chartData[$id]) !!},
                        },
                    @endforeach
                ],
                xaxis: {
                    categories: {!! json_encode($chartData['label']) !!},
                },
                colors: {!! json_encode($chartData['color']) !!},

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },

                yaxis: {
                    tickAmount: 5,
                    min: 1,
                    max: 40,
                },
            };
            var chart = new ApexCharts(document.querySelector("#task-chart"), options);
            chart.render();
        })();

        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('success', '{{ __('Link Copy on Clipboard') }}', 'success')
        });
    </script>
@endpush
