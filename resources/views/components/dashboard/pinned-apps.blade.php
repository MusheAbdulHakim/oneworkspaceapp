@php
    $pinned_apps = \App\Models\PinnedApp::where('user_id',auth()->user()->id)->get();
@endphp

@if (!empty($pinned_apps))
<h4 class="my-1">Pinned Addons</h4>
<div class="row mt-4">
    @foreach ($pinned_apps as $i => $app)
    @php
        $exceptions = ['ProductService','Stripe','Paypal'];
        $module = Module::find($app->module);
    @endphp
    @if (!in_array($module, $exceptions))
        <div class="col-2">
            <a data-ajax-popup="true" data-title="Delete {{ $app->name }}" title="Delete {{ $app->module }}"
                data-url="{{ route('apps.pin') }}" data-toggle="tooltip" href="#">
                <div class="card rounded">
                    {{ Form::open(['route' => ['pin-apps.destroy', $app->id], 'class' => 'm-0']) }}
                    @method('DELETE')
                    <a href="javascript:void(0)" class="bs-pass-para show_confirm" aria-label="Delete"
                        data-confirm="{{ __('Are You Sure?') }}"
                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                        data-confirm-yes="delete-form-{{ $app->id }}">
                        <span class="position-absolute top-0 start-100 text-white translate-middle p-2 bg-danger rounded-circle">
                            <i class="ti ti-trash"></i>
                        </span>
                    </a>
                    {{ Form::close() }}
                    <div class="card-body">
                        <div class="theme-avtar">
                            <img src="{{ get_module_img($module->getName()) }}"
                                alt="{{ $module->getName() }}" class="img-user">
                        </div>
                    </div>
                </div>
            </a>
        </div>
    @endif
    @endforeach
</div>
@endif


