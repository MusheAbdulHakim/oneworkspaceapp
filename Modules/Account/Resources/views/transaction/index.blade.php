@extends('layouts.main')
@section('page-title')
    {{ __('Transaction Summary') }}
@endsection
@section('page-breadcrumb')
    {{ __('Report') }},
    {{ __('Transaction Summary') }}
@endsection
@push('scripts')
    <script src="{{ asset('Modules/Account/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();
        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A4'}
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>
@endpush
@section('page-action')
    <div>
        @stack('addButtonHook')
        @permission('bank account create')
            <a  class="btn btn-sm btn-primary" onclick="saveAsPDF()"  data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Download') }}">
                <i class="ti ti-download"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class=" multi-collapse mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('transaction.index'),'method'=>'get','id'=>'transaction_report')) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        {{ Form::label('start_month', __('Start Month'), ['class' => 'form-label']) }}
                                        {{Form::month('start_month',isset($_GET['start_month'])?$_GET['start_month']:'',array('class'=>'month-btn form-control','placeholder'=>'Start Month'))}}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        {{ Form::label('end_month', __('End Month'), ['class' => 'form-label']) }}
                                        {{Form::month('end_month',isset($_GET['end_month'])?$_GET['end_month']:'',array('class'=>'month-btn form-control','placeholder'=>'Start Month'))}}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        {{ Form::label('account', __('Account'), ['class' => 'form-label']) }}
                                            {{ Form::select('account', $account,isset($_GET['account'])?$_GET['account']:'', array('class' => 'form-control ','placeholder' => 'Select Account')) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                        {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}
                                            {{ Form::select('category', $category,isset($_GET['category'])?$_GET['category']:'', array('class' => 'form-control ','placeholder' => 'Select Category')) }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto float-end ms-2 mt-4">
                                        <a  class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('transaction_report').submit(); return false;"
                                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('transaction.index') }}" class="btn btn-sm btn-danger"
                                            data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                            data-original-title="{{ __('Reset') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
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
        <div class="row">
            <div class="col">
                <input type="hidden" value="{{$filter['category'].' '.__('Category').' '.__('Transaction').' '.'Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange']}}" id="filename">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Report')}} :</h5>
                    <h6 class="report-text mb-0">{{__('Transaction Summary')}}</h6>
                </div>
            </div>
            @if($filter['account']!= __('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Account')}} :</h5>
                        <h6 class="report-text mb-0">{{$filter['account']}}</h6>
                    </div>
                </div>
            @endif
            @if($filter['category']!= __('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Category')}} :</h5>
                        <h6 class="report-text mb-0">{{$filter['category']}}</h6>
                    </div>
                </div>
            @endif
            <div class="col">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Duration')}} :</h5>
                    <h6 class="report-text mb-0">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h6>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($accounts as $account)
                <div class="col-xl-3 col-md-6 col-lg-3">
                    <div class="card p-4 mb-4">
                        @if($account->holder_name =='Cash')
                            <h6 class="report-text gray-text mb-0">{{$account->holder_name}}</h6>
                        @elseif(empty($account->holder_name))
                            <h6 class="report-text gray-text mb-0">{{__('Stripe / Paypal')}}</h6>
                        @else
                            <h6 class="report-text gray-text mb-0">{{$account->holder_name.' - '.$account->bank_name}}</h6>
                        @endif
                        <h5 class="report-text mb-0">{{ currency_format_with_sym($account->total)}}</h5>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Account')}}</th>
                                    <th>{{__('Type')}}</th>
                                    <th>{{__('Category')}}</th>
                                    <th>{{__('Description')}}</th>
                                    <th>{{__('Amount')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ company_date_formate($transaction->date)}}</td>
                                        <td>
                                            @if(!empty($transaction) && $transaction->holder_name=='Cash')
                                                {{$transaction->holder_name}}
                                            @else
                                                {{!empty($transaction)?$transaction->bank_name.' '.$transaction->holder_name:'-'}}
                                            @endif
                                        </td>
                                        <td>{{  $transaction->type}}</td>
                                        <td>{{  $transaction->category}}</td>
                                        <td>{{  !empty($transaction->description)?$transaction->description:'-'}}</td>
                                        <td>{{currency_format_with_sym($transaction->amount)}}</td>
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
