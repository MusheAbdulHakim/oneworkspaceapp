@php
    $modules = getshowModuleList();
@endphp
<div class="card align-middle p-3">               
    <ul class="nav nav-pills" id="pills-tab" role="tablist">
            <li class="nav-item px-1">
                <a href="{{ route('landingpage.index') }}" class="nav-link text-capitalize {{ (Request::route()->getName() == 'landingpage.index') ? ' active' : '' }}">{{ __('Details') }} <div class="float-end"></div></a>
            </li>
            <li class="nav-item px-1">
                <a href="{{ route('landingpage.custom') }}" class="nav-link text-capitalize {{ (Request::route()->getName() == 'landingpage.custom') ? ' active' : '' }}">{{ __('Custom') }} <div class="float-end"></div></a>
            </li>
            <li class="nav-item px-1">
                <a href="{{ route('join_us.index') }}" class="nav-link text-capitalize {{ (Request::route()->getName() == 'join_us.index') ? ' active' : '' }}">{{ __('Newsletter') }} <div class="float-end"></div></a>
            </li>
            <li class="nav-item px-1">
                <a href="{{ route('landing.change.blocks') }}" class="nav-link text-capitalize {{ (Request::route()->getName() == 'landing.change.blocks') ? ' active' : '' }}">{{ __('Change Blocks') }} <div class="float-end"></div></a>
            </li>
            <li class="nav-item px-1">
                <a href="{{ route('landing.seo') }}" class="nav-link text-capitalize {{ (Request::route()->getName() == 'landing.seo') ? ' active' : '' }}">{{ __('SEO') }} <div class="float-end"></div></a>
            </li>
            <li class="nav-item px-1">
                <a href="{{ route('landing.pwa') }}" class="nav-link text-capitalize {{ (Request::route()->getName() == 'landing.pwa') ? ' active' : '' }}">{{ __('PWA') }} <div class="float-end"></div></a>
            </li>
            <li class="nav-item px-1">
                <a href="{{ route('landing.cookie') }}" class="nav-link text-capitalize {{ (Request::route()->getName() == 'landing.cookie') ? ' active' : '' }}">{{ __('Cookie') }} <div class="float-end"></div></a>
            </li>
            <li class="nav-item px-1">
                <a href="{{ route('landing.qrCode') }}" class="nav-link text-capitalize {{ (Request::route()->getName() == 'landing.qrCode') ? ' active' : '' }}">{{ __('QR Code') }} <div class="float-end"></div></a>
            </li>
    </ul>
</div>


<div class="modal fade" id="exampleModalCenter" tabindex="2" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl ss_modale" role="document">
        <div class="modal-content image_sider_div">
        </div>
    </div>
</div>

<div class="modal fade" id="qrcodeModal" data-backdrop="false" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('QR Code') }}</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div id="qrdata">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('css')
    @include('landingpage::layouts.infoimagescss')
@endpush


@push('scripts')
    @include('landingpage::layouts.infoimagesjs')

    <script>
        $('#download-qr').on('click', function() {
            $.ajax({
                url: '{{ route('download.qr') }}',
                type: 'GET',
                success: function(data) {

                    if (data.success == true) {

                        $('#qrdata').html(data.data);
                    }
                    setTimeout(() => {
                        // canvasdata();
                        var element = document.querySelector("#qrdata");
                        saveCapture(element)
                        $("#qrcodeModal").removeClass("show");
                        $("#qrcodeModal").modal('hide');
                        $("body").css("overflow",'');
                        $("body").css("padding-right",'');
                        $('body').removeClass('modal-open');
                        $('#qrcodeModal').removeClass('modal-backdrop');
                        $(".modal-backdrop").removeClass("show");

                        $("#qrdata").html('');

                    }, 2000);
                }
            });


        });
    </script>
    
@endpush