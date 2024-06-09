@extends('layouts.main')
@section('page-title')
    {{ __('Manage Transfer') }}
@endsection
@section('page-breadcrumb')
{{ __('Transfer') }}
@endsection
@section('page-action')
<div>
    @permission('transfer create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Transfer') }}" data-url="{{route('transfer.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
</div>
@endsection
@php
    $company_settings = getCompanyAllSetting();
@endphp
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
                                <th>{{ !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch') }}</th>
                                <th>{{ !empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department') }}</th>
                                <th>{{ __('Transfer Date') }}</th>
                                <th>{{ __('Description') }}</th>
                                @if (Laratrust::hasPermission('transfer edit') || Laratrust::hasPermission('transfer delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transfers as $transfer)
                            <tr>
                                @if (in_array(\Auth::user()->type, \Auth::user()->not_emp_type))
                                    <td>{{ !empty( $transfer->user_id) ? $transfer->users->name : '--' }}</td>
                                @endif
                                <td>{{ !empty($transfer->branch_id) ? ($transfer->branch->name ) ?? '--' : '' }}</td>
                                <td>{{ !empty($transfer->department_id) ? ($transfer->department->name ) ?? '--' : '' }}</td>
                                <td>{{ company_date_formate($transfer->transfer_date) }}</td>
                                <td>
                                    <p style="white-space: nowrap;
                                        width: 200px;
                                        overflow: hidden;
                                        text-overflow: ellipsis;">{{  !empty($transfer->description) ? $transfer->description : '' }}
                                    </p>
                                </td>
                                @if (Laratrust::hasPermission('transfer edit') || Laratrust::hasPermission('transfer delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('transfer edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('transfer/' . $transfer->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Transfer') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission

                                            @permission('transfer delete')
                                            <div class="action-btn bg-danger ms-2">
                                                    {{Form::open(array('route'=>array('transfer.destroy', $transfer->id),'class' => 'm-0'))}}
                                                    @method('DELETE')
                                                        <a 
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$transfer->id}}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                    {{Form::close()}}
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
@push('scripts')
<script type="text/javascript">
    $(document).on('change', '#branch_id', function(){
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
                    $('#department_id').empty();
                    $('#department_id').append('<option value="" disabled>{{ __('Select Department') }}</option>');

                    $.each(data, function(key, value) {
                        $('#department_id').append('<option value="' + key + '">' + value + '</option>');
                    });
                    $('#department_id').val('');
                }
            });
        }
</script>
@endpush

