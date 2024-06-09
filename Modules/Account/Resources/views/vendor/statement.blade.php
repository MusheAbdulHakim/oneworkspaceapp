@extends('layouts.main')
@section('page-title')
    {{ __('Vendor Statement') }}
@endsection
@section('page-breadcrumb')
    {{ __('Vendor') }},{{$vendor['name']}}
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
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="card bg-none invo-tab">
                <div class="card-body">
                    {{Form::model($vendorDetail,array('route' => array('vendor.statement' , $vendor->id), 'method' => 'post'))}}
                    <h3 class="small-title">{{$vendor['name'].' '.__('Statement')}}</h3>
                    <div class="row issue_date">
                        <div class="col-md-12">
                            <div class="issue_date_main">
                            <div class="form-group">
                                {{ Form::label('from_date', __('From Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                    {{Form::date('from_date', isset($data['from_date'])?$data['from_date']:null,array('class'=>'form-control ','placeholder' => 'Select From Date','required'=>'required'))}}
                                </div>
                            </div>
                        </div>
                        </div>
                        <div class="col-md-12">
                            <div class="issue_date_main">
                            <div class="form-group">
                                {{ Form::label('until_date', __('Until Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                                <div class="form-icon-user">
                                    {{ Form::date('until_date', isset($data['until_date'])?$data['until_date']:null, array('class' => 'form-control ','placeholder' => 'Select Until Date')) }}

                                </div>
                            </div>
                        </div>
                        </div>

                    </div>
                    <div class="col-12 text-end">
                        <input type="submit" value="{{__('Apply')}}" class="btn btn-sm btn-primary">
                    </div>
                    {{Form::close()}}
                </div>

            </div>
        </div>

        <div class="col-md-8 col-lg-8 col-xl-8">
            <span id="printableArea">
                <div class="card">
                    <div class="card-body">
                        <div class="invoice">
                            <div class="invoice-print">
                                <div class="row invoice-title mt-2">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                        <img src="{{$img}}" style="max-width: 250px"/>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                        <strong>{{__('My Company')}}</strong><br>
                                        <h6 class="invoice-number">{{ $user->email }}</h6>
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-12 text-center">
                                        <strong><h5>{{__('Statement of Account')}}</h5></strong>
                                        <strong>{{($data['from_date']).'  '.'to'.'  '.($data['until_date'])}}</strong>
                                    </div>
                                    <div class="col-12">
                                        <hr>
                                    </div>
                                </div>


                                <div class="row">
                                    @if(!empty($vendor->billing_name))
                                        <div class="col-md-6">
                                            <small class="font-style">
                                                <strong>{{__('Billed To')}} :</strong><br>
                                                {{ !empty($vendor->billing_name) ? $vendor->billing_name : '' }}<br>
                                                {{ !empty($vendor->billing_address) ? $vendor->billing_address : '' }}<br>
                                                {{ !empty($vendor->billing_city) ? $vendor->billing_city . ' ,' : '' }}
                                                {{ !empty($vendor->billing_state) ? $vendor->billing_state . ' ,' : '' }}
                                                {{ !empty($vendor->billing_zip) ? $vendor->billing_zip : '' }}<br>
                                                {{ !empty($vendor->billing_country) ? $vendor->billing_country : '' }}<br>
                                                {{ !empty($vendor->billing_phone) ? $vendor->billing_phone : '' }}<br>
                                                <strong>{{__('Tax Number ')}} : </strong>{{!empty($vendor->tax_number)?$vendor->tax_number:''}}

                                            </small>
                                        </div>
                                    @endif
                                    @if(\company_setting('bill_shipping_display')=='on')
                                        <div class="col-md-6 text-end">
                                            <small>
                                                <strong>{{__('Shipped To')}} :</strong><br>
                                                {{ !empty($vendor->shipping_name) ? $vendor->shipping_name : '' }}<br>
                                                {{ !empty($vendor->shipping_address) ? $vendor->shipping_address : '' }}<br>
                                                {{ !empty($vendor->shipping_city) ? $vendor->shipping_city .' ,': '' }}
                                                {{ !empty($vendor->shipping_state) ? $vendor->shipping_state .' ,': '' }}
                                                {{ !empty($vendor->shipping_zip) ? $vendor->shipping_zip : '' }}<br>
                                                {{ !empty($vendor->shipping_country) ? $vendor->shipping_country : '' }}<br>
                                                {{ !empty($vendor->shipping_phone) ? $vendor->shipping_phone : '' }}<br>
                                                <strong>{{__('Tax Number ')}} : </strong>{{!empty($vendor->tax_number)?$vendor->tax_number:''}}
                                            </small>
                                        </div>
                                    @endif
                                </div>

                                <div class="card mt-4">
                                    <div class="card-body table-border-styletable-border-style">
                                        <div class="table-responsive">
                                        <table class="table align-items-center table_header">
                                            <thead>
                                            <tr>
                                                <th scope="col">{{__('Date')}}</th>
                                                <th scope="col">{{__('Invoice')}}</th>
                                                <th scope="col">{{__('Amount')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody class="list">
                                            @php
                                                $total = 0;
                                            @endphp
                                            @forelse($bill_payment as $payment)
                                                <tr>
                                                    <td>{{ company_date_formate($payment->date)}} </td>
                                                    <td>{{ 'user invoiceNumberFormat'.$payment->invoice_id}}</td>
                                                    <td> {{ currency_format_with_sym(($payment->amount))}}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="6" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                                </tr>
                                            @endforelse
                                            <tr class="total">
                                                <td class="light_blue"><span></span><strong>{{__('TOTAL :')}}</strong></td>
                                                <td class="light_blue"></td>
                                                @foreach($bill_payment as $key=>$payment)
                                                    @php
                                                        $total += $payment->amount;
                                                    @endphp
                                                @endforeach

                                                <td class="light_blue"><strong>{{currency_format_with_sym($total)}}</strong></td>
                                            </tr>

                                            </tbody>
                                        </table>
                                    </div>
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </span>
        </div>

    </div>
@endsection
