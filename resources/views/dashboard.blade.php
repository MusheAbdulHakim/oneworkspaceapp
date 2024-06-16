@extends('layouts.main')
@section('page-title')
{{ __('Dashboard')}}
@endsection
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
                <x-dashboard.addons />
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
                                            <h5 class="text-capitalize text-center text-white">Sales Monitoring</h5>
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
                                            <h5 class="text-capitalize text-center text-white">Accounting</h5>
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
                        </div>
                    </div>
                    @if (in_array('Hrm',$activeModules))
                    @if (Auth::user()->isAbleTo('hrm dashboard manage'))
                        @include('hrm::components.calendar')
                    @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-4 col-12" style="background-color: #ebebeb; border-top-left-radius: 15px;">
        <div class="row">
            <div class="card shadow rounded ps-3 pe-3 m-3">
                <x-dashboard.calendar />
            </div>
        </div>
        <div class="row">
            <div class="card shadow rounded p-3 mx-3" style="background-color: #888686;">
                <h5>Virtual Office Activity</h5>
                <a href="#"><h5 class="link">June 15, 2024</h5></a>
                <p>Your solo plan subscribtion was automatically renewed using your payment method.</p>
            </div>
        </div>
    </div>
</div>
@endsection
