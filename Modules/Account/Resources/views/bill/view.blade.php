@extends('layouts.main')
@section('page-title')
    {{ __('Bill Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Bill Detail') }}
@endsection
@push('scripts')
    <script>
        $(document).on('click', '#shipping', function () {
            var url = $(this).data('url');
            var is_display = $("#shipping").is(":checked");
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    'is_display': is_display,
                },
                success: function (data) {
                }
            });
        })
        $('.cp_link').on('click', function () {
            var value = $(this).attr('data-link');
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val(value).select();
            document.execCommand("copy");
            $temp.remove();
            toastrs('success', '{{__('Link Copy on Clipboard')}}', 'success')
        });
    </script>
    <script src="{{ asset('assets/js/plugins/dropzone-amd-module.min.js') }}"></script>
    <script>
        Dropzone.autoDiscover = false;
        myDropzone = new Dropzone("#dropzonewidget", {
            url: "{{ route('bill.file.upload', [$bill->id]) }}",
            success: function(file, response) {
                if (response.is_success) {
                    // dropzoneBtn(file, response);
                    location.reload();
                    myDropzone.removeFile(file);
                    toastrs('{{ __('Success') }}', 'File Successfully Uploaded', 'success');
                } else {
                    location.reload();
                    myDropzone.removeFile(response.error);
                    toastrs('Error', response.error, 'error');
                }
            },
            error: function(file, response) {
                myDropzone.removeFile(file);
                location.reload();
                if (response.error) {
                    toastrs('Error', response.error, 'error');
                } else {
                    toastrs('Error', response, 'error');
                }
            }
        });
        myDropzone.on("sending", function(file, xhr, formData) {
            formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
            formData.append("bill_id", {{ $bill->id }});
        });
    </script>
@endpush
@push('css')
<style>
    .bill_status{
        min-width: 94px;
    }
</style>
@endpush
@section('page-action')
    <div class="float-end">
        <a href="#" class="btn btn-sm btn-primary cp_link" data-link="{{route('pay.billpay',\Illuminate\Support\Facades\Crypt::encrypt($bill->id))}}" data-bs-toggle="tooltip" title="{{__('copy')}}"   data-original-title="{{__('Click to copy invoice link')}}">
            <span class="btn-inner--icon text-white"><i class="ti ti-file"></i></span>
        </a>
    </div>
@endsection
@section('content')
        @if($bill->status!=4)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row timeline-wrapper">
                                <div class="col-md-6 col-lg-4 col-xl-4">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="ti ti-plus text-primary"></i>
                                    </div>
                                    <h6 class="text-primary my-3">{{__('Create Bill')}}</h6>
                                    <p class="text-muted text-sm mb-3"><i class="ti ti-clock mr-2"></i>{{__('Created on ')}}{{ company_date_formate($bill->bill_date)}}</p>
                                    @permission('bill edit')
                                        <a href="{{ route('bill.edit',\Crypt::encrypt($bill->id)) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil mr-2"></i>{{__('Edit')}}</a>
                                    @endpermission
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="ti ti-mail text-warning"></i>
                                    </div>
                                    <h6 class="text-warning my-3">{{__('Send Bill')}}</h6>
                                    <p class="text-muted text-sm mb-3">
                                        @if($bill->status!=0)
                                            <i class="ti ti-clock mr-2"></i>{{__('Sent on')}} {{ company_date_formate($bill->send_date)}}
                                        @else
                                            @permission('bill send')
                                                <small>{{__('Status')}} : {{__('Not Sent')}}</small>
                                            @endpermission
                                        @endif

                                    </p>
                                    @if($bill->status==0)
                                        @permission('bill send')
                                                <a href="{{ route('bill.sent',$bill->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-original-title="{{__('Mark Sent')}}"><i class="ti ti-send mr-2"></i>{{__('Send')}}</a>
                                        @endpermission
                                    @endif
                                    <br><br>
                                    @stack('recurring_type')

                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="ti ti-report-money text-info"></i>
                                    </div>
                                    <h6 class="text-info my-3">{{__('Pay Bill')}}</h6>
                                    <p class="text-muted text-sm mb-3">{{__('Status')}} : {{__('Awaiting payment')}} </p>
                                    @if($bill->status!=0)
                                        @permission('bill payment create')
                                            <a href="#" data-url="{{ route('bill.payment',$bill->id) }}" data-ajax-popup="true" data-title="{{__('Add Payment')}}" class="btn btn-sm btn-info" data-original-title="{{__('Add Payment')}}"><i class="ti ti-report-money mr-2"></i>{{__('Add Payment')}}</a> <br>
                                        @endpermission
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

