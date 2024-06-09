<div class="modal-body">
    <div class="row">
        <div class="text-md-end ">
            <a  class="btn btn-sm btn-primary text-white" data-bs-toggle="tooltip"
                data-bs-toggle="bottom" title="{{ __('Download') }}" onclick="saveAsPDF()" ><span
                    class="ti ti-download" ></span></a>
        </div>
        <div class="col-form-label"  id="printableArea">
            <div class="invoice-number">
                <img src="{{ get_file(sidebar_logo())}}"
                    width="140px;">
            </div>
                <div class="invoice">
                    <div class="card-body">
                        <div class="row align-items-center mb-4">
                            <div class="col-lg-12">
                                <div class="invoice-title">
                                </div>
                                <hr>
                                <div class="row ">
                                    <div class="col-md-6">
                                        <address>
                                            <div class="my-2">
                                                <strong class="mt-2">{{ __('Invoice ID') }} :</strong> {{ \App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id) }}<br>
                                            </div>
                                            <div class="my-2">
                                                <strong>{{ __('Invoice Date') }} :</strong>  {{ company_date_formate($invoice->issue_date) }}<br>
                                            </div>
                                            <div class="my-2">
                                                <strong>{{ __('Invoice') }} :</strong>
                                                @if ($invoice->status == 0)
                                                    <span
                                                        class="badge fix_badge rounded p-1 px-3 bg-primary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 1)
                                                    <span
                                                        class="badge fix_badge rounded p-1 px-3 bg-info">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 2)
                                                    <span
                                                        class="badge fix_badge rounded p-1 px-3 bg-secondary">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 3)
                                                    <span
                                                        class="badge fix_badge rounded p-1 px-3 bg-warning">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @elseif($invoice->status == 4)
                                                    <span
                                                        class="badge fix_badge rounded p-1 px-3 bg-danger">{{ __(\App\Models\Invoice::$statues[$invoice->status]) }}</span>
                                                @endif<br>
                                            </div>
                                        </address>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        @if (module_is_active('Zatca'))
                                            <div class="float-end ">
                                                @include('zatca::zatca_qr_code', [
                                                    'invoice_id' => $invoice->id,
                                                ])
                                            </div>
                                        @else
                                            <div class="float-end ">
                                                {!! DNS2D::getBarcodeHTML(
                                                    route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice->id)),
                                                    'QRCODE',
                                                    2,
                                                    2,
                                                ) !!}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 ">
                                <h5 class="px-2 py-2"><b>{{__('Item List')}}</b></h5>
                                <div class="table-responsive mt-4">
                                    <table class="table invoice-detail-table mb-0">
                                        <thead>
                                            <tr class="thead-default">
                                                @if ($invoice->invoice_module == 'account')
                                                    <th class="text-dark">{{ __('Item') }}</th>
                                                @elseif($invoice->invoice_module == 'taskly')
                                                    <th class="text-dark">{{ __('Project') }}</th>
                                                @endif

                                                <th class="text-dark">{{ __('Description') }}</th>
                                                <th class="text-dark">{{ __('Quantity') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($iteams as $key => $iteam)
                                                <tr>
                                                    @if ($invoice->invoice_module == 'account')
                                                        <td>{{ !empty($iteam->product()) ? $iteam->product()->name : '' }}</td>
                                                    @elseif($invoice->invoice_module == 'taskly')
                                                        <td>{{ !empty($iteam->product()) ? $iteam->product()->title : '' }}</td>
                                                    @endif
                                                    <td style="white-space: break-spaces;">{{ !empty($iteam->description) ? $iteam->description :$iteam->product()->description  }}</td>
                                                    <td>{{ $iteam->quantity }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="invoice-total d-flex align-items-end flex-column justify-content-end" style="min-height: 200px;">
                                        <h6 class=" m-r-10">{{__('Customer Signature')}} </h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

    </div>
</div>
<script src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
<script>
    function saveAsPDF() {
        var element = document.getElementById('printableArea');
        var opt = {
            margin: 0.3,
            filename: '{{ \App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id,$invoice->created_by)}}',
            image: {
                type: 'jpeg',
                quality: 1
            },
            html2canvas: {
                scale: 4,
                dpi: 72,
                letterRendering: true
            },
            jsPDF: {
                unit: 'in',
                format: 'A4'
            }
        };
        html2pdf().set(opt).from(element).save();
    }
</script>
