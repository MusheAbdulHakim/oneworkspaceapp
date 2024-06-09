@extends('layouts.main')
@push('title')
    <h4 class="m-b-10">{{ __('Guests') }}</h4>
@endpush
@push('breadcrumb')
    <li class="breadcrumb-item"><a href="index.html">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Guests') }}</li>
@endpush

@push('action-btn')
    <div class="float-end">

        <a href="#" data-size="md" data-url="{{ route('guests.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Guests')}}" class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>



    </div>
@endpush

@section('contant')

    <div class="col-xl-12">
        <div class="card">

            <div class="card-body table-border-style">
                <div class="table-responsive">
                    <table class="table datatable" id="datatable1">
                        <thead>
                            <tr>
                                <th>Guest Name</th>
                                <th>Email</th>
                                <th>Telephone</th>
                                <th>Country</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Unity Pugh</td>
                                <td>9958</td>
                                <td>Curicó</td>
                                <td>2005/02/11</td>
                                <td>
                                    <div class="action-btn bg-light-warning ms-2">
                                        <a href={{ route('guests.edit',1) }}  data-ajax-popup="fulla" data-pc-animate="slide-in-right" data-bs-toggle="tooltip" data-title="{{__('Create New Rentals')}}" data-url="{{ route('guests.edit',1) }}" class="btn btn-sm btn-primary action-btn">
                                            <i class="ti ti-pencil"></i>
                                        </a>

                                    </div>
                                    <div class="action-btn bg-light-danger ms-2">
                                        <a href="#" type="button" data-bs-placement="top" data-bs-toggle="tooltip"
                                            class="btn btn-sm btn-danger action-btn bs-pass-para"
                                            data-confirm="{{ __('Are You Sure?') }}" title="{{ __('Cancel Job') }}"
                                            data-text="{{ __('This action can not be undone. Do you want to continue?') }}">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
