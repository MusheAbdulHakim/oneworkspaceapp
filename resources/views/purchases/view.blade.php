@extends('layouts.main')
@section('page-title')
    {{__('Purchase Detail')}}
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

    </script>
@endpush
@section('page-breadcrumb')
     {{__('Purchase')}},
     {{ App\Models\Purchase::purchaseNumberFormat($purchase->purchase_id) }}
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/dropzone.css') }}" type="text/css" />
    <link rel="stylesheet" href="{{ asset('Modules/Pos/Resources/assets/css/dropzone.min.css') }}">
    <style>
        .border-primary {
            border-color: #0CAF60 !important;
        }
    </style>
@endpush

@section('content')

    @permission('purchase send')
        @if($purchase->status!=4)
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row timeline-wrapper">
                                <div class="col-md-6 col-lg-4 col-xl-4">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="ti ti-plus text-primary"></i>
                                    </div>
                                    <h6 class="text-primary my-3">{{__('Create Purchase')}}</h6>
                                    <p class="text-muted text-sm mb-3"><i class="ti ti-clock mr-2"></i>{{__('Created on ')}}{{company_date_formate($purchase->purchase_date)}}</p>
                                    @permission('purchase edit')
                                        <a href="{{ route('purchases.edit',\Crypt::encrypt($purchase->id)) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil mr-2"></i>{{__('Edit')}}</a>

                                    @endpermission
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="ti ti-mail text-warning"></i>
                                    </div>
                                    <h6 class="text-warning my-3">{{__('purchase send')}}</h6>
                                    <p class="text-muted text-sm mb-3">
                                        @if($purchase->status!=0)
                                            <i class="ti ti-clock mr-2"></i>{{__('Sent on')}} {{company_date_formate($purchase->send_date)}}
                                        @else
                                            @permission('purchase send')
                                                <small>{{__('Status')}} : {{__('Not Sent')}}</small>
                                            @endpermission
                                        @endif
                                    </p>

                                    @if($purchase->status==0)
                                            @permission('purchase send')
                                                <a href="{{ route('purchases.sent',$purchase->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-original-title="{{__('Mark Sent')}}"><i class="ti ti-send mr-2"></i>{{__('Send')}}</a>
                                            @endpermission
                                    @endif
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="ti ti-report-money text-info"></i>
                                    </div>
                                    <h6 class="text-info my-3">{{__('Get Paid')}}</h6>
                                    <p class="text-muted text-sm mb-3">{{__('Status')}} : {{__('Awaiting payment')}} </p>
                                    @if($purchase->status!= 0)
                                                @permission('purchase payment create')
                                                    <a href="#" data-url="{{ route('purchases.payment',$purchase->id) }}" data-ajax-popup="true" data-title="{{__('Add Payment')}}" class="btn btn-sm btn-info" data-original-title="{{__('Add Payment')}}"><i class="ti ti-report-money mr-2"></i>{{__('Add Payment')}}</a> <br>
                                                @endpermission
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endpermission

    @if(\Auth::user()->type=='company')
    <div class="page-header pt-3">
        <div class="page-block">
            <div class="row gy-4 align-items-center">
                <div class="col-md-6">
                    <ul class="nav nav-pills nav-fill information-tab" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if(!session('tab') or (session('tab') and session('tab') == 1)) active @endif" id="purchase-setting-tab" data-bs-toggle="pill"
                                data-bs-target="#purchase-setting" type="button">{{__('Purchase')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if(session('tab') and session('tab') == 2) active @endif" id="payment-setting-tab" data-bs-toggle="pill"
                                data-bs-target="#payment-setting" type="button">{{__('Payment')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if(session('tab') and session('tab') == 3) active @endif" id="debit-setting-tab" data-bs-toggle="pill"
                                data-bs-target="#debit-setting" type="button">{{__('Debit Note')}}</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link @if(session('tab') and session('tab') == 4) active @endif" id="attachment-setting-tab" data-bs-toggle="pill"
                                data-bs-target="#attachment-setting" type="button">{{__('Attachments')}}</button>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    @if($purchase->status!=0)
                        <div class="row justify-content-between align-items-center">    {{-- mb-3--}}
                            <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                                @if(!empty($purchasePayment))
                                    <div class="all-button-box mx-2">
                                        <a href="#" data-url="{{ route('purchases.debit.note',$purchase->id) }}" data-ajax-popup="true" data-title="{{__('Add Debit Note')}}" class="btn btn-sm btn-primary">
                                            {{__('Add Debit Note')}}
                                        </a>
                                    </div>
                                @endif
                                    <div class="all-button-box mx-2">
                                        <a href="{{ route('purchases.resent',$purchase->id) }}" class="btn btn-sm btn-primary">
                                            {{__('Resend purchase')}}
                                        </a>
                                    </div>
                                <div class="all-button-box">
                                    <a href="{{ route('purchases.pdf', Crypt::encrypt($purchase->id))}}" target="_blank" class="btn btn-sm btn-primary">
                                        {{__('Download')}}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>


    @endif

    <div class="row">
        <div class="col-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade @if(!session('tab') or (session('tab') and session('tab') == 1)) active show @endif" id="purchase-setting" role="tabpanel"
                    aria-labelledby="pills-user-tab-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice">
                                <div class="invoice-print">
                                    <div class="row invoice-title mt-2">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                            <h4>{{__('Purchase')}}</h4>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                            <h4 class="invoice-number">{{ App\Models\Purchase::purchaseNumberFormat($purchase->purchase_id) }}</h4>
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
                                                        {{company_date_formate($purchase->purchase_date)}}<br><br>
                                                    </small>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                            <div class="col">
                                                <small class="font-style">
                                                    <strong>{{__('Billed To')}} :</strong><br>

                                                    @if(!empty($purchase->vender_name))
                                                        {{ (!empty( $purchase->vender_name)?$purchase->vender_name:'') }}
                                                    @else
                                                    {{ !empty($vendor->billing_name) ? $vendor->billing_name : '' }}<br>
                                                    {{ !empty($vendor->billing_address) ? $vendor->billing_address : '' }}<br>
                                                    {{ !empty($vendor->billing_city) ? $vendor->billing_city . ' ,' : '' }}
                                                    {{ !empty($vendor->billing_state) ? $vendor->billing_state . ' ,' : '' }}
                                                    {{ !empty($vendor->billing_zip) ? $vendor->billing_zip : '' }}<br>
                                                    {{ !empty($vendor->billing_country) ? $vendor->billing_country : '' }}<br>
                                                    {{ !empty($vendor->billing_phone) ? $vendor->billing_phone : '' }}<br>
                                                        <strong>{{__('Tax Number ')}} : </strong>{{!empty($vendor->tax_number)?$vendor->tax_number:''}}
                                                    @endif
                                                </small>
                                            </div>
                                        @if(!empty($company_settings['shipping_display']) && company_setting('shipping_display')=='on')
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
                                        @if (!empty($company_settings['purchase_qr_display']) && $company_settings['purchase_qr_display'] == 'on')
                                            <div class="col">
                                                <div class="float-end mt-3">
                                                    {!! DNS2D::getBarcodeHTML(route('purchases.link.copy',\Illuminate\Support\Facades\Crypt::encrypt($purchase->id)), "QRCODE",2,2) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <small>
                                                <strong>{{__('Status')}} :</strong><br>
                                                @if($purchase->status == 0)
                                                    <span class="badge bg-secondary p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                                @elseif($purchase->status == 1)
                                                    <span class="badge bg-warning p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                                @elseif($purchase->status == 2)
                                                    <span class="badge bg-danger p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                                @elseif($purchase->status == 3)
                                                    <span class="badge bg-info p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                                @elseif($purchase->status == 4)
                                                    <span class="badge bg-success p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                                                @endif
                                            </small>
                                        </div>
                                        @if(!empty($customFields) && count($purchase->customField)>0)
                                        @foreach($customFields as $field)
                                        <div class="col text-md-end">
                                            <small>
                                                <strong>{{$field->name}} :</strong><br>
                                                @if ($field->type == 'attachment')
                                                    <a href="{{ get_file($purchase->customField[$field->id]) }}" target="_blank">
                                                        <img src=" {{ get_file($purchase->customField[$field->id]) }} " class="wid-75 rounded me-3">
                                                    </a>
                                                @else
                                                    {{!empty($purchase->customField[$field->id])?$purchase->customField[$field->id]:'-'}}
                                                @endif
                                                        <br><br>
                                                    </small>
                                                </div>
                                        @endforeach
                                        @endif
                                    </div>
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="font-weight-bold">{{__('Item Summary')}}</div>
                                            <small class="mb-2">{{__('All items here cannot be deleted.')}}</small>
                                            <div class="table-responsive mt-2">

                                                <table class="table mb-0 table-striped">
                                                        <tr>
                                                            <th class="text-dark" data-width="40">#</th>
                                                            <th class="text-dark">{{__('Item Type')}}</th>
                                                            <th class="text-dark">{{__('Item')}}</th>
                                                            <th class="text-dark">{{__('Quantity')}}</th>
                                                            <th class="text-dark">{{__('Rate')}}</th>
                                                            <th class="text-dark">{{__('Discount')}} </th>
                                                            <th class="text-dark">{{__('Tax')}}</th>
                                                            <th class="text-dark">{{__('Description')}}</th>
                                                            <th class="text-end text-dark" width="12%">{{__('Price')}}<br>
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
                                                                    $taxes=App\Models\Purchase::taxs($iteam->tax);
                                                                    $totalQuantity+=$iteam->quantity;
                                                                    $totalRate+=$iteam->price;
                                                                    $totalDiscount+=$iteam->discount;
                                                                    foreach($taxes as $taxe){
                                                                        $taxDataPrice=App\Models\Purchase::taxRate($taxe->rate,$iteam->price,$iteam->quantity,$iteam->discount);
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
                                                                <td>{{!empty($iteam->product_type) ? Str::ucfirst($iteam->product_type) : '--'}}</td>
                                                                <td>{{!empty($iteam->product)?$iteam->product->name:''}}</td>
                                                                <td>{{$iteam->quantity}}</td>
                                                                <td>{{currency_format_with_sym($iteam->price)}}</td>
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
                                                                                    $taxPrice=App\Models\Purchase::taxRate($tax->rate,$iteam->price,$iteam->quantity,$iteam->discount);
                                                                                    $totalTaxPrice+=$taxPrice;
                                                                                    $data+=$taxPrice;
                                                                                @endphp
                                                                                <tr>
                                                                                    <td class="">{{$tax->name .' ('.$tax->rate .'%)'}}</td>
                                                                                    <td>{{currency_format_with_sym($taxPrice)}}</td>
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
                                                                <td style="white-space: break-spaces;">{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                                                <td class="text-end">{{ currency_format_with_sym(($iteam->price * $iteam->quantity - $iteam->discount) + $totalTaxPrice)}}</td>{{--text-right--}}
                                                            </tr>
                                                        @endforeach
                                                        <tfoot>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td><b>{{__('Total')}}</b></td>
                                                            <td><b>{{$totalQuantity}}</b></td>
                                                            <td><b>{{currency_format_with_sym($totalRate)}}</b></td>
                                                            <td><b>{{currency_format_with_sym($totalDiscount)}}</b></td>
                                                            <td><b>{{currency_format_with_sym($totalTaxPrice)}}</b></td>

                                                        </tr>
                                                        <tr>
                                                            <td colspan="7"></td>
                                                            <td class="text-end"><b>{{__('Sub Total')}}</b></td>
                                                            <td class="text-end">{{currency_format_with_sym($purchase->getSubTotal())}}</td>
                                                        </tr>

                                                            <tr>
                                                                <td colspan="7"></td>
                                                                <td class="text-end"><b>{{__('Discount')}}</b></td>
                                                                <td class="text-end">{{currency_format_with_sym($purchase->getTotalDiscount())}}</td>
                                                            </tr>

                                                        @if(!empty($taxesData))
                                                            @foreach($taxesData as $taxName => $taxPrice)
                                                                <tr>
                                                                    <td colspan="7"></td>
                                                                    <td class="text-end"><b>{{$taxName}}</b></td>
                                                                    <td class="text-end">{{ currency_format_with_sym($taxPrice) }}</td>
                                                                </tr>
                                                            @endforeach
                                                        @endif
                                                        <tr>
                                                            <td colspan="7"></td>
                                                            <td class="blue-text text-end"><b>{{__('Total')}}</b></td>
                                                            <td class="blue-text text-end">{{currency_format_with_sym($purchase->getTotal())}}</td>
                                                        </tr>
                                                        @php
                                                            $getdue = $purchase->getDue();
                                                        @endphp
                                                        <tr>
                                                            <td colspan="7"></td>
                                                            <td class="text-end"><b>{{__('Paid')}}</b></td>
                                                            <td class="text-end">{{currency_format_with_sym(($purchase->getTotal()-$getdue))}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="7"></td>
                                                            <td class="text-end"><b>{{__('Debit Note')}}</b></td>
                                                            <td class="text-end">{{ currency_format_with_sym(($purchase->purchaseTotalDebitNote()))}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="7"></td>
                                                            <td class="text-end"><b>{{__('Due')}}</b></td>
                                                            <td class="text-end">{{currency_format_with_sym($getdue)}}</td>
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



                <div class="tab-pane fade @if(session('tab') and session('tab') == 2) active show @endif" id="payment-setting" role="tabpanel"
                    aria-labelledby="pills-user-tab-2">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-border-style">
                                    <h5 class=" d-inline-block mb-5">{{__('Payment Summary')}}</h5>
                                    <div class="table-responsive">
                                        <table class="table mb-0 pc-dt-simple" id="payment_summary">
                                        <thead>

                                            <tr>
                                                <th class="text-dark">{{__('Payment Receipt')}}</th>
                                                <th class="text-dark">{{__('Date')}}</th>
                                                <th class="text-dark">{{__('Amount')}}</th>
                                                <th class="text-dark">{{__('Account')}}</th>
                                                <th class="text-dark">{{__('Reference')}}</th>
                                                <th class="text-dark">{{__('Description')}}</th>
                                                @permission('purchase payment delete')
                                                    <th class="text-dark">{{__('Action')}}</th>
                                                @endpermission
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($purchase->payments as $key =>$payment)
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
                                                        @permission('purchase payment delete')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {{Form::open(array('route'=>array('purchases.payment.destroy',$purchase->id,$payment->id),'class' => 'm-0'))}}
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
                    </div>
                </div>



                <div class="tab-pane fade @if(session('tab') and session('tab') == 3) active show @endif" id="debit-setting" role="tabpanel"
                    aria-labelledby="pills-user-tab-3">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body table-border-style">
                                    <h5 class="d-inline-block mb-5">{{__('Debit Note Summary')}}</h5>
                                    <div class="table-responsive">
                                        <table class="table mb-0 pc-dt-simple" id="debit_summary">
                                        <thead>
                                            <tr>
                                                <th class="text-dark">{{__('Date')}}</th>
                                                <th class="text-dark">{{__('Amount')}}</th>
                                                <th class="text-dark">{{__('Description')}}</th>
                                                @if(Laratrust::hasPermission('purchase debitnote edit') || Laratrust::hasPermission('purchase debitnote delete'))
                                                    <th class="text-dark">{{__('Action')}}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                            @forelse($purchase->debitNote as $key =>$debitNote)
                                                <tr>
                                                    <td>{{ company_date_formate($debitNote->date)}}</td>
                                                    <td>{{ currency_format_with_sym($debitNote->amount)}}</td>
                                                    <td>{{$debitNote->description}}</td>
                                                    <td>
                                                        @permission('purchase debitnote edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a data-url="{{ route('purchases.edit.debit.note',[$debitNote->purchase,$debitNote->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Debit Note')}}" href="#" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission
                                                        @permission('purchase debitnote delete')
                                                                <div class="action-btn bg-danger ms-2">
                                                                    {{Form::open(array('route'=>array('purchases.delete.debit.note', $debitNote->purchase,$debitNote->id),'class' => 'm-0'))}}
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
                    </div>
                </div>

                <div class="tab-pane fade @if(session('tab') and session('tab') == 4) active show @endif" id="attachment-setting" role="tabpanel"
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
                                                {{-- @if(Laratrust::hasPermission('purchase debitnote edit') || Laratrust::hasPermission('purchase debitnote delete')) --}}
                                                    <th class="text-dark">{{__('Action')}}</th>
                                                {{-- @endif --}}
                                            </tr>
                                        </thead>
                                            @forelse($purchase_attachment as $key =>$attachment)
                                                <tr>
                                                    <td>{{ ++$key }}</td>
                                                    <td>{{ $attachment->file_name }}</td>
                                                    <td>{{ $attachment->file_size }}</td>
                                                    <td>{{ company_date_formate($attachment->created_at) }}</td>
                                                    <td>
                                                        {{-- @permission('purchase attachment edit') --}}
                                                            <div class="action-btn bg-primary ms-2">
                                                                <a href="{{ url($attachment->file_path) }}" class="mx-3 btn btn-sm align-items-center" title="{{__('Download')}}" target="_blank" download>
                                                                    <i class="ti ti-download text-white"></i>
                                                                </a>
                                                            </div>
                                                        {{-- @endpermission
                                                        @permission('purchase attachment delete') --}}
                                                            <div class="action-btn bg-danger ms-2">
                                                                {{Form::open(array('route'=>array('purchases.attachment.destroy',$attachment->id),'class' => 'm-0'))}}
                                                                @method('DELETE')
                                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                                    aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$attachment->id}}">
                                                                    <i class="ti ti-trash text-white text-white"></i>
                                                                </a>
                                                                {{Form::close()}}
                                                            </div>
                                                        {{-- @endpermission --}}
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


            </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="{{ asset('Modules/Pos/Resources/assets/js/dropzone.min.js') }}"></script>
<script>
    Dropzone.autoDiscover = false;
    myDropzone = new Dropzone("#dropzonewidget", {
        maxFiles: 20,
        maxFilesize: 20,
        parallelUploads: 1,
        acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
        url: "{{ route('purchases.files.upload', [$purchase->id]) }}",
        success: function(file, response) {
            if (response.is_success) {
                // dropzoneBtn(file, response);
                myDropzone.removeFile(file);
                toastrs('{{ __('Success') }}', 'File Successfully Uploaded', 'success');
            } else {
                myDropzone.removeFile(response.error);
                toastrs('Error', response.error, 'error');
            }
        },
        error: function(file, response) {
            myDropzone.removeFile(file);
            if (response.error) {
                toastrs('Error', response.error, 'error');
            } else {
                toastrs('Error', response, 'error');
            }
        }
    });
    myDropzone.on("sending", function(file, xhr, formData) {
        formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
        formData.append("purchase_id", {{ $purchase->id }});
    });
</script>
@endpush
