@php
    $pinnedApps = \App\Models\PinnedApp::where('user_id', creatorId())->get();
@endphp
@if (!empty($pinnedApps))
    @foreach ($pinnedApps as $item)
    @php
        $module = Module::find($item->module);
    @endphp
    <div class="col-xxl-2 col-xl-2 col-lg-6 col-sm-6 product-card">
        <div class="product-card-inner">
            <div class="card user_module">
                <div class="product-img d-flex justify-content-center">
                    <div class="theme-avtar">
                        <img src="{{ get_module_img($module->getName()) }}"
                            alt="{{ $module->getName() }}" class="img-user"
                            style="max-width: 100%">
                    </div>
                    {{-- <div class="checkbox-custom">
                            <input type="checkbox" {{ ((isset($session) && !empty($session) && ( in_array($module->getName(),explode(',',$session['user_module'])) ))) ? 'checked' :''}}
                                class="form-check-input pointer user_module_check"
                                data-module-img="{{ get_module_img($module->getName()) }}"
                                data-module-price-monthly="{{ ModulePriceByName($module->getName())['monthly_price'] }}"
                                data-module-price-yearly="{{ ModulePriceByName($module->getName())['yearly_price'] }}"
                                data-module-alias="{{ Module_Alias_Name($module->getName()) }}"
                                value="{{ $module->getName() }}">
                    </div> --}}
                </div>
                <div class="product-content text-center">
                    <span class="material-icons">plus</span>
                    {{-- <h6> {{ Module_Alias_Name($module->getName()) }}</h6> --}}
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
