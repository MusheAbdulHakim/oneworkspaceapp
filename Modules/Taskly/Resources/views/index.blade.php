@extends('layouts.main')

@section('page-title')
    {{ __('Dashboard') }}
@endsection

@section('page-breadcrumb')
    {{ __('Project')}}
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-7 col-md-7 col-sm-7">
            <div class="row">
                <div class="col-xl-3 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="theme-avtar bg-primary">
                                <i class="fas fa-tasks bg-primary text-white"></i>
                            </div>
                            <p class="text-muted text-sm"></p>
                            <h6 class="mt-4 mb-4">{{ __('Total Project') }}</h6>
                            <h3 class="mb-0">{{ $totalProject }} <span class="text-success text-sm"></span></h3>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="theme-avtar bg-info">
                                <i class="fas fa-tag bg-info text-white"></i>
                            </div>
                            <p class="text-muted text-sm "></p>
                            <h6 class="mt-4 mb-4">{{ __('Total Task') }}</h6>
                            <h3 class="mb-0">{{ $totalTask }} <span class="text-success text-sm"></span></h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3">
                    <div class="card">
                        <div class="card-body">
                            <div class="theme-avtar bg-danger">
                                <i class="fas fa-bug bg-danger text-white"></i>
                            </div>
                            <p class="text-muted text-sm"></p>
                            <h6 class="mt-4 mb-4">{{ __('Total Bug') }}</h6>
                            <h3 class="mb-0">{{ $totalBugs }} <span class="text-success text-sm"></span></h3>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="theme-avtar bg-success">
                                <i class="fas fa-users bg-success text-white"></i>
                            </div>
                            <p class="text-muted text-sm"></p>
                            <h6 class="mt-4 mb-4">{{ __('Total User') }}</h6>
                            <h3 class="mb-0">{{ $totalMembers }} <span class="text-success text-sm"></span></h3>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card ">
                <div class="card-header">
                    <div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="mb-0">
                                    {{ __('Tasks') }}
                                </h5>
                            </div>
                            <div class="float-end">
                                <small><b>{{ $completeTask }}</b> {{ __('Tasks completed out of') }}
                                    {{ $totalTask }}</small>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body ">
                    <div class="table-responsive">
                        <table class="table table-centered table-hover mb-0 animated">
                            <tbody>
                                @forelse($tasks as $task)
                                    <tr>
                                        <td>
                                            <div class="font-14 my-1"><a
                                                    href="{{ route('projects.task.board', [$task->project_id]) }}"
                                                    class="text-body">{{ $task->title }}</a></div>

                                            @php($due_date = '<span class="text-' . ($task->due_date < date('Y-m-d') ? 'danger' : 'success') . '">' . date('Y-m-d', strtotime($task->due_date)) . '</span> ')

                                            <span class="text-muted font-13">{{ __('Due Date') }} :
                                                {!! $due_date !!}</span>
                                        </td>
                                        <td>
                                            <span class="text-muted font-13">{{ __('Status') }}</span> <br />
                                            @if ($task->complete == '1')
                                                <span
                                                    class="badge bg-success p-2 px-3 rounded">{{ __($task->status) }}</span>
                                            @else
                                                <span
                                                    class="badge bg-primary p-2 px-3 rounded">{{ __($task->status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-muted font-13">{{ __('Project') }}</span>
                                            <div class="font-14 mt-1 font-weight-normal">{{ $task->project->name }}</div>
                                        </td>
                                        @if (Auth::user()->hasRole('client') || Auth::user()->hasRole('client'))
                                            <td>
                                                <span class="text-muted font-13">{{ __('Assigned to') }}</span>
                                                <div class="font-14 mt-1 font-weight-normal">
                                                    @foreach ($task->users() as $user)
                                                        <span
                                                            class="badge p-2 px-2 rounded bg-secondary">{{ isset($user->name) ? $user->name : '-' }}</span>
                                                    @endforeach
                                                </div>
                                            </td>
                                        @endif
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
        <div class="col-lg-5 col-md-5 col-sm-5">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Tasks Overview') }}</h5>
                </div>
                <div class="card-body p-2">
                    <div id="task-area-chart"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="float-end">
                        <a href="#" data-bs-toggle="tooltip" data-bs-placement="top" title="Refferals"><i
                                class=""></i></a>
                    </div>
                    <h5>{{ __('Project Status') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-xl-6 col- md-8 col-12">
                            <div id="projects-chart"></div>
                        </div>
                        <div class="col-6">

                            <div class="col-6">
                                <span class="d-flex align-items-center mb-2">
                                    <i class="f-10 lh-1 fas fa-circle text-danger"></i>
                                    <span class="ms-2 text-sm">{{ __('On Going') }}</span>
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="d-flex align-items-center mb-2">
                                    <i class="f-10 lh-1 fas fa-circle text-warning"></i>
                                    <span class="ms-2 text-sm">{{ __('On Hold') }}</span>
                                </span>
                            </div>
                            <div class="col-6">
                                <span class="d-flex align-items-center mb-2">
                                    <i class="f-10 lh-1 fas fa-circle text-primary"></i>
                                    <span class="ms-2 text-sm">{{ __('Finished') }}</span>
                                </span>
                            </div>
                        </div>
                        <div class="row text-center">
                            @foreach ($arrProcessPer as $index => $value)
                                <div class="col-4">
                                    <i class="fas fa-chart"></i>
                                    <h6 class="font-weight-bold">
                                        <span>{{ $value }}%</span>
                                    </h6>
                                    <p class="text-muted">{{ __($arrProcessLabel[$index]) }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('assets/js/plugins/apexcharts.min.js') }}"></script>
    <script>
        (function() {
            var options = {
                chart: {
                    height: 170,
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


                series: {!! json_encode($arrProcessPer) !!},
                colors: ['#FF3A6E', '#6fd943', '#ffa21d'],
                labels: {!! json_encode($arrProcessLabel) !!},
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                markers: {
                    size: 1
                },
                legend: {
                    show: false
                }
            };
            var chart = new ApexCharts(document.querySelector("#projects-chart"), options);
            chart.render();

        })();
    </script>
    <script>
        (function() {
            var options = {
                chart: {
                    height: 150,
                    type: 'line',
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
                    @foreach ($chartData['stages'] as $id => $name)
                        {
                            name: "{{ __($name) }}",
                            data: {!! json_encode($chartData[$id]) !!}
                        },
                    @endforeach
                ],
                xaxis: {
                    categories: {!! json_encode($chartData['label']) !!},
                },
                colors: {!! json_encode($chartData['color']) !!},

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },
                yaxis: {
                    tickAmount: 5,
                    min: 1,
                    max: 40,
                },
            };
            var chart = new ApexCharts(document.querySelector("#task-area-chart"), options);
            chart.render();
        })();
    </script>
@endpush
