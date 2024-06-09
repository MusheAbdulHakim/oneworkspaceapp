@extends('layouts.main')

@section('page-title')
    {{__('Manage Deals')}} @if($pipeline) - {{$pipeline->name}} @endif
@endsection

@push('css')
<link rel="stylesheet" href="{{asset('assets/css/plugins/dragula.min.css')}}">
<link rel="stylesheet" href="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.css')}}">
<style>
    .comp-card{
            height: 140px;
        }
</style>
@endpush
@push('scripts')
<script src="{{ asset('assets/js/plugins/summernote-0.8.18-dist/summernote-lite.min.js')}}"></script>
    <script>
        $(document).on("change", "#change-pipeline select[name=default_pipeline_id]", function () {
            $('#change-pipeline').submit();
        });
    </script>
@endpush

@section('page-breadcrumb')
    {{__('Deals')}}
@endsection

@section('page-action')
    @if($pipeline)
        <div class="col-auto">
            {{ Form::open(array('route' => 'deals.change.pipeline','id'=>'change-pipeline')) }}
            {{ Form::select('default_pipeline_id', $pipelines,$pipeline->id, array('class' => 'form-control mx-2 custom-form-select','id'=>'default_pipeline_id')) }}
            {{ Form::close() }}
        </div>
    @endif
    <div class="col-auto pe-0 pt-2 px-1">
        @stack('addButtonHook')
    </div>
    @permission('deal import')
        <div class="col-auto pe-0 pt-2 px-1">
            <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Deal Import')}}" data-url="{{ route('deal.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
            </a>
        </div>
    @endpermission
    <div class="col-auto pe-0 pt-2 px-1">
        <a href="{{ route('deals.index') }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Kanban View')}}" class="btn btn-sm btn-primary btn-icon"><i class="ti ti-table"></i> </a>
    </div>
    @permission('deal create')
    <div class="col-auto ps-1 pt-2">
            <a class="btn btn-sm btn-primary btn-icon col-auto" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Deal')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Create Deal')}}" data-url="{{route('deals.create')}}"><i class="ti ti-plus text-white"></i></a>
    </div>
    @endpermission
@endsection
@section('content')
    @if($pipeline)
        <div class="row">
            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('Total Deals')}}</h6>
                                <h3 class="text-primary">{{ $cnt_deal['total'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-rocket bg-success text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('This Month Total Deals')}}</h6>
                                <h3 class="text-info">{{ $cnt_deal['this_month'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-rocket bg-info text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('This Week Total Deals')}}</h6>
                                <h3 class="text-warning">{{ $cnt_deal['this_week'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-rocket bg-warning text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-6">
                <div class="card comp-card">
                    <div class="card-body">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-b-20">{{__('Last 30 Days Total Deals')}}</h6>
                                <h3 class="text-danger">{{ $cnt_deal['last_30days'] }}</h3>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-rocket bg-danger text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0 pc-dt-simple" id="deAl">
                                <thead>
                                    <tr>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Price')}}</th>
                                        <th>{{__('Stage')}}</th>
                                        <th>{{__('Tasks')}}</th>
                                        <th>{{__('Users')}}</th>
                                        @if(Laratrust::hasPermission('deal edit') ||  Laratrust::hasPermission('deal delete'))
                                            <th width="10%">{{__('Action')}}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($deals) > 0)
                                        @foreach ($deals as $deal)
                                            <tr>
                                                <td>{{ $deal->name }}</td>
                                                <td>{{ currency_format_with_sym($deal->price)}}</td>
                                                <td>{{ $deal->stage->name }}</td>
                                                <td>{{count($deal->tasks)}}/{{count($deal->complete_tasks)}}</td>
                                                <td>
                                                    @foreach($deal->users as $user)
                                                    <a href="#" class="btn btn-sm mr-1 p-0 rounded-circle">
                                                        <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top" title="{{$user->name}}" @if($user->avatar) src="{{get_file($user->avatar)}}" @else src="{{ get_file('uploads/users-avatar/avatar.png')}}" @endif class="rounded-circle " width="25" height="25">
                                                    </a>
                                                    @endforeach
                                                </td>
                                                @if(Laratrust::hasPermission('deal edit') ||  Laratrust::hasPermission('deal delete'))
                                                    <td class="Action">
                                                        <span>
                                                            @permission('deal show')
                                                                @if($deal->is_active)
                                                                        <div class="action-btn bg-warning ms-2">
                                                                        <a href="{{route('deals.show',$deal->id)}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-toggle="tooltip" data-original-title="{{__('View Deal')}}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('View Deal')}}"><i class="ti ti-eye text-white"></i></a>
                                                                    </div>
                                                                @endif
                                                            @endpermission
                                                            @permission('deal edit')

                                                                <div class="action-btn bg-info ms-2">
                                                                    <a data-size="lg" data-url="{{ URL::to('deals/'.$deal->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Deal')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Deal')}}" ><i class="ti ti-pencil text-white"></i></a>
                                                                </div>
                                                            @endpermission
                                                            @permission('deal delete')
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['deals.destroy', $deal->id]]) !!}
                                                                        <a href="#!" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete Deal')}}">
                                                                            <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                                    {!! Form::close() !!}
                                                                </div>
                                                            @endpermission
                                                        </span>
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr class="font-style">
                                            <td colspan="6" class="text-center">{{ __('No data available in table') }}</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection
