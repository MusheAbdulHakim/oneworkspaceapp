@extends('layouts.main')
@section('page-title')
    {{ __('Manage Leave Report') }}
@endsection
@section('page-breadcrumb')
{{ __('Leave Report') }}
@endsection
@section('page-action')
<div>

</div>
@endsection
@push('scripts')
<script>
    $('input[name="type"]:radio').on('change', function(e) {
        var type = $(this).val();
        if (type == 'monthly') {
            $('.month').addClass('d-block');
            $('.month').removeClass('d-none');
            $('.year').addClass('d-none');
            $('.year').removeClass('d-block');
        } else {
            $('.year').addClass('d-block');
            $('.year').removeClass('d-none');
            $('.month').addClass('d-none');
            $('.month').removeClass('d-block');
        }
    });

    $('input[name="type"]:radio:checked').trigger('change');
</script>
@endpush
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('content')
<div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
        <div class=" mt-2 " id="multiCollapseExample1">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['report.leave'], 'method' => 'get', 'id' => 'report_leave']) }}
                    <div class="d-flex align-items-center justify-content-end">
                        <div class="   mx-2">
                            {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}<br>
                            <div class="form-check form-check-inline form-group">
                                <input type="radio" id="monthly" value="monthly" name="type" class="form-check-input"
                                    {{ isset($_GET['type']) && $_GET['type'] == 'monthly' ? 'checked' : 'checked' }}>
                                {{ Form::label('monthly', __('Monthly'), ['class' => 'form-label']) }}
                            </div>
                            <div class="form-check form-check-inline form-group">
                                <input type="radio" id="yearly" value="yearly" name="type" class="form-check-input yearly"
                                    {{ isset($_GET['type']) && $_GET['type'] == 'yearly' ? 'checked' : '' }}>
                                {{ Form::label('yearly', __('Yearly'), ['class' => 'form-label']) }}
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2 month">
                            <div class="btn-box">
                                {{ Form::label('month', __('Month'), ['class' => 'form-label']) }}
                                {{Form::month('month',isset($_GET['month'])?$_GET['month']:date('Y-m'),array('class'=>'month-btn form-control month-btn'))}}
                            </div>
                        </div>

                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2 year d-none">
                            <div class="btn-box">
                                {{ Form::label('year', __('Year'), ['class' => 'form-label']) }}
                                <select class="form-control " id="year" name="year" tabindex="-1" aria-hidden="true">
                                    @for ($filterYear['starting_year']; $filterYear['starting_year'] <= $filterYear['ending_year']; $filterYear['starting_year']++)
                                        <option
                                            {{ isset($_GET['year']) && $_GET['year'] == $filterYear['starting_year'] ? 'selected' : '' }}
                                            {{ !isset($_GET['year']) && date('Y') == $filterYear['starting_year'] ? 'selected' : '' }}
                                            value="{{ $filterYear['starting_year'] }}">
                                            {{ $filterYear['starting_year'] }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>


                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('branch', !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch'), ['class' => 'form-label']) }}
                                {{ Form::select('branch', $branch, isset($_GET['branch']) ? $_GET['branch'] : '', ['class' => 'form-control ','id'=>'branch']) }}
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('department', !empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department'), ['class' => 'form-label']) }}
                                <span class="department_div">
                                    {{ Form::select('department', $department, isset($_GET['department']) ? $_GET['department'] : '', ['class' => 'form-control  department']) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-auto float-end ms-2 mt-4">
                            <a  class="btn btn-sm btn-primary"
                                onclick="document.getElementById('report_leave').submit(); return false;"
                                data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                data-original-title="{{ __('apply') }}">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>

                            <a href="{{ route('report.leave') }}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"
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

    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">

                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <th>{{ __('Employee ID') }}</th>
                                <th>{{ __('Employee') }}</th>
                                <th>{{ __('Approved Leaves') }}</th>
                                <th>{{ __('Rejected Leaves') }}</th>
                                <th>{{ __('Pending Leaves') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leaves as $leave)
                                <tr>
                                    @if(!empty($leave['employee_id']))
                                            <td>
                                                @permission('employee show')
                                                    <a class="btn btn-outline-primary"
                                                        href="{{ route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($leave['id'])) }}">{{ Modules\Hrm\Entities\Employee::employeeIdFormat($leave['employee_id']) }}</a>
                                                @else
                                                    <a  class="btn btn-outline-primary">{{Modules\Hrm\Entities\Employee::employeeIdFormat($leave['employee_id'])}}</a>
                                                @endpermission
                                            </td>
                                        @else
                                            <td>--</td>
                                        @endif
                                    <td>{{ $leave['employee'] }}</td>
                                    <td>
                                        <div class="btn btn-sm btn-info rounded">{{ $leave['approved'] }}
                                            <a  class="text-white"
                                                data-url="{{ route('report.employee.leave', [$leave['id'], 'Approved', isset($_GET['type']) ? $_GET['type'] : 'no', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), isset($_GET['year']) ? $_GET['year'] : date('Y')]) }}"
                                                data-ajax-popup="true" data-size="lg" data-title="{{ __('Approved Leave Detail') }}"
                                                data-bs-toggle="tooltip" title="{{ __('View') }}"
                                                data-original-title="{{ __('View') }}">{{ __('View') }}</a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn btn-sm btn-danger rounded">{{ $leave['reject'] }}
                                            <a  class="text-white"
                                                data-url="{{ route('report.employee.leave', [$leave['id'], 'Reject', isset($_GET['type']) ? $_GET['type'] : 'no', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), isset($_GET['year']) ? $_GET['year'] : date('Y')]) }}"
                                                class="table-action table-action-delete" data-size="lg" data-ajax-popup="true"
                                                data-title="{{ __('Rejected Leave Detail') }}" data-bs-toggle="tooltip"
                                                title="{{ __('View') }}"
                                                data-original-title="{{ __('View') }}">{{ __('View') }}</a>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="m-view-btn btn btn-sm btn-warning rounded">
                                            {{ $leave['pending'] }}
                                            <a  class="text-white"
                                                data-url="{{ route('report.employee.leave', [$leave['id'], 'Pending', isset($_GET['type']) ? $_GET['type'] : 'no', isset($_GET['month']) ? $_GET['month'] : date('Y-m'), isset($_GET['year']) ? $_GET['year'] : date('Y')]) }}"
                                                class="table-action table-action-delete" data-size="lg" data-ajax-popup="true"
                                                data-title="{{ __('Pending Leave Detail') }}" data-bs-toggle="tooltip"
                                                title="{{ __('View') }}"
                                                data-original-title="{{ __('View') }}">{{ __('View') }}</a>
                                        </div>
                                    </td>
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
     <script type="text/javascript">
    $(document).on('change', '#branch', function() {
        $('#branch').trigger("change");
     });
        $(document).on('change', '#branch', function() {
                var branch_id = $(this).val();
                getDepartment(branch_id);
            });

            function getDepartment(branch_id)
            {
                var data = {
                    "branch_id": branch_id,
                    "_token": "{{ csrf_token() }}",
                }
                $.ajax({
                    url: '{{ route('employee.getdepartments') }}',
                    method: 'POST',
                    data: data,
                    success: function(data) {
                        var emp_selct = ` <select class="form-control department" name="department" id="department"> </select>`;
                            $('.department_div').html(emp_selct);

                            $('.department').append('<option value="0"> {{ __('All') }} </option>');
                            $.each(data, function(key, value) {
                                $('.department').append('<option value="' + key + '">' + value +
                                    '</option>');
                            });
                            new Choices('#department', {
                                removeItemButton: true,
                            });
                    }
                });
            }
    </script>
@endpush
