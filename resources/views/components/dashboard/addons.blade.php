
@if (ActivatedModule())
<h4 class="my-1">Oneworkspace Addons</h4>
<div class="owl-carousel owl-theme">
    @foreach (ActivatedModule() as $i => $module)
    @php
        $exceptions = ['ProductService','Stripe','Paypal'];
        $module = Module::find($module);
    @endphp
    @if (!in_array($module, $exceptions))
        <div class="item">
            <a href="{{ route('software.details',Module_Alias_Name($module->getName())) }}" target="_new">
                <div class="card p-2 rounded">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="theme-avtar">
                            <img src="{{ get_module_img($module->getName()) }}"
                                alt="{{ $module->getName() }}" class="img-user"
                                style="max-width: 100%">
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
    @endforeach
</div>
@endif

@push('css')
    <link rel="stylesheet" href="{{ asset('js/owlcarousel/assets/owl.carousel.min.css') }}">
    <link rel="stylesheet" href="{{ asset('js/owlcarousel/assets/owl.theme.default.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/owlcarousel/owl.carousel.min.js') }}"></script>
    <script>
        $('.owl-carousel').owlCarousel({
            loop:true,
            margin: 5,
            items:6,
            responsiveClass:true,
            autoplayHoverPause:true,
        });
    </script>
@endpush
