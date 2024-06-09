@extends('layouts.main')
@section('page-title')
    {{__('Tax Summary')}}
@endsection
@section('page-breadcrumb')
    {{ __('Report') }},
    {{__('Tax Summary')}}
@endsection
@push('scripts')
<script src="{{ asset('Modules/Account/Resources/assets/js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var year = '{{$currentYear}}';

        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
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
                    {{ Form::open(array('route' => array('report.tax.summary'),'method' => 'GET','id'=>'report_tax_summary')) }}
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
                                        </div>
                                    </div>

                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('year', __('Year'), ['class' => 'form-label']) }}
                                            {{ Form::select('year',$yearList,isset($_GET['year'])?$_GET['year']:'', array('class' => 'form-control ')) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <div class="row">
                                    <div class="col-auto mt-4">
                                        <a  class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('report_tax_summary').submit(); return false;"
                                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('report.tax.summary') }}" class="btn btn-sm btn-danger"
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
            <div class="col">
                <input type="hidden" value="{{__('Tax Summary').' '.'Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange']}}" id="filename">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Report')}} :</h5>
                    <h6 class="report-text mb-0">{{__('Tax Summary')}}</h6>
                </div>
            </div>
            <div class="col">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Duration')}} :</h5>
                    <h6 class="report-text mb-0">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h6>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="col-sm-12">
                            <h5>{{__('Income')}}</h5>
                            <div class="table-responsive mt-3 mb-3">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Tax')}}</th>
                                        @foreach($monthList as $month)
                                            <th>{{$month}}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse(array_keys($incomes) as $k=> $taxName)
                                        <tr>
                                            <td>{{$taxName}}</td>
                                            @foreach(array_values($incomes)[$k] as $price)
                                                <td>{{currency_format_with_sym($price)}}</td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center">{{__('Income tax not found')}}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <h5>{{__('Expense')}}</h5>
                            <div class="table-responsive mt-4">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>{{__('Tax')}}</th>
                                        @foreach($monthList as $month)
                                            <th>{{$month}}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse(array_keys($expenses) as $k=> $taxName)
                                        <tr>
                                            <td>{{$taxName}}</td>
                                            @foreach(array_values($expenses)[$k] as $price)
                                                <td>{{currency_format_with_sym($price)}}</td>
                                            @endforeach
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="13" class="text-center">{{__('Expense tax not found')}}</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


