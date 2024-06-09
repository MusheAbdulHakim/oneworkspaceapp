@extends('layouts.main')
@section('page-title')
    {{ __('Vendor-Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Vendor-Detail') }}
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
    <script>
        $(".apply_btn").click(function() {
            var from_date = $('.from_date').val();
            var until_date = $('.until_date').val();
            var id = "{{ $vendor['id'] }}";
            var type = "statement_tab";
            $.ajax({
                url: '{{ route('vendor.statement', $vendor['id']) }}',
                type: 'POST',
                data: {
                    "id": id,
                    "from_date": from_date,
                    "until_date": until_date,
                    "type": type,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $("#statement-history .list").empty();

                    // Initialize the total amount
                    var totalAmount = 0;

                    // Iterate over the data array and build the rows
                    data.data.forEach(function(item) {


                        var rowHtml = '<tr>' +
                            '<td>' + item.date + '</td>' +
                            '<td>' + item.billno + '</td>' +
                            '<td>' + data.currencySymbol + item.amount + '</td>' +
                            '</tr>';
                        $("#statement-history .list").append(rowHtml);

                        // Update the total amount
                        totalAmount += parseFloat(item.amount);
                    });

                    // Display the total amount in the total row
                    $("#total-amount").html('<strong>' + data.currencySymbol + totalAmount.toFixed(2) +
                        '</strong>');
                }
            });

        });
    </script>
    <script src="{{ asset('Modules/Account/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {
                    type: 'jpeg',
                    quality: 1
                },
                html2canvas: {
                    scale: 4,
                    dpi: 72,
                    letterRendering: true
                },
                jsPDF: {
                    unit: 'in',
                    format: 'A4'
                }
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
@endpush
@push('css')
    <style>
        .cus-card {
            min-height: 204px;
        }
    </style>
@endpush
@php
    $company_settings = getCompanyAllSetting();
    $company_settings['bill_shipping_display'] = isset($company_settings['bill_shipping_display']) ? $company_settings['bill_shipping_display'] : 'off';
@endphp
@section('page-action')
    <div>
        @permission('bill create')
            <a href="{{ route('bill.create', $vendor->id) }}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"> </i>{{ __('Create Bill') }}
            </a>
        @endpermission
        @if ($vendor->user->is_disable == 1)
            @permission('vendor edit')
                <a class="btn btn-sm btn-primary action-btn px-1" data-url="{{ route('vendors.edit', $vendor['user_id']) }}"
                    data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title=""
                    data-title="{{ __('Edit Vendor') }}" data-bs-original-title="{{ __('Edit') }}">
                    <i class="ti ti-pencil text-white"></i>
                </a>
            @endpermission
        @endif
    </div>
