@extends('layouts.main')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        (function () {
            var chartBarOptions = {
                series: [
                    {
                        name: '{{ __("Order") }}',
                        data:  {!! json_encode ($chartData['data']) !!},

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
                    categories: {!! json_encode($chartData['label']) !!},
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
                        text: '{{ __("Order") }}'
                    },

                }

            };
            var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
            arChart.render();
        })();
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center mb-2 mt-2">
                            <div class="theme-avtar bg-primary">
                                <i class="ti ti-users"></i>
                            </div>
                            <div class="ms-3 mb-3 mt-3">
                                <h6 class="ml-4">{{__('Total Customers')}}</h6>
                            </div>
                        </div>

                        <div class="number-icon ms-3 mb-3 mt-3"><h3>{{$user->total_user}}</h3></div>
                            <div class="ms-3 mb-3 mt-3">
                                <h6>{{__('Paid Customers')}} : {{$user['total_paid_user']}}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center mb-2 mt-2">
                            <div class="theme-avtar bg-warning">
                                <i class="ti ti-shopping-cart"></i>
                            </div>
                            <div class="ms-3 mb-3 mt-3">
                                <h6 class="ml-4">{{__('Total Orders')}}</h6>
                            </div>
                        </div>

                        <div class="number-icon ms-3 mb-3 mt-3"><h3>{{$user->total_orders}}</h3></div>
                            <div class="ms-3 mb-3 mt-3">
                                <h6>{{__('Order Amount')}} : <span class="text-dark">{{ super_currency_format_with_sym($user['total_orders_price']) }}</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center mb-2 mt-2">
                            <div class="theme-avtar bg-info">
                                <i class="ti ti-trophy"></i>
                            </div>
                            <div class="ms-3 mb-3 mt-3">
                                <h6 class="ml-4">{{__('Total Plans')}}</h6>
                            </div>
                        </div>
                        <div class="number-icon ms-3 mb-3 mt-3"><h3>{{$user->total_plans}}</h3></div>
                            <div class="ms-3 mb-3 mt-3">
                                <h6>{{__('Popular Plan')}} : <span class="text-primary">{{ !empty($user->popular_plan) ? $user->popular_plan->name : '' }}</span></h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if (module_is_active('LandingPage'))
            @include('landingpage::layouts.dash_qr')
        @endif

        <div class="col-md-10 col-lg-10 col-12">
            <h4 class="h4 font-weight-400">{{__('Recent Order')}}</h4>
            <div class="card">
                <div class="chart">
                    <div id="chart-sales" data-color="primary" data-height="280" class="p-3"></div>
                </div>
            </div>
        </div>
    </div>

@endsection

@if (module_is_active('LandingPage'))
    @include('landingpage::layouts.dash_qr_scripts')
@endif
