<div class="modal-body">
    <div class="row">
        <div class=" col-12">
            <div class="row mt-4">
                @foreach ($plans as $key => $plan)
                    <div class="col-lg-4 col-12">
                        <div class="card border-grey">
                            <div class="card-body text-center">
                                <h5>{{ $plan->name }}</h5>
                                <h6>{{ super_currency_format_with_sym($plan->package_price_monthly) . ' / Per Month' }}
                                </h6>
                                @if ($plan->id == $user->active_plan)
                                    <a href="#"
                                        class="btn btn-sm btn-primary my-auto w-100 d-flex align-items-center justify-content-center gap-2"
                                        title="{{ __('Click to Upgrade Plan') }}">
                                        <i class="ti ti-check "></i>
                                    </a>
                                @else
                                    <a href="{{ route('plan.details', [Crypt::encrypt($plan->id), Crypt::encrypt($user->id)]) }}"
                                        class="btn btn-sm btn-warning my-auto w-100 d-flex align-items-center justify-content-center gap-2"
                                        title="{{ __('Click to Upgrade Plan') }}">
                                        <i class="ti ti-shopping-cart-plus"></i>
                                        {{ __('Assign') }}
                                    </a>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach
            </div>
            <div class="d-flex upgrade-line align-items-center mb-2">
                <hr>
                <h6 class="mb-0">{{ __('OR') }}</h6>
                <hr>
            </div>
            <div class="row">
                <div class="col-12 text-center">
                    <a href="{{ route('module.buy', Crypt::encrypt($user->id)) }}"
                        class="btn btn-primary">{{ __('Usage Subscription') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
