@extends('layouts.main')
@section('page-title')
    {{ __('Project Report') }}
@endsection
@section('page-breadcrumb')
    {{ __('Project Report') }}
@endsection
@section('page-action')
    <div>
        <a href="#" class="btn btn-sm btn-primary filter" data-toggle="tooltip" title="{{ __('Filter') }}">
            <i class="ti ti-filter"></i>
        </a>
    </div>
@endsection
@php

    $client_keyword = Auth::user()->hasRole('client') ? 'client.' : '';
@endphp

@section('content')
    {{ Form::open(['route' => ['project_report.index'], 'method' => 'GET', 'id' => 'product_service']) }}

    <div class="row pt-4 display-none" id="show_filter">

        @if (Auth::user()->hasRole('company') || Auth::user()->hasRole('client'))
            <div class="col-2">
                <select class="select2 form-select" name="all_users" id="all_users">
                    <option value="" class="px-4">{{ __('All Users') }}</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-2">
            <select class="select2 form-select" name="status" id="status">
                <option value="" class="px-4">{{ __('All Status') }}</option>

                <option value="Ongoing">{{ __('Ongoing') }}</option>
                <option value="Finished">{{ __('Finished') }}</option>
                <option value="OnHold">{{ __('OnHold') }}</option>

            </select>
        </div>


        <div class="form-group col-md-3">
            <div class="input-group date ">
                <input class="form-control" type="date" id="start_date" name="start_date" value=""
                    autocomplete="off" placeholder="{{ __('Start Date') }}">
            </div>
        </div>
        <div class="form-group col-md-3">
            <div class="input-group date ">
                <input class="form-control" type="date" id="end_date" name="end_date" value=""
                    autocomplete="off" placeholder="{{ __('End Date') }}">
            </div>
        </div>
        <div class="col-2">

            <button type="submit" class="btn btn-primary ">{{ __('Apply') }}</button>
            <a href="{{ route('project_report.index') }}" class="btn  btn-danger" data-bs-toggle="tooltip"
                title="{{ __('Reset') }}">
                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
            </a>
        </div>
    </div>

    {{ Form::close() }}

    <div class="row">
        <div class="col-xl-12 mt-4">
            <div class="card">
                <div class="card-body table-border-style mt-3 mx-2">
                    <div class="table-responsive">
                        <table class="table datatable pc-dt-simple px-4 mt-2" id="selection-datatable1">
                            <thead>
                                <tr>
                                    <th> {{ __('#') }}</th>
                                    <th> {{ __('Project Name') }}</th>
                                    <th> {{ __('Start Date') }}</th>
                                    <th> {{ __('Due Date') }}</th>
                                    <th> {{ __('Project Member') }}</th>
                                    <th> {{ __('Progress') }}</th>
                                    <th>{{ __('Project Status') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                    <tr>
                                        <td>{{ $row['id'] }}</td>
                                        <td>{{ $row['name'] }}</td>
                                        <td>{{ $row['start_date'] }}</td>
                                        <td>{{ $row['end_date'] }}</td>
                                        <td>{!! $row['members'] !!}</td>
                                        <td>{!! $row['Progress'] !!}</td>
                                        <td>{!! $row['status'] !!}</td>
                                        <td>{!! $row['action'] !!}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>



@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Taskly/Resources/assets/css/datatables.min.css') }}">


    <style>
        table.dataTable.no-footer {
            border-bottom: none !important;
        }

        .display-none {
            display: none !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('Modules/Taskly/Resources/assets/js/jquery.dataTables.min.js') }}"></script>
      <script type="text/javascript">
        $(".filter").click(function() {
            $("#show_filter").toggleClass('display-none');
        });

    </script>
@endpush
