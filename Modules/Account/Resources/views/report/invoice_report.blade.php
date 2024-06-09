@extends('layouts.main')
@section('page-title')
    {{ __('Invoice Summary') }}
@endsection
@section('page-breadcrumb')
    {{ __('Report') }},
    {{ __('Invoice Summary') }}
@endsection
@push('scripts')
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        (function () {
            var chartBarOptions = {
                series: [
                    {
                        name: '{{ __("Invoice") }}',
                        data:  {!! json_encode($invoiceTotal) !!},

                    },
                ],

                chart: {
                    height: 300,
                    type: 'bar',
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
                        text: '{{ __("Months") }}'
                    }
                },
                colors: ['#6fd944', '#6fd944'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                markers: {
                    size: 4,
                    colors: ['#ffa21d', '#FF3A6E'],
                    opacity: 0.9,
                    strokeWidth: 2,
                    hover: {
                        size: 7,
                    }
                },
                yaxis: {
                    title: {
                        text: '{{ __("Invoice") }}'
                    },

                }

            };
            var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
            arChart.render();
        })();

    </script>
    <script src="{{ asset('Modules/Account/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
@endpush
@section('page-action')
    <div>
        <a  class="btn btn-sm btn-primary" onclick="saveAsPDF()"  data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Download') }}">
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
                    {{ Form::open(array('route' => array('report.invoice.summary'),'method' => 'GET','id'=>'report_invoice_summary')) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        {{ Form::label('start_month', __('Start Month'),['class'=>'form-label']) }}
                                        {{ Form::month('start_month',isset($_GET['start_month'])?$_GET['start_month']:'', array('class' => 'month-btn form-control')) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        {{ Form::label('end_month', __('End Month'),['class'=>'form-label']) }}

                                        {{ Form::month('end_month',isset($_GET['end_month'])?$_GET['end_month']:'', array('class' => 'month-btn form-control')) }}

                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        {{ Form::label('customer', __('Customer'),['class'=>'form-label']) }}
                                        {{ Form::select('customer',$customer,isset($_GET['customer'])?$_GET['customer']:'', array('class' => 'form-control ','placeholder' => 'Select Customer')) }}
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        {{ Form::label('status', __('Status'),['class'=>'form-label']) }}

                                        {{ Form::select('status', [''=>'Select Status']+$status,isset($_GET['status'])?$_GET['status']:'', array('class' => 'form-control ')) }}
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto mt-4">

                                        <a  class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('report_invoice_summary').submit(); return false;"
                                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('report.invoice.summary') }}" class="btn btn-sm btn-danger"
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
        </div>
    </div>

    <div id="printableArea">
        <div class="row mt-3">
            <div class="col">
                <input type="hidden" value="{{$filter['status'].' '.__('Invoice').' '.'Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange'].' '.__('of').' '.$filter['customer']}}" id="filename">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Report')}} :</h5>
                    <h6 class="report-text mb-0">{{__('Invoice Summary')}}</h6>
                </div>
            </div>
            @if($filter['customer']!= __('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Customer')}} :</h5>
                        <h6 class="report-text mb-0">{{$filter['customer'] }}</h6>
                    </div>
                </div>
            @endif
            @if($filter['status']!= __('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Status')}} :</h5>
                        <h6 class="report-text mb-0">{{$filter['status'] }}</h6>
                    </div>
                </div>
            @endif
            <div class="col">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Duration')}} :</h5>
                    <h6 class="report-text mb-0">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h6>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-md-6 col-lg-4">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Total Invoice')}}</h5>
                    <h6 class="report-text mb-0">{{ currency_format_with_sym($totalInvoice)}}</h6>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 col-lg-4">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Total Paid')}}</h5>
                    <h6 class="report-text mb-0">{{currency_format_with_sym($totalPaidInvoice)}}</h6>
                </div>
            </div>
            <div class="col-xl-4 col-md-6 col-lg-4">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Total Due')}}</h5>
                    <h6 class="report-text mb-0">{{currency_format_with_sym($totalDueInvoice)}}</h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12" id="invoice-container">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between w-100">

                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" id="profile-tab3" data-bs-toggle="pill" href="#summary" role="tab" aria-controls="pills-summary" aria-selected="true">{{__('Summary')}}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="contact-tab4" data-bs-toggle="pill" href="#invoices" role="tab" aria-controls="pills-invoice" aria-selected="false">{{__('Invoices')}}</a>
                                </li>

                            </ul>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="tab-content" id="myTabContent2">
                                    <div class="tab-pane fade fade" id="invoices" role="tabpanel" aria-labelledby="profile-tab3">
                                        <table class="table table-flush" id="report-dataTable">
                                            <thead>
                                            <tr>
                                                <th> {{__('Invoice')}}</th>
                                                <th> {{__('Date')}}</th>
                                                <th> {{__('Customer')}}</th>
                                                <th> {{__('Category')}}</th>
                                                <th> {{__('Status')}}</th>
                                                <th> {{__('	Paid Amount')}}</th>
                                                <th> {{__('Due Amount')}}</th>
                                                <th> {{__('Payment Date')}}</th>
                                                <th> {{__('Amount')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse ($invoices as $invoice)
                                                <tr>
                                                    <td class="Id">
                                                        <a href="{{ route('invoice.show',\Crypt::encrypt($invoice->id)) }}" class="btn btn-outline-primary">{{ \App\Models\Invoice::invoiceNumberFormat($invoice->id) }}</a>                                                    </td>
                                                    </td>
                                                    <td>{{ company_date_formate($invoice->send_date)}}</td>
                                                    <td>{{!empty($invoice->customer)? $invoice->customer->name:'-' }} </td>
                                                    <td>{{!empty($invoice->category)?$invoice->category->name:'-'}}</td>
                                                    <td>
                                                        @if ($invoice->status == 0)
                                                        <span
                                                            class="badge fix_badges bg-primary p-2 px-3 rounded">{{ __(App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 1)
                                                        <span
                                                            class="badge fix_badges bg-info p-2 px-3 rounded">{{ __(App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 2)
                                                        <span
                                                            class="badge fix_badges bg-secondary p-2 px-3 rounded">{{ __(App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 3)
                                                        <span
                                                            class="badge fix_badges bg-warning p-2 px-3 rounded">{{ __(App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                    @elseif($invoice->status == 4)
                                                        <span
                                                            class="badge fix_badges bg-danger p-2 px-3 rounded">{{ __(App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                    @endif
                                                    </td>
                                                    <td> {{\currency_format_with_sym($invoice->getTotal()-$invoice->getDue())}}</td>
                                                    <td> {{\currency_format_with_sym($invoice->getDue())}}</td>
                                                    <td>{{!empty($invoice->lastPayments)?company_date_formate($invoice->lastPayments->date):''}}</td>
                                                    <td> {{\currency_format_with_sym($invoice->getTotal())}}</td>
                                                </tr>
                                            @empty
                                                @include('layouts.nodatafound')
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="tab-pane fade fade show active" id="summary" role="tabpanel" aria-labelledby="profile-tab3">
                                        <div class="col-sm-12">
                                            <div class="scrollbar-inner">
                                                <div id="chart-sales" data-color="primary" data-type="bar" data-height="300" ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
