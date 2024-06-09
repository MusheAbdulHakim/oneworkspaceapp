@extends('layouts.main')
@section('page-title')
    {{ __('Manage Vendors') }}
@endsection

@section('page-breadcrumb')
    {{ __('Vendors') }}
@endsection
@section('page-action')
    <div>
        @permission('vendor import')
            <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Vendor Import')}}" data-url="{{ route('vendor.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
            </a>
        @endpermission
        <a href="{{ route('vendors.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('List View') }}">
            <i class="ti ti-list text-white"></i>
        </a>
        @permission('vendor create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Vendor') }}" data-url="{{route('vendors.create')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/letter.avatar.js') }}"></script>
@endpush
@push('css')
@endpush
@section('filter')
@endsection
@section('content')
    <div class="row">
        @foreach ($vendors as $k => $Vendor)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center">
                            @if (!empty($Vendor['vendor_id']))
                                <span class="badge bg-primary p-2 px-3 rounded">
                                    @permission('vendor show')
                                        <a href="{{ route('vendors.show', \Crypt::encrypt($Vendor['id'])) }}"
                                            class="text-white">
                                            {{ Modules\Account\Entities\Vender::vendorNumberFormat($Vendor['vendor_id']) }}
                                        </a>
                                    @else
                                        <a  class="text-white">
                                            {{ Modules\Account\Entities\Vender::vendorNumberFormat($Vendor['vendor_id']) }}
                                        </a>
                                    @endpermission
                                </span>
                            @else
                                <span class="badge p-2 px-3 rounded">
                                    <td>--</td>
                                </span>
                            @endif
                        </div>
                        <div class="card-header-right">
                            <div class="btn-group card-option">
                                @if($Vendor->is_disable == 1)
                                    <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">
                                        <i class="feather icon-more-vertical"></i>
                                    </button>
                                @else
                                    <div class="btn">
                                        <i class="ti ti-lock"></i>
                                    </div>
                                @endif
                                <div class="dropdown-menu dropdown-menu-end">
                                    @if (Laratrust::hasPermission('vendor show') || Laratrust::hasPermission('vendor edit') || Laratrust::hasPermission('vendor delete'))
                                        @permission('vendor edit')
                                            <a  data-url="{{ route('vendors.edit', $Vendor['id']) }}"
                                                data-ajax-popup="true" data-size="lg" class="dropdown-item"
                                                data-bs-whatever="{{ __('Edit vendor') }}" data-bs-toggle="tooltip"
                                                data-title="{{ __('Edit vendor') }}"><i class="ti ti-pencil"></i>
                                                {{ __('Edit') }}</a>
                                        @endpermission
                                        @if (!empty($Vendor['vendor_id']))
                                            @permission('vendor show')
                                                <a href="{{ route('vendors.show', \Crypt::encrypt($Vendor['id'])) }}"
                                                    class="dropdown-item" data-bs-whatever="{{ __('vendor Details') }}"
                                                    data-bs-toggle="tooltip"><i class="ti ti-eye"></i>
                                                    {{ __('Details') }}</a>
                                            @endpermission
                                            @permission('vendor delete')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['vendors.destroy', $Vendor['id']]]) !!}
                                                <a href="#!" class="dropdown-item  show_confirm" data-bs-toggle="tooltip">
                                                    <i class="ti ti-trash"></i>{{ __('Delete') }}
                                                </a>
                                                {!! Form::close() !!}
                                            @endpermission
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 justify-content-between">
                            <div class="col-12">
                                <div class="text-center client-box">
                                    <div class="avatar-parent-child mb-3">
                                        <a href="{{ check_file($Vendor->avatar) ? get_file($Vendor->avatar) : 'uploads/users-avatar/avatar.png' }}"
                                            target="_blank">
                                            <img src="{{ check_file($Vendor->avatar) ? get_file($Vendor->avatar) : 'uploads/users-avatar/avatar.png' }}"
                                                alt="user-image" class=" rounded-circle" width="120px" height="120px">
                                        </a>
                                    </div>
                                    <div class="h6 mt-2 mb-1 ">
                                        @permission('vendor show')
                                            <a href="{{ route('vendors.show', \Crypt::encrypt($Vendor['id'])) }}"
                                                class="text-primary">
                                                {{ !empty($Vendor->name) ? $Vendor->name : '' }}
                                            </a>
                                        @else
                                            <a  class="text-primary">
                                                {{ !empty($Vendor->name) ? $Vendor->name : '' }}
                                            </a>
                                        @endpermission
                                    </div>
                                    <div class="mb-1"><a
                                            class="text-sm small text-muted">{{ !empty($Vendor->email) ? $Vendor->email : '' }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-3">
            <a  data-url="{{ route('vendors.create') }}" class="btn-addnew-project" data-ajax-popup="true"
                data-size="lg" data-title="{{ __('Create New Vendor') }}"style="padding: 90px 10px">
                <div class="badge bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2">New Vendor</h6>
                <p class="text-muted text-center">Click here to add New Vendor</p>
            </a>
        </div>
    </div>
@endsection
