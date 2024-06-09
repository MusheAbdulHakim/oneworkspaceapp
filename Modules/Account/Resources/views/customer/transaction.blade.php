@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
    {{__('Transaction')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('customer.dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Transaction')}}</li>
@endsection

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class=" multi-collapse mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('customer.transaction'),'method' => 'GET','id'=>'frm_submit')) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('date', __('Date'),['class'=>'text-type']) }}
                                            {{ Form::text('date', isset($_GET['date'])?$_GET['date']:null, array('class' => 'form-control month-btn','id'=>'pc-daterangepicker-1')) }}
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('category', __('Category'),['class'=>'text-type']) }}
                                            {{ Form::select('category',  [''=>'Select Category']+$category,isset($_GET['category'])?$_GET['category']:'', array('class' => 'form-control select')) }}                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="col-auto mt-3">
                                <div class="row">
                                    <div class="col-auto">

                                        <a  class="btn btn-sm btn-primary" onclick="document.getElementById('frm_submit').submit(); return false;" data-bs-toggle="tooltip" title="{{__('Apply')}}" data-original-title="{{__('apply')}}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>

                                        <a href="{{route('customer.transaction')}}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="{{ __('Reset') }}" data-original-title="{{__('Reset')}}">
                                            <span class="btn-inner--icon"><i class="ti ti-refresh text-white-off "></i></span>
                                        </a>


                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">

                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Amount')}}</th>
                                <th> {{__('Account')}}</th>
                                <th> {{__('Type')}}</th>
                                <th> {{__('Category')}}</th>
                                <th> {{__('Description')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($transactions as $transaction)
                                <tr>
                                    <td>{{  Auth::user()->dateFormat($transaction->date)}}</td>
                                    <td>{{  Auth::user()->priceFormat($transaction->amount)}}</td>
                                    <td>{{  !empty($transaction->bankAccount())?$transaction->bankAccount()->bank_name .' '.$transaction->bankAccount()->holder_name:''}}</td>
                                    <td>{{  $transaction->type}}</td>
                                    <td>{{  $transaction->category}}</td>
                                    <td>{{  $transaction->description}}</td>
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
