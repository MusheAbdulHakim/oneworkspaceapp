@extends('layouts.main')
@section('page-title')
{{__('Permissions')}}
@endsection
@section('page-breadcrumb')
{{ __('Users') }}
@endsection
@section('page-action')
    <a href="#" class="btn btn-sm btn-primary" data-url="{{ route('permissions.create') }}" data-ajax-popup="true" data-title="{{__('Create New Permission')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
    </a>
@endsection
@section('content')
<div class="row">
   <div class="col-xl-3">
      <div class="card sticky-top" style="top:30px">
         <div class="list-group list-group-flush" id="useradd-sidenav">
             @foreach ($modules as $key => $module)
             <a class="list-group-item list-group-item-action @if($loop->index == 0) active @endif" id="v-pills-{{ Str::slug($module) }}-tab" data-bs-toggle="pill" href="#v-pills-{{ Str::slug($module) }}" role="tab" aria-controls="v-pills-{{ Str::slug($module) }}" aria-selected="true">{{ __($module) }}</a></li>
             @endforeach
             @stack('permission-')
         </div>
      </div>
   </div>
   <div class="col-xl-9">

      <div class="tab-content  card" id="base-permission ">
        @foreach ($modules as $key => $module)
         <div id="v-pills-{{ Str::slug($module) }}" class="card tab-pane fade  @if($loop->index == 0) active show @endif mb-0" role="tabpanel" aria-labelledby="v-pills-{{ Str::slug($module) }}-tab">
            <div class="card-header">
                <h5>{{ __($module) }}</h5>
            </div>
            <div class="row">
                <div class="col-sm-12">
                <table class="table table-flush" id="dataTable">
                    <thead>
                        <tr>
                            <th> {{__('Permissions')}}</th>
                            <th class="text-right" width="200px"> {{__('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $permissions = get_permission_by_module($module)
                        @endphp
                        @foreach ($permissions as $permission)
                        <tr>
                            <td>{{ $permission->name }}</td>
                            <td class="action">



                                <span>

                                    <div class="action-btn btn-primary ms-2">
                                        <a  data-url="{{ route('permissions.edit',$permission->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Update permission')}}" class="btn btn-outline btn-xs blue-madison" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                </span>



                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
         </div>
        @endforeach
      </div>

      <!-- [ sample-page ] end -->
   </div>
   <!-- [ Main Content ] end -->
</div>
@endsection
