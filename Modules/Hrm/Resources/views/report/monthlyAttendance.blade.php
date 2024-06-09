@extends('layouts.main')
@section('page-title')
    {{ __('Manage Monthly Attendance') }}
@endsection
@section('page-breadcrumb')
    {{ __('Monthly Attendance') }}
@endsection
@section('page-action')
    <div>

    </div>
@endsection
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('content')
    <div class="row">
        <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class=" mt-2 " id="" style="">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['report.monthly.attendance'], 'method' => 'get', 'id' => 'report_monthly_attendance']) }}
                        <div class="d-flex align-items-center justify-content-end">

                            <div class="mx-2">
                                {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}<br>
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="monthly" value="monthly" name="type"
                                        class="form-check-input"
                                        {{ isset($_GET['type']) && $_GET['type'] == 'monthly' ? 'checked' : 'checked' }}>
                                    {{ Form::label('monthly', __('Monthly'), ['class' => 'form-label']) }}
                                </div>
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="weekly" value="weekly" name="type"
                                        class="form-check-input weekly"
                                        {{ isset($_GET['type']) && $_GET['type'] == 'weekly' ? 'checked' : '' }}>
                                    {{ Form::label('weekly', __('Weekly'), ['class' => 'form-label']) }}
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-12 col-12 mx-2 month">
                                <div class="btn-box">
                                    {{ Form::label('month', __(' Month'), ['class' => 'form-label']) }}
                                    {{ Form::month('month', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), ['class' => 'month-btn form-control month-btn']) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-3 col-sm-12 col-12 mx-2 week d-none">
                                <div class="btn-box">
                                    {{ Form::label('week', __(' Week'), ['class' => 'form-label']) }}
                                    {{ Form::week('week', isset($_GET['week']) ? $_GET['week'] : date('Y-\WW'), ['class' => 'week-btn form-control week-btn']) }}
                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-12 mx-2">
                                <div class="btn-box">
                                    {{ Form::label('branch', !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch'), ['class' => 'form-label']) }}
                                    {{ Form::select('branch_id', $branch, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select ' . (!empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('select Branch')))]) }}

                                </div>
                            </div>
                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                <div class="department_div">
                                    {{ Form::label('department', !empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department'), ['class' => 'form-label ']) }}
                                    <select class="form-control  department_id " name="department_id[]"
                                        placeholder="__('Select '.(!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department')))">
                                        <option value="">
                                            {{ __('Select ' . (!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department'))) }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                                {{ Form::label('employee', __(' Employee'), ['class' => 'form-label']) }}
                                <div class="" id="employee_div">
                                    <select class="form-control choices" name="employee_id[]" id="employee_id"
                                        placeholder="Select Employee">
                                    </select>
                                </div>
                            </div>

                            <div class="col-auto float-end ms-2 mt-4">
                                <a class="btn btn-sm btn-primary"
                                    onclick="document.getElementById('report_monthly_attendance').submit(); return false;"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>
                                <a href="{{ route('report.monthly.attendance') }}" class="btn btn-sm btn-danger"
                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Reset">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                </a>
                            </div>
                        </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
        <div id="printableArea">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-report"></i>
                                    </div>
                                    <div class="ms-3">
                                        <input type="hidden"
                                            value="{{ $data['branch'] . ' ' . __('Branch') . ' ' . $data['curMonth'] . ' ' . __('Attendance Report of') . ' ' . $data['department'] . ' ' . 'Department' }}"
                                            id="filename">
                                        <h5 class="mb-0">{{ __('Report') }}</h5>
                                        <div>
                                            <p class="text-muted text-sm mb-0">{{ __('Attendance Summary') }}</p>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($data['branch'] != 'All')
                    <div class="col">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-secondary">
                                            <i class="ti ti-sitemap"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-0">
                                                {{ !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch') }}
                                            </h5>
                                            <p class="text-muted text-sm mb-0">
                                                {{ $data['branch'] }} </p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($data['department'] != 'All')
                    <div class="col">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-template"></i>
                                        </div>
                                        <div class="ms-3">
                                            <h5 class="mb-0">
                                                {{ !empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department') }}
                                            </h5>
                                            <p class="text-muted text-sm mb-0">{{ $data['department'] }}</p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-secondary">
                                        <i class="ti ti-sum"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-0">{{ __('Duration') }}</h5>
                                        <p class="text-muted text-sm mb-0">{{ $data['curMonth'] }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-3 col-md-6">
                    <div class="card mon-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-file-report"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-0">{{ __('Attendance') }}</h5>
                                        <div>
                                            <p class="text-muted text-sm mb-0">{{ __('Total present') }}:
                                                {{ $data['totalPresent'] }}</p>
                                            <p class="text-muted text-sm mb-0">{{ __('Total leave') }}:
                                                {{ $data['totalLeave'] }}</p>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card mon-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-secondary">
                                        <i class="ti ti-clock"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-0">{{ __('Overtime') }}</h5>
                                        <p class="text-muted text-sm mb-0">
                                            {{ __('Total overtime in hours') }} :
                                            {{ number_format($data['totalOvertime'], 2) }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card mon-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-info-circle"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-0">{{ __('Early leave') }}</h5>
                                        <p class="text-muted text-sm mb-0">{{ __('Total early leave in hours') }}:
                                            {{ number_format($data['totalEarlyLeave'], 2) }}</p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card mon-card">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div class="theme-avtar bg-secondary">
                                        <i class="ti ti-alarm"></i>
                                    </div>
                                    <div class="ms-3">
                                        <h5 class="mb-0">{{ __('Employee late') }}</h5>
                                        <p class="text-muted text-sm mb-0">{{ __('Total late in hours') }} :
                                            {{ number_format($data['totalLate'], 2) }}
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>


            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-body table-border-style">
                            <div class="table-responsive py-4 attendance-table-responsive">
                                <table class="table ">
                                    <thead>
                                        <tr>
                                            <th class="active">{{ __('Name') }}</th>
                                            @foreach ($dates as $date)
                                                <th>{{ $date }}</th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($employeesAttendance as $attendance)
                                            <tr>
                                                <td>{{ $attendance['name'] }}</td>
                                                @foreach ($attendance['status'] as $status)
                                                    <td>
                                                        @if ($status == 'P')
                                                            <i
                                                                class="badge bg-success p-2  rounded">{{ __('P') }}</i>
                                                        @elseif($status == 'A')
                                                            <i
                                                                class="badge bg-danger p-2  rounded">{{ __('A') }}</i>
                                                        @endif
                                                    </td>
                                                @endforeach
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
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $('input[name="type"]:radio').on('change', function(e) {
            var type = $(this).val();
            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.week').addClass('d-none');
                $('.week').removeClass('d-block');
            } else {
                $('.week').addClass('d-block');
                $('.week').removeClass('d-none');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
            }
        });

        $('input[name="type"]:radio:checked').trigger('change');
    </script>

    <script>
        $(document).ready(function() {
            var b_id = $('#branch_id').val();
            getDepartment(b_id);
        });
        $(document).on('change', 'select[name=branch_id]', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(bid) {

            $.ajax({
                url: '{{ route('report.getdepartment') }}',
                type: 'POST',
                data: {
                    "branch_id": bid,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {

                    $('.department_id').empty();
                    var emp_selct = ` <select class="form-control  department_id" name="department_id[]" id="choices-multiple"
                                        placeholder="Select Department" multiple >
                                        </select>`;

                    $('.department_id').append('<option value="0"> {{ __('All') }} </option>');
                    $.each(data, function(key, value) {
                        $('.department_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });


                }
            });
        }

        $(document).on('change', '.department_id', function() {
            var department_id = $(this).val();
            getEmployee(department_id);
        });

        function getEmployee(did) {
            $.ajax({
                url: '{{ route('report.getemployee') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {

                    $('.employee_id').empty();
                    var emp_selct = ` <select class="form-control  employee_id" name="employee_id[]" id="choices-multiple1"
                                            placeholder="Select Employee" multiple >
                                            </select>`;
                    $('#employee_div').html(emp_selct);

                    $('.employee_id').append('<option value="0"> {{ __('All') }} </option>');
                    $.each(data, function(key, value) {
                        $('.employee_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple1', {
                        removeItemButton: true,
                    });

                }
            });
        }
    </script>
@endpush
