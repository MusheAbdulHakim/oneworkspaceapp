@extends('layouts.main')

@section('page-title')
    {{ __('Report') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dragula.min.css') }}">
@endpush

@section('page-breadcrumb')
    {{ __('Report') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="p-3 card">
                <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="pills-user-tab-1" data-bs-toggle="pill"
                            data-bs-target="#pills-user-1" type="button">{{ __('General Report') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-user-tab-2" data-bs-toggle="pill" data-bs-target="#pills-user-2"
                            type="button">{{ __('Staff Report') }}</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-user-tab-3" data-bs-toggle="pill" data-bs-target="#pills-user-3"
                            type="button">{{ __('Pipelines Report') }}</button>
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-user-1" role="tabpanel" aria-labelledby="pills-user-tab-1">
                            <div class="row">
                                <div class="col-md-6">

                                    <div class="card">
                                    <div class="panel_s">
                                        <div class="panel-heading">
                                            <h5 class="panel-title" style="padding-top:15px; padding-left:20px;">{{ __('This Week Leads Conversions')}}</h5><hr>
                                        </div>
                                        <div class="panel-body">

                                            <div id="leads-this-week" height="353" style="display: block; height:315px;"
                                            data-color="primary" data-height="280"></div>
                                        </div>
                                    </div>
                                     </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                    <div class="panel_s">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"  style="padding-top:15px; padding-left:20px;">{{ __('Sources Conversion')}}</h5><hr>
                                        </div>
                                        <div class="panel-body">

                                            <div class="leads-sources-report" id="leads-sources-report" height="353" style="display: block;"
                                            data-color="primary" data-height="280"></div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="card">
                                    <div class="panel_s">
                                        <div class="panel-heading">
                                            <h5 class="panel-title"  style="padding-top:15px; padding-left:20px;">{{ __('Monthly')}}</h3>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-2 form-label"  style="padding-top:15px; padding-left:30px;">
                                                    <select name="month" class="selectpicker form-control" id="selectmonth" data-none-selected-text="Nothing selected" >
                                                        <option value="select month">{{ __('Select Month')}}</option>
                                                        <option value="1">{{ __('January')}}</option>
                                                        <option value="2">{{ __('February')}}</option>
                                                        <option value="3">{{ __('March')}}</option>
                                                        <option value="4">{{ __('April')}}</option>
                                                        <option value="5">{{ __('May')}}</option>
                                                        <option value="6">{{ __('June')}}</option>
                                                        <option value="7">{{ __('July')}}</option>
                                                        <option value="8">{{ __('August')}}</option>
                                                        <option value="9">{{ __('September')}}</option>
                                                        <option value="10">{{__('October')}}</option>
                                                        <option value="11">{{ __('November')}}</option>
                                                        <option value="12">{{ __('December')}}</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="relative" style="max-height:400px;">
                                            <div id="leads-monthly" data-color="primary" data-height="280"></div>

                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-user-2" role="tabpanel" aria-labelledby="pills-user-tab-2">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel_s">
                                        <div class="panel-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                    {{ Form::label('From Date', __('From Date'),['class'=>'col-form-label']) }}
                                                    {{ Form::date('From Date',null, array('class' => 'form-control from_date','id'=>'data_picker1',)) }}
                                                    <span id="fromDate" style="color: red;"></span>
                                                    </div>
                                                    <div class="col-md-4">
                                                    {{ Form::label('To Date', __('To Date'),['class'=>'col-form-label']) }}
                                                    {{ Form::date('To Date',null, array('class' => 'form-control to_date','id'=>'data_picker2',)) }}
                                                    <span id="toDate"  style="color: red;"></span>
                                                    </div>
                                                    <div class="col-md-4" id="filter_type" style="padding-top : 38px;">
                                                    <button  class="btn btn-primary label-margin generate_button" >{{ __('Generate') }}</button>
                                                    </div>
                                                </div>
                                            <div id="leads-staff-report"  height="400" style="display: block;" width="1100"
                                            data-color="primary" data-height="280"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                       <div class="tab-pane fade" id="pills-user-3" role="tabpanel" aria-labelledby="pills-user-tab-3">
                        <h5>{{__('Pipelines')}}</h5>
                             <div class="row">
                             <div class="col-md-12">
                             <div class="panel_s">
                               <div class="panel-body">
                                   <div id="leads-piplines-report"  height="400" style="display: block;" width="1100"
                                 data-color="primary" data-height="280"></div>
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

@push('scripts')
<script>
    $(".generate_button").click(function(){
        var from_date = $('.from_date').val();
        if(from_date == ''){
            $("#fromDate").text("Please select date");
        }else{
            $("#fromDate").empty();
        }
        var to_date = $('.to_date').val();
        if(to_date == ''){
            $("#toDate").text("Please select date");
        }else{
            $("#toDate").empty();
        }
        $.ajax({
            url: "{{ route('report.lead') }}",
            type: "get",
            data: {
                "From_Date": from_date,
                "To_Date": to_date,
                "type": 'staff_repport',
                "_token": "{{ csrf_token() }}",
            },

            cache: false,
            success: function(data) {


                $("#leads-staff-report").empty();
                var chartBarOptions = {
                series: [{
                    name: 'Lead',
                    data: data.data,
                }],

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
                    categories: data.name,


                },
                colors: ['#6fd944', '#6fd944'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                }

            };
            var arChart = new ApexCharts(document.querySelector("#leads-staff-report"), chartBarOptions);
            arChart.render();

            }
        })
    });

    $(document).ready(function(){
        $("#selectmonth").change(function(){
            var selectedVal = $(this).val()
            if( selectedVal == 'select month'){
                var selectedVal = null;
            }
           $.ajax({
             url:  "{{route('report.lead')}}",
             type: "get",
             data:{
                "start_month": selectedVal,
                "_token": "{{ csrf_token() }}",
             },
             cache: false,
             success: function(data){

                $("#leads-monthly").empty();
                var chartBarOptions = {
                series: [{
                    name: 'Lead',
                    data: data.data,
                }],

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
                    categories:data.name,

                    title: {
                        text: '{{ __("Lead Per Month") }}'
                    }
                },
                colors: ['#ff3a6e', '#ff3a6e'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                }

            };
            var arChart = new ApexCharts(document.querySelector("#leads-monthly"), chartBarOptions);
            arChart.render();
             }
           })
        });
    });


    $(document).on('click', '.lang-tab .nav-link', function() {
        $('.lang-tab .nav-link').removeClass('active');
        $('.tab-pane').removeClass('active');
        $(this).addClass('active');
        var id = $('.lang-tab .nav-link.active').attr('data-href');
        $(id).addClass('active');
    });
</script>
<script src="{{asset('assets/js/plugins/apexcharts.min.js')}}"></script>


<script>
    var options = {
            series: {!! json_encode($devicearray['data']) !!},
            chart: {
                width: 400,
                type: 'pie',
            },

            colors: ["#28B8DA","#03a9f4","#c53da9","#757575","#8e24aa","#d81b60","#0288d1"],
            labels: {!! json_encode($devicearray['label']) !!},

            responsive: [{
                breakpoint: 480,
                options: {
                    chart: {
                        width: 260
                    },
                    legend: {
                        position: 'bottom',
                    }
                }
            }]
        };
        var chart = new ApexCharts(document.querySelector("#leads-this-week"), options);
        chart.render();

        (function () {
            var chartBarOptions = {
                series: [{
                    name: 'Source',
                    data: {!! json_encode($leadsourceeData) !!},
                }],

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
                    categories: {!! json_encode($leadsourceName) !!},

                    title: {
                        text: '{{ __("Source") }}'
                    }
                },
                colors: ['#6fd944', '#6fd944'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                }

            };
            var arChart = new ApexCharts(document.querySelector("#leads-sources-report"), chartBarOptions);
            arChart.render();
         })();

         (function () {
            var chartBarOptions = {
                series: [{
                    name: 'Lead',
                    data: {!! json_encode($data) !!},
                }],

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
                    categories: {!! json_encode($labels) !!},

                    title: {
                        text: '{{ __("Lead Per Month") }}'
                    }
                },
                colors: ['#c53da9', '#c53da9'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                }

            };
            var arChart = new ApexCharts(document.querySelector("#leads-monthly"), chartBarOptions);
            arChart.render();
         })();

         (function () {
            var chartBarOptions = {
                series: [{
                    name: 'Lead',
                    data: {!! json_encode($leadusereData) !!},
                }],

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
                    categories: {!! json_encode($leaduserName) !!},


                },
                colors: ['#6fd944', '#6fd944'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                }

            };
            var arChart = new ApexCharts(document.querySelector("#leads-staff-report"), chartBarOptions);
            arChart.render();
         })();

         (function () {
            var chartBarOptions = {
                series: [{
                    name: 'Pipeline',
                    data: {!! json_encode($leadpipelineeData) !!},
                }],

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
                    categories: {!! json_encode($leadpipelineName) !!},

                    title: {
                        text: '{{ __("Pipelines") }}'
                    }
                },
                yaxis: {
                    title: {
                        text: '{{ __("Leads") }}'
                    }
                },
                colors: ['#6fd944', '#6fd944'],


                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                }

            };
            var arChart = new ApexCharts(document.querySelector("#leads-piplines-report"), chartBarOptions);
            arChart.render();
         })();
</script>

@endpush
