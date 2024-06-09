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
{{$plural_name}}
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
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{ __('Import') }}"
            data-url="{{ route('users.file.import') }}" data-toggle="tooltip" title="{{ __('Import') }}"><i
                class="ti ti-file-import"></i>
        </a>
    @endpermission
    @permission('user manage')
        <a href="{{ route('users.index') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Grid View') }}"
            class="btn btn-sm btn-primary btn-icon">
            <i class="ti ti-layout-grid"></i>
        </a>
    @endpermission
    @permission('user create')
        <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New '.($singular_name)) }}"  data-url="{{ route('users.create') }}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
</div>
@endsection
@section('content')
    <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0 pc-dt-simple" id="users">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">{{ __('Picture') }}</th>
                                        <th scope="col">{{ __('Name') }}</th>
                                        <th scope="col">{{ __('Email') }}</th>
                                        <th scope="col">{{ __('Role') }}</th>
                                        @if (Laratrust::hasPermission('user edit') || Laratrust::hasPermission('user delete'))
                                            <th width="10%"> {{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $index => $user)
                                        <tr>
                                            <th scope="row">{{++$index}}</th>
                                            <td>
                                                <a>
                                                    <img src="{{ check_file($user->avatar) ? get_file($user->avatar) :get_file('uploads/users-avatar/avatar.png') }}" class="img-fluid rounded-circle card-avatar" width="35" id="blah3">
                                                </a>
                                            </td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->email}}</td>
                                            <td>
                                                <span class="badge bg-primary p-2 px-3 rounded rounded">
                                                    {{ $user->type }}
                                                </span>
                                            </td>
                                            <td class="text-end me-3">
                                                @if($user->is_disable == 1 || Auth::user()->type == 'super admin')
                                                    @if(Auth::user()->type == "super admin")
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a data-url="{{ route('company.info',$user->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center"  data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Admin Hub')}}" data-title="{{__('Company Info')}}"> <span class="text-white"><i class="ti ti-atom"></i></a>
                                                        </div>
                                                        <div class="action-btn bg-secondary ms-2">
                                                            <a href="{{ route('login.with.company',$user->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center"   data-bs-toggle="tooltip" data-bs-original-title="{{ __('Login As Company')}}"> <span class="text-white"><i class="ti ti-replace"></i></a>
                                                        </div>
                                                    @endif
                                                    @permission('user reset password')
                                                        <div class="action-btn bg-warning  ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('users.reset', \Crypt::encrypt($user->id)) }}" data-ajax-popup="true"  data-bs-toggle="tooltip" data-bs-original-title="{{ __('Reset Password')}}"> <span class="text-white"><i class="ti ti-adjustments"></i></a>
                                                        </div>
                                                    @endpermission
                                                    @permission('user login manage')
                                                        @if ($user->is_enable_login == 1)
                                                            <div class="action-btn bg-danger ms-2">
                                                                <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center"   data-bs-toggle="tooltip" data-bs-original-title="{{ __('Login Disable')}}"> <span class="text-white"><i class="ti ti-road-sign"></i></a>
                                                            </div>
                                                        @elseif ($user->is_enable_login == 0 && $user->password == null)
                                                            <div class="action-btn bg-secondary ms-2">
                                                                <a href="#" data-url="{{ route('users.reset', \Crypt::encrypt($user->id)) }}"
                                                                    data-ajax-popup="true" data-size="md" class="mx-3 btn btn-sm d-inline-flex align-items-center login_enable" data-title="{{ __('New Password') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('New Password')}}"> <span class="text-white"><i class="ti ti-road-sign"></i></a>
                                                            </div>
                                                        @else
                                                            <div class="action-btn bg-success ms-2">
                                                                <a href="{{ route('users.login', \Crypt::encrypt($user->id)) }}"
                                                                    class="mx-3 btn btn-sm d-inline-flex align-items-center login_enable"  data-bs-toggle="tooltip" data-bs-original-title="{{ __('Login Enable')}}"> <span class="text-white">                                                            <i class="ti ti-road-sign"></i>
                                                                </a>
                                                            </div>
                                                        @endif
                                                    @endpermission
                                                    @permission('user edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a href="#" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('users.edit', $user->id) }}" class="dropdown-item" data-ajax-popup="true" data-title="{{ __('Update '.($singular_name)) }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Edit')}}"> <span class="text-white"> <i class="ti ti-edit"></i></span></a>
                                                        </div>
                                                    @endpermission
                                                    @permission('user delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {{ Form::open(['route' => ['users.destroy', $user->id], 'class' => 'm-0']) }}
                                                        @method('DELETE')
                                                        <a href="#"
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title=""
                                                            data-bs-original-title="Delete" aria-label="Delete"
                                                            data-confirm-yes="delete-form-{{ $user->id }}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        {{ Form::close() }}
                                                    </div>
                                                    @endpermission
                                                @else
                                                    <div class="text-center">
                                                        <i class="ti ti-lock"></i>
                                                    </div>
                                                @endif
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
    <!-- [ Main Content ] end -->
@endsection
@push('scripts')
{{-- Password  --}}
<script>
    $(document).on('change', '#password_switch', function() {
        if($(this).is(':checked'))
        {
            $('.ps_div').removeClass('d-none');
            $('#password').attr("required",true);

        } else {
            $('.ps_div').addClass('d-none');
            $('#password').val(null);
            $('#password').removeAttr("required");
        }
    });
    $(document).on('click', '.login_enable', function() {
        setTimeout(function() {
             $('.modal-body').append($('<input>', {type: 'hidden',val: 'true',name: 'login_enable' }));
        }, 1000);
    });
</script>

@endpush
