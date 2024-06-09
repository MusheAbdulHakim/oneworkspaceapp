@extends('layouts.main')
@section('page-title')
    {{ __('Proposal Detail') }}
@endsection
@section('page-breadcrumb')
    {{ __('Proposal') }}
@endsection
@push('scripts')
    <script>
        $(document).on('change', '.status_change', function () {
            var status = this.value;
            var url = $(this).data('url');
            $.ajax({
                url: url + '?status=' + status,
                type: 'GET',
                cache: false,
                success: function (data) {
                    location.reload();
                },
            });
        });

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
            maxFiles: 20,
            maxFilesize: 20,
            parallelUploads: 1,
            acceptedFiles: ".jpeg,.jpg,.png,.pdf,.doc,.txt",
            url: "{{ route('proposal.file.upload', [$proposal->id]) }}",
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
            formData.append("proposal_id", {{ $proposal->id }});
        });
    </script>
@endpush
@section('page-action')
    <div>
        @if($proposal->is_convert==0)
            @permission('proposal convert invoice')
                <div class="action-btn mb-1">
                    {!! Form::open(['method' => 'get', 'route' => ['proposal.convert', $proposal->id],'id'=>'proposal-form-'.$proposal->id]) !!}
                        <a href="#"
                            class="btn btn-sm bg-success align-items-center bs-pass-para show_confirm"
                            data-bs-toggle="tooltip" title="" data-bs-original-title="{{ __('Convert to Invoice')}}"
                            aria-label="Delete" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="proposal-form-{{$proposal->id}}">
                        <i class="ti ti-exchange text-white"></i>
                        </a>
                    {{Form::close()}}
                </div>
            @endpermission
        @else
            @permission('invoice show')
                <div class="action-btn ms-2">
                    <a href="{{ route('invoice.show',\Crypt::encrypt($proposal->converted_invoice_id)) }}" class="btn btn-sm bg-success align-items-center" data-bs-toggle="tooltip" title="{{__('Already convert to Invoice')}}" >
                        <i class="ti ti-eye text-white"></i>
                    </a>
                </div>
            @endpermission
        @endif

        @if (module_is_active('Retainer'))
            @include('retainer::setting.convert_retainer', ['proposal' => $proposal, 'type' => 'view'])
        @endif
        <div class="action-btn ms-2">
            <a href="#" class="btn btn-sm bg-primary align-items-center cp_link" data-link="{{route('pay.proposalpay',\Illuminate\Support\Facades\Crypt::encrypt($proposal->id))}}" data-bs-toggle="tooltip" title="{{__('Copy')}}" data-original-title="{{__('Click to copy invoice link')}}">
                <i class="ti ti-file text-white"></i>
            </a>
        </div>
    </div>
