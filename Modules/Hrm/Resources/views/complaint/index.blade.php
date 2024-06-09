@extends('layouts.main')
@section('page-title')
    {{ __('Manage Complaint') }}
@endsection
@section('page-breadcrumb')
{{ __('Complaint') }}
@endsection
@section('page-action')
<div>
    @permission('complaint create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Complaint') }}" data-url="{{route('complaint.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
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
                                <th>{{ __('Complaint From') }}</th>
                                <th>{{ __('Complaint Against') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Complaint Date') }}</th>
                                <th>{{ __('Description') }}</th>
                                @if (Laratrust::hasPermission('complaint edit') || Laratrust::hasPermission('complaint delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($complaints as $complaint)
                            <tr>
                                <td>{{ !empty( Modules\Hrm\Entities\Employee::getEmployee($complaint->complaint_from)) ? Modules\Hrm\Entities\Employee::getEmployee($complaint->complaint_from)->name : '--' }}</td>
                                <td>{{ !empty( Modules\Hrm\Entities\Employee::getEmployee($complaint->complaint_against)) ? Modules\Hrm\Entities\Employee::getEmployee($complaint->complaint_against)->name : '--' }}</td>
                                <td>{{ $complaint->title }}</td>
                                <td>{{ company_date_formate($complaint->complaint_date) }}</td>
                                <td>
                                    <p style="white-space: nowrap;
                                        width: 200px;
                                        overflow: hidden;
                                        text-overflow: ellipsis;">{{  !empty($complaint->description) ? $complaint->description : '' }}
                                    </p>
                                </td>
                                @if (Laratrust::hasPermission('complaint edit') || Laratrust::hasPermission('complaint delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('complaint edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('complaint/' . $complaint->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Complaint') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission

                                            @permission('complaint delete')
                                            <div class="action-btn bg-danger ms-2">
                                                    {{Form::open(array('route'=>array('complaint.destroy', $complaint->id),'class' => 'm-0'))}}
                                                    @method('DELETE')
                                                        <a 
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$complaint->id}}"><i
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

