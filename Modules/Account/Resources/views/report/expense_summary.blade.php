@extends('layouts.main')
@section('page-title')
    {{ __('Expense Summary') }}
@endsection
@section('page-breadcrumb')
    {{ __('Report') }},
    {{ __('Expense Summary') }}
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        (function() {
            var chartBarOptions = {
                series: [{
                    name: '{{ __('Expense') }}',
                    data: {!! json_encode($chartExpenseArr) !!},
                }, ],
                chart: {
                    height: 300,
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
                    categories: {!! json_encode($monthList) !!},
                    title: {
                        text: '{{ __('Months') }}'
                    }
                },
                colors: ['#6fd944', '#6fd944'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },

                yaxis: {
                    title: {
                        text: '{{ __('Expense') }}'
                    },
                }
            };
            var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
            arChart.render();
        })();
    </script>
    <script src="{{ asset('Modules/Account/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var year = '{{ $currentYear }}';
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
                    format: 'A2'
                }
            };
            html2pdf().set(opt).from(element).save();

        }
    </script>
@endpush
@section('page-action')
    <div>
        <a class="btn btn-sm btn-primary" onclick="saveAsPDF()" data-bs-toggle="tooltip"
            data-bs-original-title="{{ __('Download') }}">
            <i class="ti ti-download"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class=" multi-collapse mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['report.expense.summary'], 'method' => 'GET', 'id' => 'report_expense_summary']) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-2">
                                        <div class="btn-box">
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-2">
                                        <div class="btn-box">
                                            {{ Form::label('year', __('Year'), ['class' => 'form-label']) }}
                                            {{ Form::select('year', $yearList, isset($_GET['year']) ? $_GET['year'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Year']) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}
                                            {{ Form::select('category', $category, isset($_GET['category']) ? $_GET['category'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Category']) }}
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('vendor', __('Vendor'), ['class' => 'form-label']) }}
                                            {{ Form::select('vendor', $vendor, isset($_GET['vendor']) ? $_GET['vendor'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Vendor']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto float-end ms-2 mt-4">
                                        <a class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('report_expense_summary').submit(); return false;"
                                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('report.expense.summary') }}" class="btn btn-sm btn-danger"
                                            data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                            data-original-title="{{ __('Reset') }}">
                                            <span class="btn-inner--icon"><i
                                                    class="ti ti-trash-off text-white-off "></i></span>
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
    </div>

    <div id="printableArea">
        <div class="row mt-3">
            <div class="col-sm-6 col-12">
                <input type="hidden"
                    value="{{ $filter['category'] . ' ' . __('Expense Summary') . ' ' . 'Report of' . ' ' . $filter['startDateRange'] . ' to ' . $filter['endDateRange'] }}"
                    id="filename">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{ __('Report') }} :</h5>
                    <h6 class="report-text mb-0">{{ __('Expense Summary') }}</h6>
                </div>
            </div>
            @if ($filter['category'] != __('All'))
                <div class="col-sm-6 col-12">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{ __('Category') }} :</h5>
                        <h6 class="report-text mb-0">{{ $filter['category'] }}</h6>
                    </div>
                </div>
            @endif
            @if ($filter['vendor'] != __('All'))
                <div class="col-sm-6 col-12">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{ __('Vendor') }} :</h5>
                        <h6 class="report-text mb-0">{{ $filter['vendor'] }}</h6>
                    </div>
                </div>
            @endif
            <div class="col-sm-6 col-12">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{ __('Duration') }} :</h5>
                    <h6 class="report-text mb-0">{{ $filter['startDateRange'] . ' to ' . $filter['endDateRange'] }}</h6>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12" id="chart-container">
                <div class="card">
                    <div class="card-body">
                        <div class="scrollbar-inner">
                            <div id="chart-sales" data-color="primary" data-height="300"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{ __('Category') }}</th>
                                        @foreach ($monthList as $month)
                                            <th>{{ $month }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="13" class="text-dark"><span>{{ __('Payment :') }}</span></td>
                                    </tr>
                                    @foreach ($expenseArr as $i => $expense)
                                        <tr>
                                            <td>{{ !empty($expense['category']) ? $expense['category'] : '' }}</td>
                                            @foreach ($expense['data'] as $j => $data)
                                                <td>{{ currency_format_with_sym($data) }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="13" class="text-dark"><span>{{ __('Bill :') }}</span></td>
                                    </tr>
                                    @foreach ($billArray as $i => $bill)
                                        <tr>
                                            <td>{{ !empty($bill['category']) ? $bill['category'] : '' }}</td>
                                            @foreach ($bill['data'] as $j => $data)
                                                <td>{{ currency_format_with_sym($data) }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="13" class="text-dark"><span>{{ __('Purchase :') }}</span></td>
                                    </tr>
                                    @foreach ($purchaseArray as $i => $purchase)
                                        <tr>
                                            <td>{{ !empty($purchase['category']) ? $purchase['category'] : '' }}</td>
                                            @foreach ($purchase['data'] as $j => $data)
                                                <td>{{ currency_format_with_sym($data) }}</td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    @if (module_is_active('Hrm'))
                                        <tr>
                                            <td colspan="13" class="text-dark"><span>{{ __('Employee Salary :') }}</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Employee Salary') }}</td>
                                            @foreach ($EmpSalary as $j => $empsal)
                                                <td>{{ currency_format_with_sym($empsal) }}</td>
                                            @endforeach
                                        </tr>
                                        <tr>
                                            <td colspan="13" class="text-dark">
                                                <span>{{ __('Expense = Payment + Bill + Employee Salary :') }}</span>
                                            </td>
                                        </tr>
                                    @else
                                        <tr>
                                            <td colspan="13" class="text-dark">
                                                <span>{{ __('Expense = Payment + Bill + Purchase :') }}</span>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td class="text-dark">
                                            <h6>{{ __('Total') }}</h6>
                                        </td>
                                        @foreach ($chartExpenseArr as $i => $expense)
                                            <td>{{ currency_format_with_sym($expense) }}</td>
                                        @endforeach
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
