@extends('layouts.main')
@section('page-title')
    {{ __('Manage Employee') }}
@endsection
@section('title')
    {{ __('Manage Employee') }}
@endsection
@section('page-breadcrumb')
    {{ __('Employee') }}
@endsection
@section('page-action')
    <div>
        @permission('employee import')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{ __('Employee Import') }}"
                data-url="{{ route('employee.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
                    class="ti ti-file-import"></i>
            </a>
        @endpermission
        <a href="{{ route('employee.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('List View') }}">
            <i class="ti ti-list text-white"></i>
        </a>
        @permission('employee create')
            <a href="{{ route('employee.create') }}" data-title="{{ __('Create New Employee') }}" data-bs-toggle="tooltip"
                title="" class="btn btn-sm btn-primary" data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <div class="row">
        @foreach ($employees as $employee)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center">
                            @if (!empty($employee->employee_id))
                                <span class="badge bg-primary p-2 px-3 rounded">
                                    @permission('employee show')
                                        <a class="text-white"
                                            href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}">{{ Modules\Hrm\Entities\Employee::employeeIdFormat($employee->employee_id) }}</a>
                                    @else
                                        <a
                                            class="text-white">{{ Modules\Hrm\Entities\Employee::employeeIdFormat($employee->employee_id) }}</a>
                                    @endpermission
                                </span>
                            @else
                                <span class="badge p-2 px-3 rounded">
                                    <td>--</td>
                                </span>
                            @endif
                        </div>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                @if ($employee->is_disable == 1)
                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="feather icon-more-vertical"></i>
                                    </button>
                                @else
                                    <div class="btn">
                                        <i class="ti ti-lock"></i>
                                    </div>
                                @endif
                                <div class="dropdown-menu dropdown-menu-end">
                                    @if (Laratrust::hasPermission('employee show') ||
                                            Laratrust::hasPermission('employee edit') ||
                                            Laratrust::hasPermission('employee delete'))
                                        @permission('employee edit')
                                            <a href="{{ route('employee.edit', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                                                data-size="md" class="dropdown-item"
                                                data-bs-whatever="{{ __('Edit Employee') }}" data-bs-toggle="tooltip"><i
                                                    class="ti ti-pencil"></i>
                                                {{ __('Edit') }}</a>
                                        @endpermission
                                        @if (!empty($employee->employee_id))
                                            @permission('employee show')
                                                <a href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}"
                                                    class="dropdown-item" data-bs-whatever="{{ __('Employee Details') }}"
                                                    data-bs-toggle="tooltip"><i class="ti ti-eye"></i>
                                                    {{ __('Details') }}</a>
                                            @endpermission

                                            @permission('employee delete')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['employee.destroy', $employee->id]]) !!}
                                                <a href="#!" class="dropdown-item  show_confirm" data-bs-toggle="tooltip">
                                                    <i class="ti ti-trash"></i>{{ __('Delete') }}
                                                </a>
                                                {!! Form::close() !!}
                                            @endpermission
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 justify-content-between">
                            <div class="col-12">
                                <div class="text-center client-box">
                                    <div class="avatar-parent-child">
                                        <a href="{{ check_file($employee->avatar) ? get_file($employee->avatar) : 'uploads/users-avatar/avatar.png' }}"
                                            target="_blank">
                                            <img src="{{ check_file($employee->avatar) ? get_file($employee->avatar) : 'uploads/users-avatar/avatar.png' }}"
                                                alt="user-image" class=" rounded-circle" width="120px" height="120px">
                                        </a>
                                    </div>
                                    <div class="h6 mt-2 mb-1">
                                        @permission('employee show')
                                            @if (!empty($employee->employee_id))
                                                <a class="text-primary"
                                                    href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id)) }}">{{ ucfirst($employee->name) }}</a>
                                            @else
                                                <a class="text-primary">{{ ucfirst($employee->name) }}</a>
                                            @endif
                                        @else
                                            <a class="text-primary">{{ ucfirst($employee->name) }}</a>
                                        @endpermission
                                    </div>

                                    <div class="mb-1"><a class="text-sm small text-muted">{{ $employee->email }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        @permission('employee create')
            <div class="col-md-3">
                <a href="{{ route('employee.create') }}" class="btn-addnew-project"
                    data-title="{{ __('Create New Employee') }}" style="padding: 90px 10px;">
                    <div class="badge bg-primary proj-add-icon">
                        <i class="ti ti-plus"></i>
                    </div>
                    <h6 class="mt-4 mb-2">New Employee</h6>
                    <p class="text-muted text-center">Click here to add New Employee</p>
                </a>
            </div>
        @endpermission
    </div>
@endsection
