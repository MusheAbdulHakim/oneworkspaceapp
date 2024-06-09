@extends('layouts.main')
@section('page-title')
    {{__('Manage Projects')}}
@endsection
@section('page-breadcrumb')
   {{__('Manage Projects')}}
@endsection
@section('page-action')
<div>
    @permission('project import')
        <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Project Import')}}" data-url="{{ route('project.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i> </a>
    @endpermission
    <a href="{{ route('projects.index') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
        <i class="ti ti-layout-grid text-white"></i>
    </a>

    @permission('project create')
        <a class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
            data-title="{{ __('Create New Project') }}" data-url="{{ route('projects.create') }}" data-toggle="tooltip"
            title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
</div>
@endsection
@section('filter')
@endsection

@section('content')

<div class="row">
    <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive overflow_hidden">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Stage')}}</th>
                                    <th>{{__('Assigned User')}}</th>
                                    @if(Laratrust::hasPermission('project show') || Laratrust::hasPermission('project edit') || Laratrust::hasPermission('project delete'))
                                        <th scope="col" class="text-end">{{__('Action')}}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $project)
                                    <tr>
                                        <td>
                                            <h5 class="mb-0">
                                                @if ($project->is_active)
                                                    <a href="@permission('project manage') {{ route('projects.show', [$project->id]) }} @endpermission"
                                                        title="{{ $project->name }}" class="">{{ $project->name }}</a>
                                                @else
                                                    <a href="#" title="{{ __('Locked') }}"
                                                        class="">{{ $project->name }}</a>
                                                @endif
                                            </h5>
                                        </td>
                                        <td>{{ $project->status }}</td>
                                        <td>
                                            @foreach ($project->users as $user)
                                                @if ($user->pivot->is_active)
                                                    <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="{{ $user->name }}"
                                                        @if ($user->avatar) src="{{ get_file($user->avatar) }}" @else src="{{ get_file('avatar.png') }}" @endif
                                                        class="rounded-circle " width="25" height="25">
                                                @endif
                                            @endforeach
                                        </td>
                                        @if(Laratrust::hasPermission('project show') || Laratrust::hasPermission('project edit') || Laratrust::hasPermission('project delete'))
                                            <td class="text-end">
                                                @if (module_is_active('ProjectTemplate'))
                                                    @permission('project template create')
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a data-size="md" data-url="{{ route('project-template.create',['project_id'=>$project->id,'type'=>'template']) }}"  class="btn btn-sm d-inline-flex align-items-center text-white " data-ajax-popup="true" data-bs-toggle="tooltip" data-title="{{__('Save as template')}}" title="{{__('Save as template')}}"><i class="ti ti-bookmark"></i></a>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                @endif
                                                @permission('project create')
                                                    <div class="action-btn bg-secondary ms-2">
                                                        <a data-size="md" data-url="{{ route('project.copy', [$project->id]) }}"  class="btn btn-sm d-inline-flex align-items-center text-white " data-ajax-popup="true" data-bs-toggle="tooltip" data-title="{{__('Duplicate Project')}}" title="{{__('Duplicate')}}"><i class="ti ti-copy"></i></a>
                                                    </div>
                                                @endpermission
                                                @permission('project show')
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{ route('projects.show',$project->id) }}" data-bs-toggle="tooltip" title="{{__('Details')}}"  data-title="{{__('Project Details')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center text-white ">
                                                        <i class="ti ti-eye"></i>
                                                    </a>
                                                </div>
                                                @endpermission
                                                @permission('project edit')
                                                  <div class="action-btn bg-info ms-2">
                                                    <a data-size="md" data-url="{{ route('projects.edit',$project->id) }}"  class="btn btn-sm d-inline-flex align-items-center text-white " data-ajax-popup="true" data-bs-toggle="tooltip" data-title="{{__('Project Edit')}}" title="{{__('Edit')}}"><i class="ti ti-pencil"></i></a>
                                                </div>
                                                @endpermission
                                                @permission('project delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['projects.destroy', $project->id]]) !!}
                                                    <a href="#!" class="btn btn-sm   align-items-center text-white show_confirm" data-bs-toggle="tooltip" title='Delete'>
                                                        <i class="ti ti-trash"></i>
                                                    </a>
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
