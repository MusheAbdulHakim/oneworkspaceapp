@extends('layouts.main')
@section('page-title')
    {{ __('Manage Vendors') }}
@endsection
@section('page-breadcrumb')
    {{ __('Vendors') }}
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#billing_data', function() {
            $("[name='shipping_name']").val($("[name='billing_name']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_phone']").val($("[name='billing_phone']").val());
            $("[name='shipping_zip']").val($("[name='billing_zip']").val());
            $("[name='shipping_address']").val($("[name='billing_address']").val());
        })
    </script>
@endpush

@section('page-action')
    <div>
        @stack('addButtonHook')
        @permission('vendor import')
            <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Vendor Import')}}" data-url="{{ route('vendor.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
            </a>
        @endpermission
        <a href="{{ route('vendors.grid') }}" class="btn btn-sm btn-primary btn-icon"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
        @permission('vendor create')
            <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create New Vendor') }}" data-url="{{ route('vendors.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Contact') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Balance') }}</th>
                                    @if (Laratrust::hasPermission('vendor edit') || Laratrust::hasPermission('vendor delete') || Laratrust::hasPermission('vendor show'))
                                        <th width="10%"> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vendors as $k => $Vendor)
                                    <tr class="font-style">
                                        @if (!empty($Vendor['vendor_id']))
                                            <td class="Id">
                                                @permission('vendor show')
                                                    <a href="{{ route('vendors.show', \Crypt::encrypt($Vendor['id'])) }}"
                                                        class="btn btn-outline-primary">
                                                        {{ Modules\Account\Entities\Vender::vendorNumberFormat($Vendor['vendor_id']) }}
                                                    </a>
                                                @else
                                                    <a  class="btn btn-outline-primary">
                                                        {{ \Modules\Account\Entities\Vender::vendorNumberFormat($Vendor['vendor_id']) }}
                                                    </a>
                                                @endpermission
                                            </td>
                                        @else
                                            <td>--</td>
                                        @endif

                                        <td>{{ $Vendor['name'] }}</td>
                                        <td>{{ $Vendor['contact'] }}</td>
                                        <td>{{ $Vendor['email'] }}</td>
                                        <td>{{ currency_format_with_sym($Vendor['balance']) }}</td>
                                        @if (Laratrust::hasPermission('vendor edit') || Laratrust::hasPermission('vendor delete') || Laratrust::hasPermission('vendor show'))
                                            <td class="Action">
                                                @if($Vendor->is_disable == 1)
                                                    <span>
                                                        @if (!empty($Vendor['vendor_id']))
                                                            @permission('vendor show')
                                                                <div class="action-btn bg-warning ms-2">
                                                                    <a href="{{ route('vendors.show', \Crypt::encrypt($Vendor['id'])) }}"
                                                                        class="mx-3 btn btn-sm align-items-center"
                                                                        data-bs-toggle="tooltip" title="{{ __('View') }}">
                                                                        <i class="ti ti-eye text-white text-white"></i>
                                                                    </a>
                                                                </div>
                                                            @endpermission
                                                        @endif
                                                        @permission('vendor edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                                    data-url="{{ route('vendors.edit', $Vendor['id']) }}"
                                                                    data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip"
                                                                    title="" data-title="{{ __('Edit Vendor') }}"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission
                                                        @if (!empty($Vendor['vendor_id']))
                                                            @permission('vendor delete')
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {{ Form::open(['route' => ['vendors.destroy', $Vendor['id']], 'class' => 'm-0']) }}
                                                                    @method('DELETE')
                                                                    <a
                                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                        data-bs-toggle="tooltip" title=""
                                                                        data-bs-original-title="Delete" aria-label="Delete"
                                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                        data-confirm-yes="delete-form-{{ $Vendor['id'] }}"><i
                                                                            class="ti ti-trash text-white text-white"></i></a>
                                                                    {{ Form::close() }}
                                                                </div>
                                                            @endpermission
                                                        @endif
                                                    </span>
                                                @else
                                                    <div class="text-center">
                                                        <i class="ti ti-lock"></i>
                                                    </div>
                                                @endif
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
