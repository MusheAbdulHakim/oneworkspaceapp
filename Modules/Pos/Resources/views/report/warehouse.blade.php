@extends('layouts.main')
@section('page-title')
    {{__('Warehouse Report')}}
@endsection
@section('page-breadcrumb')
    {{ __('Report') }},
    {{ __('Warehouse Report') }}
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script src="{{ asset('Modules/Pos/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>
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
    <script>
        (function () {
            var chartBarOptions = {
                series: [
                    {
                        name: '{{ __("Product") }}',
                        data:  {!! json_encode($warehouseProductData) !!},
                        // data:  [150,90,160,80],
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
                    categories: {!! json_encode($warehousename) !!},
                    title: {
                        text: '{{ __("Warehouse") }}'
                    }
                },
                colors: ['#6fd944'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },

                {{--yaxis: {--}}
                {{--    title: {--}}
                {{--        text: '{{ __("Product") }}'--}}
                {{--    },--}}

                {{--}--}}

            };
            var arChart = new ApexCharts(document.querySelector("#warehouse_report"), chartBarOptions);
            arChart.render();
        })();

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

    <div id="printableArea">
        <div class="row mt-4">
            <input type="hidden" value="{{__('Warehouse Report')}}" id="filename">
            <div class="col">
                <div class="card p-4 mb-4">
                    <h7 class="report-text gray-text mb-0">{{__('Report')}} :</h7>
                    <h6 class="report-text mb-0">{{__('Warehouse Report')}}</h6>
                </div>
            </div>
            <div class="col">
                <div class="card p-4 mb-4">
                    <h7 class="report-text gray-text mb-0">{{__('Total Warehouse')}} :</h7>
                    <h6 class="report-text mb-0">{{$totalWarehouse}}</h6>
                </div>
            </div>

            <div class="col">
                <div class="card p-4 mb-4">
                    <h7 class="report-text gray-text mb-0">{{__('Total Product')}} :</h7>
                    <h6 class="report-text mb-0">{{$totalProduct}}</h6>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <div class="row ">
                            <div class="col-6">
                                <h6>{{ __('Warehouse Report') }}</h6>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="warehouse_report"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection



