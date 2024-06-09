@permission('hrm manage')
    <div class="card" id="hrm-sidenav">
        {{ Form::open(['route' => 'hrm.setting.store', 'id' => 'hrm_setting_store']) }}
        <div class="card-header">
            <div class="row">
                <div class="col-lg-10 col-md-10 col-sm-10">
                    <h5 class="">{{ __('HRM Settings') }}</h5>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row mt-2">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="employee_prefix" class="form-label">{{ __('Employee Prefix') }}</label>
                        <input type="text" name="employee_prefix" class="form-control"
                            placeholder="{{ __('Employee Prefix') }}"
                            value="{{ !empty($settings['employee_prefix']) ? $settings['employee_prefix'] : '#EMP000' }}"
                            id="employee_prefix">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="company_start_time" class="form-label">{{ __('Company Start Time') }}</label>
                        <input type="time" name="company_start_time" class="form-control"
                            value="{{ !empty($settings['company_start_time']) ? $settings['company_start_time'] : '09:00' }}"
                            id="company_start_time">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="company_end_time" class="form-label">{{ __('Company End Time') }}</label>
                        <input type="time" name="company_end_time" class="form-control"
                            value="{{ !empty($settings['company_end_time']) ? $settings['company_end_time'] : '18:00' }}"
                            id="company_end_time">
                    </div>
                </div>

                @if (Auth::user()->isAbleTo('ip restrict manage'))
                    <div class="col-md-4">
                        <div class="form-group col">
                            <div class=" form-switch p-0">
                                {{ Form::label('ip_restrict', __('IP Restrict'), ['class' => ' col-form-label py-0']) }}
                                <div class=" float-end">
                                    <input type="checkbox" class="form-check-input" id="ip_restrict"
                                    name="ip_restrict"
                                    {{ (isset($settings['ip_restrict']) && $settings['ip_restrict'] == 'on') ? 'checked' : '' }} />
                                    <label class="form-check-label form-label" for="ip_restrict"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                {{ Form::close() }}

            </div>
        </div>
        <div class="card-footer text-end">
            <input class="btn btn-print-invoice  btn-primary m-r-10 hrm_setting_btn" type="button"
                value="{{ __('Save Changes') }}">
        </div>
    </div>

    <div class="ip_restrict_div {{ !empty($settings['ip_restrict']) && $settings['ip_restrict'] != 'on' ? ' d-none ' : '' }}" id="ip_restrict">
        <div class="card">
            @if (Auth::user()->isAbleTo('ip restrict create'))
                <div class="card-header d-flex justify-content-between">
                    <h5>{{ __('IP Restriction Settings') }}</h5>
                    <a data-url="{{ route('iprestrict.create') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                        data-bs-original-title="{{ __('Create New IP') }}" data-bs-placement="top" data-size="md"
                        data-ajax-popup="true" data-title="{{ __('Create New IP') }}">
                        <i class="ti ti-plus"></i>
                    </a>
                </div>
            @endif
            <div class="table-border-style">
                <div class="card-body" style="max-height: 290px; overflow:auto">
                    <div class="table-responsive">
                        <table class="table" id="pc-dt-simple">
                            <thead>
                                <tr>
                                    <th class="w-75"> {{ __('IP') }}</th>
                                    <th width="200px"> {{ 'Action' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ips as $ip)
                                    <tr class="Action">
                                        <td class="sorting_1">{{ $ip->ip }}</td>
                                        <td class="">
                                            @permission('ip restrict edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ route('iprestrict.edit', $ip->id) }}" data-size="md"
                                                        data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit') }}"
                                                        data-bs-placement="top" data-ajax-popup="true"
                                                        data-title="{{ __('Edit IP') }}" class="edit-icon"
                                                        data-original-title="{{ __('Edit') }}"><i
                                                            class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endpermission
                                            @permission('ip restrict delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {{ Form::open(['method' => 'DELETE', 'route' => ['iprestrict.destroy', $ip->id], 'class' => 'm-0']) }}

                                                    <a class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $ip->id }}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    {{ Form::close() }}
                                                </div>
                                            @endpermission
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endpermission
@if (module_is_active('Recruitment'))
    @permission('letter offer manage')
        <div class="" id="offer-letter-settings">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h5>{{ __('Offer Letter Settings') }}</h5>
                    <div class="d-flex justify-content-end drp-languages">
                        @if (module_is_active('AIAssistant'))
                            @include('aiassistant::ai.generate_ai_btn', [
                                'template_module' => 'offer letter settings',
                                'module' => 'Recruitment',
                            ])
                        @endif
                        <ul class="list-unstyled mb-0 m-2">
                            <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                                <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                    role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage">
                                    <span class="drp-text hide-mob text-primary">
                                        {{ Str::upper($offerlang) }}
                                    </span>
                                    <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                                </a>
                                <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                    aria-labelledby="dropdownLanguage">
                                    @foreach (languages() as $key => $offerlangs)
                                        <a href="{{ route('settings.index', ['noclangs' => $noclang, 'explangs' => $explang, 'joininglangs' => $joininglang, 'offerlangs' => $key]) }}"
                                            class="dropdown-item ms-1 {{ $key == $offerlang ? 'text-primary' : '' }}">{{ Str::ucfirst($offerlangs) }}</a>
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card-body ">
                    <h5 class="font-weight-bold pb-3">{{ __('Placeholders') }}</h5>

                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <div class="card">
                            <div class="card-header card-body">
                                <div class="row text-xs">
                                    <div class="row">
                                        <p class="col-4">{{ __('Applicant Name') }} : <span
                                                class="pull-end text-primary">{applicant_name}</span></p>
                                        <p class="col-4">{{ __('Company Name') }} : <span
                                                class="pull-right text-primary">{app_name}</span></p>
                                        <p class="col-4">{{ __('Job title') }} : <span
                                                class="pull-right text-primary">{job_title}</span></p>
                                        <p class="col-4">{{ __('Job type') }} : <span
                                                class="pull-right text-primary">{job_type}</span></p>
                                        <p class="col-4">{{ __('Proposed Start Date') }} : <span
                                                class="pull-right text-primary">{start_date}</span></p>
                                        <p class="col-4">{{ __('Working Location') }} : <span
                                                class="pull-right text-primary">{workplace_location}</span></p>
                                        <p class="col-4">{{ __('Days Of Week') }} : <span
                                                class="pull-right text-primary">{days_of_week}</span></p>
                                        <p class="col-4">{{ __('Salary') }} : <span
                                                class="pull-right text-primary">{salary}</span></p>
                                        <p class="col-4">{{ __('Salary Type') }} : <span
                                                class="pull-right text-primary">{salary_type}</span></p>
                                        <p class="col-4">{{ __('Salary Duration') }} : <span
                                                class="pull-end text-primary">{salary_duration}</span></p>
                                        <p class="col-4">{{ __('Offer Expiration Date') }} : <span
                                                class="pull-right text-primary">{offer_expiration_date}</span></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style ">

                    {{ Form::open(['route' => ['offerlatter.update', $offerlang], 'method' => 'post']) }}
                    <div class="form-group col-12">
                        {{ Form::label('offer_content', __(' Format'), ['class' => 'form-label text-dark']) }}
                        <textarea name="offer_content"
                            class="form-control summernote  {{ !empty($errors->first('offer_content')) ? 'is-invalid' : '' }}" required
                            id="offer_content">{!! isset($currOfferletterLang->content) ? $currOfferletterLang->content : '' !!}</textarea>
                    </div>
                    <div class="card-footer text-end">

                        {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
        <div id="recruitment-print-settings" class="card">
            <div class="card-header">
                <h5>{{ __('Recruitment Print Settings') }}</h5>
                <small class="text-muted">{{ __('Edit your Company Job details') }}</small>
            </div>
            <div class="bg-none">
                <div class="row company-setting">
                    <form id="setting-form" method="post" action="{{ route('job.template.setting') }}"
                        enctype ="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card-header card-body">
                                    <div class="form-group">
                                        {{ Form::label('job_template', __('Job Template'), ['class' => 'form-label']) }}
                                        {{ Form::select('job_template', Modules\Recruitment\Entities\JobCandidate::templateData()['templates'], !empty($settings['job_template']) ? $settings['job_template'] : null, ['class' => 'form-control', 'required' => 'required']) }}
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('Color Input') }}</label>
                                        <div class="row gutters-xs">
                                            @foreach (Modules\Recruitment\Entities\JobCandidate::templateData()['colors'] as $key => $color)
                                                <div class="col-auto">
                                                    <label class="colorinput">
                                                        <input name="job_color" type="radio" value="{{ $color }}"
                                                            class="colorinput-input"
                                                            {{ !empty($settings['job_color']) && $settings['job_color'] == $color ? 'checked' : '' }}>
                                                        <span class="colorinput-color"
                                                            style="background: #{{ $color }}"></span>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label">{{ __('Job Image') }}</label>
                                        <div class="choose-files mt-5 ">
                                            <label for="job_logo">
                                                <div class=" bg-primary "> <i
                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}</div>
                                                <img id="blah7" class="mt-3" src="" width="70%" />
                                                <input type="file" class="form-control file" name="job_logo" id="job_logo"
                                                    data-filename="job_logo_update"
                                                    onchange="document.getElementById('blah7').src = window.URL.createObjectURL(this.files[0])">
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group mt-2 text-end">
                                        <input type="submit" value="{{ __('Save Changes') }}"
                                            class="btn btn-print-invoice  btn-primary m-r-10">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                @if (!empty($settings['job_template']) && !empty($settings['job_color']))
                                    <iframe id="job_frame" class="w-100 h-100" frameborder="0"
                                        src="{{ route('job.preview', [$settings['job_template'], $settings['job_color']]) }}"></iframe>
                                @else
                                    <iframe id="job_frame" class="w-100 h-100" frameborder="0"
                                        src="{{ route('job.preview', ['template1', 'fffff']) }}"></iframe>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endpermission
