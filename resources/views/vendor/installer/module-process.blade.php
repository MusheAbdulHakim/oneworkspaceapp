@extends('vendor.installer.layouts.master')

@section('template_title')
     {{__('Add On Install Editor')}}
@endsection

@section('title')
   {{__('Add On Install Editor')}}
@endsection

@section('container')
<div style="text-align: center;" class="inner-div">
	 <img src="{{ get_module_img($module_detail->getName()) }}" alt="{{ $module_detail->getName() }}" class="img-user" style="    width: 60px;height: 60px;border-radius: 7px;">
    <h5 class="text-capitalize" style="margin: auto; text-transform:capitalize;"> {{ Module_Alias_Name($module_detail->getName()) }}</h5>
</div>

<div class="buttons-container">
    <a class="button float-right" id="active_module"  href="{{ route('LaravelInstaller::default_module_active',$module_detail->getName()) }}">
        {{ __('Continue')}}
        <i class="fa fa-angle-double-right fa-fw" aria-hidden="true"></i>
    </a>
</div>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script type="text/javascript">
	$('body').on('click','#active_module',function(){

   		$("#active_module").empty();
        var html = '{{ __("Processing") }}';
        $("#active_module").append(html);
        $('#active_module').css('pointer-events', 'none');

	})
</script>
@endsection
