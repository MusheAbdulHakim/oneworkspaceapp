@extends('layouts.main')

@section('page-title')
    {{__('Dashboard')}}
@endsection

@section('page-breadcrumb')
    {{ __('POS')}}
@endsection


@section('content')
    <div class="row">
        @if (count($lowstockproducts) > 0)
            <div class="col-md-12">
                @foreach ($lowstockproducts as $product)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <span class="alert-icon"><i class="ti ti-alert-triangle"></i></span>
                        <strong>{{ $product['name'] }}</strong><small>{{ __(' (Only ') . $product['quantity'] . __(' items left)') }}</small>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    @if ( $productscount == 0 || $customers == 0 || $vendors == 0)
        <div class="row mt-4">
            <div class="col-md-12">
                <?php
                $alerts = [];
                $alerts[] = $productscount == 0 ? __('Please add some Products!') : '';

                $alerts[] = $customers == 0 ? __('Please add some Customers!') : '';

                $alerts[] = $vendors == 0 ? __('Please add some Vendors!') : '';

                $result = array_filter($alerts);
                ?>
                @if (isset($result) && !empty($result) && count($result) > 0)
                    @foreach ($result as $alert)
                        <div class="alert alert-warning alert-dismissible fade show  mt-1" role="alert">
                            <span class="alert-icon"><i class="ti ti-alert-triangle"></i></span>
                            <strong>{{ $alert }}</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                aria-label="Close"></button>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                <div class="col-xxl-12">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                <div class="theme-avtar bg-primary">
                                                    <i class="ti ti-hand-finger"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <small class="text-muted">{{ __('Sales Of This Month') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto text-end">
                                            <h4 class="m-0 text-primary">{{$monthlySelledAmount }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                <div class="theme-avtar bg-info">
                                                    <i class="ti ti-chart-pie"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <small class="text-muted">{{ __('Total Sales Amount') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto text-end">
                                            <h4 class="m-0 text-info">{{$totalSelledAmount  }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                <div class="theme-avtar bg-warning">
                                                    <i class="ti ti-report-money"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <small class="text-muted">{{ __('Purchase Of This Month') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto text-end">
                                            <h4 class="m-0 text-warning">{{$monthlyPurchasedAmount}}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-6 col-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="row align-items-center justify-content-between">
                                        <div class="col-auto mb-3 mb-sm-0">
                                            <div class="d-flex align-items-center">
                                                <div class="theme-avtar bg-danger">
                                                    <i class="ti ti-chart-bar"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <small class="text-muted">{{ __('Total Purchase Amount') }}</small>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-auto text-end">
                                            <h4 class="m-0 text-danger">{{$totalPurchasedAmount}}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> --}}
                    </div>
                </div>

                <div class="col-xxl-12">
                    <div class="card">
                        <div class="card-header">

                            <div class="row ">
                                <div class="col-6">
                                    <h5>{{ __('Sale Report') }}</h5>
                                </div>
                                <div class="col-6 text-end">
                                    <h6>{{ __('Last 10 Days') }}</h6>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div id="traffic-chart"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('Modules/Pos/Resources/assets/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('Modules/Pos/Resources/assets/js/moment.min.js') }}"></script>
    <script>
        (function() {
            var options = {
                chart: {
                    height: 350,
                    type: 'area',
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
                series: [
                    {
                        name: '{{ __('Sales') }}',
                        data: {!! json_encode($salesArray['value']) !!}

                    },
                ],
                xaxis: {
                    categories: {!! json_encode($salesArray['label']) !!},
                    title: {
                        text: '{{ __('Days') }}'
                    }
                },
                colors: ['#FF3A6E', '#6fd943'],

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
            var chart = new ApexCharts(document.querySelector("#traffic-chart"), options);
            chart.render();
        })();


        $(document).on('click', '.custom-checkbox .custom-control-input', function(e) {
            $.ajax({
                url: $(this).data('url'),
                method: 'PATCH',
                success: function(response) {},
                error: function(data) {
                    data = data.responseJSON;
                    show_toastr('{{ __('Error') }}', data.error, 'error')
                }
            });
        });
    </script>
@endpush



