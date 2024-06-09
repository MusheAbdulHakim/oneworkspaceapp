@extends('layouts.main')
@section('page-title')
    {{ __('Manage Tax Rebates') }}
@endsection
@section('page-breadcrumb')
{{ __('Tax Rebates') }}
@endsection
@section('page-action')
<div>
    @permission('tax rebate create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Tax Rebate') }}" data-url="{{route('taxrebate.create')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
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
                                @if (Laratrust::hasPermission('tax rebate edit') || Laratrust::hasPermission('tax rebate delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($taxrebates as $taxrebate)
                            <tr>
                                <td>{{ $taxrebate->description }}</td>
                                <td>{{ $taxrebate->amount }}</td>
                                @if (Laratrust::hasPermission('tax rebate edit') || Laratrust::hasPermission('tax rebate delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('tax rebate edit')
                                            <div class="action-btn bg-info ms-2">
                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ route('taxrebate.edit', $taxrebate->id) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('Edit Tax Rebate') }}"
                                                    data-bs-original-title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endpermission
                                            @permission('tax rebate delete')
                                            <div class="action-btn bg-danger ms-2">
                                                {{Form::open(array('route'=>array('taxrebate.destroy', $taxrebate->id),'class' => 'm-0'))}}
                                                @method('DELETE')
                                                    <a
                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$taxrebate->id}}"><i
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

