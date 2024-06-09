@extends('layouts.main')
@section('page-title')
{{__('Add-on Manager')}}
@endsection
@section('page-breadcrumb')
{{ __('Add-on Manager') }}
@endsection
@push('css')
<style>
    .system-version h5{
        position: absolute;
        bottom: -44px;
        right: 27px;
    }
    .center-text{
        display: flex;
        flex-direction: column;
    }
    .center-text .text-primary{
        font-size: 14px;
        margin-top: 5px;
    }
    .theme-main{
        display: flex;
        align-items: center;
    }
    .theme-main .theme-avtar{
        margin-right: 15px;
    }
    @media only screen and (max-width: 575px) {
        .system-version h5{
            position: unset;
            margin-bottom: 0px;
        }
        .system-version{
            text-align: center;
            margin-bottom: -22px;
        }
    }
</style>

@endpush
@section('page-action')
<div>
    <a href="{{ route('module.add') }}" class="btn btn-sm btn-primary"  data-bs-toggle="tooltip" title="" data-bs-original-title="{{ __('ModuleSetup')}}">
          <i class="ti ti-plus"></i>
    </a>
</div>
@endsection
@section('content')
<div class="row justify-content-center px-0">
    <div class=" col-12">
        <div class="card">
            <div class="card-body package-card-inner  d-flex align-items-center">
                <div class="package-itm theme-avtar">
                    <a href="https://workdo.io/product-category/dash-saas-addon?utm_source=demo&utm_medium=dash&utm_campaign=btn" target="new">
                        <img src="https://workdo.io/wp-content/uploads/2023/03/favicon.jpg" alt="">
                    </a>
                </div>
                <div class="package-content flex-grow-1  px-3">
                    <h4>{{ __('Buy More Add-on')}}</h4>
                    <div class="text-muted">{{ __('+'.count($modules).' Premium Add-on')}}</div>
                </div>
                <div class="price text-end">
                  <a class="btn btn-primary" href="https://workdo.io/product-category/dash-saas-addon?utm_source=demo&utm_medium=dash&utm_campaign=btn" target="new">
                    {{ __('Buy More Add-on')}}
                  </a>
                </div>
            </div>
        </div>
    </div>
    <!-- [ sample-page ] start -->
    <div class="event-cards row px-0">
        <h2>{{ __('Installed Add-on')}}</h2>
        @foreach ($modules as $module)
            @php
                $module_name = $module->getName();
                $id = strtolower(preg_replace('/\s+/', '_', $module_name));
                $path =$module->getPath().'/module.json';
                $json = json_decode(file_get_contents($path), true);
            @endphp
            @if (!isset($json['display']) || $json['display'] == true || ($module_name == 'GoogleCaptcha'))
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 product-card ">
                <div class="card {{ ($module->isEnabled()) ? 'enable_module' : 'disable_module'}}">
                    <div class="product-img">
                        <div class="theme-main">
                            <div class="theme-avtar">
                                <img src="{{ get_module_img($module->getName()) }}"
                                    alt="{{ $module->getName() }}" class="img-user"
                                    style="max-width: 100%">
                            </div>
                            <div class="center-text">
                                <small class="text-muted">
                                    @if ($module->isEnabled())
                                    <span class="badge bg-success">{{ __('Enable') }}</span>
                                    @else
                                    <span class="badge bg-danger">{{ __('Disable') }}</span>
                                    @endif
                                </small>
                                <small class="text-primary">{{ __('V')}}{{ sprintf("%.1f", $json['version']) }}</small>
                            </div>
                        </div>
                        <div class="checkbox-custom">
                            <div class="btn-group card-option">
                                <button type="button" class="btn p-0" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" style="">
                                    @if ($module->isEnabled())
                                    <a href="#!" class="dropdown-item module_change" data-id="{{ $id }}">
                                        <span>{{ __('Disable') }}</span>
                                    </a>
                                    @else
                                    <a href="#!" class="dropdown-item module_change" data-id="{{ $id }}">
                                        <span>{{ __('Enable') }}</span>
                                    </a>
                                    @endif
                                    <form action="{{ route('module.enable') }}" method="POST" id="form_{{ $id }}">
                                        @csrf
                                        <input type="hidden" name="name" value="{{ $module->getName() }}">
                                    </form>
                                    <form action="{{ route('module.remove', $module->getName()) }}" method="POST" id="form_{{ $id }}">
                                        @csrf
                                        <button type="button" class="dropdown-item show_confirm" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$id}}">
                                            <span class="text-danger">{{ __("Remove") }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="product-content">
                        <h4 class="text-capitalize"> {{ Module_Alias_Name($module->getName()) }}</h4>
                        <p class="text-muted text-sm mb-0">
                            {{ isset($json['description']) ? $json['description'] : '' }}
                        </p>
                        <a href="{{ route('software.details',Module_Alias_Name($module->getName())) }}" target="_new" class="btn  btn-outline-secondary w-100 mt-2">{{ __('View Details')}}</a>
                    </div>
                </div>
            </div>
            @endif
        @endforeach

        <h2>{{ __('Explore Add-on')}}</h2>

        <div class="col-xl-12">
            @foreach ($category_wise_add_ons as $key => $category_wise_add_on)
            <div id="tab-{{$key}}" class="card add_on_manager">
                <div class="card-header " >
                    <h5>{{ $category_wise_add_on['name']}}</h5>
                    <small class="text-muted">{{ $category_wise_add_on['description']}}</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach ($category_wise_add_on['add_ons'] as $add_on)

                        <div class="col-xxl-3 col-lg-4 col-sm-6 product-card ">
                                <a href="{{ $add_on['url'] }}" target="_new">
                                <div class="card enable_module manager-card" >
                                    <div class="product-img">
                                        <div class="theme-main">
                                            <div class="theme-avtar">
                                                <img src="{{ $add_on['image'] }}"
                                                    alt="" class="img-user"
                                                    style="max-width: 100%">
                                            </div>
                                        </div>
                                        <h5 class="text-capitalize"> {{ $add_on['name'] }}</h5>
                                    </div>
                                    <div class="product-content">
                                        <button class="btn btn-outline-secondary w-100 ">{{ __('View Details')}}</button>
                                    </div>
                                </div>
                            </a>
                            </div>

                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    <!-- [ sample-page ] end -->
</div>
<div class="system-version">
    @php
       $version = config('verification.system_version');
    @endphp
    <h5 class="text-muted">{{ (!empty($version) ? 'V'.$version : '')}}</h5>
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
    setTimeout(function() {
        $('#dash-layout-tab .show .dash-item').removeClass('active').each(function(index) {
            var href = '#tab-' + index;
            $(this).find('a').prop('href', href);
        });
    }, 100);
});

$(document).on('click','.module_change',function(){
    var id = $(this).attr('data-id');
    $('#form_'+id).submit();
});
</script>
<script>
    if ($('#useradd-sidenav').length > 0) {
       var scrollSpy = new bootstrap.ScrollSpy(document.body, {
           target: '#useradd-sidenav',
           offset: 300
       })
   }
</script>
@endpush
