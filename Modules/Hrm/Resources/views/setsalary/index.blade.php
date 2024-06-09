@extends('layouts.main')
@section('page-title')
    {{ __('Manage Employee Salary') }}
@endsection
@section('page-breadcrumb')
    {{ __('Employee Salary') }}
@endsection
@section('page-action')
    <div>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{ __('Employee Id') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Payroll Type') }}</th>
                                    <th>{{ __('Salary') }}</th>
                                    <th>{{ __('Net Salary') }}</th>
                                    @if (Laratrust::hasPermission('setsalary edit'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>
                                            @if (Laratrust::hasPermission('setsalary show'))
                                                <a
                                                    href="{{ route('setsalary.show', $employee->id) }}"class="btn btn-outline-primary">{{ Modules\Hrm\Entities\Employee::employeeIdFormat($employee->employee_id) }}
                                                </a>
                                            @else
                                                <a class="btn btn-outline-primary">{{ Modules\Hrm\Entities\Employee::employeeIdFormat($employee->employee_id) }}
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $employee->name }}</td>
                                        <td>{{ !empty($employee->salary_type) ? $employee->salaryType->name ?? '--' : '--' }}
                                        </td>
                                        <td>{{ currency_format($employee->salary) }}</td>
                                        <td>{{ !empty($employee->get_net_salary()) ? currency_format($employee->get_net_salary()) : '--' }}
                                            @if (Laratrust::hasPermission('setsalary edit'))
                                        <td class="Action">
                                            <span>
                                                @permission('setsalary edit')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('setsalary.show', $employee->id) }}"
                                                            class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip"
                                                            title="" data-bs-original-title="{{ __('View') }}">
                                                            <i class="ti ti-eye text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                            </span>
                                        </td>
                                @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
