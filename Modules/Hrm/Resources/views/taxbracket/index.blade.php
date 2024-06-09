@extends('layouts.main')
@section('page-title')
    {{ __('Manage Tax brackets') }}
@endsection
@section('page-breadcrumb')
{{ __('Tax Rebates') }}
@endsection
@section('page-action')
<div>
    @permission('tax bracket create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Tax Bracket') }}" data-url="{{route('taxbracket.create')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
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
                                <th>{{ __('From') }}</th>
                                <th>{{ __('To') }}</th>
                                <th>{{ __('Fixed Amount') }}</th>
                                <th>{{ __('Percentage') }}</th>
                                @if (Laratrust::hasPermission('tax bracket edit') || Laratrust::hasPermission('tax bracket delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($taxbrackets as $taxbracket)
                            <tr>
                                <td>{{ $taxbracket->from }}</td>
                                <td>{{ $taxbracket->to }}</td>
                                <td>{{ $taxbracket->fixed_amount }}</td>
                                <td>{{ $taxbracket->percentage }}%</td>
                                @if (Laratrust::hasPermission('tax bracket edit') || Laratrust::hasPermission('tax bracket delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('tax bracket edit')
                                            <div class="action-btn bg-info ms-2">
                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ route('taxbracket.edit', $taxbracket->id) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('Edit Tax Bracket') }}"
                                                    data-bs-original-title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endpermission
                                            @permission('tax bracket delete')
                                            <div class="action-btn bg-danger ms-2">
                                                {{Form::open(array('route'=>array('taxbracket.destroy', $taxbracket->id),'class' => 'm-0'))}}
                                                @method('DELETE')
                                                    <a
                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$taxbracket->id}}"><i
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