@endsection
@section('content')
    <div class="page-header">
        <div class="page-block">
            <div class="row align-items-center">
                <div class="col-md-5">
                </div>
                <div class="col-md-7 mt-4">
                    <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="vendor-details-tab" data-bs-toggle="pill"
                                data-bs-target="#vendor-details" type="button">{{ __('Details') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="vendor-bills-tab" data-bs-toggle="pill"
                                data-bs-target="#vendor-bills" type="button">{{ __('Bill') }}</button>
                        </li>
                        @stack('vendor_purchase_tab')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="vendor-payment-tab" data-bs-toggle="pill"
                                data-bs-target="#vendor-payment" type="button">{{ __('Payment') }}</button>
                        </li>
                        @stack('vendor_project_tab')
                        <li class="nav-item" role="presentation">
                            <button class="nav-link " id="statement-tab" data-bs-toggle="pill" data-bs-target="#statement"
                                type="button">{{ __('Statement') }}</button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content" id="pills-tabContent">

                <div class="tab-pane fade active show" id="vendor-details" role="tabpanel"
                    aria-labelledby="pills-user-tab-1">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="card pb-0 customer-detail-box">
                                <div class="card-body cus-card">
                                    <h5 class="card-title">{{ __('Vendor Info') }}</h5>
                                    <p class="card-text mb-0">{{ $vendor->name }}</p>
                                    <p class="card-text mb-0">{{ $vendor->email }}</p>
                                    <p class="card-text mb-0">{{ $vendor->contact }}</p>
                                    @if (!empty($customFields) && count($vendor->customField) > 0)
                                        @foreach ($customFields as $field)
                                            <p class="card-text mb-0">
                                                <strong>{{ $field->name }} :
                                                </strong>{{ !empty($vendor->customField[$field->id]) ? $vendor->customField[$field->id] : '-' }}
                                            </p>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="card pb-0 customer-detail-box">
                                <div class="card-body cus-card">
                                    <h3 class="card-title">{{ __('Billing Info') }}</h3>
                                    <p class="card-text mb-0">{{ $vendor->billing_name }}</p>
                                    <p class="card-text mb-0">{{ $vendor->billing_address }}</p>
                                    <p class="card-text mb-0">
                                        {{ $vendor->billing_city . ' ,' . $vendor->billing_state . ' ,' . $vendor->billing_zip }}
                                    </p>
                                    <p class="card-text mb-0">{{ $vendor->billing_country }}</p>
                                    <p class="card-text mb-0">{{ $vendor->billing_phone }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="card pb-0 customer-detail-box">
                                <div class="card-body cus-card">
                                    <h3 class="card-title">{{ __('Shipping Info') }}</h3>
                                    @if ($company_settings['bill_shipping_display'] == 'on')
                                        <p class="card-text mb-0">{{ $vendor->shipping_name }}</p>
                                        <p class="card-text mb-0">{{ $vendor->shipping_address }}</p>
                                        <p class="card-text mb-0">
                                            {{ $vendor->shipping_city . ' ,' . $vendor->shipping_state . ' ,' . $vendor->shipping_zip }}
                                        </p>
                                        <p class="card-text mb-0">{{ $vendor->shipping_country }}</p>
                                        <p class="card-text mb-0">{{ $vendor->shipping_phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card pb-0">
                                <div class="card-body">
                                    <h3 class="card-title">{{ __('Company Info') }}</h3>
                                    <div class="row">
                                        @php
                                            $totalBillSum = $vendor->vendorTotalBillSum($vendor['id']);
                                            $totalBill = $vendor->vendorTotalBill($vendor['id']);
                                            $averageSale = $totalBillSum != 0 ? $totalBillSum / $totalBill : 0;
                                        @endphp
                                        <div class="col-md-3 col-sm-6">
                                            <div class="p-2">
                                                <p class="card-text mb-0">{{ __('Vendor Id') }}</p>
                                                <h6 class="report-text mb-3">
                                                    {{ Modules\Account\Entities\Vender::vendorNumberFormat($vendor->vendor_id) }}
                                                </h6>
                                                <p class="card-text mb-0">{{ __('Total Sum of Bills') }}</p>
                                                <h6 class="report-text mb-0">{{ currency_format_with_sym($totalBillSum) }}
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="p-2">
                                                <p class="card-text mb-0">{{ __('Date of Creation') }}</p>
                                                <h6 class="report-text mb-3">
                                                    {{ company_date_formate($vendor->created_at) }}</h6>
                                                <p class="card-text mb-0">{{ __('Quantity of Bills') }}</p>
                                                <h6 class="report-text mb-0">{{ $totalBill }}</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="p-2">
                                                <p class="card-text mb-0">{{ __('Balance') }}</p>
                                                <h6 class="report-text mb-3">
                                                    {{ currency_format_with_sym($vendor->balance) }}</h6>
                                                <p class="card-text mb-0">{{ __('Average Sales') }}</p>
                                                <h6 class="report-text mb-0">{{ currency_format_with_sym($averageSale) }}
                                                </h6>
                                            </div>
                                        </div>
                                        <div class="col-md-3 col-sm-6">
                                            <div class="p-2">
                                                <p class="card-text mb-0">{{ __('Overdue') }}</p>
                                                <h6 class="report-text mb-3">
                                                    {{ currency_format_with_sym($vendor->vendorOverdue($vendor->id)) }}
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="vendor-bills" role="tabpanel" aria-labelledby="pills-user-tab-2">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table mb-0 pc-dt-simple" id="vendor_bills">
                                        <thead>
                                            <tr>
                                                <th> {{ __('Bill') }}</th>
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
                                            @forelse ($vendor->vendorBill($vendor->id) as $bill)
                                                <tr class="font-style">
                                                    <td class="Id">
                                                        @permission('bill show')
                                                            <a href="{{ route('bill.show', \Crypt::encrypt($bill->id)) }}"
                                                                class="btn btn-outline-primary">{{ Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id) }}
                                                            </a>
                                                        @else
                                                            <a
                                                                class="btn btn-outline-primary">{{ Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id) }}</a>
                                                        @endpermission
                                                    </td>
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
                                                                class="badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 1)
                                                            <span
                                                                class="badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 2)
                                                            <span
                                                                class="badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 3)
                                                            <span
                                                                class="badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                        @elseif($bill->status == 4)
                                                            <span
                                                                class="badge bg-success p-2 px-3 rounded">{{ __(\App\Models\Invoice::$statues[$bill->status]) }}</span>
                                                        @endif
                                                    </td>
                                                    @if (Laratrust::hasPermission('bill edit') ||
                                                            Laratrust::hasPermission('bill delete') ||
                                                            Laratrust::hasPermission('bill show'))
                                                        <td class="Action">
                                                            <span>
                                                                @permission(' bill duplicate')
                                                                    <div class="action-btn bg-secondary ms-2">

                                                                        {!! Form::open([
                                                                            'method' => 'get',
                                                                            'route' => ['bill.duplicate', $bill->id],
                                                                            'id' => 'bill-duplicate-form-' . $bill->id,
                                                                        ]) !!}
                                                                        <a class="mx-3 btn btn-sm align-items-center bs-pass-para"
                                                                            data-bs-toggle="tooltip"
                                                                            title="{{ __('Duplicate Bill') }}"
                                                                            data-original-title="{{ __('Duplicate') }}"
                                                                            data-confirm="{{ __('You want to confirm this action. Press Yes to continue or Cancel to go back') }}"
                                                                            data-confirm-yes="document.getElementById('bill-duplicate-form-{{ $bill->id }}').submit();">
                                                                            <i class="ti ti-copy text-white text-white"></i>
                                                                        </a>
                                                                        {!! Form::close() !!}

                                                                    </div>
                                                                @endpermission
                                                                @permission('bill show')
                                                                    <div class="action-btn bg-warning ms-2">
                                                                        <a href="{{ route('bill.show', \Crypt::encrypt($bill->id)) }}"
                                                                            class="mx-3 btn btn-sm  align-items-center"
                                                                            data-bs-toggle="tooltip"
                                                                            title="{{ __('Show') }}"
                                                                            data-original-title="{{ __('Detail') }}">
                                                                            <i class="ti ti-eye text-white text-white"></i>
                                                                        </a>
                                                                    </div>
                                                                @endpermission
                                                                @permission('bill edit')
                                                                    <div class="action-btn bg-info ms-2">
                                                                        <a href="{{ route('bill.edit', \Crypt::encrypt($bill->id)) }}"
                                                                            class="mx-3 btn btn-sm align-items-center"
                                                                            data-toggle="popover" title="Edit"
                                                                            data-original-title="{{ __('Edit') }}">
                                                                            <i class="ti ti-pencil text-white"></i>
                                                                        </a>
                                                                    </div>
                                                                @endpermission
                                                                @permission('bill delete')
                                                                    <div class="action-btn bg-danger ms-2">
                                                                        {{ Form::open(['route' => ['bill.destroy', $bill->id], 'class' => 'm-0']) }}
                                                                        @method('DELETE')
                                                                        <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                            data-toggle="popover" title="Delete"
                                                                            data-bs-original-title="Delete"
                                                                            aria-label="Delete"
                                                                            data-confirm="{{ __('Are You Sure?') }}"
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


                @stack('vendor_purchase_div')

                <div class="tab-pane fade" id="vendor-payment" role="tabpanel" aria-labelledby="pills-user-tab-3">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table mb-0 pc-dt-simple" id="vendor_payment">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Account') }}</th>
                                                <th>{{ __('Category') }}</th>
                                                <th>{{ __('Reference') }}</th>
                                                <th>{{ __('Description') }}</th>
                                                <th>{{ __('Payment Receipt') }}</th>
                                                @if (Laratrust::hasPermission('expense payment delete') ||
                                                        Laratrust::hasPermission('expense
                                                                                                payment edit'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($vendor->vendorPayment($vendor->id) as $payment)
                                                <tr class="font-style">
                                                    <td>{{ company_date_formate($payment->date) }}</td>
                                                    <td>{{ currency_format_with_sym($payment->amount) }}</td>
                                                    <td>{{ !empty($payment->bankAccount)
                                                        ? $payment->bankAccount->bank_name . ' ' . $payment->bankAccount->holder_name
                                                        : '' }}
                                                    </td>
                                                    @if (module_is_active('ProductService'))
                                                        <td>{{ !empty($payment->category) ? $payment->category->name : '-' }}
                                                        </td>
                                                    @else
                                                        <td>-</td>
                                                    @endif
                                                    <td>{{ !empty($payment->reference) ? $payment->reference : '-' }}</td>
                                                    <td>
                                                        <p
                                                            style="white-space: nowrap;
                                                                                    width: 200px;
                                                                                    overflow: hidden;
                                                                                    text-overflow: ellipsis;">
                                                            {{ !empty($payment->description) ? $payment->description : '' }}
                                                        </p>
                                                    </td>
                                                    <td>
                                                        @if (!empty($payment->add_receipt))
                                                            <div class="action-btn bg-primary ms-2">
                                                                <a href="{{ get_file($payment->add_receipt) }}"
                                                                    download=""
                                                                    class="mx-3 btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip" title="{{ __('Download') }}"
                                                                    target="_blank">
                                                                    <i class="ti ti-download text-white"></i>
                                                                </a>
                                                            </div>
                                                            <div class="action-btn bg-secondary ms-2">
                                                                <a href="{{ get_file($payment->add_receipt) }}"
                                                                    class="mx-3 btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip" title="{{ __('Show') }}"
                                                                    target="_blank">
                                                                    <i class="ti ti-crosshair text-white"></i>
                                                                </a>
                                                            </div>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    @if (Laratrust::hasPermission('expense payment delete') ||
                                                            Laratrust::hasPermission('expense
                                                                                                    payment edit'))
                                                        <td class="action text-end">
                                                            @permission('expense payment edit')
                                                                <div class="action-btn bg-info ms-2">
                                                                    <a class="mx-3 btn btn-sm align-items-center"
                                                                        data-url="{{ route('payment.edit', $payment->id) }}"
                                                                        data-ajax-popup="true"
                                                                        data-title="{{ __('Edit Payment') }}" data-size="lg"
                                                                        data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                        data-original-title="{{ __('Edit') }}">
                                                                        <i class="ti ti-pencil text-white"></i>
                                                                    </a>
                                                                </div>
                                                            @endpermission
                                                            @permission('expense payment delete')
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {{ Form::open(['route' => ['payment.destroy', $payment->id], 'class' => 'm-0']) }}
                                                                    @method('DELETE')
                                                                    <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
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


                @stack('vendor_project_div')

                <div class="tab-pane fade" id="statement" role="tabpanel" aria-labelledby="pills-user-tab-4">
                    <div class="row">
                        <div class="col-md-4 col-lg-4 col-xl-4">
                            <div class="card bg-none invo-tab">
                                <div class="card-body">
                                    <h3 class="small-title">{{ $vendor['name'] . ' ' . __('Statement') }}</h3>
                                    <div class="row issue_date">
                                        <div class="col-md-12">
                                            <div class="issue_date_main">
                                                <div class="form-group">
                                                    {{ Form::label('from_date', __('From Date'), ['class' => 'form-label']) }}<span
                                                        class="text-danger">*</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::date('from_date', isset($data['from_date']) ? $data['from_date'] : null, ['class' => 'form-control from_date', 'placeholder' => 'Select From Date', 'required' => 'required']) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="issue_date_main">
                                                <div class="form-group">
                                                    {{ Form::label('until_date', __('Until Date'), ['class' => 'form-label']) }}<span
                                                        class="text-danger">*</span>
                                                    <div class="form-icon-user">
                                                        {{ Form::date('until_date', isset($data['until_date']) ? $data['until_date'] : null, ['class' => 'form-control until_date', 'placeholder' => 'Select Until Date']) }}

                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-12 text-end">
                                        <input type="submit" value="{{ __('Apply') }}"
                                            class="btn btn-sm btn-primary apply_btn">
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-8 col-lg-8 col-xl-8">
                            <span>
                                <div class="card">
                                    <div class="text-end p-3">
                                        <a class="btn btn-sm btn-primary" onclick="saveAsPDF()" data-bs-toggle="tooltip"
                                            data-bs-original-title="{{ __('Download') }}">
                                            <i class="ti ti-download"></i>
                                        </a>
                                    </div>
                                    <div class="card-body" id="printableArea">
                                        <div class="invoice">
                                            <div class="invoice-print">
                                                <div class="row invoice-title mt-2">
                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                                        <img src="{{ get_file(sidebar_logo()) }}"
                                                            alt="{{ config('app.name', 'WorkDo') }}"
                                                            class="logo logo-lg" style="max-width: 250px">
                                                    </div>
                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                                        <strong>{{ __('My Company') }}</strong><br>
                                                        <h6 class="invoice-number">{{ \Auth::user()->email }}</h6>
                                                        <p>
                                                            @if (!empty($settings['company_address']))
                                                            {{ $settings['company_address'] }}
                                                            @endif
                                                            @if (!empty($settings['company_city']))
                                                            <br> {{ $settings['company_city'] }},
                                                            @endif
                                                            @if (!empty($settings['company_state']))
                                                            {{ $settings['company_state'] }}
                                                            @endif
                                                            @if (!empty($settings['company_country']))
                                                            <br>{{ $settings['company_country'] }}
                                                            @endif
                                                            @if (!empty($settings['company_zipcode']))
                                                            - {{ $settings['company_zipcode'] }}
                                                            @endif
                                                         </p>
                                                    </div>
                                                    <div
                                                        class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-12 text-center">
                                                        <strong>
                                                            <h5>{{ __('Statement of Account') }}</h5>
                                                        </strong>
                                                        <strong>{{ $data['from_date'] . '  ' . 'to' . '  ' . $data['until_date'] }}</strong>
                                                    </div>
                                                    <div class="col-12">
                                                        <hr>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-8">
                                                       <h5 class="invoice-number">{{ $vendor->name }}</h5>
                                                    </div>
                                                    <div class="col-md-4">
                                                       <h5 class="invoice-number">{{ __('Account summary') }}
                                                       </h5>
                                                    </div>
                                                 </div>
                                                <div class="row">
                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8 col-12">
                                                        <div class="row">
                                                            @if (!empty($vendor->billing_name))
                                                                <div class="col-md-4">
                                                                    <small class="font-style">
                                                                        <strong>{{ __('Billed To') }} :</strong><br>
                                                                        {{ !empty($vendor->billing_name) ? $vendor->billing_name : '' }}<br>
                                                                        {{ !empty($vendor->billing_address) ? $vendor->billing_address : '' }}<br>
                                                                        {{ !empty($vendor->billing_city) ? $vendor->billing_city . ' ,' : '' }}
                                                                        {{ !empty($vendor->billing_state) ? $vendor->billing_state . ' ,' : '' }}
                                                                        {{ !empty($vendor->billing_zip) ? $vendor->billing_zip : '' }}<br>
                                                                        {{ !empty($vendor->billing_country) ? $vendor->billing_country : '' }}<br>
                                                                        {{ !empty($vendor->billing_phone) ? $vendor->billing_phone : '' }}<br>
                                                                        <strong>{{ __('Tax Number ') }} :
                                                                        </strong>{{ !empty($vendor->tax_number) ? $vendor->tax_number : '' }}

                                                                    </small>
                                                                </div>
                                                            @endif
                                                            @if ($company_settings['bill_shipping_display'] == 'on')
                                                                <div class="col-md-4 text-end">
                                                                    <small>
                                                                        <strong>{{ __('Shipped To') }} :</strong><br>
                                                                        {{ !empty($vendor->shipping_name) ? $vendor->shipping_name : '' }}<br>
                                                                        {{ !empty($vendor->shipping_address) ? $vendor->shipping_address : '' }}<br>
                                                                        {{ !empty($vendor->shipping_city) ? $vendor->shipping_city . ' ,' : '' }}
                                                                        {{ !empty($vendor->shipping_state) ? $vendor->shipping_state . ' ,' : '' }}
                                                                        {{ !empty($vendor->shipping_zip) ? $vendor->shipping_zip : '' }}<br>
                                                                        {{ !empty($vendor->shipping_country) ? $vendor->shipping_country : '' }}<br>
                                                                        {{ !empty($vendor->shipping_phone) ? $vendor->shipping_phone : '' }}<br>
                                                                        <strong>{{ __('Tax Number ') }} :
                                                                        </strong>{{ !empty($vendor->tax_number) ? $vendor->tax_number : '' }}
                                                                    </small>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>

                                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-4 col-12">
                                                       @php
                                                        $total = 0;
                                                        @endphp

                                                        @foreach ($bill_payment as $payment)
                                                        @php
                                                            $total += $payment->amount;
                                                        @endphp
                                                        @endforeach
                                                        @php
                                                            $duebalance = $totalBillSum - $total;
                                                        @endphp
                                                            <p class="card-text mb-0">{{ __('Balance') }} :  <span> {{ currency_format_with_sym($vendor->balance) }}</span></p>
                                                            <p class="card-text mb-0">{{ __('Billed amount') }} :  <span> {{ currency_format_with_sym($totalBillSum) }}</span></p>
                                                            <p class="card-text mb-0">{{ __('Amount Paid') }} :  <span>{{ currency_format_with_sym($total) }}</span></p>
                                                            <p class="card-text mb-0">{{ __('Balance Due') }} :  <span>{{ currency_format_with_sym($duebalance) }}</span></p>
                                                      </div>
                                                </div>

                                                <div class="card mt-4">
                                                    <div class="card-body table-border-styletable-border-style">
                                                        <div class="table-responsive">
                                                            <div id="statement-history">
                                                                <table class="table align-items-center table_header">
                                                                    <thead>
                                                                        <tr>
                                                                            <th scope="col">{{ __('Date') }}</th>
                                                                            <th scope="col">{{ __('Bill') }}</th>
                                                                            <th scope="col">{{ __('Amount') }}</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody class="list">
                                                                        @php
                                                                            $total = 0;
                                                                        @endphp
                                                                        @forelse($bill_payment as $payment)
                                                                            <tr>
                                                                                <td>{{ company_date_formate($payment->date) }}
                                                                                </td>
                                                                                <td>{{ \Modules\Account\Entities\Bill::billNumberFormat($payment->bill_id) }}
                                                                                </td>
                                                                                <td> {{ currency_format_with_sym($payment->amount) }}
                                                                                </td>
                                                                            @empty
                                                                            <tr>
                                                                                <td colspan="6"
                                                                                    class="text-center text-dark">
                                                                                    <p>{{ __('No Data Found') }}</p>
                                                                                </td>
                                                                            </tr>
                                                                        @endforelse
                                                                    </tbody>
                                                                    <tfoot>
                                                                        <tr class="total">
                                                                            <td class="light_blue">
                                                                                <span></span><strong>{{ __('TOTAL :') }}</strong>
                                                                            </td>
                                                                            <td class="light_blue"></td>
                                                                            @foreach ($bill_payment as $key => $payment)
                                                                                @php
                                                                                    $total += $payment->amount;
                                                                                @endphp
                                                                            @endforeach

                                                                            <td class="light_blue" id="total-amount">
                                                                                <strong>{{ currency_format_with_sym($total) }}</strong>
                                                                            </td>
                                                                        </tr>
                                                                    </tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
