
@if (ActivatedModule())
<h4>Oneworkspace Addons</h4>
<div class="owl-carousel mt-4 owl-theme">
    @foreach (ActivatedModule() as $i => $module)
    @php
        $exceptions = ['ProductService','Stripe','Paypal'];
        $module = Module::find($module);
    @endphp
    @if (!in_array($module, $exceptions))
        <div class="item" data-bs-toggle="tooltip" data-bs-original-title="{{ $module->getName() }}">
            <a href="{{ route('software.details',Module_Alias_Name($module->getName())) }}" target="_new">
                <div class="card rounded">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-center">
                            <div class="theme-avtar">
                                <img src="{{ get_module_img($module->getName()) }}"
                                    alt="{{ $module->getName() }}" style="max-width: 100%;" class="img-user">
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
    @endforeach
</div>
@endif

@push('scripts')
    <script>
        $(document).ready(function(){
            $('.owl-carousel').owlCarousel({
                loop:true,
                margin: 5,
                items:6,
                responsiveClass:true,
                autoplayHoverPause:true,
            });
        })
    </script>
@endpush
