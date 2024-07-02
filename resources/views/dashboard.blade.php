@extends('layouts.main')

@section('page-title')
    {{ __('Dashboard') }}
@endsection

@push('css')
    <!--Slick CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('js/slick/slick-theme.css') }}"/>
    <link rel="stylesheet" type="text/css" href="{{ asset('js/slick/slick.css') }}"/>
    <style>
        .slick-slide{
          margin:10px;
        }
        .customPrev, .customNext {
            display: block;
            top: 50%;
            background: inherit;
            color: inherit;
            position: absolute;
            border: none;
            outline: none;
            -webkit-transform: translate(0, -50%);
            -ms-transform: translate(0, -50%);
            transform: translate(0, -50%);
            cursor: pointer;
            z-index: 100;
        }
        .customPrev {
            left: -20px;
        }
        .customNext {
            right: -20px;
        }
    </style>
@endpush

@section('content')

    <div class="row">
        <div class="col-md-8 col-12">
            <div class="p-3">
                <div class="row mb-4">
                    <div class="input-group mb-3 shadow py-1 rounded fill-secondary">
                        <input type="text" class="form-control border-0" placeholder="Search Oneworkspace"
                            aria-describedby="button-addon">
                        <span class="input-group-text btn" id="button-addon"><i class="ti ti-search"></i></span>
                    </div>
                </div>
                <div class="row mb-4">
                    @php
                        $activeModules = ActivatedModule();
                    @endphp
                    @if (!empty($activeModules))
                        <p class="text-black"><b>Dashboard Snapshots</b></p>
                        <div class="col-12">
                            <div class="row">
                                <div id="modules-slick">
                                    @if (in_array('Lead', $activeModules))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('lead.dashboard') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-light-secondary text-black justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center">Lead
                                                        Generation</p>
                                                </div>
                                                <div class="justify-content-centertext-center px-2">
                                                    <p>Stages: {{ $LeadPipeline->leadStages->count() ?? 0 }}</p>
                                                    <p>Deals: {{ $totalDeals ?? 0 }}</p>
                                                    <p>Clients: {{ $totalLeadClients ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if (in_array('gohighlevel',$activeModules) || in_array('GoHighLevel', $activeModules))
                                <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                    <a href="#">
                                        <div class="card manager-card rounded-0">
                                            <div class="product-img bg-light-secondary text-black justify-content-center text-nowrap my-3 pb-0">
                                                <p class="text-capitalize text-center">GoHighLevel</p>
                                            </div>
                                            @if (!empty($ghl))
                                                <div class="justify-content-centertext-center px-2">
                                                    <p>Contacts: {{ $ghl['contacts'] ?? 0 }}</p>
                                                    <p>Invoices: {{ $ghl['invoices'] ?? 0 }}</p>
                                                    <p>Funnels: {{ $ghl['funnels'] ?? 0 }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                                @endif
                                @if (in_array('Taskly', $activeModules))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('taskly.dashboard') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-light-secondary text-black justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center ">Project
                                                        Management
                                                    </p>
                                                </div>
                                                <div class="justify-content-centertext-center px-2">
                                                    <p>Projects: {{ $projects['total'] ?? 0 }}</p>
                                                    <p>Tasks: {{ $projects['tasks'] ?? 0 }}</p>
                                                    <p>Completed Tasks: {{ $projects['completedTasks'] ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if (in_array('Hrm', $activeModules))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('hrm.dashboard') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-light-secondary text-black justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center">HR
                                                        Management</p>
                                                </div>
                                                <div class="justify-content-centertext-center px-2">
                                                    <p>Employees: {{ $hrm['employees'] ?? 0 }}</p>
                                                    <p>Attendances: {{ $hrm['attendances'] ?? 0 }}</p>
                                                    <p>Resignations: {{ $projects['resignations'] ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if (in_array('Pos', $activeModules))
                                    @php
                                        $posPayments = Modules\Pos\Entities\Pos::where('created_by', creatorId())->where('workspace',getActiveWorkSpace())->with('customer','warehouse','posPayment')->get();
                                    @endphp
                                    @if (auth()->user()->isAbleTo('pos dashboard manage'))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('pos.dashboard') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-light-secondary text-black justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center">POS &
                                                        Revenue</p>
                                                </div>
                                                <div class="justify-content-centertext-center px-2">
                                                    <p>Total POS: {{ \Modules\Pos\Entities\Pos::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->count() ?? 0 }}
                                                    </p>
                                                    <p>Orders: {{ $posPayments->count() ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                    @endif
                                @endif
                                @if (in_array('Account', $activeModules))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('dashboard.account') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-light-secondary text-black justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center">Accounts &
                                                        Invoices
                                                    </p>
                                                </div>
                                                <div class="justify-content-centertext-center px-2">
                                                    <p>Income Today:
                                                        {{ currency_format_with_sym(\Modules\Account\Entities\AccountUtility::todayIncome()) }}
                                                    </p>
                                                    <p>Customers:
                                                        {{ \Modules\Account\Entities\AccountUtility::countCustomers() }}
                                                    </p>
                                                    <p>Clients: {{ $totalLeadClients ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if (in_array('AIAssistant', $activeModules))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('lead.dashboard') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-light-secondary text-black justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center ">AI
                                                        Assistant</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if (in_array('PabblyConnect', $activeModules))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('pabbly.connect.index') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-light-secondary text-black justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center">Pabbly
                                                        Connect</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if (Route::has('purchases.index'))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('purchases.index') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-light-secondary text-black justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center ">Procurement</p>
                                                </div>
                                                <div class="justify-content-centertext-center px-2">
                                                    <p>Warehouses: {{ $procurement['warehouses'] ?? 0 }}</p>
                                                    <p>Purchases: {{ $procurement['purchases'] ?? 0 }}</p>
                                                    <p>Transfers: {{ $procurement['transfers'] ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if (Route::has('gohighlevel.dashboard'))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('embeds.marketplace') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-light-secondary text-black justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center">
                                                        Unified Marketplace
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row mb-4">
                    <x-dashboard.slick-addons />
                </div>

                <div class="row mb-4">
                    <x-dashboard.bookmark-carousel />
                </div>
            </div>
        </div>
        <div class="col-md-4 col-12">
            @php
                $events = App\Models\CalendarEvent::latest()
                    ->get()
                    ->map(function (App\Models\CalendarEvent $model) {
                        return [
                            'id' => $model->id,
                            'title' => $model->title,
                            'description' => $model->description,
                            'start' => $model->startDate,
                            'end' => $model->endDate,
                            'color' => $model->color,
                            'url' => $model->url ?? '',
                        ];
                    })
                    ->all();
            @endphp
            <div class="row">
                <div class="card shadow rounded ps-3 pe-3 m-3">
                    <div class="col-12">
                        <x-dashboard.calendar :events="$events" />
                    </div>
                    <div class="col-12 mt-4">
                        @php
                            $count = 0;
                        @endphp
                        @foreach ($events as $event)
                            <hr class="text-light-secondary">
                            @if (++$count <= 5)
                                <p><b>{{ $event['title'] }}</b></p>
                                <a href="{{ $event['url'] ?? '#' }}"
                                    {{ !empty($event['url']) ? 'target="_blank"' : '' }}>
                                    <p>{{ $event['start'] }} - {{ $event['end'] }}</p>
                                </a>
                                <p class="mt-1">
                                    {{ $event['description'] }}
                                </p>
                            @else
                            @break
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript" src="{{ asset('js/slick/slick.min.js') }}"></script>
<script>
    $(document).ready(function(){
        $('#modules-slick').slick({
            infinite: true,
            lazyLoad: 'ondemand',
            slidesToShow: 3,
            slidesToScroll: 1,
            draggable: true,
            prevArrow: '<button type="button" class="btn btn-sm customPrev btn-primary"><span class="material-symbols-outlined">chevron_left</span></button>',
            nextArrow: '<button type="button" class="btn btn-sm customNext btn-primary"><span class="material-symbols-outlined">chevron_right</span></button>',
        });   
    })
</script>
@endpush
