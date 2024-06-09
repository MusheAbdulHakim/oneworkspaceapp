

    
    <a href="{{ route('landingpage.index') }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'landingpage.index') ? ' active' : '' }}">{{ __('Details') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('landingpage.custom') }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'landingpage.custom') ? ' active' : '' }}">{{ __('Custom') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('join_us.index') }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'join_us.index') ? ' active' : '' }}">{{ __('Newsletter') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
    
    <a href="{{ route('landing.change.blocks') }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'landing.change.blocks') ? ' active' : '' }}">{{ __('Change Blocks') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('landing.seo') }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'landing.seo') ? ' active' : '' }}">{{ __('SEO') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('landing.pwa') }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'landing.pwa') ? ' active' : '' }}">{{ __('PWA') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>

    <a href="{{ route('landing.cookie') }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'landing.cookie') ? ' active' : '' }}">{{ __('Cookie') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
    
    <a href="{{ route('landing.qrCode') }}" class="list-group-item list-group-item-action border-0 {{ (Request::route()->getName() == 'landing.qrCode') ? ' active' : '' }}">{{ __('QR Code') }} <div class="float-end"><i class="ti ti-chevron-right"></i></div></a>
    

    <div class="modal fade" id="exampleModalCenter" tabindex="2" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl ss_modale" role="document">
            <div class="modal-content image_sider_div">
            </div>
        </div>
    </div>

    @push('css')
        @include('landingpage::layouts.infoimagescss')
    @endpush

    @push('scripts')
        @include('landingpage::layouts.infoimagesjs')
    @endpush