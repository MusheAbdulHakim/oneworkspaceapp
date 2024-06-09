@extends('account::layouts.master')
@section('page-title')
    {{ __('Bill Detail') }}
@endsection
@push('script-page')
    <script>
        $(document).on('click', '#shipping', function() {
            var url = $(this).data('url');
            var is_display = $("#shipping").is(":checked");
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    'is_display': is_display,
                },
                success: function(data) {}
            });
        })
    </script>
@endpush
@push('css')
<style>
    .bill_status{
        min-width: 94px;
    }
</style>
@endpush
@section('action-btn')
    @if (\Auth::check() && isset(\Auth::user()->type) && \Auth::user()->type == 'company')
        @if ($bill->status != 0)
            <div class="row justify-content-between align-items-center ">
                <div class="col-12 d-flex align-items-center justify-content-between justify-content-md-end">
                    @if (!empty($billPayment))
                        <div class="all-button-box mx-2">
                            <a href="#" data-url="{{ route('bill.debit.note', $bill->id) }}" data-ajax-popup="true"
                                data-title="{{ __('Add Debit Note') }}" class="btn btn-sm btn-primary">
                                {{ __('Add Debit Note') }}
                            </a>
                        </div>
                    @endif
                    <div class="all-button-box mr-3">
                        <a href="{{ route('bill.resent', $bill->id) }}" class="btn btn-sm btn-primary">
                            {{ __('Resend Bill') }}
                        </a>
                        <a href="{{ route('bill.pdf', Crypt::encrypt($bill->id)) }}" target="_blank"
                            class="btn btn-sm btn-primary"><i class="ti ti-printer"></i>
                            {{ __('Print') }}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="row justify-content-between align-items-center ">
            <div class="col-12 offset-1 d-flex align-items-center justify-content-between justify-content-md-end">
                <div class="all-button-box mx-2">
                    <a href="#" data-url="{{ route('vendor.bill.send', $bill->id) }}" data-ajax-popup="true"
                        data-title="{{ __('Send Bill') }}" class="btn btn-sm btn-primary btn-icon-only width-auto">
                        {{ __('SendMail') }}
                    </a>
                </div>
                <div class="all-button-box mx-2">
                    <a href="{{ route('bill.pdf', Crypt::encrypt($bill->id)) }}" target="_blank"
                        class="btn btn-sm btn-primary btn-icon-only width-auto">
                        <i class="ti ti-printer"></i>{{ __('Print') }}
                    </a>
                </div>
            </div>
        </div>
    @endif
