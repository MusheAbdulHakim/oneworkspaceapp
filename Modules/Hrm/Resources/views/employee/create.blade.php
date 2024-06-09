@extends('layouts.main')
@section('page-title')
    {{ __('Create Employee') }}
@endsection
@section('page-breadcrumb')
    {{ __('Employee') }},
    {{ __('Create Employee') }}
@endsection
@php
    $company_settings = getCompanyAllSetting();
@endphp

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end mb-4">
                <div class="col-md-6">
                    <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="personal-details" data-bs-toggle="pill"
                                data-bs-target="#personal-details-tab" type="button">{{ __('Personal Details') }}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="company" data-bs-toggle="pill" data-bs-target="#company-tab"
                                type="button">{{ __('Company Details') }}</button>
                        </li>

                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{ Form::open(['route' => ['employee.store'], 'method' => 'post', 'enctype' => 'multipart/form-data']) }}

            <div class="card">
                <div class="card-body">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="personal-details-tab" role="tabpanel"
                            aria-labelledby="pills-user-tab-1">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5>{{ __('Personal Details') }}</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {{ Form::label('name', __('Name'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('name', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Employee Name']) }}
                                            </div>
                                            <p class="text-danger d-none" id="{{ 'name_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('phone', __('Phone'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::text('phone', old('phone'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter employee phone',
                                                'required' => 'required',
                                            ]) !!}
                                            <div class=" text-xs text-danger">
                                                {{ __('Please add mobile number with country code. (ex. +91)') }}
                                            </div>
                                            <p class="text-danger d-none" id="{{ 'phone_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('dob', __('Date of Birth'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {{ Form::date('dob', date('Y-m-d'), ['class' => 'form-control ', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => 'Select Date of Birth', 'max' => date('Y-m-d')]) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('gender', __('Gender'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            <div class="d-flex radio-check">
                                                <div class="custom-control custom-radio custom-control-inline">
                                                    <input type="radio" id="g_male" value="Male" name="gender"
                                                        class="form-check-input" checked="checked">
                                                    <label class="form-check-label "
                                                        for="g_male">{{ __('Male') }}</label>
                                                </div>
                                                <div class="custom-control custom-radio ms-1 custom-control-inline">
                                                    <input type="radio" id="g_female" value="Female" name="gender"
                                                        class="form-check-input">
                                                    <label class="form-check-label "
                                                        for="g_female">{{ __('Female') }}</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('email', __('Email'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::email('email', old('email'), [
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Enter employee email',
                                            ]) !!}
                                            <p class="text-danger d-none" id="{{ 'email_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('password', __('Password'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::password('password', [
                                                'class' => 'form-control',
                                                'required' => 'required',
                                                'placeholder' => 'Enter employee new password',
                                            ]) !!}
                                            <p class="text-danger d-none" id="{{ 'password_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('passport_country', __('Passport country'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('passport_country', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Passport Country']) }}
                                            </div>
                                            <p class="text-danger d-none" id="{{ 'passport_country_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('passport', __('Passport'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('passport', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Passport']) }}
                                            </div>
                                            <p class="text-danger d-none" id="{{ 'passport_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h5>{{ __('Location Details') }}</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {{ Form::label('location_type', __('Location Type'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            {{ Form::select('location_type', $location_type, null, ['class' => 'form-control select', 'required' => 'required']) }}
                                            <p class="text-danger d-none" id="{{ 'location_type_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>

                                        <div class="form-group col-md-6">
                                            {{ Form::label('country', __('Country'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('country', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Country']) }}
                                            </div>
                                            <p class="text-danger d-none" id="{{ 'country_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('state', __('State'), ['class' => 'form-label', 'required' => 'required']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('state', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter State']) }}
                                            </div>
                                            <p class="text-danger d-none" id="{{ 'state_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('city', __('City'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('city', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter City']) }}
                                            </div>
                                            <p class="text-danger d-none" id="{{ 'city_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('zipcode', __('Zip code'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::text('zipcode', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Zip code']) }}
                                            </div>
                                            <p class="text-danger d-none" id="{{ 'zipcode_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('address', __('Address'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {!! Form::textarea('address', old('address'), [
                                                'class' => 'form-control',
                                                'rows' => 2,
                                                'placeholder' => 'Enter employee address',
                                                'required' => 'required',
                                            ]) !!}
                                            <p class="text-danger d-none" id="{{ 'address_validation' }}">
                                                {{ __('This field is required.') }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5>{{ __('Document') }}</h5>
                                    <hr>
                                    <div class="card-body employee-detail-create-body">
                                        @foreach ($documents as $key => $document)
                                            <div class="row">
                                                <div class="form-group col-12 d-flex">
                                                    <div class="float-left col-4">
                                                        <label for="document"
                                                            class="float-left pt-1 form-label">{{ $document->name }}
                                                            @if ($document->is_required == 1)
                                                                <span class="text-danger">*</span>
                                                            @endif
                                                        </label>
                                                    </div>
                                                    <div class="float-right col-8">
                                                        <input type="hidden" name="emp_doc_id[{{ $document->id }}]"
                                                            value="{{ $document->id }}">
                                                        <div class="choose-files ">
                                                            <label for="document[{{ $document->id }}]">
                                                                <div class=" bg-primary document "> <i
                                                                        class="ti ti-upload px-1"></i>{{ __('Choose file here') }}
                                                                </div>
                                                                <input type="file"
                                                                    class="form-control file  d-none @error('document') is-invalid @enderror doc_data"
                                                                    @if ($document->is_required == 1) data-key="{{ $key }}" required @endif
                                                                    name="document[{{ $document->id }}]"
                                                                    id="document[{{ $document->id }}]"
                                                                    data-filename="{{ $document->id . '_filename' }}"
                                                                    onchange="document.getElementById('{{ 'blah' . $key }}').src = window.URL.createObjectURL(this.files[0])">
                                                            </label>

                                                            <p class="text-danger d-none"
                                                                id="{{ 'doc_validation-' . $key }}">
                                                                {{ __('This filed is required.') }}</p>
                                                            <img id="{{ 'blah' . $key }}" width="30%" />
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h5>{{ __('Bank Account Detail') }}</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {!! Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'form-label']) !!}
                                            {!! Form::text('account_holder_name', old('account_holder_name'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter Account Holder Name',
                                            ]) !!}

                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('account_number', __('Account Number'), ['class' => 'form-label']) !!}
                                            {!! Form::number('account_number', old('account_number'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter Account Number',
                                            ]) !!}

                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('bank_name', __('Bank Name'), ['class' => 'form-label']) !!}
                                            {!! Form::text('bank_name', old('bank_name'), ['class' => 'form-control', 'placeholder' => 'Enter Bank Name']) !!}

                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'form-label']) !!}
                                            {!! Form::text('bank_identifier_code', old('bank_identifier_code'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter Bank Identifier Code',
                                            ]) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('branch_location', __('Branch Location'), ['class' => 'form-label']) !!}
                                            {!! Form::text('branch_location', old('branch_location'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter Branch Location',
                                            ]) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('tax_payer_id', __('Tax Payer Id'), ['class' => 'form-label']) !!}
                                            {!! Form::text('tax_payer_id', old('tax_payer_id'), [
                                                'class' => 'form-control',
                                                'placeholder' => 'Enter Tax Payer Id',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col"></div>
                                <div class="col-6 text-end">
                                    <button class="btn btn-primary d-inline-flex align-items-center" id="nextButton"
                                        type="button">{{ __('Next') }}<i
                                            class="ti ti-chevron-right ms-2"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="company-tab" role="tabpanel" aria-labelledby="pills-user-tab-2">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5>{{ __('Company Detail') }}</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="form-group">
                                            {!! Form::label('employee_id', __('Employee ID'), ['class' => 'form-label']) !!}
                                            {!! Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('branch_id', !empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch'), ['class' => 'form-label']) }}<span
                                                class="text-danger pl-1">*</span>
                                            {{ Form::select('branch_id', $branches, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Select ' . (!empty($company_settings['hrm_branch_name']) ? $company_settings['hrm_branch_name'] : __('Branch')))]) }}
                                            @if (empty($branches->count()))
                                                <div class="text-xs">
                                                    {{ __('Please add Branch. ') }}<a
                                                        href="{{ route('branch.index') }}"><b>{{ __('Add Branch') }}</b></a>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('department_id', !empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department'), ['class' => 'form-label']) }}<span
                                                class="text-danger pl-1">*</span>
                                            {{ Form::select('department_id', [], null, ['class' => 'form-control', 'id' => 'department_id', 'required' => 'required', 'placeholder' => __('Select ' . (!empty($company_settings['hrm_department_name']) ? $company_settings['hrm_department_name'] : __('Department')))]) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('designation_id', !empty($company_settings['hrm_designation_name']) ? $company_settings['hrm_designation_name'] : __('Designation'), ['class' => 'form-label']) }}<span
                                                class="text-danger pl-1">*</span>
                                            {{ Form::select('designation_id', [], null, ['class' => 'form-control', 'id' => 'designation_id', 'required' => 'required', 'placeholder' => __('Select ' . (!empty($company_settings['hrm_designation_name']) ? $company_settings['hrm_designation_name'] : __('Designation')))]) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {!! Form::label('company_doj', __('Company Date Of Joining'), ['class' => 'form-label']) !!}<span class="text-danger pl-1">*</span>
                                            {{ Form::date('company_doj', date('Y-m-d'), ['class' => 'form-control ', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => 'Select company date of joining']) }}
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('role', __('Role'), ['class' => 'form-label']) }}<span
                                                class="text-danger pl-1">*</span>
                                            {{ Form::select('role', $role, null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Role']) }}
                                        </div>
                                        @stack('biometric_emp_id')
                                        @if (module_is_active('CustomField') && !$customFields->isEmpty())
                                            <div class="col-md-12">
                                                <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                                    @include('customfield::formBuilder')
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <h5>{{ __('Hours and Rates Detail') }}</h5>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h6>{{ __('Hours') }}</h6>
                                            <hr>
                                        </div>
                                        <div class="col-md-6">
                                            <h6>{{ __('Rates') }}</h6>
                                            <hr>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('hours_per_day', __('Hours Per day'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('hours_per_day', null, ['class' => 'form-control', 'step' => '0.01', 'required' => 'required', 'placeholder' => 'Enter Hours Per Day']) }}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('annual_salary', __('Annual salary'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('annual_salary', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Annual Salary']) }}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('days_per_week', __('Days Per week'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('days_per_week', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Days Per Week']) }}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('fixed_salary', __('Fixed Salary'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('fixed_salary', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Fixed Salary']) }}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('hours_per_month', __('Hours Per month'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('hours_per_month', null, ['class' => 'form-control', 'step' => '0.01', 'required' => 'required', 'placeholder' => 'Enter Hours Per Month']) }}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('rate_per_day', __('Rate per day'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('rate_per_day', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Rate Per Day']) }}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('days_per_month', __('Days per month'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('days_per_month', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Days Per Month']) }}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{ Form::label('rate_per_hour', __('Rate per hour'), ['class' => 'form-label']) }}<span
                                                class="text-danger">*</span>
                                            <div class="form-icon-user">
                                                {{ Form::number('rate_per_hour', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Enter Rate Per Hour']) }}
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <div class="form-check">
                                                <input type="checkbox" class="form-check-input"
                                                    id="payment_requires_work_advice" name="payment_requires_work_advice">
                                                <label class="form-check-label"
                                                    for="payment_requires_work_advice">{{ __('This employee must not be paid unless hours or days worked are advised') }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-6">
                                    <button class="btn btn-outline-secondary d-inline-flex align-items-center"
                                        onClick="changetab('#personal-details-tab')" type="button"><i
                                            class="ti ti-chevron-left me-2"></i>{{ __('Previous') }}</button>
                                </div>
                                <div class="col-6 text-end" id="savebutton">
                                    <a class="btn btn-secondary btn-light btn-submit"
                                        href="{{ route('employee.index') }}">{{ __('Cancel') }}</a>
                                    <button class="btn btn-primary btn-submit ms-2" type="submit"
                                        id="submit">{{ __('Create') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
    <script>
        function changetab(tabname) {
            var someTabTriggerEl = document.querySelector('button[data-bs-target="' + tabname + '"]');
            var actTab = new bootstrap.Tab(someTabTriggerEl);
            actTab.show();
        }
    </script>

    <script type="text/javascript">
        $(document).on('change', '#branch_id', function() {
            var branch_id = $(this).val();
            getDepartment(branch_id);
        });

        function getDepartment(branch_id) {
            var data = {
                "branch_id": branch_id,
                "_token": "{{ csrf_token() }}",
            }

            $.ajax({
                url: '{{ route('employee.getdepartments') }}',
                method: 'POST',
                data: data,
                success: function(data) {
                    $('#department_id').empty();
                    $('#department_id').append(
                        '<option value="" disabled>{{ __('Select Department') }}</option>');

                    $.each(data, function(key, value) {
                        $('#department_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    $('#department_id').val('');
                }
            });
        }

        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

        function getDesignation(did) {
            $.ajax({
                url: '{{ route('employee.getdesignations') }}',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    $('#designation_id').empty();
                    $('#designation_id').append(
                        '<option value="">{{ __('Select Designation') }}</option>');
                    $.each(data, function(key, value) {
                        $('#designation_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });
        }

        $("#nextButton").click(function() {
            var allFieldsFilled = true;

            // Check required fields for personal details
            if ($('#name').val().trim() === '') {
                $('#name_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#name_validation').addClass('d-none');
            }

            if ($('#phone').val().trim() === '') {
                $('#phone_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#phone_validation').addClass('d-none');
            }

            if ($('#email').val().trim() === '') {
                $('#email_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#email_validation').addClass('d-none');
            }

            if ($('#password').val().trim() === '') {
                $('#password_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#password_validation').addClass('d-none');
            }

            if ($('#passport_country').val().trim() === '') {
                $('#passport_country_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#passport_country_validation').addClass('d-none');
            }

            if ($('#passport').val().trim() === '') {
                $('#passport_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#passport_validation').addClass('d-none');
            }

            // Check required fields for location details
            if ($('#location_type').val().trim() === '') {
                $('#location_type_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#location_type_validation').addClass('d-none');
            }

            if ($('#country').val().trim() === '') {
                $('#country_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#country_validation').addClass('d-none');
            }

            if ($('#state').val().trim() === '') {
                $('#state_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#state_validation').addClass('d-none');
            }

            if ($('#city').val().trim() === '') {
                $('#city_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#city_validation').addClass('d-none');
            }

            if ($('#zipcode').val().trim() === '') {
                $('#zipcode_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#zipcode_validation').addClass('d-none');
            }

            if ($('#address').val().trim() === '') {
                $('#address_validation').removeClass('d-none');
                allFieldsFilled = false;
            } else {
                $('#address_validation').addClass('d-none');
            }

            // Check document data fields
            $(".doc_data").each(function() {
                var id = '#doc_validation-' + $(this).data("key");
                var isRequired = $(this).attr('required'); // Check if the field is marked as required
                if (isRequired && $(this).val().trim() === '') { // Check if the field is required and empty
                    $(id).removeClass('d-none');
                    allFieldsFilled = false;
                } else {
                    $(id).addClass('d-none');
                }
            });

            if (!allFieldsFilled) {
                return false; // Prevents the button from proceeding to the next tab
            } else {
                // Proceed to the next tab
                changetab('#company-tab'); // Check if this function is correctly defined and functioning
            }
        });
    </script>
@endpush
