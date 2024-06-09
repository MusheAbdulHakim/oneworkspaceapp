    @extends('layouts.main')
@section('page-title')
    {{__('POS Order')}}
@endsection
@section('page-breadcrumb')
   {{__('POS Order')}}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('Modules/Pos/Resources/assets/css/buttons.dataTables.min.css') }}">
@endpush

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>

        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).save();
        }
    </script>

@endpush

@section('page-action')
<div>
    @stack('addButtonHook')
    <a href="{{ route('pos.grid') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"title="{{ __('Grid View') }}">
        <i class="ti ti-layout-grid text-white"></i>
    </a>
</div>

@endsection


@section('content')
    <div id="printableArea">

    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                            <tr>
                                <th>{{__('POS ID')}}</th>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Warehouse') }}</th>
                                <th>{{ __('Amount') }}</th>

                            </tr>
                            </thead>

                            <tbody>

                            @forelse ($posPayments as $posPayment)
                                @if(count($posPayment->items) > 0)
                                    <tr>
                                        <td class="Id">
                                            @permission('pos show')
                                                <a href="{{ route('pos.show',\Crypt::encrypt($posPayment->id)) }}" class="btn btn-outline-primary">
                                                    {{ \Modules\Pos\Entities\Pos::posNumberFormat($posPayment->id) }}
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-outline-primary">
                                                    {{ \Modules\Pos\Entities\Pos::posNumberFormat($posPayment->id) }}
                                                </a>
                                            @endpermission
                                        </td>
                                        <td>{{ company_date_formate($posPayment->created_at)}}</td>
                                        @if($posPayment->customer_id == 0)
                                            <td class="">{{__('Walk-in Customer')}}</td>
                                        @elseif(empty($posPayment->customer))
                                            <td>{{!empty($posPayment->user)?$posPayment->user->name:''}}</td>
                                        @else
                                            <td>{{ !empty($posPayment->customer) ? $posPayment->customer->name : '' }} </td>
                                        @endif

                                        <td>{{ !empty($posPayment->warehouse) ? $posPayment->warehouse->name : '' }} </td>
                                        <td>{{!empty($posPayment->posPayment)? currency_format_with_sym($posPayment->posPayment->amount):currency_format_with_sym(0)}}</td>
                                    </tr>
                                @endif
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
