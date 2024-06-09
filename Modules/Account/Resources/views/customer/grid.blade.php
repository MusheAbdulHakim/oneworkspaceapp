@extends('layouts.main')
@section('page-title')
    {{ __('Manage Customers') }}
@endsection

@section('page-breadcrumb')
    {{ __('Customers') }}
@endsection
@section('page-action')
    <div>
        @permission('customer import')
            <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Customer Import')}}" data-url="{{ route('customer.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
            </a>
        @endpermission
        <a href="{{ route('customer.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('List View') }}">
            <i class="ti ti-list text-white"></i>
        </a>
        @permission('customer create')
            <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create New Customer') }}" data-url="{{ route('customer.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}">
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
        @foreach ($customers as $customer)
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center">
                            @if (!empty($customer['customer_id']))
                                <span class="badge bg-primary p-2 px-3 rounded">
                                    @permission('customer show')
                                        <a href="{{ route('customer.show', \Crypt::encrypt($customer['id'])) }}"
                                            class="text-white">
                                            {{ Modules\Account\Entities\Customer::customerNumberFormat($customer['customer_id']) }}
                                        </a>
                                    @else
                                        <a  class="text-white">
                                            {{ Modules\Account\Entities\Customer::customerNumberFormat($customer['customer_id']) }}
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
                                @if($customer->is_disable == 1)
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
                                    @if (Laratrust::hasPermission('customer show') || Laratrust::hasPermission('customer edit') || Laratrust::hasPermission('customer delete'))
                                        @permission('customer edit')
                                            <a  data-url="{{ route('customer.edit', $customer['id']) }}"
                                                data-ajax-popup="true" data-size="lg" class="dropdown-item"
                                                data-bs-whatever="{{ __('Edit customer') }}" data-bs-toggle="tooltip"
                                                data-title="{{ __('Edit customer') }}"><i class="ti ti-pencil"></i>
                                                {{ __('Edit') }}</a>
                                        @endpermission
                                        @if (!empty($customer['customer_id']))
                                            @permission('customer show')
                                                <a href="{{ route('customer.show', \Crypt::encrypt($customer['id'])) }}"
                                                    class="dropdown-item" data-bs-whatever="{{ __('Customer Details') }}"
                                                    data-bs-toggle="tooltip"><i class="ti ti-eye"></i>
                                                    {{ __('Details') }}</a>
                                            @endpermission
                                            @permission('customer delete')
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['customer.destroy', $customer['id']]]) !!}
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
                                        <a href="{{ check_file($customer->avatar) ? get_file($customer->avatar) : 'uploads/users-avatar/avatar.png' }}"
                                            target="_blank">
                                            <img src="{{ check_file($customer->avatar) ? get_file($customer->avatar) : 'uploads/users-avatar/avatar.png' }}"
                                                alt="user-image" class=" rounded-circle" width="120px" height="120px">
                                        </a>
                                    </div>
                                    <div class="h6 mt-2 mb-1 ">
                                        @permission('customer show')
                                            <a href="{{ route('customer.show', \Crypt::encrypt($customer['id'])) }}"
                                                class="text-primary">
                                                {{ !empty($customer->name) ? $customer->name : '' }}
                                            </a>
                                        @else
                                            <a  class="text-primary">
                                                {{ !empty($customer->name) ? $customer->name : '' }}
                                            </a>
                                        @endpermission
                                    </div>
                                    <div class="mb-1"><a
                                            class="text-sm small text-muted">{{ !empty($customer->email) ? $customer->email : '' }}</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-md-3">
            <a  data-url="{{ route('customer.create') }}" class="btn-addnew-project" data-ajax-popup="true"
                data-size="lg" data-title="{{ __('Create New Customer') }}" style="padding: 90px 10px">
                <div class="badge bg-primary proj-add-icon">
                    <i class="ti ti-plus"></i>
                </div>
                <h6 class="mt-4 mb-2">New Customer</h6>
                <p class="text-muted text-center">Click here to add New Customer</p>
            </a>
        </div>
    </div>
@endsection
