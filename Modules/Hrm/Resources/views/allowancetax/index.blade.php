@extends('layouts.main')
@section('page-title')
    {{ __('Manage Allowance Tax') }}
@endsection
@section('page-breadcrumb')
{{ __('Allowance Tax') }}
@endsection
@section('page-action')
<div>
    @permission('allowance tax create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Allowance Tax') }}" data-url="{{route('allowancetax.create')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
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
    <div class="col-sm-9">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 " >
                        <thead>
                            <tr>
                                <th>{{ __('Description') }}</th>
                                <th>{{ __('Amount') }}</th>
                                @if (Laratrust::hasPermission('allowance tax edit') || Laratrust::hasPermission('allowance tax delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allowancetaxs as $allowancetax)
                            <tr>
                                <td>{{ $allowancetax->description }}</td>
                                <td>{{ $allowancetax->amount }}</td>
                                @if (Laratrust::hasPermission('allowance tax edit') || Laratrust::hasPermission('allowance tax delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('allowance tax edit')
                                            <div class="action-btn bg-info ms-2">
                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ route('allowancetax.edit', $allowancetax->id) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('Edit Allowance Tax') }}"
                                                    data-bs-original-title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endpermission
                                            @permission('allowance tax delete')
                                            <div class="action-btn bg-danger ms-2">
                                                {{Form::open(array('route'=>array('allowancetax.destroy', $allowancetax->id),'class' => 'm-0'))}}
                                                @method('DELETE')
                                                    <a
                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$allowancetax->id}}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                {{Form::close()}}
                                            </div>
                                            @endpermission
                                        </span>
                                    </td>
                                @endif
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

