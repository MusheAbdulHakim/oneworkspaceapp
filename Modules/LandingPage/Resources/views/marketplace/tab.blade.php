
<a href="{{ route('marketplace_product',$slug) }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'marketplace_product') ? ' active' : '' }}{{ (Request::route()->getName() == 'marketplace.index') ? ' active' : '' }}">{{ __('Product Main') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

<a href="{{ route('marketplace_dedicated',$slug) }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'marketplace_dedicated') ? ' active' : '' }}">{{ __('Dedicated Section') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

<a href="{{ route('marketplace_whychoose',$slug) }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'marketplace_whychoose') ? ' active' : '' }}">{{ __('Why Choose') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

<a href="{{ route('marketplace_screenshot',$slug) }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'marketplace_screenshot') ? ' active' : '' }}">{{ __('Screenshots') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

<a href="{{ route('marketplace_addon',$slug) }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'marketplace_addon') ? ' active' : '' }}">{{ __('Add-On') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>


@push('css')
    @include('landingpage::layouts.infoimagescss')
@endpush

@push('scripts')
    @include('landingpage::layouts.infoimagesjs')
@endpush

<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl ss_modale" role="document">
        <div class="modal-content image_sider_div">
        
        </div>
    </div>
</div>