@endif
@permission('letter joining manage')
    <div class="" id="joining-letter-settings">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>{{ __('Joining Letter Settings') }}</h5>
                <div class="d-flex justify-content-end drp-languages">
                    @if (module_is_active('AIAssistant'))
                        @include('aiassistant::ai.generate_ai_btn', [
                            'template_module' => 'joining letter settings',
                            'module' => 'Hrm',
                        ])
                    @endif
                    <ul class="list-unstyled mb-0 m-2">
                        <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                            <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage1">
                                <span class="drp-text hide-mob text-primary">

                                    {{ Str::upper($joininglang) }}
                                </span>
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                            </a>
                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                aria-labelledby="dropdownLanguage1">
                                @foreach (languages() as $key => $joininglangs)
                                    <a href="{{ route('settings.index', ['noclangs' => $noclang, 'explangs' => $explang, 'joininglangs' => $key, 'offerlangs' => $offerlang]) }}"
                                        class="dropdown-item {{ $key == $joininglang ? 'text-primary' : '' }}">{{ Str::ucfirst($joininglangs) }}</a>
                                @endforeach
                            </div>
                        </li>

                    </ul>
                </div>

            </div>
            <div class="card-body ">
                <h5 class="font-weight-bold pb-3">{{ __('Placeholders') }}</h5>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header card-body">
                            <div class="row text-xs">
                                <div class="row">
                                    <p class="col-4">{{ __('Applicant Name') }} : <span
                                            class="pull-end text-primary">{date}</span></p>
                                    <p class="col-4">{{ __('Company Name') }} : <span
                                            class="pull-right text-primary">{app_name}</span></p>
                                    <p class="col-4">{{ __('Employee Name') }} : <span
                                            class="pull-right text-primary">{employee_name}</span></p>
                                    <p class="col-4">{{ __('Address') }} : <span
                                            class="pull-right text-primary">{address}</span></p>
                                    <p class="col-4">{{ __('Designation') }} : <span
                                            class="pull-right text-primary">{designation}</span></p>
                                    <p class="col-4">{{ __('Start Date') }} : <span
                                            class="pull-right text-primary">{start_date}</span></p>
                                    <p class="col-4">{{ __('Branch') }} : <span
                                            class="pull-right text-primary">{branch}</span></p>
                                    <p class="col-4">{{ __('Start Time') }} : <span
                                            class="pull-end text-primary">{start_time}</span></p>
                                    <p class="col-4">{{ __('End Time') }} : <span
                                            class="pull-right text-primary">{end_time}</span></p>
                                    <p class="col-4">{{ __('Number of Hours') }} : <span
                                            class="pull-right text-primary">{total_hours}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-border-style ">
                {{ Form::open(['route' => ['joiningletter.update', $joininglang], 'method' => 'post']) }}
                <div class="form-group col-12">
                    {{ Form::label('joining_content', __(' Format'), ['class' => 'form-label text-dark']) }}
                    <textarea name="joining_content"
                        class="form-control summernote  {{ !empty($errors->first('joining_content')) ? 'is-invalid' : '' }}" required
                        id="joining_content">{!! isset($currjoiningletterLang->content) ? $currjoiningletterLang->content : '' !!}</textarea>
                </div>
                <div class="card-footer text-end">
                    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
