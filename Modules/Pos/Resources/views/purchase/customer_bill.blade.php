<!DOCTYPE html>
<html lang="en">
<head>
    <title>{{__('WorkDo')}}</title>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>

    <!-- Meta -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <link rel="icon" href="{{ get_file(company_setting('favicon',$purchase->created_by, $purchase->workspace))}}" type="image/x-icon" />



    @stack('css')
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/main.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/flatpickr.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/plugins/animate.min.css') }}">



    <!-- font css -->
    <link rel="stylesheet" href="{{ asset('assets/fonts/tabler-icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/feather.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/material.css') }}">

    <!--bootstrap switch-->
    <link rel="stylesheet" href="{{ asset('assets/css/plugins/bootstrap-switch-button.min.css') }}">

    <!-- vendor css -->
    @if(company_setting('site_rtl',$purchase->created_by, $purchase->workspace) =='on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    @endif
    @if(company_setting('cust_darklayout',$purchase->created_by, $purchase->workspace) == 'on')
        <link rel="stylesheet" href="{{ asset('assets/css/style-dark.css') }}">
    @else
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" id="main-style-link">
    @endif

    <link rel="stylesheet" href="{{ asset('assets/css/customizer.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}" id="main-style-link">

    @stack('css')
</head>

<body class="{{ !empty(company_setting('color',$purchase->created_by, $purchase->workspace))?company_setting('color',$purchase->created_by, $purchase->workspace):'theme-3' }}">
<header class="header header-transparent" id="header-main">

</header>

<div class="main-content container">

    <div class="row justify-content-between align-items-center mb-3">
        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">

            <div class="all-button-box mx-2">

                <a href="{{ route('purchase.pdf', \Crypt::encrypt($purchase->id))}}" target="_blank" class="btn btn-primary mt-3">
                    {{__('Download')}}
                </a>
            </div>

        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                    <h2>{{__('Purchase')}}</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                    <h3 class="invoice-number float-right"></h3>

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
                                                <strong>{{__('Pos Date')}} :</strong><br>
                                                {{company_date_formate($purchase->pos_date,$purchase->created_by, $purchase->workspace)}}<br><br>
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
                                        </small>
                                    </div>
                                @endif
                                @if(company_setting('shipping_display',$purchase->created_by, $purchase->workspace)=='on')
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
                                        </small>
                                    </div>
                                @endif
                                <div class="col">
                                    <div class="float-end mt-3">
                                        {!! DNS2D::getBarcodeHTML(route('purchase.link.copy',\Illuminate\Support\Facades\Crypt::encrypt($purchase->id)), "QRCODE",2,2) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong>{{__('Status')}} :</strong><br>
                                        @if($purchase->status == 0)
                                            <span class="badge bg-primary">{{ __(Modules\Account\Entities\Bill::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 1)
                                            <span class="badge bg-warning">{{ __(Modules\Account\Entities\Bill::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 2)
                                            <span class="badge bg-danger">{{ __(Modules\Account\Entities\Bill::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 3)
                                            <span class="badge bg-info">{{ __(Modules\Account\Entities\Bill::$statues[$purchase->status]) }}</span>
                                        @elseif($purchase->status == 4)
                                            <span class="badge bg-success">{{ __(Modules\Account\Entities\Bill::$statues[$purchase->status]) }}</span>
                                        @endif
                                    </small>
                                </div>



                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-weight-bold">{{__('Item Summary')}}</div>
                                    <small>{{__('All items here cannot be deleted.')}}</small>
                                    <div class="table-responsive mt-2">
                                        <table class="table table-striped">
                                            <tr>
                                                <th class="text-dark" data-width="40">#</th>
                                                <th class="text-dark">{{__('Item Type')}}</th>
                                                <th class="text-dark">{{__('Item')}}</th>
                                                <th class="text-dark">{{__('Quantity')}}</th>
                                                <th class="text-dark">{{__('Rate')}}</th>
                                                <th class="text-dark">{{__('Tax')}}</th>
                                                <th class="text-dark">
                                                        {{__('Discount')}}

                                                </th>
                                                <th class="text-dark">{{__('Description')}}</th>
                                                <th class="text-end text-dark" width="12%">{{__('Price')}}<br>
                                                    <small class="text-danger ">{{__('before tax & discount')}}</small>
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
                                                            $taxDataPrice=App\Models\Purchase::taxRate($taxe->rate,$iteam->price,$iteam->quantity);
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
                                                    <td>{{currency_format_with_sym($iteam->price,$purchase->created_by, $purchase->workspace)}}</td>
                                                    <td>
                                                        @if(!empty($iteam->tax))
                                                            <table>
                                                                @php
                                                                    $totalTaxRate = 0;
                                                                    $data=0;
                                                                @endphp
                                                                @foreach($taxes as $tax)
                                                                    @php
                                                                        $taxPrice=App\Models\Purchase::taxRate($tax->rate,$iteam->price,$iteam->quantity);
                                                                        $totalTaxPrice+=$taxPrice;
                                                                        $data+=$taxPrice;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{$tax->name .' ('.$tax->rate .'%)'}}</td>
                                                                        <td>{{currency_format_with_sym($taxPrice,$purchase->created_by, $purchase->workspace)}}</td>
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
                                                    <td>
                                                            {{currency_format_with_sym($iteam->discount,$purchase->created_by, $purchase->workspace)}}

                                                    </td>
                                                    <td>{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                                    <td class="text-end">{{currency_format_with_sym(($iteam->price*$iteam->quantity),$purchase->created_by, $purchase->workspace)}}</td>
                                                </tr>
                                            @endforeach
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><b>{{__('Total')}}</b></td>
                                                <td><b>{{$totalQuantity}}</b></td>
                                                <td><b>{{currency_format_with_sym($totalRate,$purchase->created_by, $purchase->workspace)}}</b></td>
                                                <td><b>{{currency_format_with_sym($totalTaxPrice,$purchase->created_by, $purchase->workspace)}}</b></td>
                                                <td>
                                                        <b>{{currency_format_with_sym($totalDiscount,$purchase->created_by, $purchase->workspace)}}</b>

                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="7"></td>
                                                <td class="text-end"><b>{{__('Sub Total')}}</b></td>
                                                <td class="text-end">{{currency_format_with_sym($purchase->getSubTotal(),$purchase->created_by, $purchase->workspace)}}</td>
                                            </tr>

                                                <tr>
                                                    <td colspan="7"></td>
                                                    <td class="text-end"><b>{{__('Discount')}}</b></td>
                                                    <td class="text-end">{{currency_format_with_sym($purchase->getTotalDiscount(),$purchase->created_by, $purchase->workspace)}}</td>
                                                </tr>

                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    <tr>
                                                        <td colspan="7"></td>
                                                        <td class="text-end"><b>{{$taxName}}</b></td>
                                                        <td class="text-end">{{ currency_format_with_sym($taxPrice,$purchase->created_by, $purchase->workspace) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="7"></td>
                                                <td class="blue-text text-end"><b>{{__('Total')}}</b></td>
                                                <td class="blue-text text-end">{{currency_format_with_sym($purchase->getTotal(),$purchase->created_by, $purchase->workspace)}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="7"></td>
                                                <td class="text-end"><b>{{__('Paid')}}</b></td>
                                                <td class="text-end">{{currency_format_with_sym(($purchase->getTotal()-$purchase->getDue()),$purchase->created_by, $purchase->workspace)}}</td>
                                            </tr>

                                            <tr>
                                                <td colspan="7"></td>
                                                <td class="text-end"><b>{{__('Due')}}</b></td>
                                                <td class="text-end">{{currency_format_with_sym($purchase->getDue(),$purchase->created_by, $purchase->workspace)}}</td>
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

</div>

<footer id="footer-main">
    <div class="footer-dark">
        <div class="container">
            <div class="row align-items-center justify-content-md-between py-4 mt-4 delimiter-top">
                <div class="col-md-6">
                    <div class="copyright text-sm font-weight-bold text-center text-md-left">
                        {{!empty(company_setting('purchase_footer_title',$purchase->created_by, $purchase->workspace)) ? company_setting('purchase_footer_title',$purchase->created_by, $purchase->workspace) : ''}}
                    </div>
                </div>
                <div class="col-md-6">
                    <ul class="nav justify-content-center justify-content-md-end mt-3 mt-md-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#" target="_blank">
                                <i class="fab fa-dribbble"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" target="_blank">
                                <i class="fab fa-github"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" target="_blank">
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>


@if($message = Session::get('success'))
    <script>
        show_toastr('success', '{!! $message !!}');
    </script>
@endif
@if($message = Session::get('error'))
    <script>
        show_toastr('error', '{!! $message !!}');
    </script>
@endif


