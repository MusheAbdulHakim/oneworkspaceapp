@extends('layouts.main')
@section('page-title')
    {{ __('Manage Attendance List') }}
@endsection
@section('page-breadcrumb')
{{ __('Attendance') }}
@endsection
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('page-action')
    <div>
        @permission('attendance import')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{ __('Import') }}"
                data-url="{{ route('attendance.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
                    class="ti ti-file-import"></i>
            </a>
        @endpermission
        @permission('attendance create')
            <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create Attendance') }}" data-url="{{route('attendance.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
<div class="row">
    <div class=" mt-2 " id="multiCollapseExample1">
        <div class="card">
            <div class="card-body">
                {{ Form::open(array('route' => array('attendance.index'),'method'=>'get','id'=>'attendance_filter')) }}
                <div class="row align-items-center justify-content-end">
                    <div class="col-xl-10">
                        <div class="row">
                            <div class="col-3">
                                <label class="form-label">{{__('Type')}}</label> <br>
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="monthly" value="monthly" name="type" class="form-check-input" {{isset($_GET['type']) && $_GET['type']=='monthly' ?'checked':'checked'}}>
                                    <label class="form-check-label" for="monthly">{{__('Monthly')}}</label>
                                </div>
                                <div class="form-check form-check-inline form-group">
                                    <input type="radio" id="daily" value="daily" name="type" class="form-check-input" {{isset($_GET['type']) && $_GET['type']=='daily' ?'checked':''}}>
                                    <label class="form-check-label" for="daily">{{__('Daily')}}</label>
                                </div>
                            </div>

                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 month">
                                <div class="btn-box">
                                    {{Form::label('month',__('Month'),['class'=>'form-label'])}}
                                    {{Form::month('month',isset($_GET['month'])?$_GET['month']:date('Y-m'),array('class'=>'month-btn form-control month-btn'))}}
                                </div>
                            </div>
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                <div class="btn-box">
                                    {{ Form::label('date', __('Date'),['class'=>'form-label'])}}
                                    {!! Form::date('date', isset($_GET['date'])?$_GET['date']:null, ['class' => 'form-control ','placeholder'=>"Select Date"]) !!}
                                </div>
                            </div>
                            @if(in_array(Auth::user()->type, Auth::user()->not_emp_type))
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                        {{ Form::label('branch', !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch'),['class'=>'form-label'])}}
                                        {{ Form::select('branch', $branch,isset($_GET['branch'])?$_GET['branch']:'', array('class' => 'form-control')) }}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                        {{ Form::label('department', !empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department'),['class'=>'form-label'])}}
                                        {{ Form::select('department', $department,isset($_GET['department'])?$_GET['department']:'', array('class' => 'form-control select')) }}
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                    <div class="col-auto mt-4">
                        <div class="row">
                            <div class="col-auto">
                                <a  class="btn btn-sm btn-primary" onclick="document.getElementById('attendance_filter').submit(); return false;" data-bs-toggle="tooltip" title="{{__('Apply')}}" data-original-title="{{__('apply')}}">
                                    <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                </a>

                                <a href="{{route('attendance.index')}}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="{{ __('Reset') }}" data-original-title="{{__('Reset')}}">
                                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                </a>
                            </div>

                        </div>
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
                                @if (Laratrust::hasPermission('attendance create') || Laratrust::hasPermission('attendance edit') )
                                    <th>{{ __('Employee') }}</th>
                                @endif
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Clock In') }}</th>
                                <th>{{ __('Clock Out') }}</th>
                                <th>{{ __('Late') }}</th>
                                <th>{{ __('Early Leaving') }}</th>
                                <th>{{ __('Overtime') }}</th>
                                @if (Laratrust::hasPermission('attendance edit') || Laratrust::hasPermission('attendance delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($attendances as $attendance)
                                <tr>
                                    @if (Laratrust::hasPermission('attendance create') || Laratrust::hasPermission('attendance edit'))
                                        <td>{{ !empty($attendance->employees) ? $attendance->employees->name : '' }}</td>
                                    @endif
                                    <td>{{ company_date_formate($attendance->date) }}</td>
                                    <td>{{ $attendance->status }}</td>
                                    <td>{{ $attendance->clock_in != '00:00:00' ? $attendance->clock_in : '00:00' }}
                                    </td>
                                    <td>{{ $attendance->clock_out != '00:00:00' ? $attendance->clock_out : '00:00' }}
                                    </td>
                                    <td>{{ $attendance->late }}</td>
                                    <td>{{ $attendance->early_leaving }}</td>
                                    <td>{{ $attendance->overtime }}</td>
                                    <td class="Action">
                                        @if (Laratrust::hasPermission('attendance edit') || Laratrust::hasPermission('attendance delete'))
                                            <span>
                                                @permission('attendance edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a  class="mx-3 btn btn-sm  align-items-center"
                                                            data-url="{{ URL::to('attendance/' . $attendance->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Edit Attendance') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission

                                                @permission('attendance delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {{Form::open(array('route'=>array('attendance.destroy', $attendance->id),'class' => 'm-0'))}}
                                                        @method('DELETE')
                                                            <a
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                                aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$attendance->id}}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                        {{Form::close()}}
                                                    </div>
                                                @endpermission
                                            </span>
                                        @endif
                                    </td>
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
@push('scripts')
    <script>
        $('input[name="type"]:radio').on('change', function(e) {
            var type = $(this).val();
            if (type == 'monthly') {
                $('.month').addClass('d-block');
                $('.month').removeClass('d-none');
                $('.date').addClass('d-none');
                $('.date').removeClass('d-block');
            } else {
                $('.date').addClass('d-block');
                $('.date').removeClass('d-none');
                $('.month').addClass('d-none');
                $('.month').removeClass('d-block');
            }
        });
        $('input[name="type"]:radio:checked').trigger('change');
    </script>
     <script type="text/javascript">
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
                        $('#department').empty();
                        $('#department').append('<option value="" disabled>{{ __('All') }}</option>');

                        $.each(data, function(key, value) {
                            $('#department').append('<option value="' + key + '">' + value + '</option>');
                        });
                        $('#department').val('');
                    }
                });
            }
    </script>
@endpush
