@extends('layouts.main')
@section('page-title')
    {{ __('Manage Award Type') }}
@endsection
@section('page-breadcrumb')
{{ __('Award Type') }}
@endsection
@section('page-action')
<div>
    @permission('awardtype create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Award Type') }}" data-url="{{route('awardtype.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
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
                                <th>{{ __('Award Type') }}</th>
                                @if (Laratrust::hasPermission('awardtype edit') || Laratrust::hasPermission('awardtype delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($awardtypes as $awardtype)
                            <tr>
                                <td>{{ $awardtype->name }}</td>
                                @if (Laratrust::hasPermission('awardtype edit') || Laratrust::hasPermission('awardtype delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('awardtype edit')
                                            <div class="action-btn bg-info ms-2">
                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ route('awardtype.edit', $awardtype->id) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('Edit Award Type') }}"
                                                    data-bs-original-title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endpermission
                                            @permission('awardtype delete')
                                            <div class="action-btn bg-danger ms-2">
                                                {{Form::open(array('route'=>array('awardtype.destroy', $awardtype->id),'class' => 'm-0'))}}
                                                @method('DELETE')
                                                    <a 
                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$awardtype->id}}"><i
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

