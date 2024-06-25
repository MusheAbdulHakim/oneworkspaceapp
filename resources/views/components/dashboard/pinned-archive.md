<div class="row">
    <h4 class="my-3">My Pinned Apps</h4>
    @php
        $pin_categories = \App\Models\PinnedAppCategory::where('user_id', auth()->user()->id)->get();
    @endphp
    @if (!empty($pin_categories) && $pin_categories->count() > 0)
        @foreach ($pin_categories as $category)
            <div class="col-2">
                <a data-ajax-popup="true" data-title="{{ __('Pin App') }}"
                    data-url="{{ route('app-pin.category', $category->id) }}" data-toggle="tooltip"
                    href="#">
                    <div class="card">
                        <div class="card-body">
                            <div class="theme-avtar">
                                <span class="text-black"><i class="ti ti-plus"></i></span>
                            </div>
                        </div>
                        <div>
                            <span style="left: 90%; top: 90%;"
                                class="position-absolute translate-middle p-2 bg-danger text-white rounded-circle">
                                {{ $category->apps->count() }}
                            </span>
                        </div>
                    </div>
                    <h6 class="text-center">{{ $category->name }}</h6>
                </a>
            </div>
        @endforeach
    @endif
    <div class="col-2">
        <a data-ajax-popup="true" data-title="{{ __('Pin App') }}" data-url="{{ route('apps.pin') }}"
            data-toggle="tooltip" href="#">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-center">
                        <div class="theme-avtar">
                            <span class="text-black"><i class="ti ti-plus"></i></span>
                        </div>
                    </div>
                </div>
            </div>
            <h6 class="text-center">Add New Pin</h6>
        </a>
    </div>
</div>
