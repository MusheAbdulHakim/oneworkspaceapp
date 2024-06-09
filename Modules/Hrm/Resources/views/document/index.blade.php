@extends('layouts.main')
@section('page-title')
    {{ __('Manage Document') }}
@endsection
@section('page-breadcrumb')
{{ __('Document') }}
@endsection
@section('page-action')
<div>
    @stack('addButtonHook')
    @permission('document create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Document') }}" data-url="{{route('document.create')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
</div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Hrm/Resources/assets/css/custom.css')}}">
@endpush
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Document') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Description') }}</th>
                                @if (Laratrust::hasPermission('document edit') || Laratrust::hasPermission('document delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $document)
                            <tr>
                                <td>{{ $document->name }}</td>
                                <td>
                                    @if (!empty($document->document))
                                    <div class="action-btn bg-primary ms-2">
                                        <a class="mx-3 btn btn-sm align-items-center" href="{{ get_file($document->document) }}" download>
                                            <i class="ti ti-download text-white"></i>
                                        </a>
                                    </div>
                                        <div class="action-btn bg-secondary ms-2">
                                            <a class="mx-3 btn btn-sm align-items-center" href="{{ get_file($document->document) }}" target="_blank"  >
                                                <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Preview') }}"></i>
                                            </a>
                                        </div>
                                    @else
                                        <p>-</p>
                                    @endif
                                </td>
                                <td>{{ !empty($document->role) ? $document->role_name : 'All' }}</td>
                                <td >
                                    <p style="white-space: nowrap;
                                        width: 200px;
                                        overflow: hidden;
                                        text-overflow: ellipsis;">{{  !empty($document->description) ? $document->description : '' }}
                                    </p>
                                </td>
                                @if (Laratrust::hasPermission('document edit') || Laratrust::hasPermission('document delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('document edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ route('document.edit', $document->id) }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Document') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                            @permission('document delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {{Form::open(array('route'=>array('document.destroy', $document->id),'class' => 'm-0'))}}
                                                    @method('DELETE')
                                                        <a
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$document->id}}"><i
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
