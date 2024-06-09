@extends('layouts.main')
@push('title')
    <h4 class="m-b-10">{{ __('Inquiry View') }}</h4>
@endpush
@push('breadcrumb')
    <li class="breadcrumb-item"><a href="index.html">{{ __('Home') }}</a></li>
    <li class="breadcrumb-item">{{ __('Inquiry View') }}</li>
@endpush

@push('action-btn')
    <div class="float-end">
        <div class="btn-group  me-2">
            <button class="btn btn-warning dropdown-toggle btn-sm " type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti ti-bolt"> </i>Action</button>
            <div class="dropdown-menu" style="">
                <a class="dropdown-item" href="#">Add billing details</a>
                <a class="dropdown-item" href="#">Send guest portal link</a>
                <a class="dropdown-item" href="#">Mark for follow-up</a>
                <a class="dropdown-item" href="#">Mark as paid</a>
                <a class="dropdown-item" href="#">Mark as unread</a>
                <a class="dropdown-item" href="#">Mark as checked out</a>
                <a class="dropdown-item" href="#">Archive</a>
                <a class="dropdown-item" href="#">Manage Tags</a>
                <a class="dropdown-item" href="#">Print</a>
                <a class="dropdown-item" href="#">Clone</a>
                <a class="dropdown-item" href="#">Delete</a>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-danger"><i class="ti ti-ban"> </i> {{ __('Cancel Booking') }}</button>
    </div>
@endpush

@section('contant')

<div class="col-xl-4">

    <div class="card">
        <div class="card-body">
            <div class="card bg-primary text-white text-center">
                <div class="card-body">
                    <img src="{{ asset('assets/images/user/avatar-2.jpg') }}" alt="user-image" class="img-fluid rounded-circle">
                    <h4 class="text-white mt-2">Jon Cena</h4>
                    <small>Co-Partner @ RI Dashboard</small>
                </div>
            </div>
            <div class="accordion accordion-flush" id="accordionFlushExample">

                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingOne">
                        <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="true" aria-controls="flush-collapseOne">
                            <i class="ti ti-user"> </i> Guest Details
                        </button>
                    </h2>
                    <div id="flush-collapseOne" class="accordion-collapse collapse show" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">

                            {{ Form::open(['url' => 'coupons','method' =>'post']) }}
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            {{Form::label('name',__('Guest Name'),['class'=>'form-label'])}}
                                            {{Form::text('name',null,['class'=>'form-control font-style','required'=>'required'])}}
                                        </div>

                                        <div class="form-group col-md-12">
                                            {{Form::label('email',__('Email'),['class'=>'form-label'])}}
                                            {{Form::text('email',null,['class'=>'form-control font-style','required'=>'required'])}}
                                        </div>

                                        <div class="form-group col-md-12">
                                            {{Form::label('telephone',__('Telephone'),['class'=>'form-label'])}}
                                            {{Form::text('telephone',null,['class'=>'form-control font-style','required'=>'required'])}}
                                        </div>

                                        <div class="form-group col-md-12">
                                            {{Form::label('Birthday',__('Birthday'),['class'=>'form-label'])}}
                                            {{Form::date('Birthday',null,['class'=>'form-control font-style','required'=>'required'])}}
                                        </div>
                                    </div>
                                </div>
                            {{ Form::close() }}

                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header" id="flush-headingTwo">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="true" aria-controls="flush-collapseTwo">
                            <i class="ti ti-calendar-event"> </i> Booking Details
                        </button>
                    </h2>
                    <div id="flush-collapseTwo" class="accordion-collapse collapse show" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                        <div class="accordion-body">
                            {{ Form::open(['url' => 'coupons','method' =>'post']) }}
                                <div class="modal-body">
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            {{Form::label('address',__('Address'),['class'=>'form-label'])}}
                                            {{Form::text('address',null,['class'=>'form-control font-style','required'=>'required'])}}
                                        </div>

                                        <div class="form-group col-md-12">
                                            {{Form::label('city',__('City'),['class'=>'form-label'])}}
                                            {{Form::text('city',null,['class'=>'form-control font-style','required'=>'required'])}}
                                        </div>

                                        <div class="form-group col-md-12">
                                            {{Form::label('country',__('Country'),['class'=>'form-label'])}}
                                            {{Form::select('country',[] , null,['class'=>'form-control font-style','required'=>'required'])}}
                                        </div>

                                        <div class="form-group col-md-12">
                                            {{Form::label('state',__('State/Province'),['class'=>'form-label'])}}
                                            {{Form::text('state',null,['class'=>'form-control font-style','required'=>'required'])}}
                                        </div>

                                        <div class="form-group col-md-12">
                                            {{Form::label('email',__('Email'),['class'=>'form-label'])}}
                                            {{Form::email('email',null,['class'=>'form-control font-style','required'=>'required'])}}
                                        </div>


                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <input type="button" value="{{__('Cancel')}}" class="btn  btn-light" data-bs-dismiss="modal">
                                    <input type="submit" value="{{__('Create')}}" class="btn  btn-primary">
                                </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>





            </div>
        </div>
    </div>
