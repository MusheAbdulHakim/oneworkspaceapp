@extends('layouts.main')
@section('page-title')
    {{ __('Manage Trip') }}
@endsection
@section('page-breadcrumb')
{{ __('Trip') }}
@endsection
@section('page-action')
<div>
    @permission('travel create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Trip') }}" data-url="{{route('trip.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
</div>
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
                                @if (in_array(\Auth::user()->type, \Auth::user()->not_emp_type))
                                    <th>{{ __('Employee') }}</th>
                                @endif
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Purpose of Trip') }}</th>
                                <th>{{ __('Country') }}</th>
                                <th>{{ __('Description') }}</th>
                                @if (Laratrust::hasPermission('travel edit') || Laratrust::hasPermission('travel delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($travels as $travel)
                            <tr>
                                @if (in_array(\Auth::user()->type, \Auth::user()->not_emp_type))
                                    <td>{{ !empty( $travel->user_id) ? $travel->users->name : '--' }}</td>
                                @endif
                                <td>{{ company_date_formate($travel->start_date) }}</td>
                                <td>{{ company_date_formate($travel->end_date) }}</td>
                                <td>{{ $travel->purpose_of_visit }}</td>
                                <td>{{ $travel->place_of_visit }}</td>
                                <td>
                                    <p style="white-space: nowrap;
                                        width: 200px;
                                        overflow: hidden;
                                        text-overflow: ellipsis;">{{  !empty($travel->description) ? $travel->description : '' }}
                                    </p>
                                </td>
                                @if (Laratrust::hasPermission('travel edit') || Laratrust::hasPermission('travel delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('travel edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('trip/' . $travel->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Trip') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission

                                            @permission('travel delete')
                                            <div class="action-btn bg-danger ms-2">
                                                    {{Form::open(array('route'=>array('trip.destroy', $travel->id),'class' => 'm-0'))}}
                                                    @method('DELETE')
                                                        <a 
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$travel->id}}"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                    {{Form::close()}}
                                                </div>
                                            @endpermission

                                        </span>
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

