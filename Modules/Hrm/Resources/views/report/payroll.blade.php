@extends('layouts.main')
@section('page-title')
    {{ __('Manage Payroll Report') }}
@endsection
@section('page-breadcrumb')
{{ __('Payroll Report') }}
@endsection
@section('page-action')
<div>

</div>
@endsection
@push('scripts')
<script>
</script>
@endpush
@section('content')
<div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
        <div class=" mt-2 " id="multiCollapseExample1">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['report.payroll'], 'method' => 'get', 'id' => 'report_payroll']) }}
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('start_month', __('Start Month'), ['class' => 'form-label']) }}
                                {{Form::month('start_month',isset($_GET['start_month'])?$_GET['start_month']:date('Y-m'),array('class'=>'form-control'))}}
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('end_month', __('End Month'), ['class' => 'form-label']) }}
                                {{Form::month('end_month',isset($_GET['end_month'])?$_GET['end_month']:date('Y-m'),array('class'=>'form-control'))}}
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('report_type', __('Report Type'), ['class' => 'form-label']) }}
                                {{ Form::select('report_type', $report_type,isset($_GET['report_type']) ? $_GET['report_type'] : null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('employees', __('Employees'), ['class' => 'form-label']) }}
                                {{ Form::select('employees[]', $employees_box,isset($_GET['employees']) ? $_GET['employees'] : 0, ['class' => 'form-control choices','multiple'=>'multiple','id'=>'employees', 'placeholder' => 'Select Employee']) }}
                            </div>
                        </div>
                        <div class="col-auto float-end ms-2 mt-4">
                            <a  class="btn btn-sm btn-primary apply_btn"
                                data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                data-original-title="{{ __('apply') }}">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="{{ route('report.payroll') }}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"
                                title="{{ __('Reset') }}" data-original-title="{{ __('Reset') }}">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                            </a>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <th>{{ __('Employee ID') }}</th>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Payroll Type') }}</th>
                                @php
                                    $dates = '';
                                    if(isset($_GET['start_month']) && isset($_GET['end_month']))
                                    {
                                        $dates = date("M-Y", strtotime($_GET['start_month'])).' - '. date("M-Y", strtotime($_GET['end_month']));
                                    }
                                @endphp
                                <th>{{ isset($_GET['report_type']) ? !empty($_GET['report_type']) ? str_replace('_', ' ',$_GET['report_type']).' '.$dates : '#' : '#' }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $key => $employee)
                                <tr>
                                    @if(!empty($employee->employee_id))
                                            <td>
                                                @permission('employee show')
                                                    <a class="btn btn-outline-primary"
                                                        href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->user_id)) }}">{{ Modules\Hrm\Entities\Employee::employeeIdFormat($employee->employee_id) }}</a>
                                                @else
                                                    <a  class="btn btn-outline-primary">{{Modules\Hrm\Entities\Employee::employeeIdFormat($employee->employee_id)}}</a>
                                                @endpermission
                                            </td>
                                        @else
                                            <td>--</td>
                                        @endif
                                    <td>{{ $employee->name}}</td>
                                    <td>{{ !empty($employee->salary_type()) ?  ($employee->salary_type()) ?? '' : '' }}</td>
                                    @php
                                        $emp_total = 0;
                                        if(count($data) > 0 && array_key_exists($key,$data))
                                        {
                                            $emp_total = end($data[$key]);
                                        }
                                    @endphp
                                    <td>{{ currency_format($emp_total == 0 ? $employee->salary : $emp_total) }}</td>
                                </tr>
                            @empty
                                    @include('layouts.nodatafound')
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script>
    $(document).on("click",".export_btn",function() {
        $('input[name="is_export"]').val('yes');
        $("#report_payroll").submit();
        setTimeout(() => {
            location.reload();
        }, 500);
    });
    $(document).on("click",".apply_btn",function() {
        $('input[name="is_export"]').val('no');
        $("#report_payroll").submit();
    });
</script>
@endpush
