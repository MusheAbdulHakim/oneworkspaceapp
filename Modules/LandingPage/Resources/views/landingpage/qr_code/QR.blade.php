@php
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
@endphp
<style>
    .qrcode canvas {
        width: 100%;
        height: 100%;   
        padding: 12px 25px;
    }
    .qrcode img {
        width: 100%;
        height: 100%;   
    }
    .shareqrcode img {
        width: 65%;
        height: 65%;
    }

    .shareqrcode canvas {
        width: 65%;
        height: 65%;
    }
</style>
<div class=" modal-body-section text-center">
   
    <img src="{{ (!empty($settings['site_logo']) && check_file($settings['site_logo'])) ? get_file($settings['site_logo']) : get_file('uploads/logo/logo_dark.png')}}{{'?'.time()}}"
    alt="{{ config('app.name', 'Dash') }}" class="logo logo-lg" height="60px">
</div>
<div class="modal-body border-0">
    <div class="modal-body-section text-center">
        <div class="qr-main-image">
            <div class="qr-code-border">
                 <div class="shareqrcode"></div>
            </div>
        </div>
        <div class="text mt-3">
            <p class="text-black">
                {{__('Point your camera at the QR code, or visit')}}<br><a class="qr-link" onclick="copyToClipboard(this)"></a>
            </p>
        </div>
    </div>
</div>
    <style>
       
    </style>
    <script src="{{ asset('Modules/LandingPage/Resources/assets/js/jquery.qrcode.js') }}"></script>
    <script src="{{ asset('Modules/LandingPage/Resources/assets/js/jquery.qrcode.min.js') }}"></script>

    <script type="text/javascript">
        $('.cp_link').on('click', function() {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('{{ __('Success') }}', '{{ __('Link Copy on Clipboard') }}', 'success');
        });
    </script>

    <script type="text/javascript">
        $(document).ready(function() {
            var customURL = {!! json_encode(url('/')) !!};
            $('.Demo1').socialSharingPlugin({
                url: customURL,
                title: $('meta[property="og:title"]').attr('content'),
                description: $('meta[property="og:description"]').attr('content'),
                img: $('meta[property="og:image"]').attr('content'),
                enable: ['whatsapp', 'facebook', 'twitter', 'pinterest', 'linkedin']
            });

            $('.socialShareButton').click(function(e) {
                e.preventDefault();
                $('.sharingButtonsContainer').toggle();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            var url_link = `{{ url('/') }}`;

            $(`.qr-link`).text(url_link);

            var foreground_color =
                `{{ isset($settings['foreground_color']) ? $settings['foreground_color'] : '#000000' }}`;
            var background_color =
                `{{ isset($settings['background_color']) ? $settings['background_color'] : '#ffffff' }}`;
            var radius = `{{ isset($settings['radius']) ? $settings['radius'] : 26 }}`;
            var qr_type = `{{ isset($settings['qr_type']) ? $settings['qr_type'] : 0 }}`;
            var qr_font = `{{ isset($settings['qr_text']) ? $settings['qr_text'] : 'vCard' }}`;
            var qr_font_color =
                `{{ isset($settings['qr_text_color']) ? $settings['qr_text_color'] : '#f50a0a' }}`;
            var size = `{{ isset($settings['size']) ? $settings['size'] : 9 }}`;

            $('.shareqrcode').empty().qrcode({
                render: 'image',
                size: 100,
                ecLevel: 'H',
                minVersion: 3,
                quiet: 1,
                text: url_link,
                fill: foreground_color,
                background: background_color,
                radius: .01 * parseInt(radius, 10),
                mode: parseInt(qr_type, 10),
                label: qr_font,
                fontcolor: qr_font_color,
                image: $("#image-buffers")[0],
                mSize: .01 * parseInt(size, 10)
            });
        });
    </script>

<script>
    const copyToClipboardX = str => {
    const el = document.createElement('textarea');
    el.value = str;
    document.body.appendChild(el);
    el.select();
        document.execCommand('copy');
    document.body.removeChild(el);
  };

    function copyToClipboard(e) {
    var url_link = `{{ url("/") }}`;

    var api_key = e.text;
    
    // e.getAttribute("text")
    
    if(typeof api_key != "undefined")
    {
        copyToClipboardX(api_key);
        toastrs('{{ __('Success') }}', '{{ __('Link Copy on Clipboard') }}', 'success');
    }
    }
</script>



