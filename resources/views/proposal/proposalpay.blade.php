@extends('layouts.invoicepayheader')
@section('page-title')
    {{__('Proposal Detail')}}
@endsection
@push('script-page')
    <script>
        $(document).on('change', '.status_change', function () {
            var status = this.value;
            var url = $(this).data('url');
            $.ajax({
                url: url + '?status=' + status,
                type: 'GET',
                cache: false,
                success: function (data) {
                },
            });
        });
    </script>
@endpush
@section('action-btn')
@if(\Auth::check() && isset(\Auth::user()->type) && \Auth::user()->type=='company')
@if($proposal->status!=0)
    <div class="row justify-content-between align-items-center ">
        <div class="col-10 offset-1 d-flex align-items-center justify-content-between justify-content-md-end">
            <div class="all-button-box">
                <a href="{{ route('proposal.pdf', Crypt::encrypt($proposal->id))}}" class="btn btn-sm btn-primary" target="_blank"><i class="ti ti-printer"></i>{{__('Print')}}</a>
            </div>
        </div>
    </div>
@endif
@else
<div class="row justify-content-between align-items-center ">
    <div class="col-10  offset-1 d-flex align-items-center justify-content-between justify-content-md-end">
        <div class="all-button-box">
            <a href="{{ route('proposal.pdf', Crypt::encrypt($proposal->id))}}" class="btn btn-sm btn-primary" target="_blank"><i class="ti ti-printer"></i>{{__('Print')}}</a>
        </div>
    </div>
