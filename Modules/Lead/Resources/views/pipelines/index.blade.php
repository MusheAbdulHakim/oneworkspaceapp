@extends('layouts.main')

@section('page-title')
    {{__('Manage Pipelines')}}
@endsection
@section('page-action')
    <div class="row align-items-center m-1">
        <div class="col-auto pe-0">
            @permission('pipeline create')
                <a data-size="md" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Pipeline')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Create Pipeline')}}" data-url="{{route('pipelines.create')}}"><i class="ti ti-plus text-white"></i></a>
            @endpermission
        </div>
    </div>
@endsection
@section('page-breadcrumb')
    {{__('Setup')}},
    {{__('Pipelines')}}
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-3 col-12">
            @include('lead::layouts.system_setup')
        </div>
        <div class="col-xl-9">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table " id="pipeline">
                            <thead>
                                <tr>
                                    <th>{{__('Pipeline')}}</th>
                                    <th width="250px">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pipelines as $pipeline)
                                    <tr>
                                        <td>{{ $pipeline->name }}</td>
                                        <td class="Action">
                                            <span>
                                            @permission('pipeline edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a data-size="md" data-url="{{ URL::to('pipelines/'.$pipeline->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Pipeline')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Pipeline')}}" ><i class="ti ti-pencil text-white"></i></a>
                                                </div>
                                                @endpermission
                                                @if(count($pipelines) > 1)
                                                    @permission('pipeline delete')
                                                        <div class="action-btn bg-danger mx-2">
                                                            <div class="action-btn bg-danger">
                                                                {!! Form::open(['method' => 'DELETE', 'route' => ['pipelines.destroy', $pipeline->id]]) !!}
                                                                <a href="#!" class="btn btn-sm d-inline-flex align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                                    <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                                {!! Form::close() !!}
                                                            </div>
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
