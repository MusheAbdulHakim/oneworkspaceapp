@extends('layouts.main')
@section('page-title')
    {{ __('Account Statement Summary') }}
@endsection
@section('page-breadcrumb')
    {{ __('Report') }},
    {{ __('Account Statement Summary') }}
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
        <a  class="btn btn-sm btn-primary" onclick="saveAsPDF()"  data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Download') }}">
            <i class="ti ti-download"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class=" multi-collapse mt-2 " id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(array('route' => array('report.account.statement'),'method'=>'get','id'=>'report_account')) }}
                        <div class="row align-items-center justify-content-end">
                            <div class="col-xl-10">
                                <div class="row">

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-2">
                                        <div class="btn-box">
                                        {{ Form::label('start_month', __('Start Month'), ['class' => 'form-label']) }}
                                            {{Form::month('start_month',isset($_GET['start_month'])?$_GET['start_month']:'',array('class'=>'month-btn form-control'))}}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-2">
                                        <div class="btn-box">
                                        {{ Form::label('end_month', __('End Month'), ['class' => 'form-label']) }}
                                            {{Form::month('end_month',isset($_GET['end_month'])?$_GET['end_month']:'',array('class'=>'month-btn form-control'))}}
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-2">
                                        <div class="btn-box">
                                        {{ Form::label('account', __('Account'), ['class' => 'form-label']) }}
                                            {{Form::select('account', $account,isset($_GET['account'])?$_GET['account']:'', array('class' => 'form-control ','placeholder' => 'Select Account')) }}
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-2">
                                        <div class="btn-box">
                                        {{ Form::label('type', __('Type'), ['class' => 'form-label']) }}
                                            {{ Form::select('type',$types,isset($_GET['type'])?$_GET['type']:'', array('class' => 'form-control ','placeholder' => 'Select Type')) }}
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto float-end ms-2 mt-4">
                                        <a  class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('report_account').submit(); return false;"
                                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('report.account.statement') }}" class="btn btn-sm btn-danger"
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
        <div class="row mt-3">
            <div class="col-md-6 col-12">
                <input type="hidden" value="{{__('Account Statement').' '.$filter['type'].' '.'Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange']}}" id="filename">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Report')}} :</h5>
                    <h6 class="report-text mb-0">{{__('Account Statement Summary')}}</h6>
                </div>
            </div>
            @if($filter['account']!=__('All'))
                <div class="col-md-6 col-12">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Account')}} :</h5>
                        <h6 class="report-text mb-0">{{$filter['account']}}</h6>
                    </div>
                </div>
            @endif
            @if($filter['type']!=__('All'))
                <div class="col-md-6 col-12">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Type')}} :</h5>
                        <h6 class="report-text mb-0">{{$filter['type']}}</h6>
                    </div>
                </div>
            @endif
            <div class="col-md-6 col-12">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Duration')}} :</h5>
                    <h6 class="report-text mb-0">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h6>
                </div>
            </div>
        </div>

        @if(!empty($reportData['revenueAccounts']))
            <div class="row">
                @foreach($reportData['revenueAccounts'] as $account)
                    <div class="col-xl-3 col-md-6 col-lg-3">
                        <div class="card p-4 mb-4">
                            @if($account->holder_name =='Cash')
                                <h5 class="report-text gray-text mb-0">{{$account->holder_name}}</h5>
                            @elseif(empty($account->holder_name))
                                <h5 class="report-text gray-text mb-0">{{__('Stripe / Paypal')}}</h5>
                            @else
                                <h5 class="report-text gray-text mb-0">{{$account->holder_name.' - '.$account->bank_name}}</h5>
                            @endif
                            <h6 class="report-text mb-0">{{ currency_format_with_sym($account->total)}}</h6>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($reportData['paymentAccounts']))
            <div class="row">
                @foreach($reportData['paymentAccounts'] as $account)
                    <div class="col-xl-3 col-md-6 col-lg-3">
                        <div class="card p-4 mb-4">
                            @if($account->holder_name =='Cash')
                                <h5 class="report-text gray-text mb-0">{{$account->holder_name}}</h5>
                            @elseif(empty($account->holder_name))
                                <h5 class="report-text gray-text mb-0">{{__('Stripe / Paypal')}}</h5>
                            @else
                                <h5 class="report-text gray-text mb-0">{{$account->holder_name.' - '.$account->bank_name}}</h5>
                            @endif
                            <h5 class="report-text mb-0">{{currency_format_with_sym($account->total)}}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
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
                                    <th>{{__('Amount')}}</th>
                                    <th>{{__('Description')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!empty($reportData['revenues']))
                                    @foreach ($reportData['revenues'] as $revenue)
                                        <tr class="font-style">
                                            <td>{{ company_date_formate($revenue->date) }}</td>
                                            <td>{{ currency_format_with_sym($revenue->amount) }}</td>
                                            <td>{{$revenue->description}} </td>
                                        </tr>
                                    @endforeach
                                @endif
                                @if(!empty($reportData['payments']))
                                    @foreach ($reportData['payments'] as $payments)
                                        <tr class="font-style">
                                            <td>{{ company_date_formate($payments->date) }}</td>
                                            <td>{{ currency_format_with_sym($payments->amount) }}</td>
                                            <td>{{!empty($payments->description)?$payments->description:'-'}} </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
