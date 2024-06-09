
@extends($layout)
@section('page-title')
    {{ __('Add-on Listing') }}
@endsection
@section('content')
<!-- wrapper start -->
<div class="wrapper">
    <section class="common-banner-section">
        <div class="offset-container offset-left">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-9 col-md-7 col-12">
                    <div class="common-banner-content">
                        <div class="section-title text-center">
                            <h2>{{__('All Add-on')}}</h2>
                            <p>{{__('Lay a solid foundation for your fashion brand. Grab a high-converting fashion theme
                                powered by a secure backend coupled with an intuitive eCommerce mobile app.')}}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-5 col-12">
                    <div class="banner-image">
                        <img src="{{ asset('market_assets/images/dash-banner')}}-image.png" alt="">
                        <div class="ripple-icon position-top">
                            <div class="pulse0"></div>
                            <div class="pulse1"></div>
                            <div class="pulse2"></div>
                            <div class="pulse3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>`
    <section class="product-listing-section padding-bottom">
        <div class="container">
            <div class="tabs-wrapper">
                <div class="product-list-search">
                    <div class="product-search">
                        <form action="{{ route('apps.software') }}">
                            <div class="input-wrapper">
                                <input type="text" name="query" placeholder="Search product">
                                <button type="submit" class="search-btn"><svg xmlns="http://www.w3.org/2000/svg"
                                        width="20" height="20" viewBox="0 0 20 20" fill="none">
                                        <path
                                            d="M18.3998 19.6009C18.7317 19.9328 19.2699 19.9328 19.6018 19.6009C19.9338 19.2689 19.9338 18.7307 19.6018 18.3988L18.3998 19.6009ZM14.7349 15.936L18.3998 19.6009L19.6018 18.3988L15.937 14.7339L14.7349 15.936Z"
                                            fill="#6FD943" />
                                        <path
                                            d="M1.31573 6.324C1.89864 3.83897 3.83897 1.89864 6.324 1.31573C8.11866 0.894758 9.98639 0.894757 11.781 1.31573C14.2661 1.89864 16.2064 3.83897 16.7893 6.324C17.2103 8.11866 17.2103 9.98639 16.7893 11.781C16.2064 14.2661 14.2661 16.2064 11.781 16.7893C9.98639 17.2103 8.11866 17.2103 6.324 16.7893C3.83897 16.2064 1.89864 14.2661 1.31573 11.7811C0.894757 9.98639 0.894757 8.11866 1.31573 6.324Z"
                                            stroke="#6FD943" stroke-width="1.7" stroke-linecap="round" />
                                        <path
                                            d="M1.31573 6.324C0.894757 8.11866 0.894757 9.98639 1.31573 11.7811C1.89864 14.2661 3.83897 16.2064 6.324 16.7893C8.11866 17.2103 9.98639 17.2103 11.781 16.7893C14.2661 16.2064 16.2064 14.2661 16.7893 11.781C17.2103 9.98639 17.2103 8.11866 16.7893 6.324C16.2064 3.83897 14.2661 1.89864 11.781 1.31573C9.98639 0.894757 8.11866 0.894758 6.324 1.31573"
                                            stroke="#808191" stroke-width="1.7" stroke-linecap="round" />
                                    </svg>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="product-list-row row no-gutters">
                    <div class="product-filter-right-column col-lg-12 col-md-12 col-12">
                        <div class="tabs-container">
                            <div class="tab-content active">
                                <div class="row product-row">
                                    @if (count($modules) > 0)
                                    @foreach ($modules as $module)
                                    @php
                                    $path = $module->getPath() . '/module.json';
                                    $json = json_decode(file_get_contents($path), true);
                                @endphp
                                @if (!isset($json['display']) || $json['display'] == true)
                                    <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 product-card">
                                        <div class="product-card-inner">
                                            <div class="product-img">
                                                <div class="theme-avtar">
                                                    <a target="_new" href="{{ route('software.details',Module_Alias_Name($module->getName())) }}">
                                                        <img src="{{ get_module_img($module->getName()) }}"
                                                            alt="{{ $module->getName() }}" class="img-user">
                                                    </div>
                                            </div>
                                            <div class="product-content">
                                                <div class="lbl">{{__('Statistics')}}</div>
                                                <h4> <a target="_new" href="{{ route('software.details',Module_Alias_Name($module->getName())) }}">{{ Module_Alias_Name($module->getName()) }}</a> </h4>
                                                <div class="price">
                                                    <ins><span class="currency-type">{{ super_currency_format_with_sym(ModulePriceByName($module->getName())['monthly_price']) }}</span> <span class="time-lbl text-muted">{{ __('/Month') }}</span></ins>
                                                            <ins><span class="currency-type">{{ super_currency_format_with_sym(ModulePriceByName($module->getName())['yearly_price']) }}</span> <span class="time-lbl text-muted">{{ __('/Year') }}</span></ins>
                                                </div>
                                                <a target="_new" href="{{ route('software.details',Module_Alias_Name($module->getName())) }}" class="btn cart-btn">{{ __('View Details')}}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach
                                    @else
                                    <div class="col-lg-12 col-md-12">
                                        <div class="card p-5">
                                            <div class="d-flex justify-content-center">
                                                <div class="ms-3 text-center">
                                                    <h3>{{ __('Modules Not Available') }}</h3>
                                                    <p class="text-muted">{{ __('Click ') }}<a
                                                            href="{{ url('/') }}">{{ __('here') }}</a>
                                                        {{ __('to back home') }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="listing-info padding-top ">
                <p>{{__('An effective fashion theme should be visually appealing and easy to navigate. A good theme makes it easy for customers to find and buy the items they’re interested in. The theme should also be responsive so that it looks good on all devices.')}}</p>
                <p>{{__('With the Style theme, you get all of the above - and more. The theme gives you everything you need to sell your products and keep your audience coming back for more. Easily customize the theme and adjust its design to your branding needs. Add products, polish product pages, and start growing your online business.')}}</p>
            </div>
        </div>
    </section>
</div>
<!-- wrapper end -->
@endsection

