@if (ActivatedModule())
    <h4><b>Addon Manager</b></h4>
    <div class="owl-carousel mt-4 owl-theme">
        @php
            $exceptions = ['ProductService', 'Stripe', 'Paypal'];
            $icon_numbers = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
        @endphp
        @foreach (ActivatedModule() as $i => $module)
            @php
                $module = Module::find($module);
                $i += 1;
            @endphp
            @if (!in_array($module, $exceptions))
                <div class="item" data-bs-toggle="tooltip" data-bs-original-title="{{ $module->getName() }}">
                    <a href="{{ route('software.details', Module_Alias_Name($module->getName())) }}" target="_new">
                        <div class="card rounded">
                            <div class="card-body p-2 m-2">
                                <div class="d-flex align-items-center justify-content-center">
                                    <div class="">
                                        @php
                                            $src = asset("images/widgets/$i.png");
                                            $capitalizeName = strtoupper($module->getName());
                                        @endphp
                                        @if (in_array($i, $icon_numbers))
                                            @php
                                                if($capitalizeName == 'GHL'){
                                                    $src = asset('images/widgets/GoHighlevel.png');
                                                }
                                                if($capitalizeName == 'HRM'){
                                                    $src = asset('images/widgets/HRM.png');
                                                }
                                                if($capitalizeName == 'Lead'){
                                                    $src = asset('images/widgets/HRM.png');
                                                }
                                            @endphp
                                            <img src='{{ $src }}'
                                                alt="{{ $capitalizeName }}" style="max-width: 100%;"
                                                class="img-user">
                                        @else
                                        <img src='{{ $src }}'
                                            alt="{{ $capitalizeName }}" style="max-width: 100%;"
                                            class="img-user">
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
