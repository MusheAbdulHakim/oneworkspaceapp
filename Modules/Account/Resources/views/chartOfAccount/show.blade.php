@extends('layouts.main')
@section('page-title')
    {{__('Account Drilldown Report')}}
@endsection
@section('page-breadcrumb')
    {{__('Chart of Account')}},
    {{__('Account Drilldown Report')}},
    {{ucwords($account->code. ' - ' .$account->name)}}
@endsection


@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('chart-of-account.show',$account->id),'method' => 'GET','id'=>'report_drilldown')) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('start_date', __('Start Date'),['class'=>'form-label']) }}
                                            {{ Form::date('start_date',$filter['startDateRange'], array('class' => 'month-btn form-control')) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('end_date', __('End Date'),['class'=>'form-label']) }}
                                            {{ Form::date('end_date',$filter['endDateRange'], array('class' => 'month-btn form-control')) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('account', __('Account'),['class'=>'form-label']) }}
                                            {{ Form::select('account',$accounts,isset($_GET['account'])?$_GET['account']:'', array('class' => 'form-control select')) }}                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto mt-4">
                                        <a href="#" class="btn btn-sm btn-primary" onclick="document.getElementById('report_drilldown').submit(); return false;" data-bs-toggle="tooltip" title="{{__('Apply')}}" data-original-title="{{__('apply')}}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{route('chart-of-account.show',$account->id)}}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="{{ __('Reset') }}" data-original-title="{{__('Reset')}}">
                                            <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off"></i></span>
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



    <div id="printableArea">
        <div class="row mt-2">
            <div class="col-3">
                <div class="card p-4 mb-4">
                    <h6 class="mb-0">{{__('Report')}} :</h6>
                    <h7 class="text-sm mb-0">{{__('Account Drilldown')}}</h7>
                </div>
            </div>

            @if(!empty($account))
                <div class="col-3">
                    <div class="card p-4 mb-4">
                        <h6 class="mb-0">{{__('Account Name')}} :</h6>
                        <h7 class="text-sm mb-0">{{$account->name}}</h7>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card p-4 mb-4">
                        <h6 class="mb-0">{{__('Account Code')}} :</h6>
                        <h7 class="text-sm mb-0">{{$account->code}}</h7>
                    </div>
                </div>
            @endif

            <div class="col-3">
                <div class="card p-4 mb-4">
                    <h6 class="mb-0">{{__('Duration')}} :</h6>
                    <h7 class="text-sm mb-0">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h7>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 mb-4">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th> {{__('Account Name')}}</th>
                                    <th> {{__('Name')}}</th>
                                    <th> {{__('Transaction Type')}}</th>
                                    <th> {{__('Transaction Date')}}</th>
                                    <th> {{__('Debit')}}</th>
                                    <th> {{__('Credit')}}</th>
                                    <th> {{__('Balance')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $balance     = 0;
                                    $totalDebit  = 0;
                                    $totalCredit = 0;
                                    $chartDatas  = \Modules\Account\Entities\AccountUtility::getAccountData($account->id,$filter['startDateRange'],$filter['endDateRange']);
                                    $accountName = \Modules\Account\Entities\ChartOfAccount::find($account->id);
                                @endphp
                                @php
                                    $debitTotal = 0;
                                    $creditTotal = 0;
                                @endphp

                                @foreach ($chartDatas as $transaction)
                                    @php
                                        $account = \Modules\Account\Entities\ChartOfAccount::find($transaction->account_id);
                                        $debit = -$transaction->debit;
                                        $credit = $transaction->credit;
                                        $debitTotal += $debit;
                                        $creditTotal += $credit;
                                        $balance = $debitTotal + $creditTotal;
                                    @endphp

                                    <tr>
                                        <td>{{$accountName->name}}</td>
                                        <td>{{ !empty($transaction->user_name) ? $transaction->user_name : '-'}}</td>
                                        <td>{{$transaction->reference}}</td>
                                        <td>{{$transaction->date}}</td>
                                        <td>{{!empty($transaction->debit) ? $transaction->debit :'-'}}</td>
                                        <td>{{!empty($transaction->credit) ? $transaction->credit :'-'}}</td>
                                        <td>{{$balance}}</td>
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
