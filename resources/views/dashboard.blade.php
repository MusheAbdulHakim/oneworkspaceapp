@extends('layouts.main')

@section('page-title')
    {{ __('Dashboard') }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('js/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/owlcarousel/assets/owl.theme.default.min.css') }}">
@endpush

@section('content')

    <div class="row">
        <div class="col-md-8 col-12">
            <div class="p-3">
                <div class="row mb-4">
                    <div class="input-group mb-3 shadow py-1 rounded fill-secondary">
                        <input type="text" class="form-control border-0" placeholder="Search"
                            aria-describedby="button-addon">
                        <span class="input-group-text btn" id="button-addon"><i class="ti ti-search"></i></span>
                    </div>
                </div>
                <div class="row">
                    @php
                        $activeModules = ActivatedModule();
                    @endphp
                    @if (!empty($activeModules))
                        <h4>Widgets</h4>
                        <div class="col-12">
                            <div class="row">
                                @if (in_array('Lead', $activeModules))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('lead.dashboard') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-secondary justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center text-white">Lead
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

                                <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                    <a href="#">
                                        <div class="card manager-card rounded-0">
                                            <div class="product-img bg-secondary justify-content-center text-nowrap my-3 pb-0">
                                                <p class="text-capitalize text-center text-white">Marketing
                                                    Automation</p>
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
                                @if (in_array('Taskly', $activeModules))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('taskly.dashboard') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-secondary justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center text-white">Project
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
                                                    class="product-img bg-secondary justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center text-white">HR
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
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('pos.dashboard') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-secondary justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center text-white">POS &
                                                        Revenue</p>
                                                </div>
                                                <div class="justify-content-centertext-center px-2">
                                                    <p>Total POS:
                                                    </p>
                                                    <p>Barcode:
                                                    </p>

                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if (in_array('Account', $activeModules))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('dashboard.account') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-secondary justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center text-white">Accounts &
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
                                                    class="product-img bg-secondary justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center text-white">AI
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
                                                    class="product-img bg-secondary justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center text-white">Pabbly
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
                                                    class="product-img bg-secondary justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center text-white">Procurement</p>
                                                </div>
                                                <div class="justify-content-centertext-center px-2">
                                                    <p>Purchases: {{ $procurement['totalPurchases'] ?? 0 }}</p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                                @if (Route::has('ghl.dashboard'))
                                    <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                        <a href="{{ route('embeds.marketing-hub') }}">
                                            <div class="card manager-card rounded-0">
                                                <div
                                                    class="product-img bg-secondary justify-content-center text-nowrap my-3 pb-0">
                                                    <p class="text-capitalize text-center text-white">
                                                        Unified Marketplace
                                                    </p>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
                <div class="row">
                    <x-dashboard.addons />
                </div>
                

                <div class="row">
                    <div class="col-12 my-3">
                        <div class="row">
                            <div class="col-md-9 col-12">
                                <h4>URL Bookmarks</h4>
                            </div>
                            <div class="col-md-3 col-12">
                                <a href="{{ route('bookmark.create') }}" data-bs-toggle="tooltip"
                                    data-title="{{ __('Add Bookmark') }}"
                                    data-bs-original-title="{{ __('Add Bookmark') }}"
                                    class="btn btn-sm btn-primary btn-icon">
                                    <i class="ti ti-plus"></i> Add Bookmark
                                </a>
                            </div>
                        </div>
                    </div>
                    <x-dashboard.bookmarks :bookmarks="\App\Models\UrlBookmark::where('status', '1')->get()" />
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
                                <h5>{{ $event['title'] }}</h5>
                                <a href="{{ $event['url'] ?? '#' }}"
                                    {{ !empty($event['url']) ? 'target="_blank"' : '' }}>
                                    <span class="link">{{ $event['start'] }} - {{ $event['end'] }}</span>
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
<script src="{{ asset('js/owlcarousel/owl.carousel.min.js') }}"></script>
@endpush
