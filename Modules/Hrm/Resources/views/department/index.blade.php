
@extends('layouts.main')
@php
    $company_settings = getCompanyAllSetting();
@endphp
@section('page-title')
    {{!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department') }}
@endsection
@section('page-breadcrumb')
{{ __('Department') }}
@endsection
@section('page-action')
<div>
    @permission('department create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Department') }}" data-url="{{route('department.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-3">
        @include('hrm::layouts.hrm_setup')
    </div>
    @permission('department name edit')
    <div class="col-sm-9">
        <div class="card">
            <div class="d-flex justify-content-between">
                <div class="card-body table-border-style">
                    <h4>{{!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department')}}</h4>
                </div>
                <div class="d-flex align-items-center px-4">
                    <div class="action-btn bg-info">
                        <a  class="mx-3 btn btn-sm  align-items-center"
                            data-url="{{ route('departmentname.edit') }}"
                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                            data-title="{{ __('Edit '.(!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department')))}}"
                            data-bs-original-title="{{ __('Edit Name') }}">
                            <i class="ti ti-pencil text-white"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endpermission
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 " >
                        <thead>
                            <tr>
                                <th>{{!empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch')}}</th>
                                <th>{{!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department') }}</th>
                                <th width="200px">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody >
                            @forelse ($departments as $department)
                                <tr>
                                    <td>{{ !empty($department->branch) ? ($department->branch->name ) ?? '' : '' }}</td>
                                    <td>{{ $department->name }}</td>
                                    <td class="Action">
                                        <span>
                                            @permission('department edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('department/' . $department->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Department') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                            @permission('department delete')
                                            <div class="action-btn bg-danger ms-2">
                                                {{Form::open(array('route'=>array('department.destroy', $department->id),'class' => 'm-0'))}}
                                                @method('DELETE')
                                                    <a 
                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$department->id}}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                {{Form::close()}}
                                            </div>
                                            @endpermission
                                    </span>
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
</div>
@endsection