@endpermission
@permission('letter certificate manage')
    <div class="" id="experience-certificate-settings">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>{{ __('Certificate of Experience Settings') }}</h5>
                <div class="d-flex justify-content-end drp-languages">
                    @if (module_is_active('AIAssistant'))
                        @include('aiassistant::ai.generate_ai_btn', [
                            'template_module' => 'experience certificate settings',
                            'module' => 'Hrm',
                        ])
                    @endif
                    <ul class="list-unstyled mb-0 m-2">
                        <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                            <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage1">
                                <span class="drp-text hide-mob text-primary">

                                    {{ Str::upper($explang) }}
                                </span>
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                            </a>
                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                aria-labelledby="dropdownLanguage1">
                                @foreach (languages() as $key => $explangs)
                                    <a href="{{ route('settings.index', ['noclangs' => $noclang, 'explangs' => $key, 'joininglangs' => $joininglang, 'offerlangs' => $offerlang]) }}"
                                        class="dropdown-item {{ $key == $explang ? 'text-primary' : '' }}">{{ Str::ucfirst($explangs) }}</a>
                                @endforeach
                            </div>
                        </li>

                    </ul>
                </div>

            </div>
            <div class="card-body ">
                <h5 class="font-weight-bold pb-3">{{ __('Placeholders') }}</h5>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header card-body">
                            <div class="row text-xs">
                                <div class="row">
                                    <p class="col-4">{{ __('Company Name') }} : <span
                                            class="pull-right text-primary">{app_name}</span></p>
                                    <p class="col-4">{{ __('Employee Name') }} : <span
                                            class="pull-right text-primary">{employee_name}</span></p>
                                    <p class="col-4">{{ __('Date of Issuance') }} : <span
                                            class="pull-right text-primary">{date}</span></p>
                                    <p class="col-4">{{ __('Designation') }} : <span
                                            class="pull-right text-primary">{designation}</span></p>
                                    <p class="col-4">{{ __('Start Date') }} : <span
                                            class="pull-right text-primary">{start_date}</span></p>
                                    <p class="col-4">{{ __('Branch') }} : <span
                                            class="pull-right text-primary">{branch}</span></p>
                                    <p class="col-4">{{ __('Start Time') }} : <span
                                            class="pull-end text-primary">{start_time}</span></p>
                                    <p class="col-4">{{ __('End Time') }} : <span
                                            class="pull-right text-primary">{end_time}</span></p>
                                    <p class="col-4">{{ __('Number of Hours') }} : <span
                                            class="pull-right text-primary">{total_hours}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-border-style ">

                {{ Form::open(['route' => ['experiencecertificate.update', $explang], 'method' => 'post']) }}
                <div class="form-group col-12">
                    {{ Form::label('experience_content', __(' Format'), ['class' => 'form-label text-dark']) }}
                    <textarea name="experience_content"
                        class="form-control summernote  {{ !empty($errors->first('experience_content')) ? 'is-invalid' : '' }}" required
                        id="experience_content">{!! isset($curr_exp_cetificate_Lang->content) ? $curr_exp_cetificate_Lang->content : '' !!}</textarea>
                </div>

                <div class="card-footer text-end">

                    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@endpermission