<div class="row justify-content-between align-items-center mb-3">
    <div class="col-md-6">
        <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="bill-tab" data-bs-toggle="pill"
                    data-bs-target="#bill" type="button">{{ __('Bill') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="payment-summary-tab" data-bs-toggle="pill"
                    data-bs-target="#payment-summary" type="button">{{ __('Payment Summary') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="debit-summary-tab"
                    data-bs-toggle="pill" data-bs-target="#debit-summary"
                    type="button">{{ __('Debit Note Summary') }}</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="bill-attechment-tab" data-bs-toggle="pill"
                    data-bs-target="#bill-attechment" type="button">{{ __('Attachment') }}</button>
            </li>
            @stack('add_recurring_tab')

        </ul>
    </div>
    @if(\Auth::user()->type=='company')
        @if($bill->status!=0)
            <div class="col-md-6 d-flex align-items-center justify-content-between justify-content-md-end">
                @if(!empty($billPayment) && $vendor->debit_note_balance != 0)
                    <div class="all-button-box mx-2">
                        <a href="#" data-url="{{ route('bill.debit.note',$bill->id) }}" data-ajax-popup="true" data-title="{{__('Apply Debit Note')}}" class="btn btn-sm btn-primary">
                            {{__('Add Debit Note')}}
                        </a>
                    </div>
                @endif
                <div class="all-button-box mx-2">
                    <a href="{{ route('bill.resent',$bill->id) }}" class="btn btn-sm btn-primary">
                        {{__('Resend Bill')}}
                    </a>
                </div>
                <div class="all-button-box">
                    <a href="{{ route('bill.pdf', Crypt::encrypt($bill->id))}}" target="_blank" class="btn btn-sm btn-primary">
                        {{__('Download')}}
                    </a>
                </div>
            </div>
        @endif
    @else
        <div class="col-md-6 d-flex align-items-center justify-content-between justify-content-md-end">
            <div class="all-button-box mx-2">
                <a href="#" data-url="{{route('bill.sent',$bill->id)}}" data-ajax-popup="true" data-title="{{__('Send Bill')}}" class="btn btn-sm btn-primary">
                    {{__('Send Mail')}}
                </a>
            </div>
            <div class="all-button-box mx-2">
                <a href="{{ route('bill.pdf', Crypt::encrypt($bill->id))}}" target="_blank" class="btn btn-sm btn-primary">
                    {{__('Download')}}
                </a>
            </div>
        </div>
    @endif
</div>


<div class="row">
    <div class="col-lg-12">
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade active show" id="bill" role="tabpanel"
            aria-labelledby="pills-user-tab-1">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                    <h2>{{__('Bill')}}</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                    <h3 class="invoice-number float-end">{{ Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id) }}</h3>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col text-end">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <div class="me-4">
                                            <small>
                                                <strong>{{__('Issue Date')}} :</strong><br>
                                                {{ company_date_formate($bill->bill_date)}}<br><br>
                                            </small>
                                        </div>
                                        <div>
                                            <small>
                                                <strong>{{__('Due Date')}} :</strong><br>
                                                {{ company_date_formate($bill->due_date)}}<br><br>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if(!empty($vendor->billing_name))
                                    <div class="col">
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
                                @if(company_setting('bill_shipping_display')=='on')
                                    <div class="col">
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
                                    <div class="col">
                                        <div class="float-end mt-3 ">
                                         {!! DNS2D::getBarcodeHTML(route('pay.billpay',\Illuminate\Support\Facades\Crypt::encrypt($bill->id)), "QRCODE",2,2) !!}
                                        </div>
                                    </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong>{{__('Status')}} :</strong><br>
                                        @if ($bill->status == 0)
                                            <span
                                                class="badge fix_badges bg-primary p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 1)
                                            <span
                                                class="badge fix_badges bg-info p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 2)
                                            <span
                                                class="badge fix_badges bg-secondary p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 3)
                                            <span
                                                class="badge fix_badges bg-warning p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 4)
                                            <span
                                                class="badge fix_badges bg-danger p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @endif
                                    </small>
                                </div>
                                @if(!empty($customFields) && count($bill->customField)>0)
                                    @foreach($customFields as $field)
                                        <div class="col text-md-end">
                                            <small>
                                                <strong>{{$field->name}} :</strong><br>
                                                @if ($field->type == 'attachment')
                                                        <a href="{{ get_file($bill->customField[$field->id]) }}" target="_blank">
                                                            <img src=" {{ get_file($bill->customField[$field->id]) }} " class="wid-75 rounded me-3">
                                                        </a>
                                                    @else
                                                        <p>{{ !empty($bill->customField[$field->id]) ? $bill->customField[$field->id] : '-' }}</p>
                                                    @endif
                                                <br><br>
                                            </small>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-bold mb-2">{{__('Item Summary')}}</div>
                                    <small class="mb-2">{{__('All items here cannot be deleted.')}}</small>
                                    <div class="table-responsive mt-2">
                                        <table class="table mb-0 table-striped">
                                            <tr>
                                                <th class="text-dark" data-width="40">#</th>
                                                @if ($bill->bill_module == 'account' || $bill->bill_module == '')
                                                    <th class="text-dark">{{ __('Item Type') }}</th>
                                                    <th class="text-dark">{{ __('Item') }}</th>
                                                @elseif($bill->bill_module == 'taskly')
                                                    <th class="text-dark">{{ __('Project') }}</th>
                                                @endif
                                                <th class="text-dark">{{__('Quantity')}}</th>
                                                <th class="text-dark">{{__('Rate')}}</th>
                                                <th class="text-dark">{{__('Discount')}}</th>
                                                <th class="text-dark">{{__('Tax')}}</th>
                                                @if ($bill->bill_module == 'account' || $bill->bill_module == '')
                                                <th class="text-dark">{{__('Chart Of Account')}}</th>
                                                <th class="text-dark">{{__('Account Amount')}}</th>
                                                @endif
                                                <th class="text-dark">{{__('Description')}}</th>
                                                <th class="text-right text-dark" width="12%">{{__('Price')}}<br>
                                                    <small class="text-danger font-weight-bold">{{__('After discount & tax')}}</small>
                                                </th>
                                            </tr>
                                            @php
                                                $totalQuantity=0;
                                                $totalRate=0;
                                                $totalTaxPrice=0;
                                                $totalDiscount=0;
                                                $taxesData=[];
                                                $TaxPrice_array = [];
                                            @endphp

                                            @foreach($iteams as $key =>$iteam)
                                                @if(!empty($iteam->tax))
                                                    @php
                                                        $taxes= Modules\Account\Entities\AccountUtility::tax($iteam->tax);
                                                        $totalQuantity+=$iteam->quantity;
                                                        $totalRate+=$iteam->price;
                                                        $totalDiscount+=$iteam->discount;
                                                        foreach($taxes as $taxe){
                                                            $taxDataPrice= Modules\Account\Entities\AccountUtility::taxRate($taxe->rate,$iteam->price,$iteam->quantity,$iteam->discount);
                                                            if (array_key_exists($taxe->name,$taxesData))
                                                            {
                                                                $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                            }
                                                            else
                                                            {
                                                                $taxesData[$taxe->name] = $taxDataPrice;
                                                            }
                                                        }
                                                    @endphp
                                                @endif
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    @if ($bill->bill_module == 'account'|| $bill->bill_module == '')
                                                        <td>{{ !empty($iteam->product_type) ? Str::ucfirst($iteam->product_type) : '--' }}
                                                        </td>
                                                        <td>{{ !empty($iteam->product()) ? $iteam->product()->name : '' }}</td>
                                                    @elseif($bill->bill_module == 'taskly')
                                                        <td>{{ !empty($iteam->product()) ? $iteam->product()->title : '' }}</td>
                                                    @endif
                                                    <td>{{$iteam->quantity}}</td>
                                                    <td>{{ currency_format_with_sym($iteam->price)}}</td>
                                                    <td>{{currency_format_with_sym($iteam->discount)}}</td>

                                                    <td>
                                                        @if(!empty($iteam->tax))
                                                            <table>
                                                                @php
                                                                    $totalTaxRate = 0;
                                                                    $data=0;
                                                                @endphp
                                                                @foreach($taxes as $tax)
                                                                    @php
                                                                        $taxPrice= Modules\Account\Entities\AccountUtility::taxRate($tax->rate,$iteam->price,$iteam->quantity,$iteam->discount);
                                                                        $totalTaxPrice+=$taxPrice;
                                                                        $data+=$taxPrice;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{$tax->name .' ('.$tax->rate .'%)'}}</td>
                                                                        <td>{{ currency_format_with_sym($taxPrice)}}</td>
                                                                    </tr>
                                                                @endforeach
                                                                @php
                                                                    array_push($TaxPrice_array,$data);
                                                                @endphp
                                                            </table>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>

                                                    @if ($bill->bill_module == 'account'|| $bill->bill_module == '')
                                                        @php
                                                            $chartAccount = \Modules\Account\Entities\ChartOfAccount::find($iteam->chart_account_id);
                                                        @endphp
                                                        <td>{{!empty($chartAccount) ? $chartAccount->name : '-'}}</td>
                                                        <td>{{currency_format_with_sym($iteam->amount)}}</td>
                                                    @endif
                                                    <td style="white-space: break-spaces;">{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                                    @php
                                                        $tr_tex = (array_key_exists($key,$TaxPrice_array) == true) ? $TaxPrice_array[$key] : 0;
                                                    @endphp
                                                    <td class="">{{ currency_format_with_sym(($iteam->price * $iteam->quantity - $iteam->discount) + $tr_tex)}}</td>
                                                </tr>
                                            @endforeach
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                @if ($bill->bill_module == 'account' || $bill->bill_module == '')
                                                    <td></td>
                                                @endif
                                                <td><b>{{__('Total')}}</b></td>
                                                <td><b>{{$totalQuantity}}</b></td>
                                                <td><b>{{ currency_format_with_sym($totalRate)}}</b></td>
                                                <td><b>{{ currency_format_with_sym($totalDiscount)}}</b></td>
                                                <td><b>{{ currency_format_with_sym($totalTaxPrice)}}</b></td>
                                                <td></td>
                                                <td><b>{{currency_format_with_sym($bill->getAccountTotal())}}</b></td>

                                            </tr>
                                            @php
                                                $colspan = 6;
                                                if ($bill->bill_module == 'account' || $bill->bill_module == '') {
                                                    $colspan = 9;
                                                }
                                            @endphp
                                            <tr>
                                                <td colspan="{{ $colspan }}"></td>
                                                <td class="text-right"><b>{{__('Sub Total')}}</b></td>
                                                <td class="text-right">{{ currency_format_with_sym($bill->getSubTotal())}}</td>
                                            </tr>
                                                <tr>
                                                    <td colspan="{{ $colspan }}"></td>
                                                    <td class="text-right"><b>{{__('Discount')}}</b></td>
                                                    <td class="text-right">{{ currency_format_with_sym($bill->getTotalDiscount())}}</td>
                                                </tr>
                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    <tr>
                                                        <td colspan="{{ $colspan }}"></td>
                                                        <td class="text-right"><b>{{$taxName}}</b></td>
                                                        <td class="text-right">{{  currency_format_with_sym($taxPrice) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="{{ $colspan }}"></td>
                                                <td class="blue-text text-right"><b>{{__('Total')}}</b></td>
                                                <td class="blue-text text-right">{{ currency_format_with_sym($bill->getTotal())}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{ $colspan }}"></td>
                                                <td class="text-right"><b>{{__('Paid')}}</b></td>
                                                <td class="text-right">{{ currency_format_with_sym(($bill->getTotal()-$bill->getDue())-($bill->billTotalDebitNote()))}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{ $colspan }}"></td>
                                                <td class="text-right"><b>{{ __('Debit note Applied') }}</b></td>
                                                <td class="text-right">
                                                    {{ currency_format_with_sym(($bill->billTotalDebitNote()))}}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{ $colspan }}"></td>
                                                <td class="text-right"><b>{{ __('Debit note issued') }}</b></td>
                                                <td class="text-right">
                                                    {{ currency_format_with_sym($bill->billTotalCustomerDebitNote()) }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="{{ $colspan }}"></td>
                                                <td class="text-right"><b>{{__('Due')}}</b></td>
                                                <td class="text-right">{{ currency_format_with_sym($bill->getDue())}}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </div>
            <div class="tab-pane fade" id="payment-summary" role="tabpanel"
            aria-labelledby="pills-user-tab-2">
                <div class="card">
                    <div class="card-body table-border-style">
                        <h5 class=" d-inline-block mb-5">{{__('Payment Summary')}}</h5>
                        <div class="table-responsive">
                            <table class="table mb-0 pc-dt-simple" id="invoice-payment">
                            <thead>
                                <tr>
                                    <th class="text-dark">{{__('Payment Receipt')}}</th>
                                    <th class="text-dark">{{__('Date')}}</th>
                                    <th class="text-dark">{{__('Amount')}}</th>
                                    <th class="text-dark">{{__('Account')}}</th>
                                    <th class="text-dark">{{__('Reference')}}</th>
                                    <th class="text-dark">{{__('Description')}}</th>
                                    @permission('bill payment delete')
                                        <th class="text-dark">{{__('Action')}}</th>
                                    @endpermission
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($bill->payments as $key =>$payment)
                                    <tr>
                                        <td>
                                            @if(!empty($payment->add_receipt))
                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="{{ get_file($payment->add_receipt)}}" download="" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Download')}}" target="_blank" >
                                                        <i class="ti ti-download text-white" ></i>
                                                    </a>
                                                </div>
                                                <div class="action-btn bg-secondary ms-2">
                                                    <a href="{{ get_file($payment->add_receipt)}}" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Show')}}" target="_blank" >
                                                        <i class="ti ti-crosshair text-white" ></i>
                                                    </a>
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>{{ company_date_formate($payment->date)}}</td>
                                        <td>{{ currency_format_with_sym($payment->amount)}}</td>
                                        <td>{{!empty($payment->bankAccount)?$payment->bankAccount->bank_name.' '.$payment->bankAccount->holder_name:''}}</td>
                                        <td>{{$payment->reference}}</td>
                                        <td style="white-space: break-spaces;">{{$payment->description}}</td>
                                        <td class="text-dark">
                                            @permission('bill payment delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {{Form::open(array('route'=>array('bill.payment.destroy',$bill->id,$payment->id),'class' => 'm-0'))}}
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$payment->id}}">
                                                            <i class="ti ti-trash text-white text-white"></i>
                                                        </a>
                                                    {{Form::close()}}
                                                </div>
                                            @endpermission
                                        </td>
                                    </tr>
                                @empty
                                    @include('layouts.nodatafound')
                                @endforelse
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="debit-summary" role="tabpanel"
            aria-labelledby="pills-user-tab-3">
                <div class="card">
                    <div class="card-body table-border-style">
                        <h5 class="d-inline-block mb-5">{{__('Debit Note Summary')}}</h5>
                        <div class="table-responsive">
                            <table class="table mb-0 pc-dt-simple" id="debit-note">
                            <thead>
                                <tr>
                                    <th class="text-dark">{{__('Date')}}</th>
                                    <th class="text-dark">{{__('Amount')}}</th>
                                    <th class="text-dark">{{__('Description')}}</th>
                                    @if(Laratrust::hasPermission('debitnote edit') || Laratrust::hasPermission('debitnote delete'))
                                        <th class="text-dark">{{__('Action')}}</th>
                                    @endif
                                </tr>
                            </thead>
                                @forelse($bill->debitNote as $key =>$debitNote)
                                    <tr>
                                        <td>{{ company_date_formate($debitNote->date)}}</td>
                                        <td>{{ currency_format_with_sym($debitNote->amount)}}</td>
                                        <td>{{$debitNote->description}}</td>
                                        <td>
                                            @permission('debitnote edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a data-url="{{ route('bill.edit.debit.note',[$debitNote->bill,$debitNote->id]) }}" data-ajax-popup="true" data-title="{{__('Add Debit Note')}}" href="#" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                            @permission('debitnote delete')
                                                    <div class="action-btn bg-danger ms-2">
                                                        {{Form::open(array('route'=>array('bill.delete.debit.note', $debitNote->bill,$debitNote->id),'class' => 'm-0'))}}
                                                        @method('DELETE')
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$debitNote->id}}">
                                                            <i class="ti ti-trash text-white text-white"></i>
                                                        </a>
                                                    {{Form::close()}}
                                                    </div>
                                            @endpermission
                                        </td>
                                    </tr>
                                @empty
                                    @include('layouts.nodatafound')
                                @endforelse
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="bill-attechment" role="tabpanel"
            aria-labelledby="pills-user-tab-4">
                <div class="row">
                    <h5 class="d-inline-block my-3">{{ __('Attachments') }}</h5>
                    <div class="col-3">
                        <div class="card border-primary border">
                            <div class="card-body table-border-style">
                                <div class="col-md-12 dropzone browse-file" id="dropzonewidget">
                                    <div class="dz-message my-5" data-dz-message>
                                        <span>{{ __('Drop files here to upload') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-9">
                        <div class="card border-primary border">
                            <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table mb-0 pc-dt-simple" id="attachment">
                                    <thead>
                                        <tr>
                                            <th class="text-dark">{{__('#')}}</th>
                                            <th class="text-dark">{{__('File Name')}}</th>
                                            <th class="text-dark">{{__('File Size')}}</th>
                                            <th class="text-dark">{{__('Date Created')}}</th>
                                                <th class="text-dark">{{__('Action')}}</th>
                                        </tr>
                                    </thead>
                                        @forelse($bill_attachment as $key =>$attachment)

                                                <td>{{ ++$key }}</td>
                                                <td>{{ $attachment->file_name }}</td>
                                                <td>{{ $attachment->file_size }}</td>
                                                <td>{{ company_date_formate($attachment->created_at) }}</td>
                                                <td>
                                                        <div class="action-btn bg-primary ms-2">
                                                            <a href="{{ url($attachment->file_path) }}" class="mx-3 btn btn-sm align-items-center" title="{{__('Download')}}" target="_blank" download>
                                                                <i class="ti ti-download text-white"></i>
                                                            </a>
                                                        </div>
                                                        <div class="action-btn bg-danger ms-2">
                                                            {{Form::open(array('route'=>array('bill.attachment.destroy',$attachment->id),'class' => 'm-0'))}}
                                                            @method('DELETE')
                                                            <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                                aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$attachment->id}}">
                                                                <i class="ti ti-trash text-white text-white"></i>
                                                            </a>
                                                            {{Form::close()}}
                                                        </div>
                                                </td>
                                            </tr>
                                        @empty
                                            @include('layouts.nodatafound')
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @stack('add_recurring_pills')

        </div>
    </div>
</div>
@endsection
