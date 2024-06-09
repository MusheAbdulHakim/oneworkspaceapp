@extends('layouts.main')
@php
    if(Auth::user()->type=='super admin')
    {
        $plural_name = __('Customers');
        $singular_name = __('Customer');
    }
    else{

        $plural_name =__('Users');
        $singular_name =__('User');
    }
@endphp
@section('page-title')
    {{ $plural_name}}
@endsection
@section('page-breadcrumb')
    {{ $plural_name}}
@endsection
@section('page-action')
    <div>
        @permission('user logs history')
            <a href="{{ route('users.userlog.history') }}" class="btn btn-sm btn-primary"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('User Logs History') }}"><i class="ti ti-user-check"></i>
            </a>
        @endpermission
        @permission('user import')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{ __('Import Customers') }}"
                data-url="{{ route('users.file.import') }}" data-toggle="tooltip" title="{{ __('Import Customers') }}"><i
                    class="ti ti-file-import"></i>
            </a>
        @endpermission
        @permission('user manage')
            <a href="{{ route('users.list.view') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('List View') }}"
                class="btn btn-sm btn-primary btn-icon ">
                <i class="ti ti-list"></i>
            </a>
        @endpermission
        @permission('user create')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Create New '.($singular_name)) }}" data-url="{{ route('users.create') }}" data-bs-toggle="tooltip"
                data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <!-- [ Main Content ] start -->
    <div class="row">
        <div id="loading-bar-spinner" class="spinner"><div class="spinner-icon"></div></div>
        @foreach ($users as $user)
            <div class="col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-header border-0 pb-0">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-primary p-2 px-3 rounded">{{ $user->type }}</span>
                        </div>
                        <div class="card-header-right">
                            @permission('user manage')
                                <div class="btn-group card-option">
                                    @if($user->is_disable == 1 || Auth::user()->type == "super admin")
                                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="true">
                                            <i class="feather icon-more-vertical"></i>
                                        </button>
                                    @else
                                        <div class="btn">
                                            <i class="ti ti-lock"></i>
                                        </div>
                                    @endif
                                    <div class="dropdown-menu dropdown-menu-end" data-popper-placement="bottom-end">
                                        @permission('user edit')
                                            <a data-url="{{ route('users.edit', $user->id) }}" class="dropdown-item"
                                                data-ajax-popup="true" data-title="{{ __('Update '.($singular_name)) }}"
                                                data-toggle="tooltip" data-original-title="{{ __('Edit') }}">
                                                <i class="ti ti-pencil"></i>
                                                <span>{{ __('Edit') }}</span>
                                            </a>
                                        @endpermission
                                        @permission('user delete')
                                            {{ Form::open(['route' => ['users.destroy', $user->id], 'class' => 'm-0']) }}
                                            @method('DELETE')
                                            <a href="#!" class="dropdown-item bs-pass-para show_confirm" aria-label="Delete"
                                                data-confirm="{{ __('Are You Sure?') }}"
                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                data-confirm-yes="delete-form-{{ $user->id }}">
                                                <i class="ti ti-trash"></i>
                                                <span>{{ __('Delete') }}</span>
                                            </a>
                                            {{ Form::close() }}
                                        @endpermission
                                        @if(Auth::user()->type == "super admin")
                                            <a href="{{ route('login.with.company',$user->id) }}" class="dropdown-item"
                                                data-bs-original-title="{{ __('Login As Company') }}">
                                                <i class="ti ti-replace"></i>
                                                <span> {{ __('Login As Company') }}</span>
                                            </a>
                                            <a href="#!" data-url="{{ route('upgrade.plan',$user->id) }}"
                                                data-ajax-popup="true" data-size="xl" class="dropdown-item"
                                                data-title="{{ __('Upgrade Plan') }}"
                                                data-bs-original-title="{{ __('Upgrade Plan') }}">
                                                <i class="ti ti-trending-up"></i>
                                                <span> {{ __('Upgrade Plan') }}</span>
                                            </a>
                                        @endif
                                        @permission('user reset password')
                                            <a href="#!" data-url="{{ route('users.reset', \Crypt::encrypt($user->id)) }}"
                                                data-ajax-popup="true" data-size="md" class="dropdown-item"
                                                data-title="{{ __('Reset Password') }}"
                                                data-bs-original-title="{{ __('Reset Password') }}">
                                                <i class="ti ti-adjustments"></i>
                                                <span> {{ __('Reset Password') }}</span>
                                            </a>
                                        @endpermission
                                        @permission('user login manage')
                                            @if ($user->is_enable_login == 1)
                                                <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                                    class="dropdown-item">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-danger"> {{ __('Login Disable') }}</span>
                                                </a>
                                            @elseif ($user->is_enable_login == 0 && $user->password == null)
                                                <a href="#" data-url="{{ route('users.reset', \Crypt::encrypt($user->id)) }}"
                                                    data-ajax-popup="true" data-size="md" class="dropdown-item login_enable"
                                                    data-title="{{ __('New Password') }}" class="dropdown-item">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-success"> {{ __('Login Enable') }}</span>
                                                </a>
                                            @else
                                                <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                                    class="dropdown-item">
                                                    <i class="ti ti-road-sign"></i>
                                                    <span class="text-success"> {{ __('Login Enable') }}</span>
                                                </a>
                                            @endif
                                        @endpermission
                                    </div>
                                </div>
                            @endpermission
                        </div>
                    </div>
                    <div class="card-body  text-center">
                        <img src="{{ check_file($user->avatar) ? get_file($user->avatar) : get_file('uploads/users-avatar/avatar.png') }}"
                            alt="user-image" class="img-fluid rounded-circle" width="120px">
                        <h4 class="mt-2">{{ $user->name }}</h4>
                        <small>{{ $user->email }}</small>
                        @if( Auth::user()->type == "super admin")
                            <div class="mt-4">
                                <div class="row justify-content-between align-items-center">
                                    <div class="col-6 text-center">
                                        <span class="d-block font-bold mb-0">{{!empty($user->plan) ? (!empty($user->plan->name) ? $user->plan->name :'Basic Plan'):'Plan Not Activated'}}</span>
                                    </div>
                                    <div class="col-6 text-center Id ">
                                        <a href="#" data-url="{{route('company.info', $user->id)}}" data-size="lg" data-ajax-popup="true" class="btn btn-outline-primary" data-title="{{__('Company Info')}}">{{__('AdminHub')}}</a>
                                    </div>
                                    <div class="col-12">
                                        <hr class="my-3">
                                    </div>
                                    @php
                                        $plan_expire_date = !empty($user->plan_expire_date) ? $user->plan_expire_date :'';
                                        if($plan_expire_date == '0000-00-00'){
                                            $plan_expire_date = date('d-m-Y');
                                        }
                                        if(empty($plan_expire_date)){
                                            $plan_expire_date =  !empty($user->trial_expire_date) ? $user->trial_expire_date : '--';
                                        }
                                    @endphp
                                    <div class="col-12 text-center pb-2">
                                        <span class="text-dark text-xs">{{__('Plan Expired :' )}}
                                            @if(!empty($user->plan))
                                                {{company_date_formate($plan_expire_date)}}
                                            @else
                                                --
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
        @auth('web')
            @permission('user create')
                <div class="col-md-3 All">
                    <a href="#" class="btn-addnew-project " style="padding: 90px 10px;" data-ajax-popup="true" data-size="md"
                        data-title="{{ __('Create New '.($singular_name)) }}" data-url="{{ route('users.create') }}">
                        <div class="bg-primary proj-add-icon">
                            <i class="ti ti-plus my-2"></i>
                        </div>
                        <h6 class="mt-4 mb-2">{{ __('New '.($singular_name)) }}</h6>
                        <p class="text-muted text-center">{{ __('Click here to Create New '.($singular_name)) }}</p>
                    </a>
                </div>
            @endpermission
        @endauth
    </div>
    <!-- [ Main Content ] end -->
@endsection
@push('scripts')
    {{-- Password  --}}
    <script>
        $(document).on('change', '#password_switch', function() {
            if ($(this).is(':checked')) {
                $('.ps_div').removeClass('d-none');
                $('#password').attr("required", true);

            } else {
                $('.ps_div').addClass('d-none');
                $('#password').val(null);
                $('#password').removeAttr("required");
            }
        });
        $(document).on('click', '.login_enable', function() {
            setTimeout(function() {
                $('.modal-body').append($('<input>', {
                    type: 'hidden',
                    val: 'true',
                    name: 'login_enable'
                }));
            }, 2000);
        });
    </script>
@endpush
