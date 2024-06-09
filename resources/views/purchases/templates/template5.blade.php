<!DOCTYPE html>
<html lang="en" dir="{{ $settings['site_rtl'] == 'on'?'rtl':''}}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ \App\Models\Purchase::purchaseNumberFormat($purchase->purchase_id,$purchase->created_by,$purchase->workspace)}} | {{ !empty(company_setting('title_text',$purchase->created_by,$purchase->workspace)) ? company_setting('title_text',$purchase->created_by,$purchase->workspace) : (!empty(admin_setting('title_text')) ? admin_setting('title_text') :'WorkDo') }}</title>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">


    <style type="text/css">
        html[dir="rtl"]  {
                letter-spacing: 0.1px;
            }
        :root {
            --theme-color: {{$color}};
            --white: #ffffff;
            --black: #000000;
        }

        body {
            font-family: 'Lato', sans-serif;
        }

        p,
        li,
        ul,
        ol {
            margin: 0;
            padding: 0;
            list-style: none;
            line-height: 1.5;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tr th {
            padding: 0.75rem;
            text-align: left;
        }

        table tr td {
            padding: 0.75rem;
            text-align: left;
        }

        table th small {
            display: block;
            font-size: 12px;
        }

        .invoice-preview-main {
            max-width: 700px;
            width: 100%;
            margin: 0 auto;
            background: #ffff;
            box-shadow: 0 0 10px #ddd;
        }

        .invoice-logo {
            max-width: 200px;
            width: 100%;
        }

        .invoice-header table td {
            padding: 15px 30px;
        }

        .text-right {
            text-align: right;
        }

        .no-space tr td {
            padding: 0;
            white-space: nowrap;
        }

        .vertical-align-top td {
            vertical-align: top;
        }

        .view-qrcode {
            max-width: 114px;
            height: 114px;
            margin-left: auto;
            margin-top: 15px;
            background: var(--white);
        }

        .view-qrcode img {
            width: 100%;
            height: 100%;
        }

        .invoice-body {
            padding: 30px 25px 0;
        }

        table.add-border tr {
            border-top: 1px solid var(--theme-color);
        }

        tfoot tr:first-of-type {
            border-bottom: 1px solid var(--theme-color);
        }

        .total-table tr:first-of-type td {
            padding-top: 0;
        }

        .total-table tr:first-of-type {
            border-top: 0;
        }

        .sub-total {
            padding-right: 0;
            padding-left: 0;
        }

        .border-0 {
            border: none !important;
        }

        .invoice-summary td,
        .invoice-summary th {
            font-size: 13px;
            font-weight: 600;
        }

        .total-table td:last-of-type {
            width: 146px;
        }

        .invoice-footer {
            padding: 15px 20px;
        }

        .itm-description td {
            padding-top: 0;
        }
        html[dir="rtl"] table tr td,
        html[dir="rtl"] table tr th{
            text-align: right;
        }
        html[dir="rtl"]  .text-right{
            text-align: left;
        }
        html[dir="rtl"] .view-qrcode{
            margin-left: 0;
        }
        p:not(:last-of-type){
            margin-bottom: 15px;
        }
        .invoice-summary p{
            margin-bottom: 0;
        }
        .wid-75 {
            width: 75px;
        }
    </style>
</head>

<body>
    <div class="invoice-preview-main" id="boxes">
        <div class="invoice-header">
            <table class="vertical-align-top">
                <tbody>
                    <tr>
                        <td style="width: 60%;">
                            <h3 style=" display: block; text-transform: uppercase; font-size: 30px; font-weight: bold; padding: 15px; background: var(--theme-color); color: {{$font_color}};">{{ __('PURCHASE')}}</h3>
                            @if ($settings['purchase_qr_display'] == 'on')
                                <div class="view-qrcode" style="margin-left: 0; margin-bottom: 15px;">
                                    <p> {!! DNS2D::getBarcodeHTML(route('purchases.link.copy',\Crypt::encrypt($purchase->purchase_id)), "QRCODE",2,2) !!}
                                </div>
                            @endif
                            <table class="no-space">
                                <tbody>
                                    <tr>
                                        <td>{{ __('Number: ')}}</td>
                                        <td class="text-right">{{\App\Models\Purchase::purchaseNumberFormat($purchase->purchase_id,$purchase->created_by,$purchase->workspace)}}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Purchase Date:')}}</td>
                                        <td class="text-right">{{company_date_formate($purchase->purchase_date,$purchase->created_by,$purchase->workspace)}}</td>
                                    </tr>
                                    @if(!empty($customFields) && count($purchase->customField)>0)
                                    @foreach($customFields as $field)
                                        <tr>
                                            <td>{{$field->name}} :</td>
                                            <td class="text-right" style="white-space: normal;">
                                                @if ($field->type == 'attachment')
                                                        <a href="{{ get_file($purchase->customField[$field->id]) }}" target="_blank">
                                                            <img src=" {{ get_file($purchase->customField[$field->id]) }} " class="wid-75 rounded me-3">
                                                        </a>
                                                    @else
                                                        {{!empty($purchase->customField)?$purchase->customField[$field->id]:'-'}}
                                                    @endif
                                                </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                        </td>

                        <td class="text-right">
                            <img class="invoice-logo"  src="{{$img}}"
                                alt="">
                                <p style="margin-top: 15px;">
                                    @if(!empty($settings['company_name'])){{$settings['company_name']}}@endif<br>
                                    @if(!empty($settings['company_email'])){{$settings['company_email']}}@endif<br>
                                    @if(!empty($settings['company_telephone'])){{$settings['company_telephone']}}@endif<br>
                                    @if(!empty($settings['company_address'])){{$settings['company_address']}}@endif
                                    @if(!empty($settings['company_city'])) <br> {{$settings['company_city']}}, @endif
                                    @if(!empty($settings['company_state'])){{$settings['company_state']}}@endif
                                    @if(!empty($settings['company_country'])) <br>{{$settings['company_country']}}@endif
                                    @if(!empty($settings['company_zipcode'])) - {{$settings['company_zipcode']}}@endif<br>
                                    @if(!empty($settings['registration_number'])){{__('Registration Number')}} : {{$settings['registration_number']}} @endif<br>
                                    @if(!empty($settings['tax_type']) && !empty($settings['vat_number'])){{$settings['tax_type'].' '. __('Number')}} : {{$settings['vat_number']}} <br>@endif
                                </p>
                        </td>

                    </tr>
                </tbody>
            </table>

        </div>
        <div class="invoice-body">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <strong style="margin-bottom: 10px; display:block;">{{ __('Bill To')}}:</strong>
                            <p>
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
                           @endif
                            </p>
                        </td>
                        @if($settings['purchase_shipping_display']=='on')
                            <td class="text-right">
                                <strong style="margin-bottom: 10px; display:block;">{{__('Ship To')}}:</strong>
                                <p>
                                    {{ !empty($vendor->shipping_name) ? $vendor->shipping_name : '' }}<br>
                                    {{ !empty($vendor->shipping_address) ? $vendor->shipping_address : '' }}<br>
                                    {{ !empty($vendor->shipping_city) ? $vendor->shipping_city .' ,': '' }}
                                    {{ !empty($vendor->shipping_state) ? $vendor->shipping_state .' ,': '' }}
                                    {{ !empty($vendor->shipping_zip) ? $vendor->shipping_zip : '' }}<br>
                                    {{ !empty($vendor->shipping_country) ? $vendor->shipping_country : '' }}<br>
                                    {{ !empty($vendor->shipping_phone) ? $vendor->shipping_phone : '' }}<br>
                                </p>
                            </td>
                        @endif
                    </tr>
                </tbody>
            </table>
            <table class="add-border invoice-summary" style="margin-top: 30px;">
                <thead style="background-color: var(--theme-color); color: {{$font_color}};">
                    <tr>
                        <th>{{__('Item Type')}}</th>
                        <th>{{__('Item')}}</th>
                        <th>{{__('Quantity')}}</th>
                        <th>{{__('Rate')}}</th>
                        <th>{{__('Discount')}}</th>
                        <th>{{__('Tax')}}(%)</th>
                        <th>{{__('Price')}}<small>{{ __('After discount & tax')}}</small></th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($purchase->itemData) && count($purchase->itemData) > 0)
                        @foreach($purchase->itemData as $key => $item)
                            <tr>
                                <td>{{!empty($item->product_type) ? Str::ucfirst($item->product_type) : '--' }}</td>
                                <td>{{$item->name}}</td>
                                <td>{{$item->quantity}}</td>
                                <td>{{currency_format_with_sym($item->price,$purchase->created_by,$purchase->workspace)}}</td>
                                <td>{{($item->discount!=0)?currency_format_with_sym($item->discount,$purchase->created_by,$purchase->workspace):'-'}}</td>
                                <td>
                                    @if(!empty($item->itemTax))
                                        @foreach($item->itemTax as $taxes)
                                            <span>{{$taxes['name']}} </span><span> ({{$taxes['rate']}}) </span> <span>{{$taxes['price']}}</span>
                                        @endforeach
                                    @else
                                       <p>-</p>
                                    @endif
                                </td>
                                <td>{{currency_format_with_sym(($item->price * $item->quantity),$purchase->created_by,$purchase->workspace)}}</td>
                                @if ($item->description != null)
                                    <tr class="border-0 itm-description ">
                                        <td colspan="6">{{$item->description}} </td>
                                    </tr>
                                @endif
                        @endforeach
                    @else
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>
                                <p>-</p>
                                <p>-</p>
                            </td>
                            <td>-</td>
                            <td>-</td>
                        <tr class="border-0 itm-description ">
                                <td colspan="6">-</td>
                            </tr>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td>{{__('Total')}}</td>
                        <td>{{$purchase->totalQuantity}}</td>
                         <td>{{currency_format_with_sym($purchase->totalRate,$purchase->created_by,$purchase->workspace)}}</td>
                        <td>{{currency_format_with_sym($purchase->totalDiscount,$purchase->created_by,$purchase->workspace)}}</td>
                        <td>{{currency_format_with_sym($purchase->totalTaxPrice,$purchase->created_by,$purchase->workspace) }}</td>
                        <td>{{currency_format_with_sym($purchase->getSubTotal(),$purchase->created_by,$purchase->workspace)}}</td>
                    </tr>
                    <tr>
                        <td colspan="5"></td>
                        <td colspan="2" class="sub-total">
                            <table class="total-table">
                                <tr>
                                    <td>{{__('Subtotal')}}:</td>
                                    <td>{{currency_format_with_sym($purchase->getSubTotal(),$purchase->created_by,$purchase->workspace)}}</td>
                                </tr>
                                @if($purchase->getTotalDiscount())
                                    <tr>
                                        <td>{{__('Discount')}}:</td>
                                        <td>{{currency_format_with_sym($purchase->getTotalDiscount(),$purchase->created_by,$purchase->workspace)}}</td>
                                    </tr>
                                @endif
                                @if(!empty($purchase->taxesData))
                                    @foreach($purchase->taxesData as $taxName => $taxPrice)
                                        <tr>
                                            <td>{{$taxName}} :</td>
                                            <td>{{ currency_format_with_sym($taxPrice,$purchase->created_by,$purchase->workspace)  }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td>{{__('Total')}}:</td>
                                    <td>{{currency_format_with_sym(($purchase->getSubTotal()-$purchase->getTotalDiscount()+$purchase->getTotalTax()),$purchase->created_by,$purchase->workspace)}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Paid')}}:</td>
                                    <td>{{currency_format_with_sym(($purchase->getTotal()-$purchase->getDue()),$purchase->created_by,$purchase->workspace)}}</td>
                                </tr>
                                <tr>
                                    <td>{{__('Due Amount')}}:</td>
                                    <td>{{currency_format_with_sym($purchase->getDue(),$purchase->created_by,$purchase->workspace)}}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="invoice-footer">
                <p> {{$settings['purchase_footer_title']}} <br>
                    {{$settings['purchase_footer_notes']}} </p>
            </div>
        </div>
    </div>
    @if(!isset($preview))
      @include('purchases.script')
    @endif
</body>

</html>
