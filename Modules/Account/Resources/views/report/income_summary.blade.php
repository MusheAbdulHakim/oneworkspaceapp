@extends('layouts.main')
@section('page-title')
    {{ __('Income Summary') }}
@endsection
@section('page-breadcrumb')
    {{ __('Report') }},
    {{ __('Income Summary') }}
@endsection
@push('scripts')
<script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        (function () {
        var chartBarOptions = {
            series: [
                {
                    name: '{{ __("Income") }}',
                    data:  {!! json_encode($chartIncomeArr) !!},

                },
            ],
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

            yaxis: {
                title: {
                    text: '{{ __("Income") }}'
                },

            }

        };
        var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
        arChart.render();
        })();
    </script>
    <script src="{{ asset('Modules/Account/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var year = '{{$currentYear}}';
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
                    {{ Form::open(array('route' => array('report.income.summary'),'method' => 'GET','id'=>'report_income_summary')) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        {{ Form::label('year', __('Year'), ['class' => 'form-label']) }}
                                        {{ Form::select('year',$yearList,isset($_GET['year'])?$_GET['year']:'', array('class' => 'form-control ')) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}
                                        {{ Form::select('category',$category,isset($_GET['category'])?$_GET['category']:'', array('class' => 'form-control ','placeholder' => 'Select Category')) }}
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        {{ Form::label('customer', __('Customer'), ['class' => 'form-label']) }}
                                        {{ Form::select('customer',$customer,isset($_GET['customer'])?$_GET['customer']:'', array('class' => 'form-control ','placeholder' => 'Select Customer')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto float-end ms-2 mt-4">
                                        <a  class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('report_income_summary').submit(); return false;"
                                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('report.income.summary') }}" class="btn btn-sm btn-danger"
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
                <input type="hidden" value="{{$filter['category'].' '.__('Income Summary').' '.'Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange']}}" id="filename">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Report')}} :</h5>
                    <h6 class="report-text mb-0">{{__('Income Summary')}}</h6>
                </div>
            </div>
            @if($filter['category']!= __('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Category')}} :</h5>
                        <h6 class="report-text mb-0">{{$filter['category']}}</h6>
                    </div>
                </div>
            @endif
            @if($filter['customer']!= __('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Customer')}} :</h5>
                        <h6 class="report-text mb-0">{{$filter['customer']}}</h6>
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
            <div class="col-12" id="chart-container">
                <div class="card">
                    <div class="card-body">
                        <div class="scrollbar-inner">
                            <div id="chart-sales" data-color="primary" data-height="300" ></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table ">
                                <thead>
                                <tr>
                                    <th>{{__('Category')}}</th>
                                    @foreach($monthList as $month)
                                        <th>{{$month}}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="13" class="text-dark"><span>{{__('Revenue :')}}</span></td>
                                </tr>
                                @foreach($incomeArr as $i=>$income)
                                    <tr>
                                        <td>{{!empty($income['category']) ? $income['category'] :''}}</td>
                                        @foreach($income['data'] as $j=>$data)
                                            <td>{{ currency_format_with_sym($data)}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="13" class="text-dark"><span>{{__('Invoice :')}}</span></td>
                                </tr>
                                @foreach($invoiceArray as $i=>$invoice)
                                    <tr>
                                        <td>{{!empty($invoice['category']) ? $invoice['category'] :''}}</td>
                                        @foreach($invoice['data'] as $j=>$data)
                                            <td>{{currency_format_with_sym($data)}}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr>
                                    <td colspan="13" class="text-dark"><span>{{__('Income = Revenue + Invoice :')}}</span></td>
                                </tr>
                                <tr>
                                    <td class="text-dark"><h6>{{__('Total')}}</h6></td>
                                    @foreach($chartIncomeArr as $i=>$income)
                                        <td>{{currency_format_with_sym($income)}}</td>
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


