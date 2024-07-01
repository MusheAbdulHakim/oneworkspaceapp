@if (!empty(ActivatedModule()) && count(ActivatedModule()) > 0)
    <p class="text-black"><b>Addon Manager</b></p>
    <div class="carouselContent">
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
        <div class="owl-theme">
            <div class="owl-controls">
                <div class="custom-carouselNav owl-nav"></div>
            </div>
        </div>
    </div>
    @push('scripts')
        <style>
            .carouselContent {
                position: relative;
                .owl-theme {
                    .custom-carouselNav {
                        position: absolute;
                        top: 10%;
                        left: 0;
                        right: 0;
    
                        .owl-prev, .owl-next {
                            position: absolute;
                            height: 100px;
                            color: inherit;
                            background: none;
                            border: none;
                            z-index: 100;
    
                            i {
                                font-size: 2.5rem;
                                color: #cecece;
                            }
                        }
    
                        .owl-prev {
                            left: 0;
                        }
    
                        .owl-next {
                            right: 0;
                        }
                    }
                }
            }
        </style>
        <script>
            $(document).ready(function() {
                $('.owl-carousel').owlCarousel({
                    loop: true,
                    margin: 5,
                    items: 6,
                    responsiveClass: true,
                    autoplayHoverPause: true,
                    nav: true,
                    dots: false,
                    lazyLoad: true,
                    navText: [
                        '<span class="material-symbols-outlined">chevron_left</span>',
                        '<span class="material-symbols-outlined">chevron_right</span>',
                    ],
                    navContainer: '.carouselContent .custom-carouselNav'
                });
            })
        </script>
    @endpush
@endif

