@extends('layouts.main')
@section('page-title')
    {{ __('Dashboard') }}
@endsection
@section('page-breadcrumb')
    {{ __('Account') }}
@endsection
@section('content')
    @if (session('status'))
        <div class="alert alert-success" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-7">
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-primary">
                                        <i class="ti ti-users"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                    <h6 class="mb-3 text-primary">{{ __('Customers') }}</h6>
                                    <h3 class="mb-0 text-primary">
                                        {{ \Modules\Account\Entities\AccountUtility::countCustomers() }}

                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-info">
                                        <i class="ti ti-note"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                    <h6 class="mb-3 text-info">{{ __('Vendors') }}</h6>
                                    <h3 class="mb-0 text-info">
                                        {{ \Modules\Account\Entities\AccountUtility::countVendors() }}
                                    </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-warning">
                                        <i class="ti ti-file-invoice"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                    <h6 class="mb-3 text-warning">{{ __('Invoices') }}</h6>
                                    <h3 class="mb-0 text-warning">{{ \App\Models\Invoice::countInvoices() }} </h3>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-6">
                            <div class="card">
                                <div class="card-body">
                                    <div class="theme-avtar bg-danger">
                                        <i class="ti ti-report-money"></i>
                                    </div>
                                    <p class="text-muted text-sm mt-4 mb-2">{{ __('Total') }}</p>
                                    <h6 class="mb-3 text-danger">{{ __('Bills') }}</h6>
                                    <h3 class="mb-0 text-danger">
                                        {{ \Modules\Account\Entities\AccountUtility::countBills() }} </h3>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card" style="min-height: 370px;">
                        <div class="card-header">
                            <h5 class="mt-1 mb-0">{{ __('Account Balance') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Bank') }}</th>
                                            <th>{{ __('Holder Name') }}</th>
                                            <th>{{ __('Balance') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($bankAccountDetail as $bankAccount)
                                            <tr class="font-style">
                                                <td>{{ $bankAccount->bank_name }}</td>
                                                <td>{{ $bankAccount->holder_name }}</td>
                                                <td>{{ currency_format_with_sym($bankAccount->opening_balance) }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">
                                                    <div class="text-center">
                                                        <h6>{{ __('there is no account balance') }}</h6>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-5">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mt-1 mb-0">{{ __('Cashflow') }}</h5>
                        </div>
                        <div class="card-body">
                            <div id="cash-flow"></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h5 class="mt-1 mb-0">{{ __('Income Vs Expense') }}</h5>
                            <div class="row mt-4">

                                <div class="col-md-6 col-12 my-2">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-primary">
                                            <i class="ti ti-report-money"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0">{{ __('Income Today') }}</p>
                                            <h4 class="mb-0 text-success">
                                                {{ currency_format_with_sym(\Modules\Account\Entities\AccountUtility::todayIncome()) }}
                                            </h4>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-info">
                                            <i class="ti ti-file-invoice"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0">{{ __('Expense Today') }}</p>
                                            <h4 class="mb-0 text-info">
                                                {{ currency_format_with_sym(\Modules\Account\Entities\AccountUtility::todayExpense()) }}
                                            </h4>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-warning">
                                            <i class="ti ti-report-money"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0">{{ __('Income This Month') }}</p>
                                            <h4 class="mb-0 text-warning">
                                                {{ currency_format_with_sym(\Modules\Account\Entities\AccountUtility::incomeCurrentMonth()) }}
                                            </h4>

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 col-12 my-2">
                                    <div class="d-flex align-items-start">
                                        <div class="theme-avtar bg-danger">
                                            <i class="ti ti-file-invoice"></i>
                                        </div>
                                        <div class="ms-2">
                                            <p class="text-muted text-sm mb-0">{{ __('Expense This Month') }}</p>
                                            <h4 class="mb-0 text-danger">
                                                {{ currency_format_with_sym(\Modules\Account\Entities\AccountUtility::expenseCurrentMonth()) }}
                                            </h4>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-7">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ __('Income & Expense') }}
                                <span class="float-end text-muted">{{ __('Current Year') . ' - ' . $currentYear }}</span>
                            </h5>

                        </div>
                        <div class="card-body">
                            <div id="incExpBarChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-5">
                    <div class="card" style="height: 315px">
                        <div class="card-header">
                            <h5>{{ __('Income By Category') }}
                                <span class="float-end text-muted">{{ __('Year') . ' - ' . $currentYear }}</span>
                            </h5>

                        </div>
                        <div class="card-body">
                            <div id="incomeByCategory"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-7">
                    <div class="card" style="min-height: 369px;">
                        <div class="card-header">
                            <h5 class="mt-1 mb-0">{{ __('Latest Income') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Customer') }}</th>
                                            <th>{{ __('Amount Due') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($latestIncome as $income)
                                            <tr>
                                                <td>{{ company_date_formate($income->date) }}</td>
                                                <td>{{ !empty($income->customer) ? $income->customer->name : '-' }}</td>
                                                <td>{{ currency_format_with_sym($income->amount) }}</td>
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



                <div class="col-xxl-5">
                    <div class="card" style="height: 369px">
                        <div class="card-header">
                            <h5>{{ __('Expense By Category') }}
                                <span class="float-end text-muted">{{ __('Year') . ' - ' . $currentYear }}</span>
                            </h5>

                        </div>
                        <div class="card-body">
                            <div id="expenseByCategory"></div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mt-1 mb-0">{{ __('Latest Expense') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Vendor') }}</th>
                                            <th>{{ __('Amount Due') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($latestExpense as $expense)
                                            <tr>
                                                <td>{{ company_date_formate($expense->date) }}</td>
                                                <td>{{ !empty($expense->vendor) ? $expense->vendor->name : '-' }}</td>
                                                <td>{{ currency_format_with_sym($expense->amount) }}</td>
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
                <div class="col-xxl-7">
                    <div class="card" style="min-height: 395px;">
                        <div class="card-header">
                            <h5 class="mt-1 mb-0">{{ __('Recent Invoices') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Customer') }}</th>
                                            <th>{{ __('Issue Date') }}</th>
                                            <th>{{ __('Due Date') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentInvoice as $invoice)
                                            <tr>
                                                <td>{{ \App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id) }}
                                                </td>
                                                <td>{{ !empty($invoice->customer) ? $invoice->customer->name : '' }} </td>
                                                <td>{{ company_date_formate($invoice->issue_date) }}</td>
                                                <td>{{ company_date_formate($invoice->due_date) }}</td>
                                                <td>{{ currency_format_with_sym($invoice->getTotal()) }}</td>
                                                <td>
                                                    @if ($invoice->status == 0)
                                                        <span
                                                            class="p-2 px-3 fix_badges rounded badge bg-secondary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 1)
                                                        <span
                                                            class="p-2 px-3 fix_badges rounded badge bg-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 2)
                                                        <span
                                                            class="p-2 px-3 fix_badges rounded badge bg-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 3)
                                                        <span
                                                            class="p-2 px-3 fix_badges rounded badge bg-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 4)
                                                        <span
                                                            class="p-2 px-3 fix_badges rounded badge bg-success">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                    @endif
                                                </td>
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
                <div class="col-xxl-5">
                    <div class="card" style="height: 396px">
                        <div class="card-body">

                            <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-Dashboard-tab" data-bs-toggle="pill"
                                        href="#invoice_weekly_statistics" role="tab" aria-controls="pills-home"
                                        aria-selected="true">{{ __('Invoices Weekly Statistics') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                        href="#invoice_monthly_statistics" role="tab" aria-controls="pills-profile"
                                        aria-selected="false">{{ __('Invoices Monthly Statistics') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="invoice_weekly_statistics" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0 ">
                                            <tbody class="list">
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Invoice Generated') }}
                                                        </p>

                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($weeklyInvoice['invoiceTotal']) }}
                                                        </h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Paid') }}</p>
                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($weeklyInvoice['invoicePaid']) }}
                                                        </h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Due') }}</p>
                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($weeklyInvoice['invoiceDue']) }}
                                                        </h4>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="invoice_monthly_statistics" role="tabpanel"
                                    aria-labelledby="pills-profile-tab">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0 ">
                                            <tbody class="list">
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Invoice Generated') }}
                                                        </p>

                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($monthlyInvoice['invoiceTotal']) }}
                                                        </h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Paid') }}</p>
                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($monthlyInvoice['invoicePaid']) }}
                                                        </h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Due') }}</p>
                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($monthlyInvoice['invoiceDue']) }}
                                                        </h4>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-7">
                    <div class="card" style="min-height: 408px;">
                        <div class="card-header">
                            <h5 class="mt-1 mb-0">{{ __('Recent Bills') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Vendor') }}</th>
                                            <th>{{ __('Bill Date') }}</th>
                                            <th>{{ __('Due Date') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($recentBill as $bill)
                                            <tr>
                                                <td>{{ \Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id) }}
                                                </td>
                                                <td>{{ !empty($bill->vendor_name) ? $bill->vendor_name : '' }} </td>
                                                <td>{{ company_date_formate($bill->bill_date) }}</td>
                                                <td>{{ company_date_formate($bill->due_date) }}</td>
                                                <td>{{ currency_format_with_sym($bill->getTotal()) }}</td>
                                                <td>
                                                    @if ($bill->status == 0)
                                                        <span
                                                            class="p-2 px-3 fix_badge rounded badge bg-secondary">{{ __(\Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                                    @elseif($bill->status == 1)
                                                        <span
                                                            class="p-2 px-3 fix_badge rounded badge bg-warning">{{ __(\Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                                    @elseif($bill->status == 2)
                                                        <span
                                                            class="p-2 px-3 fix_badge rounded badge bg-danger">{{ __(\Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                                    @elseif($bill->status == 3)
                                                        <span
                                                            class="p-2 px-3 fix_badge rounded badge bg-info">{{ __(\Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                                    @elseif($bill->status == 4)
                                                        <span
                                                            class="p-2 px-3 fix_badge rounded badge bg-success">{{ __(\Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                                    @endif
                                                </td>
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
                <div class="col-xxl-5">
                    <div class="card" style="height: 408px">
                        <div class="card-body">

                            <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                        href="#bills_weekly_statistics" role="tab" aria-controls="pills-home"
                                        aria-selected="true">{{ __('Bills Weekly Statistics') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-profile-tab" data-bs-toggle="pill"
                                        href="#bills_monthly_statistics" role="tab" aria-controls="pills-profile"
                                        aria-selected="false">{{ __('Bills Monthly Statistics') }}</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="pills-tabContent">
                                <div class="tab-pane fade show active" id="bills_weekly_statistics" role="tabpanel"
                                    aria-labelledby="pills-home-tab">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0 ">
                                            <tbody class="list">
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Bill Generated') }}</p>

                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($weeklyBill['billTotal']) }}</h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Paid') }}</p>
                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($weeklyBill['billPaid']) }}</h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Due') }}</p>
                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($weeklyBill['billDue']) }}</h4>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="bills_monthly_statistics" role="tabpanel"
                                    aria-labelledby="pills-profile-tab">
                                    <div class="table-responsive">
                                        <table class="table align-items-center mb-0 ">
                                            <tbody class="list">
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Bill Generated') }}</p>

                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($monthlyBill['billTotal']) }}</h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Paid') }}</p>
                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($monthlyBill['billPaid']) }}</h4>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <h5 class="mb-0">{{ __('Total') }}</h5>
                                                        <p class="text-muted text-sm mb-0">{{ __('Due') }}</p>
                                                    </td>
                                                    <td>
                                                        <h4 class="text-muted">
                                                            {{ currency_format_with_sym($monthlyBill['billDue']) }}</h4>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @if (module_is_active('Goal'))
                    @include('goal::dashboard.dshboard_div')
                @endif
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        (function() {
            var chartBarOptions = {
                series: [{
                        name: "{{ __('Income') }}",
                        data: {!! json_encode($incExpLineChartData['income']) !!}
                    },
                    {
                        name: "{{ __('Expense') }}",
                        data: {!! json_encode($incExpLineChartData['expense']) !!}
                    }
                ],

                chart: {
                    height: 250,
                    type: 'area',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: {!! json_encode($incExpLineChartData['day']) !!},
                    title: {
                        text: '{{ __('Date') }}'
                    }
                },
                colors: ['#ffa21d', '#FF3A6E'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                yaxis: {
                    title: {
                        text: '{{ __('Amount') }}'
                    },

                }

            };
            var arChart = new ApexCharts(document.querySelector("#cash-flow"), chartBarOptions);
            arChart.render();
        })();

        (function() {
            var options = {
                chart: {
                    height: 180,
                    type: 'bar',
                    toolbar: {
                        show: false,
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                series: [{
                    name: "{{ __('Income') }}",
                    data: {!! json_encode($incExpBarChartData['income']) !!}
                }, {
                    name: "{{ __('Expense') }}",
                    data: {!! json_encode($incExpBarChartData['expense']) !!}
                }],
                xaxis: {
                    categories: {!! json_encode($incExpBarChartData['month']) !!},
                },
                colors: ['#3ec9d6', '#FF3A6E'],
                fill: {
                    type: 'solid',
                },
                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: true,
                    position: 'top',
                    horizontalAlign: 'right',
                },
                markers: {
                    size: 4,
                    colors: ['#3ec9d6', '#FF3A6E', ],
                    opacity: 0.9,
                    strokeWidth: 2,
                    hover: {
                        size: 7,
                    }
                }
            };
            var chart = new ApexCharts(document.querySelector("#incExpBarChart"), options);
            chart.render();
        })();

        (function() {
            var options = {
                chart: {
                    height: 140,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!! json_encode($expenseCatAmount) !!},
                colors: {!! json_encode($expenseCategoryColor) !!},
                labels: {!! json_encode($expenseCategory) !!},
                legend: {
                    show: true
                }
            };
            var chart = new ApexCharts(document.querySelector("#expenseByCategory"), options);
            chart.render();
        })();

        (function() {
            var options = {
                chart: {
                    height: 140,
                    type: 'donut',
                },
                dataLabels: {
                    enabled: false,
                },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '70%',
                        }
                    }
                },
                series: {!! json_encode($incomeCatAmount) !!},
                colors: {!! json_encode($incomeCategoryColor) !!},
                labels: {!! json_encode($incomeCategory) !!},
                legend: {
                    show: true
                }
            };
            var chart = new ApexCharts(document.querySelector("#incomeByCategory"), options);
            chart.render();
        })();
    </script>
@endpush
