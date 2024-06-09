@extends('layouts.main')

@section('page-title')
    {{ __('Project Detail') }}
@endsection


@section('page-breadcrumb')
    {{ __('Project Report') }},
    {{ __('Project Details') }}
@endsection


@section('page-action')
    <div>
        <a href="#" onclick="saveAsPDF()" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
            title="{{ __('Download') }}"    >
            <i class="ti ti-file-download "></i>
        </a>
    </div>
@endsection

@php
    $client_keyword = Auth::user()->hasRole('client') ? 'client.' : '';
@endphp
@section('content')

    <div class="row">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row" id="printableArea">
                <div class="col-md-6">
                    <div class="card report-card">
                        <div class="card-header">
                            <h5>{{ __('Overview') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-7">

                                    <table class="table" id="pc-dt-simple">
                                        <tbody>
                                            <tr class="table_border">
                                                <th class="table_border">{{ __('Project Name') }}:</th>
                                                <td class="table_border">{{ $project->name }}</td>
                                            </tr>
                                            <tr>
                                                <th class="table_border">{{ __('Project Status') }}:</th>
                                                <td class="table_border">
                                                    @if ($project->status == 'Finished')
                                                        <div class="badge  bg-success p-2 px-3 rounded">
                                                            {{ __('Finished') }}
                                                        </div>
                                                    @elseif($project->status == 'Ongoing')
                                                        <div class="badge  bg-secondary p-2 px-3 rounded">
                                                            {{ __('Ongoing') }}</div>
                                                    @else
                                                        <div class="badge bg-warning p-2 px-3 rounded">
                                                            {{ __('OnHold') }}</div>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr role="row">
                                                <th class="table_border">{{ __('Start Date') }}:</th>
                                                <td class="table_border">
                                                    {{ company_date_formate($project->start_date) }}</td>
                                            </tr>
                                            <tr>
                                                <th class="table_border">{{ __('Due Date') }}:</th>
                                                <td class="table_border">{{ company_date_formate($project->end_date) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <th class="table_border">{{ __('Total Members') }}:</th>
                                                <td class="table_border">
                                                    {{ (int) $project->users->count() + (int) $project->clients->count() }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-5 ">
                                    @php
                                        $task_percentage = $project->project_progress()['percentage'];
                                        $data = trim($task_percentage, '%');
                                        $status = $data > 0 && $data <= 25 ? 'red' : ($data > 25 && $data <= 50 ? 'orange' : ($data > 50 && $data <= 75 ? 'blue' : ($data > 75 && $data <= 100 ? 'green' : '')));
                                    @endphp

                                    <div class="circular-progressbar p-0">
                                        <div class="flex-wrapper">
                                            <div class="single-chart">
                                                <svg viewBox="0 0 36 36"
                                                    class="circular-chart orange {{ $status }}">
                                                    <path class="circle-bg"
                                                        d="M18 2.0845
                                                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                                                a 15.9155 15.9155 0 0 1 0 -31.831" />
                                                    <path class="circle" stroke-dasharray="{{ $data }}, 100"
                                                        d="M18 2.0845
                                                                                a 15.9155 15.9155 0 0 1 0 31.831
                                                                                a 15.9155 15.9155 0 0 1 0 -31.831" />
                                                    <text x="18" y="20.35"
                                                        class="percentage">{{ $data }}%</text>
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @php
                    $mile_percentage = $project->project_milestone_progress()['percentage'];
                    $mile_percentage = trim($mile_percentage, '%');
                @endphp

                <div class="col-md-6">
                    <div class="card report-card">
                        <div class="card-header" style="padding: 25px 35px !important;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="row">
                                    <h5 class="mb-0">{{ __('Milestone Progress') }}</h5>

                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                            </div>

                            <div id="milestone-chart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card report-chart-card">
                        <div class="card-header">
                            <div class="float-end">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i
                                        class=""></i></a>
                            </div>
                            <h5>{{ __('Task Priority') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div id='chart_priority'></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="card report-chart-card">
                        <div class="card-header">
                            <div class="float-end">
                                <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i
                                        class=""></i></a>
                            </div>
                            <h5>{{ __('Task Status') }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-12">
                                    <div id="chart"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-md-5">
                    <div class="card report-card">
                        <div class="card-header">
                            <h5>{{ __('Users') }}</h5>
                        </div>
                        <div class="card-body table-border-style top-10-scroll">
                            <div class="table-responsive">
                                <table class=" table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Assigned Tasks') }}</th>
                                            <th>{{ __('Done Tasks') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($project->users as $user)
                                            @php
                                                $hours_format_number = 0;
                                                $total_hours = 0;
                                                $hourdiff_late = 0;
                                                $esti_late_hour = 0;
                                                $esti_late_hour_chart = 0;

                                                $total_user_task = Modules\Taskly\Entities\Task::where('project_id', $project->id)
                                                    ->whereRaw('FIND_IN_SET(?,  assign_to) > 0', [$user->id])
                                                    ->get()
                                                    ->count();

                                                $all_task = Modules\Taskly\Entities\Task::where('project_id', $project->id)
                                                    ->whereRaw('FIND_IN_SET(?,  assign_to) > 0', [$user->id])
                                                    ->get();

                                                $total_complete_task = Modules\Taskly\Entities\Task::join('stages', 'stages.id', '=', 'tasks.status')
                                                    ->where('project_id', '=', $project->id)
                                                    ->where('assign_to', '=', $user->id)
                                                    ->where('stages.complete', '=', '1')
                                                    ->get()
                                                    ->count();
                                            @endphp

                                            <tr>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $total_user_task }}</td>
                                                <td>{{ $total_complete_task }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-7">
                    <div class="card report-card">
                        <div class="card-header">
                            <h5>{{ __('Milestones') }}</h5>
                        </div>
                        <div class="card-body table-border-style top-10-scroll">
                            <div class="table-responsive">
                                <table class=" table ">
                                    <thead>
                                        <tr>
                                            <th> {{ __('Name') }}</th>
                                            <th> {{ __('Progress') }}</th>
                                            <th> {{ __('Cost') }}</th>
                                            <th> {{ __('Status') }}</th>
                                            <th> {{ __('Start Date') }}</th>
                                            <th> {{ __('End Date') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($project->milestones as $key => $milestone)
                                            <tr>
                                                <td>{{ $milestone->title }}</td>
                                                <td>
                                                    <div class="progress_wrapper">
                                                        <div class="progress">
                                                            <div class="progress-bar" role="progressbar"
                                                                style="width: {{ $milestone->progress }}px;"
                                                                aria-valuenow="55" aria-valuemin="0"
                                                                aria-valuemax="100"></div>
                                                        </div>
                                                        <div class="progress_labels">
                                                            <div class="total_progress">

                                                                <strong> {{ $milestone->progress }}%</strong>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $milestone->cost }}</td>
                                                <td>
                                                    @if ($milestone->status == 'complete')
                                                        <label
                                                            class="badge bg-success p-2 px-3 rounded">{{ __('Complete') }}</label>
                                                    @else
                                                        <label
                                                            class="badge bg-warning p-2 px-3 rounded">{{ __('Incomplete') }}</label>
                                                    @endif
                                                </td>
                                                <td>{{ $milestone->start_date }}</td>
                                                <td>{{ $milestone->end_date }}</td>


                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{ Form::open(['route' => [$client_keyword.'project_report.show', [$project->id]], 'method' => 'GET', 'id' => 'product_service']) }}
            <div class="mt-3 mb-2 d-sm-flex align-items-center justify-content-end" id="show_filter">

                @if (Auth::user()->hasRole('company') || Auth::user()->hasRole('client'))
                    <div class="col-3 px-2">
                        <select class="select2 form-select" name="all_users" id="all_users">
                            <option value="" class="px-4">{{ __('All Users') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-2" >
                    <select class="select2 form-select" name="milestone_id" id="milestone_id">
                        <option value="" class="px-4">{{ __('All Milestones') }}</option>
                        @foreach ($milestones as $milestone)
                            <option value="{{ $milestone->id }}">{{ $milestone->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2 px-2" >
                    <select class="select2 form-select" name="status" id="status">
                        <option value="" class="px-4">{{ __('All Status') }}</option>
                        @foreach ($stages as $stage)
                            <option value="{{ $stage->id }}">{{ __($stage->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-2">
                    <select class="select2 form-select" name="priority" id="priority">
                        <option value="" class="px-4">{{ __('All Priority') }}</option>
                        <option value="Low">{{ __('Low') }}</option>
                        <option value="Medium">{{ __('Medium') }}</option>
                        <option value="High">{{ __('High') }}</option>
                    </select>
                </div>

                <button type="submit" class=" btn btn-primary col-1 mx-2">{{ __('Apply') }}</button>
                <a href="{{ route($client_keyword.'project_report.show', [$project->id]) }}" class="btn  btn-danger" data-bs-toggle="tooltip"
                    title="{{ __('Reset') }}">
                    <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
                </a>
                
            </div>
            {{ Form::close() }}

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body mt-3 mx-2">
                        <div class="row">
                            <div class="col-md-12 mt-2">

                                <div class="table-responsive">
                                    <table
                                        class="table datatable pc-dt-simple table-centered table-hover mb-0 animated px-4 mt-2"
                                        id="tasks-selection-datatable">
                                        <thead>
                                            <th>{{ __('Task Name') }}</th>
                                            <th>{{ __('Milestone') }}</th>
                                            <th>{{ __('Start Date') }}</th>
                                            <th>{{ __('Due Date') }}</th>
                                            @if (Auth::user()->hasRole('company') || Auth::user()->hasRole('client'))
                                                <th>{{ __('Assigned to') }}</th>
                                            @endif
                                            <th>{{ __('Priority') }}</th>
                                            <th>{{ __('Status') }}</th>

                                        </thead>
                                        <tbody>
                                            @foreach($tasksData as $row)
                                                <tr>
                                                    <td>{!! $row['title'] !!}</td>
                                                    <td>{!! $row['milestone'] !!}</td>
                                                    <td>{!! $row['start_date'] !!}</td>
                                                    <td>{!! $row['due_date'] !!}</td>
                                                    <td>{!! $row['user_name'] !!}</td>
                                                    <td>{!! $row['priority'] !!}</td>
                                                    <td>{!! $row['status'] !!}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
            <!-- [ sample-page ] end -->
        <!-- [ Main Content ] end -->
    </div>
@endsection


@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Taskly/Resources/assets/css/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('Modules/Taskly/Resources/assets/css/custom.css') }}">
    <style type="text/css">
        .apexcharts-menu-icon {
            display: none;
        }

        table.dataTable.no-footer {
            border-bottom: none !important;
        }

        .table_border {
            border: none !important
        }
    </style>
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script src="{{ asset('Modules/Taskly/Resources/assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('Modules/Taskly/Resources/assets/js/apexcharts.min.js') }}"></script>

    <script>
        (function() {
            var options = {
                series: [{!! json_encode($mile_percentage) !!}],
                chart: {
                    height: 475,
                    type: 'radialBar',
                    offsetY: -20,
                    sparkline: {
                        enabled: true
                    }
                },
                plotOptions: {
                    radialBar: {
                        startAngle: -90,
                        endAngle: 90,
                        track: {
                            background: "#e7e7e7",
                            strokeWidth: '97%',
                            margin: 5, // margin is in pixels
                        },
                        dataLabels: {
                            name: {
                                show: true
                            },
                            value: {
                                offsetY: -50,
                                fontSize: '20px'
                            }
                        }
                    }
                },
                grid: {
                    padding: {
                        top: -10
                    }
                },
                colors: ["#51459d"],
                labels: ['Progress'],
            };
            var chart = new ApexCharts(document.querySelector("#milestone-chart"), options);
            chart.render();
        })();

        var arrProcessPer_status_task =  {!! json_encode($arrProcessPer_status_task) !!};
        if(arrProcessPer_status_task.length > 0){
            var options = {
                series: {!! json_encode($arrProcessPer_status_task) !!},
                chart: {
                    width: 380,
                    type: 'pie',
                },
                colors: {!! json_encode($chartData['color']) !!},
                labels: {!! json_encode($arrProcess_Label_status_tasks) !!},
                responsive: [{
                    breakpoint: 480,
                    options: {
                        chart: {
                            width: 100
                        },
                        legend: {
                            position: 'bottom'
    
                        }
                    }
                }]
            };
    
            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        }



        var options = {
            series: [{
                data: {!! json_encode($arrProcessPer_priority) !!}
            }],
            chart: {
                height: 250,
                type: 'bar',
            },
            colors: ['#6fd943', '#ff3a6e', '#3ec9d6'],
            plotOptions: {
                bar: {

                    columnWidth: '50%',
                    distributed: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: true
            },
            xaxis: {
                categories: {!! json_encode($arrProcess_Label_priority) !!},
                labels: {
                    style: {
                        colors: {!! json_encode($chartData['color']) !!},

                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart_priority"), options);
        chart.render();


        ///=====================Hour Chart =============================================================///


        var options = {
            series: [{
                data: [{!! json_encode($esti_logged_hour_chart) !!}, {!! json_encode($logged_hour_chart) !!}],

            }],
            chart: {
                height: 210,
                type: 'bar',
            },
            colors: ['#963aff', '#ffa21d'],
            plotOptions: {
                bar: {
                    horizontal: true,
                    columnWidth: '30%',
                    distributed: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: true
            },
            xaxis: {
                categories: ["Estimated Hours", "Logged Hours "],

            }
        };

        var chart = new ApexCharts(document.querySelector("#chart-hours"), options);
        chart.render();
    </script>


    <script>
        var filename = $('#chart-hours').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,

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