@endsection
@section('content')
    @permission('proposal send')
        @if($proposal->status!=4)
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row timeline-wrapper">
                                <div class="col-md-6 col-lg-4 col-xl-4">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="ti ti-plus text-primary"></i>
                                    </div>
                                    <h6 class="text-primary my-3">{{__('Create Proposal')}}</h6>
                                    <p class="text-muted text-sm mb-3"><i class="ti ti-clock mr-2"></i>{{__('Created on ')}}{{ company_date_formate($proposal->issue_date)}}</p>
                                    @permission('proposal edit')
                                        <a href="{{ route('proposal.edit',\Crypt::encrypt($proposal->id)) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="ti ti-pencil mr-2"></i>{{__('Edit')}}</a>
                                    @endpermission
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="ti ti-mail text-warning"></i>
                                    </div>
                                    <h6 class="text-warning my-3">{{__('Send Proposal')}}</h6>
                                    <p class="text-muted text-sm mb-3">
                                        @if($proposal->status!=0)
                                            <i class="ti ti-clock mr-2"></i>{{__('Sent on')}} {{ company_date_formate($proposal->send_date)}}
                                        @else
                                            @permission('proposal send')
                                                <small>{{__('Status')}} : {{__('Not Sent')}}</small>
                                            @endpermission
                                        @endif
                                    </p>
                                    @if($proposal->status==0)
                                        @permission('proposal send')
                                            <a href="{{ route('proposal.sent',$proposal->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" data-original-title="{{__('Mark Sent')}}"><i class="ti ti-send mr-2"></i>{{__('Send')}}</a>
                                        @endpermission
                                    @endif
                                </div>
                                <div class="col-md-6 col-lg-4 col-xl-4">
                                    <div class="timeline-icons"><span class="timeline-dots"></span>
                                        <i class="ti ti-report-money text-info"></i>
                                    </div>
                                    <h6 class="text-info my-3">{{__('Proposal Status')}}</h6>
                                    <small>
                                        @if($proposal->status == 0)
                                            <span class="badge fix_badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 1)
                                            <span class="badge fix_badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 2)
                                            <span class="badge fix_badge bg-secondary p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 3)
                                            <span class="badge fix_badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 4)
                                            <span class="badge fix_badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                        @endif
                                    </small>
                                    <br>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endpermission
    <div class="row justify-content-between align-items-center mb-3">
        <div class="col-md-4">
            <ul class="nav nav-pills nav-fill cust-nav information-tab" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="proposal-tab" data-bs-toggle="pill"
                        data-bs-target="#proposal" type="button">{{ __('Proposal') }}</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="proposal-attechment-tab" data-bs-toggle="pill"
                        data-bs-target="#proposal-attechment" type="button">{{ __('Attechment') }}</button>
                </li>
            </ul>
        </div>
        <div class="col-md-8 d-flex align-items-center justify-content-between justify-content-md-end">
            <div class="row align-items-center ">
                <div class="col-md-12 d-flex align-items-center justify-content-md-end">
                    @if(\Auth::user()->type=='company' && $proposal->status!=0)
                        <div class="all-button-box mx-2">
                            <a href="{{ route('proposal.resent',$proposal->id) }}" class="btn btn-xs btn-primary btn-icon-only width-auto">{{__('Resend Proposal')}}</a>
                        </div>
                    @endif
                    <div class="all-button-box">
                        <a href="{{ route('proposal.pdf', Crypt::encrypt($proposal->id))}}" class="btn btn-xs btn-primary btn-icon-only width-auto" target="_blank">{{__('Download')}}</a>
                    </div>
                    <div class="all-button-box mx-2">
                        <select class="form-control status_change pe-5" name="status" data-url="{{route('proposal.status.change',$proposal->id)}}">
                            @foreach($status as $k=>$val)
                                <option value="{{$k}}" {{($proposal->status==$k)?'selected':''}}>{{$val}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade active show" id="proposal" role="tabpanel"
                aria-labelledby="pills-user-tab-1">
                    <div class="card">
                        <div class="card-body">
                            <div class="invoice">
                                <div class="invoice-print">
                                    <div class="row invoice-title mt-2">
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12">
                                            <h2>{{__('Proposal')}}</h2>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-12 text-end">
                                            <h3 class="invoice-number">{{ \App\Models\Proposal::proposalNumberFormat($proposal->proposal_id) }}</h3>
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
                                                        {{ company_date_formate($proposal->issue_date)}}<br><br>
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
                                        @if(!empty($company_settings['proposal_shipping_display']) && $company_settings['proposal_shipping_display'] =='on')
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
                                                    {!! DNS2D::getBarcodeHTML(route('pay.proposalpay',\Illuminate\Support\Facades\Crypt::encrypt($proposal->id)), "QRCODE",2,2) !!}
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col">
                                            <small>
                                                <strong>{{__('Status')}} :</strong><br>
                                                @if($proposal->status == 0)
                                                    <span class="badge fix_badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                                @elseif($proposal->status == 1)
                                                    <span class="badge fix_badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                                @elseif($proposal->status == 2)
                                                    <span class="badge fix_badge bg-secondary p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                                @elseif($proposal->status == 3)
                                                    <span class="badge fix_badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                                @elseif($proposal->status == 4)
                                                    <span class="badge fix_badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                                @endif
                                            </small>
                                        </div>


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
                                    <div class="row mt-4">
                                        <div class="col-md-12">
                                            <div class="font-weight-bold">{{__('Item Summary')}}</div>
                                            <small>{{__('All items here cannot be deleted.')}}</small>
                                            <div class="table-responsive mt-2">
                                                <table class="table mb-0 table-striped">
                                                    <tr>
                                                        <th class="text-dark" data-width="40">#</th>
                                                        @if($proposal->proposal_module == "account" || "cmms")
                                                            <th class="text-dark">{{__('Item Type')}}</th>
                                                            <th class="text-dark">{{__('Item')}}</th>
                                                        @elseif($proposal->proposal_module == "taskly")
                                                        <th class="text-dark">{{__('Project')}}</th>
                                                        @endif
                                                        <th class="text-dark">{{__('Quantity')}}</th>
                                                        <th class="text-dark">{{__('Rate')}}</th>
                                                        <th class="text-dark">{{__('Discount')}}</th>
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
                                                                $taxes= \App\Models\Proposal::tax($iteam->tax);
                                                                $totalQuantity+=$iteam->quantity;
                                                                $totalRate+=$iteam->price;
                                                                $totalDiscount+=$iteam->discount;
                                                                foreach($taxes as $taxe){
                                                                    $taxDataPrice= \App\Models\Proposal::taxRate($taxe->rate,$iteam->price,$iteam->quantity,$iteam->discount);
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
                                                            @elseif($proposal->proposal_module == 'cmms')
                                                                <td>{{ !empty($iteam->product_type) ? Str::ucfirst($iteam->product_type) : '--' }}
                                                                </td>
                                                                <td>{{ !empty($iteam->product()) ? $iteam->product()->name : '' }}</td>
                                                                @endif
                                                            <td>{{$iteam->quantity}}</td>
                                                            <td>{{ currency_format_with_sym($iteam->price)}}</td>
                                                            <td>
                                                                    {{ currency_format_with_sym($iteam->discount)}}
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
                                                                                $taxPrice= \App\Models\Proposal::taxRate($tax->rate,$iteam->price,$iteam->quantity,$iteam->discount);
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
                                                            @php
                                                                $tr_tex = (array_key_exists($key,$TaxPrice_array) == true) ? $TaxPrice_array[$key] : 0;
                                                            @endphp
                                                            <td style="white-space: break-spaces;">{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                                            <td class="text-end">{{ currency_format_with_sym(($iteam->price*$iteam->quantity) -$iteam->discount + $tr_tex )}}</td>
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
                                                        <td><b>{{ currency_format_with_sym ($totalRate)}}</b></td>
                                                        <td><b>{{ currency_format_with_sym ($totalDiscount)}}</b></td>
                                                        <td><b>{{ currency_format_with_sym ($totalTaxPrice)}}</b></td>
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
                                                        <td class="text-end">{{ currency_format_with_sym   ($proposal->getSubTotal())}}</td>
                                                    </tr>
                                                    <tr>

                                                        <td colspan="{{$colspan}}"></td>
                                                        <td class="text-end"><b>{{__('Discount')}}</b></td>
                                                        <td class="text-end">{{ currency_format_with_sym   ($proposal->getTotalDiscount())}}</td>
                                                    </tr>
                                                    @if(!empty($taxesData))
                                                        @foreach($taxesData as $taxName => $taxPrice)
                                                            <tr>
                                                                <td colspan="{{$colspan}}"></td>
                                                                <td class="text-end"><b>{{$taxName}}</b></td>
                                                                <td class="text-end">{{  currency_format_with_sym  ($taxPrice) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                    <tr>
                                                        <td colspan="{{$colspan}}"></td>
                                                        <td class="blue-text text-end"><b>{{__('Total')}}</b></td>
                                                        <td class="blue-text text-end">{{ currency_format_with_sym ($proposal->getTotal())}}</td>
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
                <div class="tab-pane fade " id="proposal-attechment" role="tabpanel"
                aria-labelledby="pills-user-tab-1">
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
                                            @forelse($proposal_attachment as $key =>$attachment)

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
                                                                {{Form::open(array('route'=>array('proposal.attachment.destroy',$attachment->id),'class' => 'm-0'))}}
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
            </div>
        </div>
    </div>


@endsection
