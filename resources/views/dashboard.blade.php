@extends('layouts.main')

@section('page-title')
{{ __('Dashboard')}}
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
                    <input type="text" class="form-control border-0" placeholder="Search" aria-describedby="button-addon">
                    <span class="input-group-text btn" id="button-addon"><i class="ti ti-search"></i></span>
                </div>
            </div>
            <div class="row">
                @php
                    $activeModules = ActivatedModule();
                @endphp
                @if (!empty($activeModules))
                    <div class="col-12">
                        <div class="row">
                            @if (in_array('Lead',$activeModules))
                            <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                <a href="{{ route('lead.dashboard') }}">
                                    <div class="card manager-card rounded-0">
                                        <div class="product-img bg-secondary justify-content-center my-3">
                                            <h5 class="text-capitalize text-center text-white">Lead Generation</h5>
                                        </div>
                                        <div class="card-body">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif

                            <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                <a href="#">
                                    <div class="card manager-card rounded-0">
                                        <div class="product-img bg-secondary justify-content-center my-3">
                                            <h5 class="text-capitalize text-center text-white">Marketing Automation</h5>
                                        </div>
                                        <div class="card-body">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @if (in_array('Taskly', $activeModules))
                            <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                <a href="{{ route('taskly.dashboard') }}">
                                    <div class="card manager-card rounded-0">
                                        <div class="product-img bg-secondary justify-content-center my-3">
                                            <h5 class="text-capitalize text-center text-white">Project Management</h5>
                                        </div>
                                        <div class="card-body">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif
                            @if (in_array('Hrm', $activeModules))
                            <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                <a href="{{ route('hrm.dashboard') }}">
                                    <div class="card manager-card rounded-0">
                                        <div class="product-img bg-secondary justify-content-center my-3">
                                            <h5 class="text-capitalize text-center text-white">HR Management</h5>
                                        </div>
                                        <div class="card-body">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif
                            @if (in_array('Pos', $activeModules))
                            <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                <a href="{{ route('pos.dashboard') }}">
                                    <div class="card manager-card rounded-0">
                                        <div class="product-img bg-secondary justify-content-center my-3">
                                            <h5 class="text-capitalize text-center text-white">POS & Revenue</h5>
                                        </div>
                                        <div class="card-body">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif
                            @if (in_array('Account', $activeModules))
                            <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                <a href="{{ route('dashboard.account') }}">
                                    <div class="card manager-card rounded-0">
                                        <div class="product-img bg-secondary justify-content-center my-3">
                                            <h5 class="text-capitalize text-center text-white">Accounts & Invoices</h5>
                                        </div>
                                        <div class="card-body">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif
                            @if (in_array('AIAssistant', $activeModules))
                            <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                <a href="{{ route('lead.dashboard') }}">
                                    <div class="card manager-card rounded-0">
                                        <div class="product-img bg-secondary justify-content-center my-3">
                                            <h5 class="text-capitalize text-center text-white">AI Assistant</h5>
                                        </div>
                                        <div class="card-body">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif
                            @if (in_array('PabblyConnect', $activeModules))
                            <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                <a href="{{ route('pabbly.connect.index') }}">
                                    <div class="card manager-card rounded-0">
                                        <div class="product-img bg-secondary justify-content-center my-3">
                                            <h5 class="text-capitalize text-center text-white">Pabbly Connect</h5>
                                        </div>
                                        <div class="card-body">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif
                            @if (Route::has('purchases.index'))    
                            <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                <a href="{{ route('purchases.index') }}">
                                    <div class="card manager-card rounded-0">
                                        <div class="product-img bg-secondary justify-content-center my-3">
                                            <h5 class="text-capitalize text-center text-white">Procurement</h5>
                                        </div>
                                        <div class="card-body">
                                        </div>
                                    </div>
                                </a>
                            </div>
                            @endif
                            @if (Route::has('ghl.dashboard'))    
                            <div class="col-xxl-3 col-lg-3 col-sm-6 product-card">
                                <a href="{{ route('ghl.dashboard') }}">
                                    <div class="card manager-card rounded-0">
                                        <div class="product-img bg-secondary justify-content-center my-3">
                                            <h5 class="text-capitalize text-center text-white">Expert Marketplace</h5>
                                        </div>
                                        <div class="card-body">
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
                <x-dashboard.pinned-apps />
            </div>
            <div class="row">
                <h4 class="my-3">My Pinned Apps</h4>
                @php
                    $pin_categories = \App\Models\PinnedAppCategory::where('user_id',auth()->user()->id)->get();
                @endphp
                @if (!empty($pin_categories) && ($pin_categories->count() > 0))
                @foreach ($pin_categories as $category)
                <div class="col-2">
                    <a data-ajax-popup="true"
                        data-title="{{ __('Pin App') }}"
                        data-url="{{ route('app-pin.category', $category->id) }}" data-toggle="tooltip" href="#">
                        <div class="card">
                            <div class="card-body">
                                <div class="theme-avtar">
                                    <span class="text-black"><i class="ti ti-plus"></i></span>
                                </div>
                            </div>
                            <div>
                                <span style="left: 90%; top: 90%;" class="position-absolute translate-middle p-2 bg-danger text-white rounded-circle">
                                    {{ $category->apps->count() }}
                                </span>
                            </div>
                        </div>
                        <h6 class="text-center">{{ $category->name }}</h6>
                    </a>
                </div>
                @endforeach
                @endif
                <div class="col-2">
                    <a data-ajax-popup="true" data-title="{{ __('Pin App') }}"
                        data-url="{{ route('apps.pin') }}" data-toggle="tooltip" href="#">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="theme-avtar">
                                        <span class="text-black"><i class="ti ti-plus"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h6 class="text-center">Add New Pin</h6>
                    </a>
                </div>
            </div>

        </div>
    </div>
    <div class="col-md-4 col-12" style="">
        @php
            $events = App\Models\CalendarEvent::latest()->get()->map(function (App\Models\CalendarEvent $model) {
                return [
                    'id' => $model->id,
                    'title' => $model->title,
                    'description' => $model->description,
                    'start' => $model->startDate,
                    'end' => $model->endDate,
                    'url' => $model->url,
                ];
            })->all();
        @endphp
        <div class="row">
            <div class="card shadow rounded ps-3 pe-3 m-3">
                <x-dashboard.calendar :events="$events" />
            </div>
        </div>
        <div class="row">
            <div>
                @php
                    $count = 0;
                @endphp
                @foreach ($events as $event)
                    @php
                        $count++;
                    @endphp
                    @if ($count <= 5)
                    <div class="card shadow rounded p-3 mx-3">
                        <h5>{{ $event['title'] }}</h5>
                        <a href="{{ $event['url'] ?? '#' }}" {{ !empty($event['url']) ? 'target="_blank"': '' }}><h5 class="link">{{ $event['start'] }} - {{ $event['end'] }}</h5></a>
                        <p>
                            {{ $event['description'] }}
                        </p>
                    </div>
                    @else
                     @break
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script src="{{ asset('js/owlcarousel/owl.carousel.min.js') }}"></script>
@endpush
