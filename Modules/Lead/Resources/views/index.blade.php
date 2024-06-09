@extends('layouts.main')

@section('page-title')
    {{ __('Dashboard')}}
@endsection

@section('page-breadcrumb')
    {{ __('CRM')}}
@endsection

@push('css')
<link rel="stylesheet" href="{{ asset('Modules/Lead/Resources/assets/css/main.css') }}" />

@endpush
@push('scripts')
<script src="{{ asset('Modules/Lead/Resources/assets/js/main.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        @if($calenderTasks)
            (function () {
                var etitle;
                var etype;
                var etypeclass;
                var calendar = new FullCalendar.Calendar(document.getElementById('event_calendar'), {
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'timeGridDay,timeGridWeek,dayGridMonth'
                    },
                    buttonText: {
                    timeGridDay: "{{__('Day')}}",
                    timeGridWeek: "{{__('Week')}}",
                    dayGridMonth: "{{__('Month')}}"
                    },
                    themeSystem: 'bootstrap',
                    initialDate: '{{$transdate}}',
                    slotDuration: '00:10:00',
                    navLinks: true,
                    droppable: true,
                    selectable: true,
                    selectMirror: true,
                    editable: true,
                    dayMaxEvents: true,
                    handleWindowResize: true,
                    events: {!! json_encode($calenderTasks) !!},
                });
                calendar.render();
            })();
        @endif

        $(document).on('click', '.fc-daygrid-event', function (e) {
            if (!$(this).hasClass('deal')) {
                e.preventDefault();
                var event = $(this);
                var title = $(this).find('.fc-event-title-container .fc-event-title').html();
                var size = 'md';
                var url = $(this).attr('href');
                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size);

                $.ajax({
                    url: url,
                    success: function (data) {
                        $('#commonModal .body').html(data);
                        $("#commonModal").modal('show');
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        toastrs('Error', data.error, 'error')
                    }
                });
            }
        });
    </script>

    <script>
        @if(\Auth::user()->type == 'client')
        (function() {
            @if(!empty($dealdata['date']))
            var options = {
                chart: {
                    height: 104,
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

                series: [{
                    name: "{{__('Won Deal by day')}}",
                    data:{!! json_encode($dealdata['deal']) !!}
                }, ],

                xaxis: {
                    categories:{!! json_encode($dealdata['date']) !!},

                },
                colors: ['#6fd943','#2633cb'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                yaxis: {
                    tickAmount: 3,
                }

            };

            @endif
            var chart = new ApexCharts(document.querySelector("#deal_data"), options);
            chart.render();
        })();
        @endif
    </script>

    <script>
        (function() {
            @if(!empty($chartcall['date']))
            var options = {
                chart: {
                    height: 104,
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

                series: [{
                    name: "{{__('Deal calls by day')}}",
                    data:{!! json_encode($chartcall['dealcall']) !!}
                }, ],

                xaxis: {
                    categories:{!! json_encode($chartcall['date']) !!},

                },
                colors: ['#6fd943','#2633cb'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                yaxis: {
                    tickAmount: 3,
                }

            };

            @endif
            var chart = new ApexCharts(document.querySelector("#callchart"), options);
            chart.render();
        })();
    </script>


    <script>
        var WorkedHoursChart = (function () {
            var $chart = $('#deal_stage');

            function init($this) {
                var options = {
                    chart: {
                        height: 270,
                        type: 'bar',
                        zoom: {
                            enabled: false
                        },
                        toolbar: {
                            show: false
                        },
                        shadow: {
                            enabled: false,
                        },

                    },
                    plotOptions: {
                bar: {
                    columnWidth: '30%',
                    borderRadius: 10,
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
                    stroke: {
                show: true,
                width: 1,
                colors: ['#fff']
            },
                    series: [{
                        name: 'Platform',
                        data: {!! json_encode($dealStageData) !!},
                    }],
                    xaxis: {
                        labels: {
                            // format: 'MMM',
                            style: {
                                colors: '#293240',
                                fontSize: '12px',
                                fontFamily: "sans-serif",
                                cssClass: 'apexcharts-xaxis-label',
                            },
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true,
                            borderType: 'solid',
                            color: '#f2f2f2',
                            height: 6,
                            offsetX: 0,
                            offsetY: 0
                        },
                        title: {
                            text: 'Platform'
                        },
                        categories: {!! json_encode($dealStageName) !!},
                    },
                    yaxis: {
                        labels: {
                            style: {
                                color: '#f2f2f2',
                                fontSize: '12px',
                                fontFamily: "Open Sans",
                            },
                        },
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: true,
                            borderType: 'solid',
                            color: '#f2f2f2',
                            height: 6,
                            offsetX: 0,
                            offsetY: 0
                        }
                    },
                    fill: {
                        type: 'solid',
                        opacity: 1

                    },
                    markers: {
                        size: 4,
                        opacity: 0.7,
                        strokeColor: "#000",
                        strokeWidth: 3,
                        hover: {
                            size: 7,
                        }
                    },
                    grid: {
                        borderColor: '#f2f2f2',
                        strokeDashArray: 5,
                    },
                    dataLabels: {
                        enabled: false
                    }
                }
                // Get data from data attributes
                var dataset = $this.data().dataset,
                    labels = $this.data().labels,
                    color = $this.data().color,
                    height = $this.data().height,
                    type = $this.data().type;

                // Init chart
                var chart = new ApexCharts($this[0], options);
                // Draw chart
                setTimeout(function () {
                    chart.render();
                }, 300);
            }

            // Events
            if ($chart.length) {
                $chart.each(function () {
                    init($(this));
                });
            }
        })();
    </script>

    <script>
        var today = new Date()
        var curHr = today.getHours()
        var target = document.getElementById("greetings");

        if (curHr < 12) {
            target.innerHTML = "{{ __('Good Morning,') }}";
        } else if (curHr < 17) {
            target.innerHTML = "{{ __('Good Afternoon,') }}";
        } else {
            target.innerHTML = "{{ __('Good Evening,') }}";
        }
    </script>

@endpush

@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Lead/Resources/assets/css/custom.css')}}">
@endpush
@section('content')


    <div class="row">

            @php
            $class = '';
            if(count($arrCount) < 3)
            {
                $class = 'col-lg-4 col-md-4';
            }
            else
            {
                $class = 'col-lg-3 col-md-3';
            }
            @endphp

            <div class="col-xxl-7">
                <div class="row">
                        <div class="{{ $class }} col-6">
                        <div class="card dash1-card">
                            <div class="card-body mb-2">
                                <div class="theme-avtar bg-danger">
                                    <i class="ti ti-home"></i>
                                </div>
                                <p class="text-muted text-m mt-4 mb-4" id="greetings"></p>
                                <h5 class="mb-0">{{$workspace->name }}</h5>
                            </div>
                        </div>
                        </div>

                    @if(isset($arrCount['client']))
                        <div class="{{ $class }} col-6">
                        <div class="card dash1-card">
                            <div class="card-body mb-2">
                                <div class="theme-avtar bg-success">
                                    <i class="ti ti-user"></i>
                                </div>
                                <p class="text-muted text-m mt-4 mb-4">{{ __('Total Client') }}</p>
                                <h3 class="mb-0">{{ $arrCount['client'] }}</h3>
                            </div>
                        </div>
                        </div>
                    @endif


                    @if(isset($arrCount['user']))
                        <div class="{{ $class }} col-6">
                            <div class="card dash1-card">
                                <div class="card-body mb-2">
                                    <div class="theme-avtar bg-info">
                                        <i class="ti ti-users"></i>
                                    </div>
                                    <p class="text-muted text-m mt-4 mb-4">{{ __('Total User') }}</p>
                                    <h3 class="mb-0">{{ $arrCount['user'] }}</h3>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if(isset($arrCount['deal']))
                        <div class="{{ $class }} col-6">
                            <div class="card dash1-card">
                                <div class="card-body mb-2">
                                    <div class="theme-avtar bg-warning">
                                        <i class="ti ti-rocket"></i>
                                    </div>
                                    <p class="text-muted text-m mt-4 mb-4">{{ __('Total Deal') }}</p>
                                    <h3 class="mb-0">{{ $arrCount['deal'] }}</h3>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if(isset($arrCount['task']))
                        <div class="{{ $class }} col-6">
                            <div class="card dash1-card">
                                <div class="card-body mb-2">
                                    <div class="theme-avtar bg-danger">
                                        <i class="ti ti-subtask"></i>
                                    </div>
                                    <p class="text-muted text-m mt-4 mb-4">{{ __('Total Task') }}</p>
                                    <h3 class="mb-0">{{ $arrCount['task'] }}</h3>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>


                <div class="card top-card ">
                    <div class="card-header">
                        <h5>{{ __('Recently created deals') }}</h5>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{__('Deal Name')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('Created At')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($deals as $deal)
                                        <tr>
                                            <td>{{$deal->name}}</td>
                                            <td>{{$deal->stage->name}}</td>
                                            <td>{{$deal->created_at}}</td>
                                        </tr>
                                    @empty
                                        @include('layouts.nodatafound')
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                @if($calenderTasks)
                <div class="card">
                        <div class="card-header">
                            <h5>{{__('Calendar')}}</h5>
                        </div>
                        <div class="card-body">
                            <div class="w-100" id='event_calendar'></div>
                        </div>
                    </div>

                @endif
            </div>

            <div class="col-xxl-5">

                @if(!empty($dealdata))
                    @if(\Auth::user()->type == 'client')
                        <div class="card">
                            <div class="card-header ">
                                @if(\Auth::user()->type != 'super admin')
                                <h5>{{__('Won Deals by day')}}</h5>
                                @endif
                            </div>
                            <div class="card-body p-2">
                                <div id="deal_data" data-color="primary"  data-height="230">
                                </div>
                            </div>
                        </div>
                    @endif
                @endif

                @if(!empty($chartcall))
                        <div class="card">
                            <div class="card-header ">
                                @if(\Auth::user()->type != 'super admin')
                                <h5>{{__('Deal calls by day')}}</h5>
                                @endif
                            </div>
                            <div class="card-body p-2">
                                <div id="callchart" data-color="primary"  data-height="230"></div>
                            </div>
                        </div>
                @endif
                @if(!empty($dealStageData))
                    @if(\Auth::user()->type == 'company')
                    <div class="card">
                        <div class="card-header ">
                            <h5>{{__('Deals by stage')}}</h5>
                        </div>
                        <div class="card-body p-2">
                            <div id="deal_stage" data-color="primary"  data-height="230"></div>
                        </div>
                    </div>
                    @endif
                @endif

                <div class="card">
                    <div class="card-header">
                        <h5>{{ __('Recently modified deals') }}</h5>
                    </div>
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>{{__('Deal Name')}}</th>
                                        <th>{{__('Status')}}</th>
                                        <th>{{__('Updated At')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($deals as $deal)
                                        <tr>
                                            <td>{{$deal->name}}</td>
                                            <td>{{$deal->stage->name}}</td>
                                            <td>{{$deal->updated_at}}</td>
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

@endsection
