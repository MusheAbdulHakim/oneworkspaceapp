@php
if(Auth::user()->type=='super admin')
{
$titles = __('Customer Log History') ;
}
else{

$titles = __('User Log History') ;
}
@endphp
@extends('layouts.main')
@section('page-title')
{{ $titles }}
@endsection
@section('page-breadcrumb')
{{ $titles }}
@endsection
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
<li class="breadcrumb-item">{{__('User Log History')}}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="mt-2 ">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(array('route' => array('users.userlog.history'),'method'=>'get','id'=>'user_userlog')) }}
                    <div class="row align-items-center justify-content-end">
                        <div class="col-xl-10">
                            <div class="row">
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                        {{Form::label('month',__('Month'),['class'=>'form-label'])}}
                                        {{Form::month('month',isset($_GET['month'])?$_GET['month']:date('Y-m'),array('class'=>'month-btn form-control'))}}
                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                        @if(Auth::user()->type=='super admin')
                                        {{ Form::label('users', __('Customer'),['class'=>'form-label']) }}
                                        @else
                                        {{ Form::label('users', __('User'),['class'=>'form-label']) }}
                                        @endif
                                        {{ Form::select('users', $filteruser,isset($_GET['users'])?$_GET['users']:'', array('class' => 'form-control select')) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto mt-4">
                            <div class="row">
                                <div class="col-auto">
                                    <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('user_userlog').submit(); return false;" data-bs-toggle="tooltip" title="{{__('Apply')}}" data-original-title="{{__('apply')}}">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>
                                    <a href="{{route('users.userlog.history')}}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip" title="{{ __('Reset') }}" data-original-title="{{__('Reset')}}">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="users_log">
                        <thead>
                            <tr>
                                @if(Auth::user()->type == 'super admin' || Auth::user()->type == 'company')
                                <th>{{ __('User Name') }}</th>
                                <th>{{ __('Role') }}</th>
                                @endif
                                <th>{{ __('Last Login') }}</th>
                                <th>{{ __('Ip') }}</th>
                                <th>{{ __('Country') }}</th>
                                <th>{{ __('Device') }}</th>
                                <th>{{ __('OS') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($userdetails as $user)
                            @php
                            $userdetail = json_decode($user->details);
                            @endphp
                            <tr>
                                @if(Auth::user()->type == 'super admin' || Auth::user()->type == 'company')
                                <td>{{ $user->user_name }}</td>
                                <td>
                                    <span class="me-5 badge p-2 px-3 rounded bg-primary status_badge">{{$user->user_type}}</span>
                                </td>
                                @endif
                                <td>{{ !empty($user->date) ? company_datetime_formate($user->date) : '-' }}</td>
                                <td>{{ $user->ip }}</td>
                                <td>{{ !empty($userdetail->country)?$userdetail->country:'-' }}</td>
                                <td>{{ $userdetail->device_type }}</td>
                                <td>{{ $userdetail->os_name }}</td>
                                <td>
                                    <div class="action-btn bg-warning ms-2">
                                        <a href="#" class="mx-3 btn btn-sm align-items-center" data-size="lg" data-url="{{ route('users.userlog.view', [$user->id]) }}" data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title="" data-title="{{ __('View User Logs') }}" data-bs-original-title="{{ __('View') }}">
                                            <i class="ti ti-eye text-white"></i>
                                        </a>
                                    </div>
                                    @permission('user delete')
                                    <div class="action-btn bg-danger ms-2">
                                        {{ Form::open(['route' => ['users.userlog.destroy', $user->id], 'class' => 'm-0']) }}
                                        @method('DELETE')
                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete" data-confirm-yes="delete-form-{{ $user->id }}"><i class="ti ti-trash text-white text-white"></i></a>
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
@endsection