<div class="row">
    <h5>Active Modules</h5>
    @if (!empty(ActivatedModule()))
        @foreach (ActivatedModule() as $module)
        @if ($module != 'ProductService')
        <div class="col-lg-4 col-md-6 col-12">
            <div class="card">
                <div class="card-body p-3">
                    <div class="d-flex align-items-center mb-2 mt-2">
                        <div class="theme-avtar bg-primary">
                            <i class="ti ti-layout-2"></i>
                        </div>
                        <div class="ms-3 mb-3 mt-3">
                            <h6 class="ml-4">{{$module}}</h6>
                            @if ($module == 'Hrm')

                            @endif
                            @if ($module == 'Account')
                            <p class="text-sm mb-0">{{ __('Income Today') }}</p>
                            <h5 class="mb-0">
                                {{ currency_format_with_sym(\Modules\Account\Entities\AccountUtility::todayIncome()) }}
                            </h5>
                            <p class="text-sm mb-0">{{ __('Customers') }}</p>
                            <h5 class="mb-0">
                                {{ (\Modules\Account\Entities\AccountUtility::countCustomers()) }}
                            </h5>

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
        @endforeach

    @endif
</div>
