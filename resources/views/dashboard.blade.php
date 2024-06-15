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
                <h4>Oneworkspace Addons</h4>
                @if (!empty(ActivatedModule()))
                    @php
                        $exceptions = ['ProductService','Stripe','Paypal'];
                    @endphp
                    <div class="col-12">
                        {{-- <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">

                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                              <span class="carousel-control-next-icon" aria-hidden="true"></span>
                              <span class="visually-hidden">Next</span>
                            </button>
                        </div> --}}
                        <div class="row">
                            @foreach (ActivatedModule() as $i => $module)
                                @php
                                    $module = Module::find($module);
                                @endphp
                                @if (!in_array($module, $exceptions))
                                <div class="col-2 {{ $i == '0' ? 'active': '' }} product-card">
                                    <a href="{{ route('software.details',Module_Alias_Name($module->getName())) }}" target="_new">
                                        <div class="card manager-card roundedf">
                                            <div class="theme-avtar justify-content-center">
                                                <img src="{{ get_module_img($module->getName()) }}"
                                                    alt="{{ $module->getName() }}" class="img-user"
                                                    style="max-width: 100%">
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif
                                @endforeach
                        </div>
                    </div>

                    <div class="">
                        <h3>Pinned Apps</h3>
                        <x-pin-apps />
                    </div>

                @endif
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
