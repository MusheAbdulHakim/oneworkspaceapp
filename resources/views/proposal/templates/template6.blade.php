<!DOCTYPE html>
<html lang="en"
    dir="{{ company_setting('site_rtl', $proposal->created_by, $proposal->workspace) == 'on' ? 'rtl' : '' }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {{ \App\Models\Proposal::proposalNumberFormat($proposal->proposal_id, $proposal->created_by, $proposal->workspace) }}
        |
        {{ !empty(company_setting('title_text', $proposal->created_by, $proposal->workspace)) ? company_setting('title_text', $proposal->created_by, $proposal->workspace) : (!empty(admin_setting('title_text')) ? admin_setting('title_text') : 'WorkDo') }}
    </title>
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">


    <style type="text/css">
        :root {
            --theme-color: {{ $color }};
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
        html[dir="rtl"] table tr th {
            text-align: right;
        }

        html[dir="rtl"] .text-right {
            text-align: left;
        }

        html[dir="rtl"] .view-qrcode {
            margin-left: 0;
            margin-right: auto;
        }

        p:not(:last-of-type) {
            margin-bottom: 15px;
        }

        .invoice-summary p {
            margin-bottom: 0;
        }
        .wid-75 {
            width: 75px;
        }
    </style>
</head>

<body>
    <div class="invoice-preview-main" id="boxes">
        <div class="invoice-header" style="border-top: 15px solid var(--theme-color);">
            <table>
                <tbody>
                    <tr>
                        <td>
                            <h3
                                style="text-transform: uppercase; font-size: 40px; font-weight: bold; color: {{ $color }};">
                                {{ __('PROPOSAL') }}</h3>
                        </td>
                        <td class="text-right">
                            <img class="invoice-logo" src="{{ $img }}" alt="">
                        </td>

                    </tr>
                </tbody>
            </table>

        </div>
        <div class="invoice-body">
            <table class="vertical-align-top">
                <tbody>
                    <tr>
                        @if (!empty($settings['company_name']) && !empty($settings['company_email']) && !empty($settings['company_address']))
                            <td style="font-size: 13px;">
                                <strong style="margin-bottom: 10px; display:block;">{{ __('From:') }}</strong>
                                <p>
                                    @if (!empty($settings['company_name']))
                                        {{ $settings['company_name'] }}
                                    @endif
                                    <br>
                                    @if (!empty($settings['company_email']))
                                        {{ $settings['company_email'] }}
                                    @endif
                                    <br>
                                    @if (!empty($settings['company_telephone']))
                                        {{ $settings['company_telephone'] }}
                                    @endif
                                    <br>
                                    @if (!empty($settings['company_address']))
                                        {{ $settings['company_address'] }}
                                    @endif
                                    @if (!empty($settings['company_city']))
                                        <br> {{ $settings['company_city'] }},
                                    @endif
                                    @if (!empty($settings['company_state']))
                                        {{ $settings['company_state'] }}
                                    @endif
                                    @if (!empty($settings['company_country']))
                                    <br>{{ $settings['company_country'] }}
                                    @endif
                                    @if (!empty($settings['company_zipcode']))
                                        - {{ $settings['company_zipcode'] }}
                                    @endif
                                </p>
                            </td>
                        @endif

                        @if (!empty($customer->billing_name) && !empty($customer->billing_address) && !empty($customer->billing_zip))
                            <td style="font-size: 13px;">
                                <strong style="margin-bottom: 10px; display:block;">{{ __('Bill To') }}:</strong>
                                <p>
                                    {{ !empty($customer->billing_name) ? $customer->billing_name : '' }}<br>
                                    {{ !empty($customer->billing_address) ? $customer->billing_address : '' }}<br>
                                    {{ !empty($customer->billing_city) ? $customer->billing_city . ' ,' : '' }}
                                    {{ !empty($customer->billing_state) ? $customer->billing_state . ' ,' : '' }}
                                    {{ !empty($customer->billing_zip) ? $customer->billing_zip : '' }}<br>
                                    {{ !empty($customer->billing_country) ? $customer->billing_country : '' }}<br>
                                    {{ !empty($customer->billing_phone) ? $customer->billing_phone : '' }}<br>
                                </p>
                            </td>
                        @endif
                        @if ($settings['proposal_shipping_display'] == 'on')
                            @if (!empty($customer->shipping_name) && !empty($customer->shipping_address) && !empty($customer->shipping_zip))
                                <td class="text-right" style="font-size: 13px;">
                                    <strong style="margin-bottom: 10px; display:block;">{{ __('Ship To') }}:</strong>
                                    <p>
                                        {{ !empty($customer->shipping_name) ? $customer->shipping_name : '' }}<br>
                                        {{ !empty($customer->shipping_address) ? $customer->shipping_address : '' }}<br>
                                        {{ !empty($customer->shipping_city) ? $customer->shipping_city .' ,': '' }}
                                        {{ !empty($customer->shipping_state) ? $customer->shipping_state .' ,': '' }}
                                        {{ !empty($customer->shipping_zip) ? $customer->shipping_zip : '' }}<br>
                                        {{ !empty($customer->shipping_country) ? $customer->shipping_country : '' }}<br>
                                        {{ !empty($customer->shipping_phone) ? $customer->shipping_phone : '' }}<br>
                                    </p>
                                </td>
                            @endif
                        @endif
                    </tr>
                    <tr style="border-bottom: 1px solid var(--theme-color);">
                        <td>
                            <p>
                                @if (!empty($settings['registration_number']))
                                    {{ __('Registration Number') }} : {{ $settings['registration_number'] }}
                                @endif
                                <br>
                                @if (!empty($settings['tax_type']) && !empty($settings['vat_number']))
                                    {{ $settings['tax_type'] . ' ' . __('Number') }} : {{ $settings['vat_number'] }} <br>
                                @endif
                            </p>
                        </td>
                        @if ($settings['proposal_qr_display'] == 'on')
                            <td colspan="2">
                                <div class="view-qrcode" style="margin-top: 0;">
                                    {!! DNS2D::getBarcodeHTML(route('pay.proposalpay', Crypt::encrypt($proposal->proposal_id)), 'QRCODE', 2, 2) !!}
                                </div>
                            </td>
                        @endif
                    </tr>
                </tbody>
            </table>
            <table>
                <tbody>
                    <tr>
                        <td style="width: 60%;">
                            <table class="no-space vertical-align-top">
                                <tbody>
                                    <tr>
                                        <td>{{ __('Number: ') }}</td>
                                        <td class="text-right">
                                            {{ \App\Models\Proposal::proposalNumberFormat($proposal->proposal_id, $proposal->created_by, $proposal->workspace) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ __('Issue Date:') }}</td>
                                        <td class="text-right">
                                            {{ company_date_formate($proposal->issue_date, $proposal->created_by, $proposal->workspace) }}
                                        </td>
                                    </tr>
                                    @if (!empty($customFields) && count($proposal->customField) > 0)
                                        @foreach ($customFields as $field)
                                            <tr>
                                                <td>{{ $field->name }}:</td>
                                                <td class="text-right" style="white-space: normal;">
                                                    @if ($field->type == 'attachment')
                                                        <a href="{{ get_file($proposal->customField[$field->id]) }}" target="_blank">
                                                            <img src=" {{ get_file($proposal->customField[$field->id]) }} " class="wid-75 rounded me-3">
                                                        </a>
                                                    @else
                                                        <p>{{ !empty($proposal->customField[$field->id]) ? $proposal->customField[$field->id] : '-' }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="add-border invoice-summary" style="margin-top: 30px;">
                <thead style="background-color: var(--theme-color); color: {{ $font_color }};">
                    <tr>
                         @if($proposal->proposal_module == "account")
                            <th>{{__('Item Type')}}</th>
                        @endif
                        <th>{{ __('Item') }}</th>
                        <th>{{ __('Quantity') }}</th>
                        <th>{{ __('Rate') }}</th>
                        <th>{{ __('Discount') }}</th>
                        <th>{{ __('Tax') }} (%)</th>
                        <th>{{ __('Price') }}<small>{{ __('After discount & tax') }}</small></th>
                    </tr>
                </thead>
                <tbody>
                    @if (isset($proposal->itemData) && count($proposal->itemData) > 0)
                        @foreach ($proposal->itemData as $key => $item)
                            <tr>
                                 @if($proposal->proposal_module == "account")
                                    <td>{{!empty($item->product_type) ? Str::ucfirst($item->product_type) : '--' }}</td>
                                @endif
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ currency_format_with_sym($item->price, $proposal->created_by, $proposal->workspace) }}
                                </td>
                                <td>{{ $item->discount != 0 ? currency_format_with_sym($item->discount, $proposal->created_by, $proposal->workspace) : '-' }}
                                </td>
                                <td>
                                    @if (!empty($item->itemTax))
                                        @foreach ($item->itemTax as $taxes)
                                            <span>{{ $taxes['name'] }} </span><span> ({{ $taxes['rate'] }}) </span>
                                            <span>{{ $taxes['price'] }}</span>
                                        @endforeach
                                    @else
                                        <p>-</p>
                                    @endif
                                </td>
                                <td>{{ currency_format_with_sym($item->price * $item->quantity - $item->discount + (isset($item->tax_price) ? $item->tax_price : 0), $proposal->created_by, $proposal->workspace) }}
                                </td>
                                @if ($item->description != null)
                            <tr class="border-0 itm-description ">
                                <td colspan="6">{{ $item->description }} </td>
                            </tr>
                        @endif
                    @endforeach
                @else
                    <tr>
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
                        @if($proposal->proposal_module == "account")
                            <td></td>
                        @endif
                        <td>{{ __('Total') }}</td>
                        <td>{{ $proposal->totalQuantity }}</td>
                        <td>{{ currency_format_with_sym($proposal->totalRate, $proposal->created_by, $proposal->workspace) }}
                        </td>
                        <td>{{ currency_format_with_sym($proposal->totalDiscount, $proposal->created_by, $proposal->workspace) }}
                        </td>
                        <td>{{ currency_format_with_sym($proposal->totalTaxPrice, $proposal->created_by, $proposal->workspace) }}
                        </td>
                        <td>{{ currency_format_with_sym($proposal->getSubTotal(), $proposal->created_by, $proposal->workspace) }}
                        </td>
                    </tr>
                    <tr>
                        @php
                            $colspan = 4;
                            if($proposal->proposal_module == "account"){
                                $colspan = 5;
                            }
                        @endphp
                        <td colspan="{{$colspan}}"></td>
                        <td colspan="2" class="sub-total">
                            <table class="total-table">
                                @if ($proposal->getTotalDiscount())
                                    <tr>
                                        <td>{{ __('Discount') }}:</td>
                                        <td>{{ currency_format_with_sym($proposal->getTotalDiscount(), $proposal->created_by, $proposal->workspace) }}
                                        </td>
                                    </tr>
                                @endif
                                @if (!empty($proposal->taxesData))
                                    @foreach ($proposal->taxesData as $taxName => $taxPrice)
                                        <tr>
                                            <td>{{ $taxName }} :</td>
                                            <td>{{ currency_format_with_sym($taxPrice, $proposal->created_by, $proposal->workspace) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                <tr>
                                    <td>{{ __('Total') }}:</td>
                                    <td>{{ currency_format_with_sym($proposal->getSubTotal() - $proposal->getTotalDiscount() + $proposal->getTotalTax(), $proposal->created_by, $proposal->workspace) }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="invoice-footer">
                <p>
                    {{ $settings['proposal_footer_title'] }} <br>
                    {{ $settings['proposal_footer_notes'] }}
                </p>
            </div>
        </div>
    </div>
    @if (!isset($preview))
        @include('proposal.script')
    @endif
</body>

</html>
