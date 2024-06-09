@extends('layouts.main')
@section('page-title')
    {{ __('Manage Customers') }}
@endsection
@section('page-breadcrumb')
    {{ __('Customers') }}
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#billing_data', function () {
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
    @permission('customer import')
        <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Customer Import')}}" data-url="{{ route('customer.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
        </a>
    @endpermission
    <a href="{{ route('customer.grid') }}" class="btn btn-sm btn-primary btn-icon"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
        </a>
    @permission('customer create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg" data-title="{{ __('Create New Customer') }}" data-url="{{route('customer.create')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th> {{__('Name')}}</th>
                                <th> {{__('Contact')}}</th>
                                <th> {{__('Email')}}</th>
                                <th> {{__('Balance')}}</th>
                                @if (Laratrust::hasPermission('customer edit') || Laratrust::hasPermission('customer delete') || Laratrust::hasPermission('customer show'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($customers as $k=>$customer)
                            <tr>
                                @if (!empty($customer['customer_id']))
                                    <td class="Id">
                                        @permission('customer show')
                                            <a href="{{ route('customer.show',\Crypt::encrypt($customer['id'])) }}" class="btn btn-outline-primary">
                                                {{ Modules\Account\Entities\Customer::customerNumberFormat($customer['customer_id']) }}
                                            </a>
                                        @else
                                            <a class="btn btn-outline-primary">
                                                {{ Modules\Account\Entities\Customer::customerNumberFormat($customer['customer_id']) }}
                                            </a>
                                        @endpermission
                                    </td>
                                @else
                                    <td>--</td>
                                @endif
                                <td class="font-style">{{$customer['name']}}</td>
                                <td>{{$customer['contact']}}</td>
                                <td>{{$customer['email']}}</td>
                                <td>{{ currency_format_with_sym($customer['balance'])}}</td>
                                @if (Laratrust::hasPermission('customer edit') || Laratrust::hasPermission('customer delete') || Laratrust::hasPermission('customer show'))
                                    <td class="Action">
                                        @if($customer->is_disable == 1)
                                            <span>
                                                @if (!empty($customer['customer_id']))
                                                    @permission('customer show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('customer.show',\Crypt::encrypt($customer['id'])) }}" class="mx-3 btn btn-sm align-items-center"
                                                        data-bs-toggle="tooltip" title="{{__('View')}}">
                                                            <i class="ti ti-eye text-white text-white"></i>
                                                        </a>
                                                    </div>
                                                    @endpermission
                                                @endif
                                                @permission('customer edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a  class="mx-3 btn btn-sm  align-items-center"
                                                            data-url="{{ route('customer.edit',$customer['id']) }}" data-ajax-popup="true"  data-size="lg"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-title="{{ __('Edit Customer') }}"
                                                            data-bs-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @if (!empty($customer['customer_id']))
                                                    @permission('customer delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {{Form::open(array('route'=>array('customer.destroy', $customer['id']),'class' => 'm-0'))}}
                                                            @method('DELETE')
                                                                <a
                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                                    aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$customer['id']}}"><i
                                                                        class="ti ti-trash text-white text-white"></i></a>
                                                            {{Form::close()}}
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