@endsection
@section('content')
    @php
        $vendor = $bill->vendor;
    @endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                    <h2>{{ __('Bill') }}</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                    <h3 class="invoice-number ">
                                        {{ \Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id, $company_id, $workspace_id) }}
                                    </h3>
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
                                                <strong>{{ __('Issue Date') }} :</strong><br>
                                                @php
                                                    $user = \App\Models\User::where('id', $bill->created_by)->first();
                                                @endphp
                                                {{ company_date_formate($bill->bill_date, $company_id, $workspace_id) }}<br><br>
                                            </small>
                                        </div>
                                        <div>
                                            <small>
                                                <strong>{{ __('Due Date') }} :</strong><br>
                                                {{ company_date_formate($bill->due_date, $company_id, $workspace_id) }}<br><br>
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if (!empty($vendor->billing_name))
                                    @if (!empty($vendor->billing_name) && !empty($vendor->billing_address) && !empty($vendor->billing_zip))
                                        <div class="col">
                                            <small class="font-style">
                                                <strong>{{ __('Billed To') }} :</strong><br>
                                                {{ !empty($vendor->billing_name) ? $vendor->billing_name : '' }}<br>
                                                {{ !empty($vendor->billing_address) ? $vendor->billing_address : '' }}<br>
                                                {{ !empty($vendor->billing_city) ? $vendor->billing_city . ' ,' : '' }}
                                                {{ !empty($vendor->billing_state) ? $vendor->billing_state . ' ,' : '' }}
                                                {{ !empty($vendor->billing_zip) ? $vendor->billing_zip : '' }}<br>
                                                {{ !empty($vendor->billing_country) ? $vendor->billing_country : '' }}<br>
                                                {{ !empty($vendor->billing_phone) ? $vendor->billing_phone : '' }}<br>
                                            </small>
                                        </div>
                                    @endif
                                @endif
                                @if (company_setting('bill_shipping_display', $company_id, $workspace_id) == 'on')
                                    @if (!empty($vendor->shipping_name) && !empty($vendor->shipping_address) && !empty($vendor->shipping_zip))
                                        <div class="col">
                                            <small>
                                                <strong>{{ __('Shipped To') }} :</strong><br>
                                                {{ !empty($vendor->shipping_name) ? $vendor->shipping_name : '' }}<br>
                                                {{ !empty($vendor->shipping_address) ? $vendor->shipping_address : '' }}<br>
                                                {{ !empty($vendor->shipping_city) ? $vendor->shipping_city .' ,': '' }}
                                                {{ !empty($vendor->shipping_state) ? $vendor->shipping_state .' ,': '' }}
                                                {{ !empty($vendor->shipping_zip) ? $vendor->shipping_zip : '' }}<br>
                                                {{ !empty($vendor->shipping_country) ? $vendor->shipping_country : '' }}<br>
                                                {{ !empty($vendor->shipping_phone) ? $vendor->shipping_phone : '' }}<br>
                                            </small>
                                        </div>
                                    @endif
                                @endif
                                <div class="col">
                                    <div class="float-end mt-3">
                                        <p> {!! DNS2D::getBarcodeHTML(
                                            route('pay.billpay', \Illuminate\Support\Facades\Crypt::encrypt($bill->id)),
                                            'QRCODE',
                                            2,
                                            2,
                                        ) !!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong>{{ __('Status') }} :</strong><br>
                                        @if ($bill->status == 0)
                                            <span
                                                class="badge bg-primary p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 1)
                                            <span
                                                class="badge bg-warning p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 2)
                                            <span
                                                class="badge bg-danger p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 3)
                                            <span
                                                class="badge bg-info p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 4)
                                            <span
                                                class="badge bg-success p-2 px-3 rounded bill_status">{{ __(Modules\Account\Entities\Bill::$statues[$bill->status]) }}</span>
                                        @endif
                                    </small>
                                </div>
                                @if (!empty($customFields) && count($bill->customField) > 0)
                                    @foreach ($customFields as $field)
                                        <div class="col text-md-end">
                                            <small>
                                                <strong>{{ $field->name }} :</strong><br>
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
                                    <div class="font-weight-bold">{{ __('Item Summary') }}</div>
                                    <small>{{ __('All items here cannot be deleted.') }}</small>
                                    <div class="table-responsive mt-3">
                                        <table class="table  mb-0">
                                            <tr>
                                                <th class="text-dark" data-width="40">#</th>
                                                @if ($bill->bill_module == 'account' || $bill->bill_module == '')
                                                    <th class="text-dark">{{ __('Item Type') }}</th>
                                                    <th class="text-dark">{{ __('Item') }}</th>
                                                @elseif($bill->bill_module == 'taskly')
                                                    <th class="text-dark">{{ __('Project') }}</th>
                                                @endif
                                                <th class="text-dark">{{__('Quantity')}}</th>
                                                <th class="text-dark">{{ __('Rate') }}</th>
                                                <th class="text-dark">{{ __('Discount') }}</th>
                                                <th class="text-dark">{{ __('Tax') }}</th>
                                                <th class="text-dark">{{ __('Description') }}</th>
                                                <th class="text-end text-dark" width="12%">{{ __('Price') }}<br>
                                                    <small
                                                        class="text-danger font-weight-bold">{{ __('after discount & tax') }}</small>
                                                </th>
                                            </tr>
                                            @php
                                                $totalQuantity = 0;
                                                $totalRate = 0;
                                                $totalTaxPrice = 0;
                                                $totalDiscount = 0;
                                                $taxesData = [];
                                                $TaxPrice_array = [];

                                            @endphp

                                            @foreach ($iteams as $key => $iteam)
                                                @if (!empty($iteam->tax))
                                                    @php
                                                        $taxes = Modules\Account\Entities\AccountUtility::tax($iteam->tax);
                                                        $totalQuantity += $iteam->quantity;
                                                        $totalRate += $iteam->price;
                                                        $totalDiscount += $iteam->discount;
                                                        foreach ($taxes as $taxe) {
                                                            $taxDataPrice = Modules\Account\Entities\AccountUtility::taxRate($taxe->rate, $iteam->price, $iteam->quantity, $iteam->discount);
                                                            if (array_key_exists($taxe->name, $taxesData)) {
                                                                $taxesData[$taxe->name] = $taxesData[$taxe->name] + $taxDataPrice;
                                                            } else {
                                                                $taxesData[$taxe->name] = $taxDataPrice;
                                                            }
                                                        }
                                                    @endphp
                                                @endif
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    @if ($bill->bill_module == 'account' || $bill->bill_module == '')
                                                        <td>{{ !empty($iteam->product_type) ? Str::ucfirst($iteam->product_type) : '--' }}
                                                        </td>
                                                        <td>{{ !empty($iteam->product()) ? $iteam->product()->name : '' }}</td>
                                                    @elseif($bill->bill_module == 'taskly')
                                                        <td>{{ !empty($iteam->product()) ? $iteam->product()->title : '' }}</td>
                                                    @endif
                                                    <td>{{ $iteam->quantity }}</td>
                                                    <td>{{ currency_format_with_sym($iteam->price, $company_id, $workspace_id) }}
                                                    </td>
                                                    <td>{{ currency_format_with_sym($iteam->discount, $company_id, $workspace_id) }}
                                                    </td>
                                                    <td>
                                                        @if (!empty($iteam->tax))
                                                            <table>
                                                                @php
                                                                    $totalTaxRate = 0;
                                                                    $data = 0;
                                                                @endphp
                                                                @foreach ($taxes as $tax)
                                                                    @php
                                                                        $taxPrice = Modules\Account\Entities\AccountUtility::taxRate($tax->rate, $iteam->price, $iteam->quantity, $iteam->discount);
                                                                        $totalTaxPrice+=$taxPrice;
                                                                        $data+=$taxPrice;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{ $tax->name . ' (' . $tax->rate . '%)' }}
                                                                        </td>
                                                                        <td>{{ currency_format_with_sym($taxPrice, $company_id, $workspace_id) }}
                                                                        </td>
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
                                                    <td style="white-space: break-spaces;">{{ !empty($iteam->description) ? $iteam->description : '-' }}</td>
                                                    @php
                                                        $tr_tex = (array_key_exists($key,$TaxPrice_array) == true) ? $TaxPrice_array[$key] : 0;
                                                    @endphp
                                                    <td class="text-end">
                                                        {{ currency_format_with_sym($iteam->price * $iteam->quantity - $iteam->discount + $tr_tex, $company_id, $workspace_id) }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            <tfoot>
                                                <tr>
                                                    <td></td>
                                                    @if ($bill->bill_module == 'account' || $bill->bill_module == '')
                                                        <td></td>
                                                    @endif
                                                    <td><b>{{ __('Total') }}</b></td>
                                                    <td><b>{{ $totalQuantity }}</b></td>
                                                    <td><b>{{ currency_format_with_sym($totalRate, $company_id, $workspace_id) }}</b>
                                                    </td>
                                                    <td><b>{{ currency_format_with_sym($totalDiscount, $company_id, $workspace_id) }}</b>
                                                    </td>
                                                    <td><b>{{ currency_format_with_sym($totalTaxPrice, $company_id, $workspace_id) }}</b>
                                                    </td>
                                                    <td></td>
                                                </tr>
                                                @php
                                                    $colspan = 6;
                                                    if ($bill->bill_module == 'account' || $bill->bill_module == '') {
                                                        $colspan = 7;
                                                    }
                                                @endphp
                                                <tr>
                                                    <td colspan="{{ $colspan }}"></td>
                                                    <td class="text-end"><b>{{ __('Sub Total') }}</b></td>
                                                    <td class="text-end">
                                                        {{ currency_format_with_sym($bill->getSubTotal(), $company_id, $workspace_id) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="{{ $colspan }}"></td>
                                                    <td class="text-end"><b>{{ __('Discount') }}</b></td>
                                                    <td class="text-end">
                                                        {{ currency_format_with_sym($bill->getTotalDiscount(), $company_id, $workspace_id) }}
                                                    </td>
                                                </tr>
                                                @if (!empty($taxesData))
                                                    @foreach ($taxesData as $taxName => $taxPrice)
                                                        <tr>
                                                            <td colspan="{{ $colspan }}"></td>
                                                            <td class="text-end"><b>{{ $taxName }}</b></td>
                                                            <td class="text-end">
                                                                {{ currency_format_with_sym($taxPrice, $company_id, $workspace_id) }}
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                <tr>
                                                    <td colspan="{{ $colspan }}"></td>
                                                    <td class="blue-text text-end"><b>{{ __('Total') }}</b></td>
                                                    <td class="blue-text text-end">
                                                        {{ currency_format_with_sym($bill->getTotal(), $company_id, $workspace_id) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="{{ $colspan }}"></td>
                                                    <td class="text-end"><b>{{ __('Paid') }}</b></td>
                                                    <td class="text-end">
                                                        {{ currency_format_with_sym($bill->getTotal() - $bill->getDue() - $bill->billTotalDebitNote(), $company_id, $workspace_id) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="{{ $colspan }}"></td>
                                                    <td class="text-end"><b>{{ __('Debit Note') }}</b></td>
                                                    <td class="text-end">
                                                        {{ currency_format_with_sym($bill->billTotalDebitNote(), $company_id, $workspace_id) }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="{{ $colspan }}"></td>
                                                    <td class="text-end"><b>{{ __('Due') }}</b></td>
                                                    <td class="text-end">
                                                        {{ currency_format_with_sym($bill->getDue(), $company_id, $workspace_id) }}
                                                    </td>
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

        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{ __('Payment Summary') }}</h5>
            <div class="card">
                <div class="card-body table-border-style py-0">
                    <div class="table-responsive m-0">
                        <table class="table ">
                            <tr>
                                <th class="text-dark">{{ __('Date') }}</th>
                                <th class="text-dark">{{ __('Amount') }}</th>
                                <th class="text-dark">{{ __('Account') }}</th>
                                <th class="text-dark">{{ __('Reference') }}</th>
                                <th class="text-dark">{{ __('Description') }}</th>
                            </tr>
                            @forelse($bill->payments as $key =>$payment)
                                <tr>
                                    <td>{{ company_date_formate($payment->date, $company_id, $workspace_id) }}</td>
                                    <td>{{ currency_format_with_sym($payment->amount, $company_id, $workspace_id) }}</td>
                                    <td>{{ !empty($payment->bankAccount) ? $payment->bankAccount->bank_name . ' ' . $payment->bankAccount->holder_name : '' }}
                                    </td>
                                    <td>{{ $payment->reference }}</td>
                                    <td>{{ $payment->description }}</td>
                                </tr>
                            @empty
                                @include('layouts.nodatafound')
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{ __('Debit Note Summary') }}</h5>
            <div class="card">
                <div class="card-body table-border-style py-0">
                    <div class="table-responsive m-0">
                        <table class="table ">
                            <tr>
                                <th class="text-dark">{{ __('Date') }}</th>
                                <th class="text-dark">{{ __('Amount') }}</th>
                                <th class="text-dark">{{ __('Description') }}</th>
                            </tr>
                            @forelse($bill->debitNote as $key =>$debitNote)
                                <tr>
                                    <td>{{ company_date_formate($debitNote->date, $company_id, $workspace_id) }}</td>
                                    <td>{{ currency_format_with_sym($debitNote->amount, $company_id, $workspace_id) }}</td>
                                    <td>{{ $debitNote->description }}</td>
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
@endsection
