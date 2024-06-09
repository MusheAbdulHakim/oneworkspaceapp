@extends('layouts.main')
@section('page-title')
    {{ __('Manage Payments') }}
@endsection
@section('page-breadcrumb')
    {{ __('Payment') }}
@endsection
@section('page-action')
    <div>
        @if (module_is_active('ProductService'))
            @permission('bill create')
                <a href="{{ route('category.index') }}"data-size="md" class="btn btn-sm btn-primary"
                    data-bs-toggle="tooltip"data-title="{{ __('Setup') }}" title="{{ __('Setup') }}"><i
                        class="ti ti-settings"></i></a>
            @endpermission
        @endif
        @permission('expense payment create')
            <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="lg"
                data-title="{{ __('Create New Payment') }}" data-url="{{ route('payment.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class=" multi-collapse mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['payment.index'], 'method' => 'GET', 'id' => 'payment_form']) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                                            {{ Form::text('date', isset($_GET['date']) ? $_GET['date'] : null, ['class' => 'form-control flatpickr-to-input', 'placeholder' => 'Select Date']) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('account', __('Account'), ['class' => 'form-label']) }}
                                            {{ Form::select('account', $account, isset($_GET['account']) ? $_GET['account'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Account']) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('vendor', __('Vendor'), ['class' => 'form-label']) }}
                                            {{ Form::select('vendor', $vendor, isset($_GET['vendor']) ? $_GET['vendor'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Vendor']) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}
                                            {{ Form::select('category', $category, isset($_GET['category']) ? $_GET['category'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Category']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto mt-4">
                                <div class="row">
                                    <div class="col-auto">
                                        <a  class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('payment_form').submit(); return false;"
                                            data-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('payment.index') }}" class="btn btn-sm btn-danger"
                                            data-bs-toggle="tooltip" title="{{ __('Reset') }}">
                                            <span class="btn-inner--icon"><i
                                                    class="ti ti-trash-off text-white-off "></i></i></span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Account') }}</th>
                                    <th>{{ __('Vendor') }}</th>
                                    <th>{{ __('Category') }}</th>
                                    <th>{{ __('Reference') }}</th>
                                    <th>{{ __('Description') }}</th>
                                    <th>{{ __('Payment Receipt') }}</th>
                                    @if (Laratrust::hasPermission('expense payment delete') || Laratrust::hasPermission('expense payment edit'))
                                        <th>{{ __('Action') }}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($payments as $payment)
                                {{-- @dd(!empty($payment->bank_name) && $payment->bank_name != '') --}}
                                    <tr class="font-style">
                                        <td>{{ company_date_formate($payment->date) }}</td>
                                        <td>{{ currency_format_with_sym($payment->amount) }}</td>
                                        <td>{{ !empty($payment->bank_name) && $payment->bank_name != ''  ? $payment->bank_name . ' ' . $payment->holder_name : $payment->holder_name }}
                                        </td>
                                        <td>{{ !empty($payment->vendors_name) ? $payment->vendors_name : '-' }}</td>
                                        @if (module_is_active('ProductService'))
                                            <td>{{ !empty($payment->category_name) ? $payment->category_name : '-' }}</td>
                                        @else
                                            <td>-</td>
                                        @endif
                                        <td>{{ !empty($payment->reference) ? $payment->reference : '-' }}</td>
                                        <td>
                                            <p style="white-space: nowrap;
                                                width: 200px;
                                                overflow: hidden;
                                                text-overflow: ellipsis;">{{  !empty($payment->description) ? $payment->description : '' }}
                                            </p>
                                        </td>
                                        <td>
                                            @if (!empty($payment->add_receipt))
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="{{ get_file($payment->add_receipt) }}" download=""
                                                        class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('Download') }}" target="_blank">
                                                        <i class="ti ti-download text-white"></i>
                                                    </a>
                                                </div>
                                                <div class="action-btn bg-secondary ms-2">
                                                    <a href="{{ get_file($payment->add_receipt) }}"
                                                        class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip"
                                                        title="{{ __('Show') }}" target="_blank">
                                                        <i class="ti ti-crosshair text-white"></i>
                                                    </a>
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if (Laratrust::hasPermission('expense payment delete') || Laratrust::hasPermission('expense payment edit'))
                                            <td class="action">
                                                @permission('expense payment edit')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a  class="mx-3 btn btn-sm align-items-center"
                                                            data-url="{{ route('payment.edit', $payment->id) }}"
                                                            data-ajax-popup="true" data-title="{{ __('Edit Payment') }}"
                                                            data-size="lg" data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                            data-original-title="{{ __('Edit') }}">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('expense payment delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {{ Form::open(['route' => ['payment.destroy', $payment->id], 'class' => 'm-0']) }}
                                                        @method('DELETE')
                                                        <a
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm="{{ __('Are You Sure?') }}"
                                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                            data-confirm-yes="delete-form-{{ $payment->id }}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        {{ Form::close() }}
                                                    </div>
                                                @endpermission
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
