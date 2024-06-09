@extends('layouts.main')
@section('page-title')
    {{__('Warehouse')}}
@endsection
@push('script-page')
@endpush
@section('page-breadcrumb')
  {{__('Warehouse')}}
@endsection
@section('page-action')
    <div>
        @stack('addButtonHook')
        @permission('warehouse import')
            <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Warehouse Import')}}" data-url="{{ route('warehouses.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
            </a>
        @endpermission
        @permission('warehouse create')
            <a data-size="lg" data-url="{{ route('warehouses.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Warehouse')}}"  class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Address')}}</th>
                                <th>{{__('City')}}</th>
                                <th>{{__('Zip Code')}}</th>
                                @if(Laratrust::hasPermission('warehouse show') || Laratrust::hasPermission('warehouse edit') || Laratrust::hasPermission('warehouse delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($warehouses as $warehouse)
                                <tr class="font-style">
                                    <td>{{ $warehouse->name}}</td>
                                    <td>{{ $warehouse->address }}</td>
                                    <td>{{ $warehouse->city }}</td>
                                    <td>{{ $warehouse->city_zip }}</td>

                                    @if(Laratrust::hasPermission('warehouse show') || Laratrust::hasPermission('warehouse edit') || Laratrust::hasPermission('warehouse delete'))
                                        <td class="Action">
                                            @permission('warehouse show')
                                                <div class="action-btn bg-warning ms-2">

                                                    <a href="{{ route('warehouses.show',$warehouse->id) }}" class="mx-3 btn btn-sm
                                                         align-items-center"
                                                       data-bs-toggle="tooltip" title="{{__('View')}}"><i class="ti ti-eye text-white"></i></a>

                                                </div>
                                            @endpermission
                                            @permission('warehouse edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a class="mx-3 btn btn-sm  align-items-center" data-url="{{ route('warehouses.edit',$warehouse->id) }}" data-ajax-popup="true"  data-size="lg " data-bs-toggle="tooltip" title="{{__('Edit')}}"  data-title="{{__('Edit Warehouse')}}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                            @permission('warehouse delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['warehouses.destroy', $warehouse->id],'id'=>'delete-form-'.$warehouse->id]) !!}
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti ti-trash text-white"></i></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endpermission
                                        </td>
                                    @endif
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
