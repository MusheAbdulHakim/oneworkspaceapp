@extends('layouts.main')
@section('page-title')
    {{__('Email Templates')}}
@endsection
@section("page-breadcrumb")
    {{__('Email Templates')}}
@endsection
@section('page-action')

@endsection

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card table-card">
            <div class="card-header card-body table-border-style">
                <h5></h5>
                <div class="">
                    <table class="table mb-0 pc-dt-simple" id="d">
                        <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Module') }}</th>
                                <th class="text-end">{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>                        
                            @foreach ($email_templates as $email_template)
                            <tr>
                                <td>{{ $email_template->name }}</td>
                                <td class="text-capitalize">{{ Module_Alias_Name($email_template->module_name) }}</td>
                                <td class="text-end">
                                    <div class="action-btn bg-warning ms-2">
                                        <a href="{{ route('manage.email.language',[$email_template->id,getActiveLanguage()]) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('View')}}">
                                            <i class="ti ti-eye text-white"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- [ basic-table ] end -->
</div>

@endsection
