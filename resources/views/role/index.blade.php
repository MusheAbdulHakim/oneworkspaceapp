@extends('layouts.main')
@section('page-title')
    {{__('Manage Roles')}}
@endsection
@section('page-breadcrumb')
{{ __('Roles') }}
@endsection
@section('page-action')
    @permission('roles create')
        <div>
            <a href="#" class="btn btn-sm btn-primary" data-url="{{ route('roles.create') }}" data-size="xl" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}" data-ajax-popup="true" data-title="{{__('Create New Role')}}">
                <i class="ti ti-plus"></i>
            </a>
        </div>
    @endpermission
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <tr>
                                    <th> {{__('Role')}}</th>
                                    <th> {{__('Permissions')}}</th>
                                    <th class="text-end" width="200px"> {{__('Action')}}</th>
                                </tr>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($roles as $role)
                            @php
                                $permissions = $role->permissions()->get();
                            @endphp
                            <tr>
                                <td width="150px">{{ $role->name }}</td>
                                <td class="permission">
                                    @foreach ($permissions as $permission)
                                        @if (module_is_active($permission->module) || $permission->module == 'General')
                                            <span class="badge rounded p-2 m-1 px-3 bg-primary"> {{ $permission->name }}</span>
                                        @endif
                                    @endforeach
                                </td>
                                <td class="text-end">
                                    <span>
                                        @permission('roles edit')
                                        <div class="action-btn bg-info ms-2">
                                            <a  data-url="{{ route('roles.edit',$role->id) }}" data-size="xl" data-ajax-popup="true" data-title="{{__('Update permission')}}" class="btn btn-outline btn-xs blue-madison" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="ti ti-pencil text-white"></i>
                                            </a>
                                        </div>
                                        @endpermission
                                        @if (!in_array($role->name,\App\Models\User::$not_edit_role))
                                            @permission('roles delete')
                                                <div class="action-btn bg-danger ms-2" data-bs-toggle="tooltip" data-bs-placement="bottom" title="" data-bs-original-title="Delete" aria-describedby="tooltip434956">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id],'id'=>'delete-form-'.$role->id]) !!}

                                                    <a type="submit" class="mx-3 btn btn-sm align-items-center show_confirm" data-toggle="tooltip" title="" data-original-title="Delete">
                                                        <i class="ti ti-trash text-white"></i>
                                                    </a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endpermission
                                        @endif
                                    </span>
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
@push('scripts')
    <script>
        function Checkall(module = null) {
            var ischecked = $("#checkall-"+module).prop('checked');
            if(ischecked == true)
            {
                $('.checkbox-'+module).prop('checked',true);
            }
            else
            {
                $('.checkbox-'+module).prop('checked',false);
            }
        }
    </script>
    <script type="text/javascript">
        function CheckModule(cl = null)
        {
            var ischecked = $("#"+cl).prop('checked');
            if(ischecked == true)
            {
                $('.'+cl).find("input[type=checkbox]").prop('checked',true);
            }
            else
            {
                $('.'+cl).find("input[type=checkbox]").prop('checked',false);
            }
        }
    </script>
@endpush
