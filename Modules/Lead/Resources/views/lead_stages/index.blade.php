@extends('layouts.main')
@section('page-title')
    {{__('Manage Lead Stages')}}
@endsection
@push('scripts')
    <script src="{{ asset('Modules/Lead/Resources/assets/js/jquery-ui.min.js')}}"></script>

    @if (\Auth::user()->type == 'company')
        <script>
           $(document).ready(function () {
            var $dragAndDrop = $("body .lead-stages tbody").sortable({
                handle: '.sort-handler'
                });

                myFunction();
            });
            function myFunction(){
                $(".lead-stages").sortable({
                    stop: function() {
                        var order = [];
                        $(this).find('tr').each(function(index, data) {
                            order[index] = $(data).attr('data-id');
                        });
                        $.ajax({
                            url: "{{ route('lead_stages.order') }}",
                            data: {
                                order: order,
                                _token: $('meta[name="csrf-token"]').attr('content')
                            },
                            type: 'POST',
                            success: function(data) {},
                            error: function(data) {
                                data = data.responseJSON;
                            }
                        })
                    }
                });
            }
        </script>
    @endif
@endpush
@section('page-breadcrumb')
   {{__('Setup')}},
   {{__('Lead Stages')}}
@endsection
@section('page-action')
    <div class="">
        @permission('leadstages create')
                <a class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Create Lead Stage')}}" data-ajax-popup="true" data-size="md" data-title="{{__('Create Lead Stage')}}" data-url="{{route('lead-stages.create')}}"><i class="ti ti-plus text-white"></i></a>
        @endpermission
    </div>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-3">
        @include('lead::layouts.system_setup')
    </div>
        <div class="col-sm-9">
            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                @php($i=0)
                @foreach($pipelines as $key => $pipeline)
                    <li class="nav-item">
                        <a class="nav-link @if($i==0) active @endif" id="pills-home-tab" data-bs-toggle="pill" href="#tab{{$key}}" role="tab" aria-controls="pills-home" aria-selected="true">{{$pipeline['name']}}</a>
                    </li>
                    @php($i++)
                @endforeach
            </ul>
            <div class="card">
                <div class="card-header">
                    <h5 class="">
                        {{ __('Lead Stages') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="tab-content tab-bordered">
                        @php($i=0)
                            @foreach($pipelines as $key => $pipeline)
                            <div class="tab-pane fade show @if($i==0) active @endif" id="tab{{$key}}" role="tabpanel">
                            <table class="table" data-repeater-list="stages">
                                <thead>
                                    <th><i class="fas fa-crosshairs"></i></th>
                                    <th>{{ __('Name') }}</th>
                                    <th class="d-flex justify-content-end">{{ __('Action') }}</th>
                                </thead>
                                <tbody class="lead-stages">
                                    @foreach ($pipeline['lead_stages'] as $lead_stages)
                                    <tr data-id="{{$lead_stages->id}}">
                                        <td><i class="fas fa-crosshairs sort-handler"></i></td>
                                        <td>{{$lead_stages->name}}</td>
                                        <td class="d-flex justify-content-end">
                                            @permission('leadstages edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a data-size="md" data-url="{{ route('lead-stages.edit',$lead_stages->id) }}" data-ajax-popup="true" data-title="{{__('Edit Lead Stages')}}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Edit Lead Stages')}}" ><i class="ti ti-pencil text-white"></i></a>
                                                </div>
                                            @endpermission
                                            @if(count($pipeline['lead_stages']))
                                                @permission('leadstages delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['lead-stages.destroy', $lead_stages->id]]) !!}
                                                            <a href="#!" class="mx-3 btn btn-sm d-inline-flex align-items-center show_confirm" data-bs-toggle="tooltip" data-bs-placement="top" title="{{__('Delete')}}">
                                                                <span class="text-white"> <i class="ti ti-trash"></i></span></a>
                                                        {!! Form::close() !!}
                                                    </div>
                                                @endpermission
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @php($i++)
                    @endforeach
                    </div>
                </div>
            </div>
            <div class="alert alert-dark" role="alert">
                {{__('Note : You can easily change order of Lead stage using drag & drop.')}}
            </div>
</div>
@endsection
