@extends($layout)
@section('page-title')
    {{ __('Software Details') }}
@endsection
@section('content')
<!-- wrapper start -->
<div class="wrapper">

    <section class="dedicated-themes-section padding-bottom padding-top">
        <div class="container">
            <div class="section-title text-center section">
                <h1 style="font-size: 115px">404</h1>
                <div>{{ __('Ooops!!! The Add on you are looking for is not found')}}</div>
            </div>
        </div>
    </section>

    <section class="bg-white padding-top padding-bottom ">
        <div class="container">
            <div class="section-title text-center">
                <h2>{{ __('Why Choose a Dedicated Fashion Theme ')}} <b>{{ __('for Your Business?')}}</b></h2>
                <p>{{ __('With Alligō, you can take care of the entire partner lifecycle - from onboarding through nurturing, cooperating, and rewarding. Find top performers and let go of those who aren’t a good fit.')}}}</p>
            </div>
            @if (count($modules) > 0)
                <div class="row product-row">
                    @foreach ($modules as $module)
                        @php
                            $path = $module->getPath() . '/module.json';
                            $json = json_decode(file_get_contents($path), true);
                        @endphp
                        @if (!isset($json['display']) || $json['display'] == true)
                        <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-12 product-card">
                            <div class="product-card-inner">
                                <div class="product-img">
                                    <a href="product.html">
                                        <img src="assets/images/Custom-Fields.png" alt="">
                                    </a>
                                </div>
                                <div class="product-content">
                                    <h4> <a href="product.html">{{ Module_Alias_Name($module->getName()) }}</a> </h4>
                                    <div class="price">
                                        <ins><span class="currency-type">{{ super_currency_format_with_sym(ModulePriceByName($module->getName())['monthly_price']) }}</span> <span class="time-lbl text-muted">{{ __('/Month') }}</span></ins>
                                                    <ins><span class="currency-type">{{ super_currency_format_with_sym(ModulePriceByName($module->getName())['yearly_price']) }}</span> <span class="time-lbl text-muted">{{ __('/Year') }}</span></ins>
                                    </div>
                                    <a href="{{ route('software.details',Module_Alias_Name($module->getName())) }}" target="_new"  class="btn cart-btn">View Details</a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
    </section>
</div>
<!-- wrapper end -->
@endsection