</div>

<div class="col-xl-8">
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active text-uppercase" id="messages-tab" data-bs-toggle="tab" href="#messages" role="tab" aria-controls="messages" aria-selected="true"><i class="ti ti-mail"> </i>{{ __("Messages") }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-uppercase" id="notes-tab" data-bs-toggle="tab" href="#notes" role="tab" aria-controls="notes" aria-selected="false"><i class="ti ti-note"> </i>{{ __('Notes') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-uppercase" id="files-tab" data-bs-toggle="tab" href="#files" role="tab" aria-controls="files" aria-selected="false"><i class="ti ti-file-text"> </i>{{ __('Files') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-uppercase" id="interactions-tab" data-bs-toggle="tab" href="#interactions" role="tab" aria-controls="interactions" aria-selected="false"><i class="ti ti-brand-hipchat"> </i>{{ __('Interactions') }}</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-uppercase" id="bookings-tab" data-bs-toggle="tab" href="#bookings" role="tab" aria-controls="bookings" aria-selected="false"><i class="ti ti-calendar"> </i>{{ __('Bookings') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-uppercase" id="attrbutes-tab" data-bs-toggle="tab" href="#attrbutes" role="tab" aria-controls="attrbutes" aria-selected="false"><i class="ti ti-tag"> </i>{{ __('Attrbutes') }}</a>
                </li>

            </ul>


            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="messages" role="tabpanel" aria-labelledby="messages-tab">
                    <center>
                        <p class="">No Messages</p>
                    </center>
                </div>

                <div class="tab-pane fade" id="notes" role="tabpanel" aria-labelledby="notes-tab">
                    <center>
                        <p class="">No Notes</p>
                    </center>
                </div>

                <div class="tab-pane fade" id="files" role="tabpanel" aria-labelledby="files-tab">
                    <div class="text-end mb-2">
                        <a href="#" data-size="md" data-url="{{ route('guests.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Guests')}}" class="btn btn-sm btn-primary">
                            <i class="ti ti-plus"></i>
                        </a>
                    </div>
                    <div class="table-responsive">


                        <table class="table datatable" id="datatable1">
                            <thead>
                                <tr>
                                    <th>Uploaded Date</th>
                                    <th>User</th>
                                    <th>Name</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Unity Pugh</td>
                                    <td>9958</td>
                                    <td>Curicó</td>

                                    <td>
                                        <div class="action-btn bg-light-warning ms-2">
                                            <a href="{{ route('inquiry.edit',1) }}" class="btn btn-sm btn-warning action-btn"
                                                title="{{ __('View') }}">
                                                <i class="ti ti-eye"></i>
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

                <div class="tab-pane fade" id="interactions" role="tabpanel" aria-labelledby="interactions-tab">
                    <div class="text-end mb-2">
                        <a href="#" data-size="md" data-url="{{ route('guests.create') }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Guests')}}" class="btn btn-sm btn-primary">
                            <i class="ti ti-plus"></i>
                        </a>
                    </div>
                    <div class="table-responsive">
                        <table class="table datatable" id="datatable2">
                            <thead>
                                <tr>
                                    <th>Flight No</th>
                                    <th>Airline</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Timezone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Unity Pugh</td>
                                    <td>9958</td>
                                    <td>Curicó</td>
                                    <td>2005/02/11</td>
                                    <td>2005/02/11</td>

                                    <td>
                                        <div class="action-btn bg-light-warning ms-2">
                                            <a href="{{ route('inquiry.edit',1) }}" class="btn btn-sm btn-warning action-btn"
                                                title="{{ __('View') }}">
                                                <i class="ti ti-eye"></i>
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

                <div class="tab-pane fade" id="bookings" role="tabpanel" aria-labelledby="bookings-tab">
                    <div class="table-responsive">
                        <table class="table datatable" id="datatable4">
                            <thead>
                                <tr>
                                    <th>Source</th>
                                    <th>Updated</th>
                                    <th>Arrive</th>
                                    <th>Depart</th>
                                    <th>Nights</th>
                                    <th>Guests</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Unity Pugh</td>
                                    <td>9958</td>
                                    <td>Curicó</td>
                                    <td>Curicó</td>
                                    <td>Curicó</td>
                                    <td>Curicó</td>
                                    <td>Curicó</td>

                                    <td>
                                        <div class="action-btn bg-light-warning ms-2">
                                            <a href="{{ route('inquiry.edit',1) }}" class="btn btn-sm btn-warning action-btn"
                                                title="{{ __('View') }}">
                                                <i class="ti ti-eye"></i>
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

                <div class="tab-pane fade" id="attrbutes" role="tabpanel" aria-labelledby="attrbutes-tab">
                    <center>
                        <p class="">No other bookings</p>
                    </center>
                </div>


            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')

@endpush
