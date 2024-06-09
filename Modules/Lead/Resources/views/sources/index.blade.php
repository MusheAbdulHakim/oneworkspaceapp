@extends('layouts.main')
@section('page-title')
    {{__('Manage Sources')}}
@endsection

@section('page-action')
    <div class="row align-items-center m-1">
        @permission('source create')
            <div class="col-auto pe-0">
                <a class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Source')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Create Source')}}" data-url="{{route('sources.create')}}"><i class="ti ti-plus text-white"></i></a>
            </div>
        @endpermission
    </div>
@endsection

@section('page-breadcrumb')
    {{__('Setup')}},
    {{__('Sources')}}
@endsection

@section('content')

    <div class="row">
        <div class="col-sm-3">
            @include('lead::layouts.system_setup')
        </div>
        <div class="col-md-9">
            <div class="card ">
                <div class=" card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 " id="sources">
                            <thead>
                                <tr>
                                    <th>{{__('Source')}}</th>
                                    <th width="250px">{{__('Action')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($sources as $source)
                                <tr>
                                    <td>{{ $source->name }}</td>
                                    <td class="Active ">
                                        <span>
                                        @permission('source edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a data-size="md" data-url="{{ URL::to('sources/'.$source->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Source')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Source')}}" ><i class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endpermission
                                            @permission('source delete')
                                               <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['sources.destroy', $source->id]]) !!}
                                                        <a href="#!" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                           <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                    {!! Form::close() !!}
                                                </div>
                                            @endif
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                @include('layouts.nodatafound')
                            @endforelse

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
