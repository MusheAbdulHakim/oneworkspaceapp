@extends('layouts.main')
@section('page-title')
    {{ __('Manage Holiday') }}
@endsection
@section('page-breadcrumb')
{{ __('Holiday') }}
@endsection
@section('page-action')
<div>
    @permission('holiday import')
        <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Holiday Import')}}" data-url="{{ route('holiday.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
        </a>
    @endpermission
    <a href="{{ route('holiday.calender') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
        data-bs-original-title="{{ __('Calendar View') }}">
        <i class="ti ti-calendar"></i>
    </a>
    @permission('holiday create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Holiday') }}" data-url="{{route('holiday.create')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
</div>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12 col-lg-12 col-xl-12 col-md-12">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['holiday.index'], 'method' => 'get', 'id' => 'holiday_filter']) }}
                    <div class="d-flex align-items-center justify-content-end">
                        <div class=" col-xl-3 col-lg-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('start_date', __('Start Date'), ['class' => 'form-label']) }}
                                {{ Form::date('start_date',isset($_GET['start_date']) ? $_GET['start_date'] : '', ['class' => 'form-control ','placeholder'=>"Select Date"]) }}
                            </div>
                        </div>
                        <div class=" col-xl-3 col-lg-12 col-12 mx-2">
                            <div class="btn-box">
                                {{ Form::label('end_date', __('End Date'), ['class' => 'form-label']) }}
                                {{ Form::date('end_date',isset($_GET['end_date']) ? $_GET['end_date'] : '', ['class' => 'form-control ','placeholder'=>"Select Date"]) }}
                            </div>
                        </div>
                        <div class="col-auto float-end ms-2 mt-4">
                            <a  class="btn btn-sm btn-primary"
                                onclick="document.getElementById('holiday_filter').submit(); return false;"
                                data-bs-toggle="tooltip" title="" data-bs-original-title="apply">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="{{ route('holiday.index') }}" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                title="" data-bs-original-title="Reset">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                            </a>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>

    <div class="col-sm-12">
        <div class="card">
            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                <th>{{ __('Occasion') }}</th>
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                @if (Laratrust::hasPermission('holiday edit') || Laratrust::hasPermission('holiday delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($holidays as $holiday)
                            <tr>

                                <td>{{ $holiday->occasion }}</td>
                                <td>{{ company_date_formate($holiday->start_date) }}</td>
                                <td>{{ company_date_formate($holiday->end_date) }}</td>
                                @if (Laratrust::hasPermission('holiday edit') || Laratrust::hasPermission('holiday delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('holiday edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('holiday/' . $holiday->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Holiday') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission

                                            @permission('holiday delete')
                                            <div class="action-btn bg-danger ms-2">
                                                    {{Form::open(array('route'=>array('holiday.destroy', $holiday->id),'class' => 'm-0'))}}
                                                    @method('DELETE')
                                                        <a
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$holiday->id}}"><i
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

