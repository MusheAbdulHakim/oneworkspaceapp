@if (ActivatedModule())
    <h4>Oneworkspace Addons</h4>
    <div class="owl-carousel mt-4 owl-theme">
        @foreach (ActivatedModule() as $i => $module)
            @php
                $exceptions = ['ProductService', 'Stripe', 'Paypal'];
                $module = Module::find($module);
                $icon_numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
                $i += 1;
            @endphp
            @if (!in_array($module, $exceptions))
                <div class="item" data-bs-toggle="tooltip" data-bs-original-title="{{ $module->getName() }}">
                    <a href="{{ route('software.details', Module_Alias_Name($module->getName())) }}" target="_new">
                        <div class="card rounded">
                            <div class="card-body p-2 m-2">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="theme-avtar">
                                        @if (in_array($i, $icon_numbers))
                                            <img src='{{ asset("images/widgets/$i.png") }}'
                                                alt="{{ $module->getName() }}" style="max-width: 100%;"
                                                class="img-user">
                                        @else
                                            <span class="material-icons text-dark">extension</span>
                                        @endif
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
        $(document).ready(function() {
            $('.owl-carousel').owlCarousel({
                loop: true,
                margin: 5,
                items: 6,
                responsiveClass: true,
                autoplayHoverPause: true,
            });
        })
    </script>
@endpush
