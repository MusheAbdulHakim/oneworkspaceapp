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
        <a href="{{ route('bill.grid') }}" class="btn btn-sm btn-primary btn-icon"
            data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
            <i class="ti ti-layout-grid text-white"></i>
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
        .bill_status {
            min-width: 94px;
        }
    </style>
@endpush
@section('content')
    <div class="row">
        <div class="mt-2" id="multiCollapseExample1">

            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['bill.index'], 'method' => 'GET', 'id' => 'frm_submit']) }}
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
                                    <a class="btn btn-sm btn-primary"
                                        onclick="document.getElementById('frm_submit').submit(); return false;"
                                        data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                        data-original-title="{{ __('apply') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>
                                    <a href="{{ route('bill.index') }}" class="btn btn-sm btn-danger "
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
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th> {{ __('Bill') }}</th>
                                    @if (!\Auth::user()->type != 'vendor')
                                        <th> {{ __('Vendor') }}</th>
                                    @endif
                                    <th> {{ __('Account Type') }}</th>
                                    <th> {{ __('Bill Date') }}</th>
                                    <th> {{ __('Due Date') }}</th>
                                    <th>{{ __('Due Amount') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    @if (Laratrust::hasPermission('bill edit') ||
                                            Laratrust::hasPermission('bill delete') ||
                                            Laratrust::hasPermission('bill show') ||
                                            Laratrust::hasPermission('bill duplicate'))
                                        <th width="10%"> {{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bills as $bill)
                                    <tr class="font-style">
                                        <td class="Id">
                                            @permission('bill show')
                                                <a href="{{ route('bill.show', \Crypt::encrypt($bill->id)) }}"
                                                    class="btn btn-outline-primary">{{ Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id) }}</a>
                                            @else
                                                <a
                                                    class="btn btn-outline-primary">{{ Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id) }}</a>
                                    @endif
                                    </td>

                                    @if (!\Auth::user()->type != 'vendor')
                                        <td> {{ !empty($bill->vendor_name) ? $bill->vendor_name : '' }}</td>
                                    @endif
                                    <td>{{ $bill->account_type }}</td>
                                    <td>{{ company_date_formate($bill->bill_date) }}</td>
                                    <td>
                                        @if ($bill->due_date < date('Y-m-d'))
                                            <p class="text-danger">
                                                {{ company_date_formate($bill->due_date) }}</p>
                                        @else
                                            {{ company_date_formate($bill->due_date) }}
                                        @endif
                                    </td>
                                    <td>{{ currency_format_with_sym($bill->getDue()) }}</td>
                                    <td>
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
                                    </td>
                                    @if (Laratrust::hasPermission('bill edit') ||
                                            Laratrust::hasPermission('bill delete') ||
                                            Laratrust::hasPermission('bill show') ||
                                            Laratrust::hasPermission('bill duplicate'))
                                        <td class="Action">
                                            <span>
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="#" class="btn btn-sm  align-items-center cp_link"
                                                        data-link="{{ route('pay.billpay', \Illuminate\Support\Facades\Crypt::encrypt($bill->id)) }}"
                                                        data-bs-toggle="tooltip" title="{{ __('Copy') }}"
                                                        data-original-title="{{ __('Click to copy Bill link') }}">
                                                        <i class="ti ti-file text-white"></i>
                                                    </a>
                                                </div>
                                                @permission('bill duplicate')
                                                    <div class="action-btn bg-secondary ms-2">
                                                        {!! Form::open([
                                                            'method' => 'get',
                                                            'route' => ['bill.duplicate', $bill->id],
                                                            'id' => 'duplicate-form-' . $bill->id,
                                                        ]) !!}
                                                        <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="{{ __('Duplicate') }}" aria-label="Delete"
                                                            data-text="{{ __('You want to confirm duplicate this invoice. Press Yes to continue or Cancel to go back') }}"
                                                            data-confirm-yes="duplicate-form-{{ $bill->id }}">
                                                            <i class="ti ti-copy text-white text-white"></i>
                                                        </a>
                                                        {{ Form::close() }}
                                                    </div>
                                                @endpermission
                                                @permission('bill show')
                                                    <div class="action-btn bg-warning ms-2">
                                                        <a href="{{ route('bill.show', \Crypt::encrypt($bill->id)) }}"
                                                            class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                            title="{{ __('Show') }}" data-original-title="{{ __('Detail') }}">
                                                            <i class="ti ti-eye text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @if (module_is_active('ProductService') && $bill->bill_module == 'taskly'
                                                        ? module_is_active('Taskly')
                                                        : module_is_active('Account'))
                                                    @permission('bill edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="{{ route('bill.edit', \Crypt::encrypt($bill->id)) }}"
                                                                class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                                title="Edit" data-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                @endif
                                                @permission('bill delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {{ Form::open(['route' => ['bill.destroy', $bill->id], 'class' => 'm-0']) }}
                                                        @method('DELETE')
                                                        <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $bill->id }}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        {{ Form::close() }}
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
    @push('scripts')
        <script>
            $(document).on("click", ".cp_link", function() {
                var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toastrs('success', '{{ __('Link Copy on Clipboard') }}', 'success')
            });
        </script>
    @endpush