</div>
@endif
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                    <h4>{{__('Proposal')}}</h4>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                    <h4 class="invoice-number">{{ \App\Models\Proposal::proposalNumberFormat($proposal->proposal_id,$proposal->created_by,$proposal->workspace) }}</h4>
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
                                                {{company_date_formate($proposal->issue_date,$proposal->created_by,$proposal->workspace)}}<br><br>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if (!empty($customer->billing_name) && !empty($customer->billing_address) && !empty($customer->billing_zip))
                                <div class="col">
                                    <small class="font-style">
                                        <strong>{{__('Billed To')}} :</strong><br>
                                        {{ !empty($customer->billing_name) ? $customer->billing_name : '' }}<br>
                                        {{ !empty($customer->billing_address) ? $customer->billing_address : '' }}<br>
                                        {{ !empty($customer->billing_city) ? $customer->billing_city . ' ,' : '' }}
                                        {{ !empty($customer->billing_state) ? $customer->billing_state . ' ,' : '' }}
                                        {{ !empty($customer->billing_zip) ? $customer->billing_zip : '' }}<br>
                                        {{ !empty($customer->billing_country) ? $customer->billing_country : '' }}<br>
                                        {{ !empty($customer->billing_phone) ? $customer->billing_phone : '' }}<br>
                                        <strong>{{__('Tax Number ')}} : </strong>{{!empty($customer->tax_number)?$customer->tax_number:''}}

                                    </small>
                                </div>
                            @endif
                            @if(!empty($company_settings['proposal_shipping_display']) && $company_settings['proposal_shipping_display']=='on')
                                @if (!empty($customer->shipping_name) && !empty($customer->shipping_address) && !empty($customer->shipping_zip))
                                    <div class="col">
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
                             @endif
                            @if (!empty($company_settings['proposal_qr_display']) && $company_settings['proposal_qr_display'] == 'on')
                                <div class="col">
                                    <div class="float-end mt-3">
                                        <p> {!! DNS2D::getBarcodeHTML(route('pay.proposalpay',\Illuminate\Support\Facades\Crypt::encrypt($proposal->id)), "QRCODE",2,2) !!}</p>
                                    </div>
                                </div>
                            @endif
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong>{{__('Status')}} :</strong><br>
                                        @if($proposal->status == 0)
                                            <span class="badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 1)
                                            <span class="badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 2)
                                            <span class="badge bg-success p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 3)
                                            <span class="badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 4)
                                            <span class="badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @endif
                                    </small>
                                </div>



                                @if(!empty($customFields) && count($proposal->customField)>0)
                                    @foreach($customFields as $field)
                                        <div class="col text-end">
                                            <small>
                                                <strong>{{$field->name}} :</strong><br>
                                                @if ($field->type == 'attachment')
                                                    <a href="{{ get_file($proposal->customField[$field->id]) }}" target="_blank">
                                                        <img src=" {{ get_file($proposal->customField[$field->id]) }} " class="wid-75 rounded me-3">
                                                    </a>
                                                @else
                                                    {{ !empty($proposal->customField[$field->id]) ? $proposal->customField[$field->id] : '-' }}
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
                                    <small>{{__('All items here cannot be deleted.')}}</small>
                                    <div class="table-responsive mt-2">
                                        <table class="table mb-0 ">
                                            <tr>
                                                <th class="text-dark" data-width="40">#</th>
                                                @if($proposal->proposal_module == "account")
                                                    <th class="text-dark">{{__('Item Type')}}</th>
                                                    <th class="text-dark">{{__('Item')}}</th>
                                                @elseif($proposal->proposal_module == "taskly")
                                                  <th class="text-dark">{{__('Project')}}</th>
                                                @endif
                                                <th class="text-dark">{{__('Quantity')}}</th>
                                                <th class="text-dark">{{__('Rate')}}</th>
                                                <th class="text-dark"> {{__('Discount')}}</th>
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

                                            @foreach($item as $key =>$iteam)
                                                @if(!empty($iteam->tax))
                                                    @php
                                                        $taxes=\Modules\ProductService\Entities\Tax::tax($iteam->tax);
                                                        $totalQuantity+=$iteam->quantity;
                                                        $totalRate+=$iteam->price;
                                                        $totalDiscount+=$iteam->discount;
                                                        foreach($taxes as $taxe){
                                                            $taxDataPrice=\App\Models\Proposal::taxRate($taxe->rate,$iteam->price,$iteam->quantity,$iteam->discount);
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
                                                    @if($proposal->proposal_module == "account")
                                                    <td>{{!empty($iteam->product_type) ? Str::ucfirst($iteam->product_type) : '--'}}</td>
                                                    <td>{{!empty($iteam->product())?$iteam->product()->name:''}}</td>
                                                    @elseif($proposal->proposal_module == "taskly")
                                                        <td>{{!empty($iteam->product())?$iteam->product()->title:''}}</td>
                                                    @endif
                                                    <td>{{$iteam->quantity}}</td>
                                                    <td>{{currency_format_with_sym($iteam->price,$proposal->created_by,$proposal->workspace)}}</td>
                                                    <td>
                                                        {{currency_format_with_sym($iteam->discount,$proposal->created_by,$proposal->workspace)}}
                                                    </td>
                                                    <td>
                                                        @if(!empty($iteam->tax))
                                                            <table>
                                                                @php
                                                                    $totalTaxRate = 0;
                                                                    $data = 0;
                                                                @endphp
                                                                @foreach($taxes as $tax)
                                                                    @php
                                                                        $taxPrice=App\Models\Proposal::taxRate($tax->rate,$iteam->price,$iteam->quantity,$iteam->discount);

                                                                        $totalTaxPrice+=$taxPrice;
                                                                        $data+=$taxPrice;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{$tax->name .' ('.$tax->rate .'%)'}}</td>
                                                                        <td>{{currency_format_with_sym($taxPrice,$proposal->created_by,$proposal->workspace)}}</td>
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
                                                    @php
                                                        $tr_tex = (array_key_exists($key,$TaxPrice_array) == true) ? $TaxPrice_array[$key] : 0;
                                                    @endphp
                                                    <td style="white-space: break-spaces;">{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                                    <td class="text-end">{{ currency_format_with_sym(($iteam->price*$iteam->quantity) -$iteam->discount + $tr_tex ,$proposal->created_by,$proposal->workspace)}}</td>
                                                </tr>
                                            @endforeach
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                @if($proposal->proposal_module == "account")
                                                    <td></td>
                                                @endif
                                                <td><b>{{__('Total')}}</b></td>
                                                <td><b>{{$totalQuantity}}</b></td>
                                                <td><b>{{currency_format_with_sym($totalRate,$proposal->created_by,$proposal->workspace)}}</b></td>
                                                <td><b>{{currency_format_with_sym($totalDiscount,$proposal->created_by,$proposal->workspace)}}</b></td>
                                                <td><b>{{currency_format_with_sym($totalTaxPrice,$proposal->created_by,$proposal->workspace)}}</b></td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            @php
                                                $colspan = 6;
                                                if($proposal->proposal_module == "account"){
                                                    $colspan = 7;
                                                }
                                            @endphp
                                            <tr>
                                                <td colspan="{{$colspan}}"></td>
                                                <td class="text-end"><b>{{__('Sub Total')}}</b></td>
                                                <td class="text-end">{{currency_format_with_sym($proposal->getSubTotal(),$proposal->created_by,$proposal->workspace)}}</td>
                                            </tr>
                                                <tr>
                                                    <td colspan="{{$colspan}}"></td>
                                                    <td class="text-end"><b>{{__('Discount')}}</b></td>
                                                    <td class="text-end">{{currency_format_with_sym($proposal->getTotalDiscount(),$proposal->created_by,$proposal->workspace)}}</td>
                                                </tr>
                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    <tr>
                                                        <td colspan="{{$colspan}}"></td>
                                                        <td class="text-end"><b>{{$taxName}}</b></td>
                                                        <td class="text-end">{{currency_format_with_sym($taxPrice,$proposal->created_by,$proposal->workspace) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="{{$colspan}}"></td>
                                                <td class="blue-text text-end"><b>{{__('Total')}}</b></td>
                                                <td class="blue-text text-end">{{currency_format_with_sym($proposal->getTotal(),$proposal->created_by,$proposal->workspace)}}</td>
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
    </div>

@endsection
