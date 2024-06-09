@extends('layouts.main')
@section('page-title')
    {{__('Purchase Print Setting')}}
@endsection
@section('page-breadcrumb')
    {{__('Print-Settings')}}
@endsection

@php
    $company_settings = getCompanyAllSetting();
@endphp

@push('scripts')
    <script>

        $(document).on("change", "select[name='purchase_template'], input[name='purchase_color']", function () {
            var template = $("select[name='purchase_template']").val();
            var color = $("input[name='purchase_color']:checked").val();
            $('#purchase_frame').attr('src', '{{url('/purchase/preview')}}/' + template + '/' + color);
        });
        document.getElementById('purchase_logo').onchange = function () {
            var src = URL.createObjectURL(this.files[0])
            document.getElementById('purchase_image').src = src
        }

    </script>
@endpush
@section('content')
    <div class="col-sm-12 mt-4">
        <div class="card">
            <div class="card-body">
                <div class="tab-content" id="pills-tabContent">
                    <!--Purchase Setting-->
                    <div class="tab-pane fade  show active" id="pills-purchase" role="tabpanel" aria-labelledby="pills-purchase-tab">
                        <div class="bg-none">
                            <div class="row company-setting">
                                <div class="col-md-3">
                                    <div class="card-header card-body">
                                        <h5></h5>
                                        <form id="setting-form" method="post" action="{{route('purchase.template.setting')}}" enctype="multipart/form-data">
                                            @csrf
                                            <div class="form-group">
                                                {{Form::label('purchase_template',__('Purchase Template'),array('class'=>'form-label')) }}
                                                {{ Form::select('purchase_template',Modules\Account\Entities\AccountUtility::templateData()['templates'],isset($company_settings['purchase_template']) && !empty($company_settings['purchase_template']) ? $company_settings['purchase_template'] : null, array('class' => 'form-control choices','required'=>'required')) }}
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">{{__('Color Input')}}</label>
                                                <div class="row gutters-xs">
                                                    @foreach( Modules\Account\Entities\AccountUtility::templateData()['colors'] as $key => $color)
                                                        <div class="col-auto">
                                                            <label class="colorinput">
                                                                <input name="purchase_color" type="radio" value="{{$color}}" class="colorinput-input" {{(isset($company_settings['purchase_color']) && !empty($company_settings['purchase_color']) && $company_settings['purchase_color'] == $color) ? 'checked' : ''}}>
                                                                <span class="colorinput-color" style="background: #{{$color}}"></span>
                                                            </label>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-form-label">{{__('Purchase Logo')}}</label>
                                                    <label for="purchase_logo">
                                                        <input type="file" class="form-control file" name="purchase_logo" id="purchase_logo" data-filename="purchase_logo_update">
                                                        <img id="purchase_image" class="mt-2" style="width:50%;"/>
                                                </label>
                                            </div>
                                            <div class="form-group mt-2 text-end">
                                                <input type="submit" value="{{__('Save Changes')}}" class="btn btn-print-invoice  btn-primary m-r-10">
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="col-md-9">
                                    @if(isset($company_settings['purchase_template']) && isset($company_settings['purchase_color']) && !empty( $company_settings['purchase_template']) && !empty($company_settings['purchase_color']))
                                        <iframe id="purchase_frame" class="w-100 h-100" frameborder="0" src="{{route('purchase.preview',[$company_settings['purchase_template'], $company_settings['purchase_color']])}}"></iframe>
                                    @else
                                        <iframe id="purchase_frame" class="w-100 h-100" frameborder="0" src="{{route('purchase.preview',['template1','fffff'])}}"></iframe>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<script>

    $(document).on("change", "select[name='purchase_template'], input[name='purchase_color']", function () {
        var template = $("select[name='purchase_template']").val();
        var color = $("input[name='purchase_color']:checked").val();
        $('#purchase_frame').attr('src', '{{url('/purchase/preview')}}/' + template + '/' + color);
    });
    document.getElementById('purchase_logo').onchange = function () {
        var src = URL.createObjectURL(this.files[0])
        document.getElementById('purchase_image').src = src
    }

</script>
