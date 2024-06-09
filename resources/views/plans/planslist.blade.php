@extends('layouts.main')
@section('page-title')
    {{ __('Subscription Setting') }}
@endsection
@section('page-breadcrumb')
    {{ __('Subscription Setting') }}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/subscription.css') }}">
@endpush
@section('page-action')
    <div>
        <div class="button-tab-wrapper">
            <div class="create-packge-tab">
                <label for="plan_package">
                    <h5>{{ __('Create Package') }}</h5>
                </label>
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="plan_package" class="form-check-input input-primary pointer" id="plan_package"
                        {{ admin_setting('plan_package') == 'on' ? 'checked' : '' }}>
                    <label class="form-check-label" for="plan_package"></label>
                </div>
            </div>
            <div class="custome-design-tab">
                <label for="custome_package">
                    <h5>{{ __('Custom Design Package') }}</h5>
                </label>
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="custome_package" class="form-check-input input-primary pointer"
                        id="custome_package" {{ admin_setting('custome_package') == 'on' ? 'checked' : '' }}>
                    <label class="form-check-label" for="custome_package"></label>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
@section('content')
    <div class="row justify-content-center px-0">
        @if (admin_setting('custome_package') == 'on' && admin_setting('plan_package') == 'on')
            <div class=" col-12">
                <div class="">
                    <div class="card-body package-card-inner  d-flex align-items-center justify-content-center my-3">
                        <div class="tab-main-div">
                            <div class="nav-pills">
                                <a class="nav-link active p-2" href="{{ route('plan.list') }}" role="tab"
                                    aria-controls="pills-home"
                                    aria-selected="true">{{ __('Pre-Packaged Subscription') }}</a>
                            </div>
                            <div class="nav-pills">
                                <a class="nav-link  p-2" href="{{ route('plans.index') }}" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Usage Subscription') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if (admin_setting('plan_package') == 'on')
            <div class="row">
                <div class="col mb-4">
                    <div class="float-end">
                        <a href="#" data-size="lg" data-url="{{ route('plans.create') }}" data-ajax-popup="true"
                            data-bs-toggle="tooltip" title="" data-title="Create New Plan"
                            class="btn btn-sm btn-primary" data-bs-original-title="Create">
                            <i class="ti ti-plus"></i>
                        </a>
                    </div>
                </div>
                <div class="plan-package-table">
                    <div id="table-scroll" class="table-scroll">
                        <div class="table-wrap">
                            <!-- basic-plan-card-wrap-scrollbar this class use for scrollbar -->
                            <div
                                class="basic-plan-card-wrap {{ $plan->count() > 3 ? 'basic-plan-card-wrap-scrollbar' : '' }} d-flex">
                                <div class="compare-plans plans-compare-main">
                                    <div class="compare-plan-title">
                                        <h4>{{ __('Compare our plans') }}</h4>
                                    </div>
                                    <ul class="compare-plan-opction p-0">
                                        @foreach ($modules as $module)
                                            @php
                                                $id = strtolower(preg_replace('/\s+/', '_', $module->getName()));
                                                $path = $module->getPath() . '/module.json';
                                                $json = json_decode(file_get_contents($path), true);
                                            @endphp
                                            @if (!isset($json['display']) || $json['display'] == true)
                                                <li>
                                                    <a target="_new"
                                                        href="{{ route('software.details', Module_Alias_Name($module->getName())) }}">{{ Module_Alias_Name($module->getName()) }}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @foreach ($plan as $single_plan)
                                    @php
                                        $plan_modules = !empty($single_plan->modules) ? explode(',', $single_plan->modules) : [];
                                    @endphp
                                    <div class="basic-plan-card">
                                        <div class="basic-plan text-center mb-4">

                                            <div class="d-flex justify-content-center">
                                                <div class="form-check form-switch custom-switch-v1">
                                                    <input type="checkbox" data-id="{{ $single_plan->id }}"
                                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ __('Enable/Disable') }}"
                                                        class="form-check-input input-primary"
                                                        {{ $single_plan->status == 1 ? 'checked' : '' }}>
                                                </div>
                                                <h4 class="px-5">
                                                    {{ !empty($single_plan->name) ? $single_plan->name : __('Basic') }}
                                                </h4>

                                                <a href="#" class="btn btn-sm btn-primary"
                                                    data-url="{{ route('plans.edit', $single_plan->id) }}"
                                                    data-ajax-popup="true" data-title="Edit Plan" data-toggle="tooltip"
                                                    data-size="lg" title="" data-bs-original-title="Edit">
                                                    <span class=""><i class="ti ti-pencil text-white"></i></span>
                                                </a>

                                                @if ($single_plan->id != 1)
                                                    {!! Form::open([
                                                        'method' => 'DELETE',
                                                        'route' => ['plans.destroy', $single_plan->id],
                                                        'id' => 'delete-form-' . $single_plan->id,
                                                    ]) !!}
                                                    <a href="#!"
                                                        class="btn btn-sm btn-primary ms-2 align-items-center text-white show_confirm"
                                                        data-bs-toggle="tooltip" title='Delete'>
                                                        <i class="ti ti-trash"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                @endif

                                            </div>

                                            <div class="price">
                                                <ins class="per_month_price">{{ super_currency_format_with_sym($single_plan->package_price_monthly) }}<span
                                                        class="off-type">{{ __('/Per Month') }}</span></ins>
                                            </div>
                                            <div class="price">
                                                <ins class="per_year_price">{{ super_currency_format_with_sym($single_plan->package_price_yearly) }}<span
                                                        class="off-type">{{ __('/Per Year') }}</span></ins>
                                            </div>
                                            <ul class="plan-info">
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="8"
                                                        viewBox="0 0 9 8" fill="none">
                                                        <path
                                                            d="M8.34762 1.03752C8.18221 0.872095 7.91403 0.872095 7.74858 1.03752L2.67378 6.11237L0.723112 4.1617C0.557699 3.99627 0.289518 3.99629 0.124072 4.1617C-0.0413573 4.32712 -0.0413573 4.5953 0.124072 4.76073L2.37426 7.01088C2.53962 7.1763 2.808 7.17618 2.9733 7.01088L8.34762 1.63656C8.51305 1.47115 8.51303 1.20295 8.34762 1.03752Z"
                                                            fill="#0CAF60" />
                                                    </svg>
                                                    <span>{{ __('Max User :') }}
                                                        <b>{{ $single_plan->number_of_user == -1 ? 'Unlimited' : (!empty($single_plan->number_of_user) ? $single_plan->number_of_user : 'Unlimited') }}</b></span>
                                                </li>
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="8"
                                                        viewBox="0 0 9 8" fill="none">
                                                        <path
                                                            d="M8.34762 1.03752C8.18221 0.872095 7.91403 0.872095 7.74858 1.03752L2.67378 6.11237L0.723112 4.1617C0.557699 3.99627 0.289518 3.99629 0.124072 4.1617C-0.0413573 4.32712 -0.0413573 4.5953 0.124072 4.76073L2.37426 7.01088C2.53962 7.1763 2.808 7.17618 2.9733 7.01088L8.34762 1.63656C8.51305 1.47115 8.51303 1.20295 8.34762 1.03752Z"
                                                            fill="#0CAF60" />
                                                    </svg>
                                                    <span>{{ __('Max Workspace :') }}
                                                        <b>{{ $single_plan->number_of_workspace == -1 ? 'Unlimited' : (!empty($single_plan->number_of_workspace) ? $single_plan->number_of_workspace : 'Unlimited') }}</b></span>
                                                </li>
                                                <li>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="9" height="8"
                                                        viewBox="0 0 9 8" fill="none">
                                                        <path
                                                            d="M8.34762 1.03752C8.18221 0.872095 7.91403 0.872095 7.74858 1.03752L2.67378 6.11237L0.723112 4.1617C0.557699 3.99627 0.289518 3.99629 0.124072 4.1617C-0.0413573 4.32712 -0.0413573 4.5953 0.124072 4.76073L2.37426 7.01088C2.53962 7.1763 2.808 7.17618 2.9733 7.01088L8.34762 1.63656C8.51305 1.47115 8.51303 1.20295 8.34762 1.03752Z"
                                                            fill="#0CAF60" />
                                                    </svg>
                                                    <span>{{ __('Free Trial Days :') }}
                                                        <b>{{ !empty($single_plan->trial_days) ? $single_plan->trial_days : 0 }}</b></span>
                                                </li>
                                            </ul>
                                        </div>
                                        <ul class="basic-plan-ul compare-plan-opction p-0">
                                            @foreach ($modules as $module)
                                                @php
                                                    $id = strtolower(preg_replace('/\s+/', '_', $module->getName()));
                                                    $path = $module->getPath() . '/module.json';
                                                    $json = json_decode(file_get_contents($path), true);
                                                @endphp
                                                @if (!isset($json['display']) || $json['display'] == true)
                                                    @if (in_array($module->getName(), $plan_modules))
                                                        <li>
                                                            <a href="#">
                                                                <img src="{{ asset('images/right.svg') }}">
                                                            </a>
                                                        </li>
                                                    @else
                                                        <li>
                                                            <a href="#">
                                                                <img src="{{ asset('images/wrong.svg') }}">
                                                            </a>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
@include('plans.plan_script')