@permission('letter noc manage')
    <div class="" id="noc-settings">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h5>{{ __('No Objection Certificate Settings') }}</h5>
                <div class="d-flex justify-content-end drp-languages">
                    @if (module_is_active('AIAssistant'))
                        @include('aiassistant::ai.generate_ai_btn', [
                            'template_module' => 'noc settings',
                            'module' => 'Hrm',
                        ])
                    @endif
                    <ul class="list-unstyled mb-0 m-2">
                        <li class="dropdown dash-h-item drp-language" style="margin-top: -19px;">
                            <a class="dash-head-link dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown"
                                role="button" aria-haspopup="false" aria-expanded="false" id="dropdownLanguage1">
                                <span class="drp-text hide-mob text-primary">

                                    {{ Str::upper($noclang) }}
                                </span>
                                <i class="ti ti-chevron-down drp-arrow nocolor"></i>
                            </a>
                            <div class="dropdown-menu dash-h-dropdown dropdown-menu-end"
                                aria-labelledby="dropdownLanguage1">
                                @foreach (languages() as $key => $noclangs)
                                    <a href="{{ route('settings.index', ['noclangs' => $key, 'explangs' => $explang, 'joininglangs' => $joininglang, 'offerlangs' => $offerlang]) }}"
                                        class="dropdown-item {{ $key == $noclang ? 'text-primary' : '' }}">{{ Str::ucfirst($noclangs) }}</a>
                                @endforeach
                            </div>
                        </li>

                    </ul>
                </div>

            </div>
            <div class="card-body ">
                <h5 class="font-weight-bold pb-3">{{ __('Placeholders') }}</h5>

                <div class="col-lg-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header card-body">
                            <div class="row text-xs">
                                <div class="row">
                                    <p class="col-4">{{ __('Date') }} : <span
                                            class="pull-end text-primary">{date}</span></p>
                                    <p class="col-4">{{ __('Company Name') }} : <span
                                            class="pull-right text-primary">{app_name}</span></p>
                                    <p class="col-4">{{ __('Employee Name') }} : <span
                                            class="pull-right text-primary">{employee_name}</span></p>
                                    <p class="col-4">{{ __('Designation') }} : <span
                                            class="pull-right text-primary">{designation}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-body table-border-style ">
                {{ Form::open(['route' => ['noc.update', $noclang], 'method' => 'post']) }}
                <div class="form-group col-12">
                    {{ Form::label('noc_content', __(' Format'), ['class' => 'form-label text-dark']) }}
                    <textarea name="noc_content"
                        class="form-control summernote  {{ !empty($errors->first('noc_content')) ? 'is-invalid' : '' }}" required
                        id="noc_content">{!! isset($currnocLang->content) ? $currnocLang->content : '' !!}</textarea>
                </div>

                <div class="card-footer text-end">

                    {{ Form::submit(__('Save Changes'), ['class' => 'btn  btn-primary']) }}
                </div>

                {{ Form::close() }}
            </div>
        </div>
    </div>
@endpermission
<link rel="stylesheet" href="{{ asset('Modules/Hrm/Resources/assets/css/custom.css') }}">
<script src="{{ asset('js/custom.js') }}"></script>

<script>
    $(".hrm_setting_btn").click(function() {
        $("#hrm_setting_store").submit();
    });
</script>
<script>
    $(document).on('change', '#ip_restrict', function() {
        if ($(this).is(':checked')) {
            $('.ip_restrict_div').removeClass('d-none');

        } else {
            $('.ip_restrict_div').addClass('d-none');

        }
    });
</script>
<script>
    $(document).on("change", "select[name='job_template'], input[name='job_color']", function() {
        var template = $("select[name='job_template']").val();
        var color = $("input[name='job_color']:checked").val();
        $('#job_frame').attr('src', '{{ url('/job/preview') }}/' + template + '/' + color);
    });
</script>