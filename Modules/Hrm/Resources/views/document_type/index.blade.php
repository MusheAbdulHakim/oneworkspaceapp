@extends('layouts.main')
@section('page-title')
    {{ __('Manage Document Type') }}
@endsection
@section('page-breadcrumb')
{{ __('Document Type') }}
@endsection
@section('page-action')
<div>
    @permission('documenttype create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Document Type') }}" data-url="{{route('document-type.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
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
                                <th>{{ __('Document') }}</th>
                                <th>{{ __('Required Field') }}</th>
                                @if (Laratrust::hasPermission('documenttype edit') || Laratrust::hasPermission('documenttype delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($document_types as $document_type)
                            <tr>
                                <td>{{ $document_type->name }}</td>
                                <td>
                                    <h6 class="float-left mr-1">
                                        @if ($document_type->is_required == 1)
                                            <div class="badge bg-success p-2 px-3 rounded status-badge7">{{ __('Required') }}</div>
                                        @else
                                            <div class="badge bg-danger p-2 px-3 rounded status-badge7">{{ __('Not Required') }}
                                            </div>
                                        @endif
                                    </h6>
                                </td>
                                @if (Laratrust::hasPermission('documenttype edit') || Laratrust::hasPermission('documenttype delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('documenttype edit')
                                            <div class="action-btn bg-info ms-2">
                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ route('document-type.edit', $document_type->id) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('Edit Document Type') }}"
                                                    data-bs-original-title="{{ __('Edit') }}">
                                                    <i class="ti ti-pencil text-white"></i>
                                                </a>
                                            </div>
                                            @endpermission
                                            @permission('documenttype delete')
                                            <div class="action-btn bg-danger ms-2">
                                                {{Form::open(array('route'=>array('document-type.destroy', $document_type->id),'class' => 'm-0'))}}
                                                @method('DELETE')
                                                    <a 
                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$document_type->id}}"><i
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

