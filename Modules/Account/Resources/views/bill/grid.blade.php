@extends('layouts.main')
@section('page-title')
    {{ __('Manage Bills') }}
@endsection
@section('page-breadcrumb')
    {{ __('Bill') }}
@endsection
@section('page-action')
    <div>
        @stack('addButtonHook')
        <a href="{{ route('bill.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('List View') }}">
            <i class="ti ti-list text-white"></i>
        </a>
        @if (module_is_active('ProductService'))
        @permission('bill create')
            <a href="{{ route('category.index') }}"data-size="md" class="btn btn-sm btn-primary"
                data-bs-toggle="tooltip"data-title="{{ __('Setup') }}" title="{{ __('Setup') }}"><i
                    class="ti ti-settings"></i></a>

            <a href="{{ route('bill.create', 0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    @endif
    </div>
@endsection
@push('css')
<style>
    .bill_status{
        min-width: 94px;
    }
</style>
@endpush
@section('content')
    <div class="row">
        <div class="mt-2" id="multiCollapseExample1">

            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['bill.grid'], 'method' => 'GET', 'id' => 'frm_submit']) }}
                    <div class="row align-items-center justify-content-end">
                        <div class="col-xl-10">
                            <div class="row">
                                <div class="col-3">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 month">
                                    <div class="btn-box">
                                        {{ Form::label('bill_date', __('Date'), ['class' => 'form-label']) }}
                                        {{ Form::date('bill_date', isset($_GET['bill_date']) ? $_GET['bill_date'] : null, ['class' => 'form-control form-control flatpickr-to-input', 'placeholder' => 'Select Date']) }}
                                    </div>
                                </div>
                                @if (!\Auth::user()->type != 'vendor')
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 date">
                                        <div class="btn-box">
                                            {{ Form::label('vendor', __('Vendor'), ['class' => 'form-label']) }}
                                            {{ Form::select('vendor', $vendor, isset($_GET['vendor']) ? $_GET['vendor'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Vendor']) }}
                                        </div>
                                    </div>
                                @endif
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                        {{ Form::label('status', __('Status'), ['class' => 'form-label']) }}
                                        {{ Form::select('status', $status, isset($_GET['status']) ? $_GET['status'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Status']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto mt-4">
                            <div class="row">
                                <div class="col-auto">
                                    <a  class="btn btn-sm btn-primary"
                                        onclick="document.getElementById('frm_submit').submit(); return false;"
                                        data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                        data-original-title="{{ __('apply') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>
                                    <a href="{{ route('bill.grid') }}" class="btn btn-sm btn-danger "
                                        data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                        data-original-title="{{ __('Reset') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row">
                @foreach($bills as $bill)
                    <div class="col-lg-4">
                        <div class="card hover-shadow-lg">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-10">
                                        <h6 class="mb-0">
                                            @if (Laratrust::hasPermission('bill show'))
                                                <a href="{{route('bill.show',\Crypt::encrypt($bill->id))}}">{{ Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id) }}</a>
                                            @else
                                                <a >{{ Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id) }}</a>
                                            @endif

                                        </h6>
                                    </div>
                                    <div class="col-2 text-end">
                                        <div class="actions">
                                            <div class="dropdown">
                                                <a  class="action-item" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#"  data-link="{{route('pay.billpay',\Illuminate\Support\Facades\Crypt::encrypt($bill->id))}}" class="dropdown-item cp_link" >
                                                        <i class="ti ti-copy"></i> {{__('Click to copy bill link')}}
                                                    </a>
                                                    @permission('bill duplicate')
                                                    {!! Form::open([
                                                        'method' => 'get',
                                                        'route' => ['bill.duplicate', $bill->id],
                                                        'id' => 'duplicate-form-' . $bill->id,
                                                    ]) !!}
                                                            <a href="#!" class="show_confirm dropdown-item" data-confirm-yes="duplicate-form-{{ $bill->id }}">
                                                            <i class="ti ti-copy"></i> {{ __('Duplicate') }}
                                                            </a>
                                                        {!! Form::close() !!}
                                                    @endpermission
                                                    @permission('bill edit')
                                                        <a href="{{ route('bill.edit', \Crypt::encrypt($bill->id)) }}"class="dropdown-item" >
                                                            <i class="ti ti-edit"></i> {{__('Edit')}}
                                                        </a>
                                                    @endpermission
                                                    @permission('bill delete')
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['bill.destroy', $bill->id]]) !!}
                                                            <a href="#!" class="show_confirm dropdown-item">
                                                                <i class="ti ti-trash"></i> {{ __('Delete') }}
                                                            </a>
                                                        {!! Form::close() !!}
                                                    @endpermission
                                                    @permission('bill show')
                                                        <a href="{{route('bill.show',\Crypt::encrypt($bill->id))}}" class="dropdown-item" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                        <i class="ti ti-eye"></i> {{__('View')}}
                                                        </a>
                                                    @endpermission
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="p-3 border border-dashed">

                                    @if ($bill->status == 0)
                                            <span
                                                class="badge fix_badges bg-primary p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 1)
                                            <span
                                                class="badge fix_badges bg-info p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 2)
                                            <span
                                                class="badge fix_badges bg-secondary p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 3)
                                            <span
                                                class="badge fix_badges bg-warning p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 4)
                                            <span
                                                class="badge fix_badges bg-danger p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @endif

                                    <div class="row align-items-center mt-3">
                                        <div class="col-6">
                                            <h6 class="mb-0">{{currency_format_with_sym($bill->getTotal())}}</h6>
                                            <span class="text-sm text-muted">{{__('Total Amount')}}</span>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-0">{{currency_format_with_sym($bill->getDue())}}</h6>
                                            <span class="text-sm text-muted">{{__('Due Amount')}}</span>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mt-3">
                                        <div class="col-6">
                                            <h6 class="mb-0">{{company_date_formate($bill->bill_date)}}</h6>
                                            <span class="text-sm text-muted">{{__('Issue Date')}}</span>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-0">{{company_date_formate($bill->due_date)}}</h6>
                                            <span class="text-sm text-muted">{{__('Due Date')}}</span>
                                        </div>
                                    </div>
                                </div>
                                @if (\Auth::user()->type != 'vendor')
                                    <div class="user-group pt-2">
                                            <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="{{ !empty($bill->vendor_name) ? $bill->vendor_name:'' }}"
                                                @if (!empty($bill->avatar) ? $bill->avatar:'') src="{{ get_file(!empty($bill->avatar) ? $bill->avatar:'') }}" @else src="{{ 'uploads/users-avatar/avatar.png' }}" @endif
                                                class="rounded-circle " width="25" height="25">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
            @endforeach
            @auth('web')
                @permission('bill create')
                <div class="col-md-3">
                    <a href="{{ route('bill.create', 0) }}" class="btn-addnew-project"
                        data-title="{{ __('Create Bill') }}" style="padding: 90px 10px;">
                        <div class="badge bg-primary proj-add-icon">
                            <i class="ti ti-plus"></i>
                        </div>
                        <h6 class="mt-4 mb-2">{{ __('Create Bill') }}</h6>
                        <p class="text-muted text-center">{{ __('Click here to add Bill') }}</p>
                    </a>
                </div>
                @endpermission
            @endauth
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on("click",".cp_link",function() {
            var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toastrs('success', '{{__('Link Copy on Clipboard')}}', 'success')
        });
    </script>
@endpush
