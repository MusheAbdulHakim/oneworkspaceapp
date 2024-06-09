@extends('layouts.main')
@section('page-title')
    {{ __('Manage Leave') }}
@endsection
@section('page-breadcrumb')
    {{ __('Leave') }}
@endsection
@section('page-action')
    <div>
        @stack('addButtonHook')
        @permission('leave create')
            <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Leave') }}"
                data-url="{{ route('leave.create') }}" data-toggle="tooltip" title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
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
                                    @if (in_array(\Auth::user()->type, \Auth::user()->not_emp_type))
                                        <th>{{ __('Employee') }}</th>
                                    @endif
                                    <th>{{ __('Leave Type') }}</th>
                                    <th>{{ __('Applied On') }}</th>
                                    <th>{{ __('Start Date') }}</th>
                                    <th>{{ __('End Date') }}</th>
                                    <th>{{ __('Total Days') }}</th>
                                    <th>{{ __('Leave Reason') }}</th>
                                    <th>{{ __('status') }}</th>
                                    @if (Laratrust::hasPermission('leave edit') || Laratrust::hasPermission('leave delete') || Laratrust::hasPermission('leave approver manage'))
                                        <th width="200px">{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($leaves as $leave)
                                    <tr>
                                        @if (in_array(\Auth::user()->type, \Auth::user()->not_emp_type))
                                            <td>{{ !empty($leave->user_id) ? $leave->name : '--' }}
                                            </td>
                                        @endif
                                        <td>{{ !empty($leave->leave_type_id) ? $leave->leaveType->title : '' }}
                                        </td>
                                        <td>{{ company_date_formate($leave->applied_on) }}</td>
                                        <td>{{ company_date_formate($leave->start_date) }}</td>
                                        <td>{{ company_date_formate($leave->end_date) }}</td>

                                        <td>{{ $leave->total_leave_days }}</td>
                                        <td>
                                            <p style="white-space: nowrap;
                                        width: 200px;
                                        overflow: hidden;
                                        text-overflow: ellipsis;">
                                                {{ !empty($leave->leave_reason) ? $leave->leave_reason : '' }}
                                            </p>
                                        </td>
                                        <td>
                                            @if ($leave->status == 'Pending')
                                                <div class="badge bg-warning p-2 px-3 rounded status-badge5">
                                                    {{ $leave->status }}</div>
                                            @elseif($leave->status == 'Approved')
                                                <div class="badge bg-success p-2 px-3 rounded status-badge5">
                                                    {{ $leave->status }}</div>
                                            @else
                                                <div class="badge bg-danger p-2 px-3 rounded status-badge5">
                                                    {{ $leave->status }}</div>
                                            @endif
                                        </td>
                                        @if (Laratrust::hasPermission('leave edit') || Laratrust::hasPermission('leave delete') || Laratrust::hasPermission('leave approver manage'))
                                            <td class="Action">
                                                <span>
                                                    @permission('leave approver manage')
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ URL::to('leave/' . $leave->id . '/action') }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                                title="" data-title="{{ __('Manage Leave') }}"
                                                                data-bs-original-title="{{ __('Leave Action') }}">
                                                                <i class="ti ti-caret-right text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @if ($leave->status == 'Pending')
                                                        @permission('leave edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a class="mx-3 btn btn-sm  align-items-center"
                                                                    data-url="{{ URL::to('leave/' . $leave->id . '/edit') }}"
                                                                    data-ajax-popup="true" data-size="md"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-title="{{ __('Edit Leave') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission

                                                        @permission('leave delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {{ Form::open(['route' => ['leave.destroy', $leave->id], 'class' => 'm-0']) }}
                                                                @method('DELETE')
                                                                <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="Delete" aria-label="Delete"
                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                    data-confirm-yes="delete-form-{{ $leave->id }}"><i
                                                                        class="ti ti-trash text-white text-white"></i></a>
                                                                {{ Form::close() }}
                                                            </div>
                                                        @endpermission
                                                    @endif
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


@push('scripts')
    <script>
        $(document).on('change', '#employee_id', function() {
            var employee_id = $(this).val();
            $.ajax({
                url: '{{ route('leave.jsoncount') }}',
                type: 'POST',
                data: {
                    "employee_id": employee_id,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    var oldval = $('#leave_type_id').val();
                    $('#leave_type_id').empty();
                    $('#leave_type_id').append(
                        '<option value="">{{ __('Select Leave Type') }}</option>');

                    $.each(data, function(key, value) {
                        if (value.total_leave >= value.days) {
                            $('#leave_type_id').append('<option value="' + value.id +
                                '" disabled>' + value.title + '&nbsp(' + value.total_leave +
                                '/' + value.days + ')</option>');
                        } else {
                            $('#leave_type_id').append('<option value="' + value.id + '">' +
                                value.title + '&nbsp(' + value.total_leave + '/' + value
                                .days + ')</option>');
                            if (oldval) {
                                if (oldval == value.id) {
                                    $("#leave_type_id option[value=" + oldval + "]").attr(
                                        "selected", "selected");
                                }
                            }
                        }
                    });

                }
            });
        });
    </script>
@endpush
