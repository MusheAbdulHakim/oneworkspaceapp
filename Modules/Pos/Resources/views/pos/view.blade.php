@extends('layouts.main')
@section('page-title')
    {{__('POS Detail')}}
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
@push('css')
<link rel="stylesheet" href="{{ asset('Modules/Pos/Resources/assets/css/custom.css') }}" id="main-style-link">
@endpush
@section('page-breadcrumb')
{{__('POS Order')}},
{{\Modules\Pos\Entities\Pos::posNumberFormat($pos->pos_id) }}
@endsection
@section('page-action')
    <div class="float-end">
        <a href="{{ route('pos.pdf', Crypt::encrypt($pos->id))}}" class="btn btn-primary" target="_blank">{{__('Download')}}</a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mt-2">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                            <h4>{{__('POS')}}</h4>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 justify-content-end d-flex">
                            <h4 class="invoice-number">{{ \Modules\Pos\Entities\Pos::posNumberFormat($pos->pos_id) }}</h4>
                        </div>
                        <div class="col-12">
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-5">
                            @if(!empty($customer->billing_name))
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
                            @endif
                        </div>
                        <div class="col-4">
                            @if(isset($company_setting['pos_shipping_display']) && $company_setting['pos_shipping_display']=='on')
                                @if(!empty($customer->shipping_name))
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
                                @endif
                            @endif
                        </div>
                        <div class="col-3">
                            <div class="d-flex align-items-center justify-content-end">
                                <div class="me-4">
                                    <small>
                                        <strong>{{__('Issue Date')}} :</strong>
                                        {{company_date_formate($pos->pos_date)}}<br><br>
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="table-responsive mt-3">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th class="text-dark" >#</th>
                                            <th class="text-dark">{{__('Items')}}</th>
                                            <th class="text-dark">{{__('Quantity')}}</th>
                                            <th class="text-dark">{{__('Price')}}</th>
                                            <th class="text-dark">{{__('Tax')}}</th>
                                            <th class="text-dark">{{__('Tax Amount')}}</th>
                                            <th class="text-dark">{{__('Total')}}</th>
                                        </tr>
                                    </thead>
                                    @php
                                        $totalQuantity=0;
                                        $totalRate=0;
                                        $totalTaxPrice=0;
                                        $totalDiscount=0;
                                        $taxesData=[];
                                    @endphp
                                    @foreach($iteams as $key =>$iteam)
                                        @if(!empty($iteam->tax))
                                        @php
                                                        $taxes=\Modules\Pos\Entities\Pos::tax($iteam->tax);
                                                        $totalQuantity+=$iteam->quantity;
                                                        $totalRate+=$iteam->price;
                                                        $totalDiscount+=$iteam->discount;
                                                        foreach($taxes as $taxe){
                                                            $taxDataPrice=\Modules\Pos\Entities\Pos::taxRate($taxe->rate,$iteam->price,$iteam->quantity);
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
                                            <td>{{!empty($iteam->product)?$iteam->product->name:''}}</td>
                                            <td>{{$iteam->quantity}}</td>
                                            <td>{{currency_format_with_sym($iteam->price)}}</td>
                                            <td>
                                                @if(!empty($iteam->tax))
                                                    <table>
                                                        @php
                                                            $totalTaxRate = 0;
                                                            $totalTaxPrice = 0;
                                                        @endphp
                                                        @foreach($taxes as $tax)
                                                            @php
                                                                $taxPrice=\Modules\Pos\Entities\Pos::taxRate($tax->rate,$iteam->price,$iteam->quantity);
                                                                $totalTaxPrice+=$taxPrice;
                                                            @endphp
                                                            <tr>
                                                                <span class="badge bg-primary p-2 px-3 rounded mt-1 mr-1">{{$tax->name .' ('.$tax->rate .'%)'}}</span> <br>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{currency_format_with_sym($totalTaxPrice)}}</td>
                                            <td >{{currency_format_with_sym(($iteam->price*$iteam->quantity+$totalTaxPrice))}}</td>
                                        </tr>
                                    @endforeach

                                    <tr>
                                        <td><b>{{__(' Sub Total')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{currency_format_with_sym($posPayment['amount'])}}</td>
                                    </tr>
                                    <tr>
                                        <td><b>{{__('Discount')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{currency_format_with_sym($posPayment['discount'])}}</td>
                                    </tr>
                                    <tr class="pos-header">
                                        <td><b>{{__('Total')}}</b></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>{{currency_format_with_sym($posPayment['discount_amount'])}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
