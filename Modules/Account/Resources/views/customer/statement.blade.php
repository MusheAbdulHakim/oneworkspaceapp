@extends('layouts.main')

@section('page-title')
    {{__('Customer Statement')}}
@endsection
@push('scripts')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
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
@section('page-breadcrumb')
        {{__('Customer')}},
        {{$customer['name']}},
    {{__('Customer Statement')}}
@endsection
@section('page-action')
    <div class="float-end">
        <a  class="btn btn-sm btn-primary" onclick="saveAsPDF()"  data-bs-toggle="tooltip" title="{{__('Download')}}">
            <span class="btn-inner--icon"><i class="ti ti-download"></i></span>
        </a>
    </div>

@endsection


@section('content')

    <div class="row">

        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="card bg-none invo-tab">
                <div class="card-body">

                {{Form::model($customerDetail,array('route' => array('customer.statement' , $customer->id), 'method' => 'post'))}}
                <h3 class="small-title">{{$customer['name'].' '.__('Statement')}}</h3>
                <div class="row issue_date">
                    <div class="col-md-12">
                        <div class="issue_date_main">
                        <div class="form-group">
                            {{ Form::label('from_date', __('From Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                            {{Form::date('from_date', isset($data['from_date'])?$data['from_date']:null,array('class'=>'form-control ','required'=>'required'))}}

                        </div>
                    </div>

                        <div class="issue_date_main">
                        <div class="form-group">
                            {{ Form::label('until_date', __('Until Date'),['class'=>'form-label']) }}<span class="text-danger">*</span>
                            {{Form::date('until_date', isset($data['until_date'])?$data['until_date']:null,array('class'=>'form-control ','required'=>'required'))}}

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
                                    <img src="{{ get_file(sidebar_logo()) }}"
                                        alt="{{ config('app.name', 'WorkDo') }}" class="logo logo-lg" style="max-width: 250px">
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
                                @if(!empty($customer->billing_name))
                                    <div class="col-md-6">
                                        <small class="font-style">
                                            <strong>{{__('Billed To')}} :</strong><br>
                                            {{ !empty($customer->billing_name) ? $customer->billing_name : '' }}<br>
                                            {{ !empty($customer->billing_address) ? $customer->billing_address : '' }}<br>
                                            {{ !empty($customer->billing_city) ? $customer->billing_city .' ,': '' }}
                                            {{ !empty($customer->billing_state) ? $customer->billing_state .' ,': '' }}
                                            {{ !empty($customer->billing_zip) ? $customer->billing_zip : '' }}<br>
                                            {{ !empty($customer->billing_country) ? $customer->billing_country : '' }}<br>
                                            {{ !empty($customer->billing_phone) ? $customer->billing_phone : '' }}<br>
                                            <strong>{{__('Tax Number ')}} : </strong>{{!empty($customer->tax_number)?$customer->tax_number:''}}

                                        </small>
                                    </div>
                                @endif
                                @if (company_setting('invoice_shipping_display') == 'on' || company_setting('proposal_shipping_display') == 'on' )
                                    <div class="col-md-6 text-end">
                                        <small>
                                            <strong>{{__('Shipped To')}} :</strong><br>
                                            {{ !empty($customer->shipping_name) ? $customer->shipping_name : '' }}<br>
                                            {{ !empty($customer->shipping_address) ? $customer->shipping_address : '' }}<br>
                                            {{ !empty($customer->shipping_city) ? $customer->shipping_city .' ,': '' }}
                                            {{ !empty($customer->shipping_state) ? $customer->shipping_state .' ,': '' }}
                                            {{ !empty($customer->shipping_zip) ? $customer->shipping_zip : '' }}<br>
                                            {{ !empty($customer->shipping_country) ? $customer->shipping_country : '' }}<br>
                                            {{ !empty($customer->shipping_phone) ? $customer->shipping_phone : '' }}<br>
                                            <strong>{{__('Tax Number ')}} : </strong>{{!empty($customer->tax_number)?$customer->tax_number:''}}
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
                                            <th scope="col">{{__('Payment Type')}}</th>
                                            <th scope="col">{{__('Amount')}}</th>

                                        </tr>
                                        </thead>
                                        <tbody class="list">
                                        @php
                                            $total = 0;

                                        @endphp
                                        @forelse($invoice_payment as $payment)
                                            <tr>
                                                <td>{{company_date_formate($payment->date)}} </td>
                                                <td>{{\App\Models\Invoice::invoiceNumberFormat($payment->invoice_id)}}</td>
                                                <td>{{$payment->payment_type}} </td>
                                                <td> {{currency_format_with_sym(($payment->amount))}}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                            </tr>
                                        @endforelse
                                        <tr class="total">
                                            <td class="light_blue"><span></span><strong>{{__('TOTAL :')}}</strong></td>
                                            <td class="light_blue"></td>
                                            <td class="light_blue"></td>
                                            @foreach($invoice_payment as $key=>$payment)
                                           @php
                                               $total += $payment->amount;
                                           @endphp
                                            @endforeach
                                                <td class="light_blue"><span></span><strong>{{currency_format_with_sym($total)}}</strong></td>
                                        </tr>
                                        </tfoot>
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
