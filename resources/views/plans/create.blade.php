
{{ Form::open(['route' => 'plan.store', 'enctype' => 'multipart/form-data']) }}
<div class="modal-body">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}
                {{ Form::text('name', null, ['class' => 'form-control','required'=>'required', 'placeholder' => __('Enter Plan Name')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('is_free_plan', __('Plan Type'), ['class' => 'form-label']) }}
                {{ Form::select('is_free_plan',$plan_type, null, ['class' => 'form-control','required'=>'required','id'=>'is_free_plan', 'placeholder' => __('--- Select Plan Type ---')]) }}
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('number_of_user', __('Number of User'), ['class' => 'form-label']) }}
                {{ Form::number('number_of_user', null, ['class' => 'form-control','required'=>'required','placeholder' => __('Number of User'),'step' => '1','id'=>'number_of_user']) }}
                <span class="small text-danger">{{__('Note: "-1" for Unlimited')}}</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                {{ Form::label('number_of_workspace', __('Number of Workspace'), ['class' => 'form-label']) }}
                {{ Form::number('number_of_workspace', null, ['class' => 'form-control','required'=>'required','placeholder' => __('Number of Workspace'),'step' => '1','id'=>'number_of_workspace']) }}
                <span class="small text-danger">{{__('Note: "-1" for Unlimited')}}</span>
            </div>
        </div>
        <div class="col-md-6 plan_price_div">
            <div class="form-group">
                {{ Form::label('package_price_monthly', __('Basic Package Price/Month').' ( '.company_setting('defult_currancy_symbol').' )', ['class' => 'form-label add_lable']) }}
                {{ Form::number('package_price_monthly',null, ['class' => 'form-control ','placeholder' => __('Price/month'),'step' => '0.1','min'=>'0']) }}
            </div>
        </div>
        <div class="col-md-6 plan_price_div">
            <div class="form-group">
                {{ Form::label('package_price_yearly', __('Basic Package Price/Year').' ( '.company_setting('defult_currancy_symbol').' )', ['class' => 'form-label add_lable']) }}
                {{ Form::number('package_price_yearly',null, ['class' => 'form-control','placeholder' => __('Price/Yearly'),'step' => '0.1','min'=>'0']) }}
            </div>
        </div>
        <div class="col-md-6 mt-3 plan_price_div">
            <label class="form-check-label" for="trial"></label>
            <div class="form-group">
                <label for="trial" class="form-label">{{ __('Trial is enable(on/off)') }}</label>
                <div class="form-check form-switch custom-switch-v1 float-end">
                    <input type="checkbox" name="trial" class="form-check-input input-primary pointer" value="1" id="trial" {{ company_setting('trial')=='on'?' checked ':'' }}>
                    <label class="form-check-label" for="trial"></label>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-none plan_div plan_price_div">
            <div class="form-group">
                {{ Form::label('trial_days', __('Trial Days'), ['class' => 'form-label']) }}
                {{ Form::number('trial_days',null, ['class' => 'form-control','placeholder' => __('Enter Trial days'),'step' => '1','min'=>'1']) }}
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                {{ Form::label('add_on', __('Add-on'), ['class' => 'form-label']) }}
            </div>
        </div>
        @if (count($modules))
            @foreach ($modules as $module)
                @if(in_array($module,getshowModuleList()))
                    @php
                        $id = strtolower(preg_replace('/\s+/', '_', $module->getName()));
                        $path =$module->getPath().'/module.json';
                        $json = json_decode(file_get_contents($path), true);
                    @endphp
                    <div class="col-sm-4 col-lg-4 col-md-4">
                        <div class="card">
                            <div class="card-body p-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex align-items-center">
                                        <div class="theme-avtar">
                                            <img src="{{ get_module_img($module->getName()) }}{{'?'.time()}}" alt="{{ $module->getName() }}" class="img-user rounded" style="max-width: 100%"  >
                                        </div>
                                        <div class="ms-3">
                                            <label for="modules">
                                                <h5 class="mb-0 pointer">{{ Module_Alias_Name($module->getName()) }}</h5>
                                            </label>
                                            <p class="text-muted text-sm mb-0">
                                                {{ isset($json['description']) ? $json['description'] : '' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input modules" name="modules[]" value="{{$module->getName()}}" id="modules" type="checkbox">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        @else
            <div class="col-lg-12 col-md-12">
                <div class="card p-5">
                    <div class="d-flex justify-content-center">
                        <div class="ms-3 text-center">
                            <h3>{{ __('Add-on Not Available') }}</h3>
                            <p class="text-muted">{{ __('Click ') }}<a
                                    href="{{route('module.index') }}">{{ __('here') }}</a>
                                {{ __('To Acctive Add-on') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn  btn-light" data-bs-dismiss="modal">{{__('Cancel')}}</button>
    {{Form::submit(__('Create'),array('class'=>'btn  btn-primary'))}}
</div>
{{Form::close()}}

