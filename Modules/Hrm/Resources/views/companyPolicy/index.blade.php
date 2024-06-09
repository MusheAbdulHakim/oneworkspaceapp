@extends('layouts.main')
@section('page-title')
    {{ __('Manage Company Policy') }}
@endsection
@section('page-breadcrumb')
{{ __('Company Policy') }}
@endsection
@section('page-action')
    <div>
        @permission('attendance create')
            <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create Company Policy') }}" data-url="{{route('company-policy.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
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
                                <th>{{ !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Attachment') }}</th>
                                @if (Laratrust::hasPermission('companypolicy edit') || Laratrust::hasPermission('companypolicy delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($companyPolicy as $policy)
                                <tr>
                                    <td>{{ !empty($policy->branches) ? $policy->branches->name : '--' }}</td>
                                    <td>{{ $policy->title }}</td>
                                    <td >
                                        <p style="white-space: nowrap;
                                            width: 200px;
                                            overflow: hidden;
                                            text-overflow: ellipsis;">{{  !empty($policy->description) ? $policy->description : '' }}
                                        </p>
                                    </td>
                                    <td>
                                        @if (!empty($policy->attachment))
                                        <div class="action-btn bg-primary ms-2">

                                            <a  class="mx-3 btn btn-sm align-items-center" href="{{ get_file($policy->attachment) }}" download="">
                                                <i class="ti ti-download text-white"></i>
                                            </a>
                                        </div>
                                            <div class="action-btn bg-secondary ms-2">
                                                <a class="mx-3 btn btn-sm align-items-center" href="{{ get_file($policy->attachment) }}" target="_blank"  >
                                                    <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Preview') }}"></i>
                                                </a>
                                            </div>
                                        @else
                                            <p>--</p>
                                        @endif
                                    </td>
                                    @if (Laratrust::hasPermission('companypolicy edit') || Laratrust::hasPermission('companypolicy delete'))
                                        <td class="Action">
                                            <span>
                                                @permission('companypolicy edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a  class="mx-3 btn btn-sm  align-items-center"
                                                            data-url="{{ URL::to('company-policy/' . $policy->id . '/edit') }}"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Edit Company Policy') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('companypolicy delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {{Form::open(array('route'=>array('company-policy.destroy', $policy->id),'class' => 'm-0'))}}
                                                    @method('DELETE')
                                                        <a 
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$policy->id}}"><i
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
