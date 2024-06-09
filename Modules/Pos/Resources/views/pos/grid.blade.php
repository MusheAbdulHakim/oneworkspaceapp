@extends('layouts.main')
@section('page-title')
    {{__('POS Order')}}
@endsection
@section('page-breadcrumb')
   {{__('POS Order')}}
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('css/datatable/buttons.dataTables.min.css') }}">
@endpush

@push('scripts')
    <script src="{{ asset('js/letter.avatar.js') }}"></script>
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
    <a href="{{ route('pos.report') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
    title="{{ __('List View') }}">
        <i class="ti ti-list text-white"></i>
    </a>
</div>

@endsection

@section('content')
<div class="row">
    @forelse ($posPayments as $posPayment)
    @if(count($posPayment->items) > 0)
        <div class="col-md-3">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div class="row align-items-center">
                        <div class="col-10">
                            @permission('pos show')
                                <a href="{{ route('pos.show',\Crypt::encrypt($posPayment->id)) }}">
                                    {{ \Modules\Pos\Entities\Pos::posNumberFormat($posPayment->id) }}
                                </a>
                            @else
                                <a href="#">
                                    {{ \Modules\Pos\Entities\Pos::posNumberFormat($posPayment->id) }}
                                </a>
                            @endpermission
                        </div>
                        <div class="col-2">
                            <div class="card-header-right">
                                <div class="btn-group card-option">
                                    <button type="button" class="btn dropdown-toggle"
                                        data-bs-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false">
                                        <i class="feather icon-more-vertical"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        @if (Laratrust::hasPermission('pos show'))
                                            @permission('pos show')
                                                <a href="{{ route('pos.show',\Crypt::encrypt($posPayment->id)) }}" class="dropdown-item"
                                                    data-size="md" data-bs-whatever="{{ __('Contract Details') }}"
                                                    data-bs-toggle="tooltip"><i class="ti ti-eye"></i>
                                                    {{ __('Details') }}</a>
                                            @endpermission
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-2 justify-content-between">
                        <div class="col-12">
                            <div class="text-center client-box">
                                <div class="avatar-parent-child">
                                    <img alt="user-image" class="img-fluid rounded-circle" @if(!empty($posPayment->avatar)) src="{{(!empty($posPayment->avatar))? get_file("profile/".$posPayment->avatar): asset(url("./assets/img/clients/160x160/img-1.png"))}}" @else   @if($posPayment->customer_id == 0)avatar="{{__('Walk-in Customer')}}" @else  avatar="@if(module_is_active('Account')) {{!empty($posPayment->customer) ? $posPayment->customer->name : ''}} @else '' @endif " @endif @endif>
                                </div>
                                <h5 class="h6 mt-2 mb-1 text-primary">
                                    @if($posPayment->customer_id == 0)
                                        <a href="{{  route('pos.show',\Crypt::encrypt($posPayment->id)) }}"  data-title="{{__('Purchase Details')}}" class="action-item text-primary mt-2">
                                            {{ ucfirst('Walk-in Customer') }}
                                        </a>
                                    @else
                                        @if(module_is_active('Account'))
                                            <a href="{{  route('pos.show',\Crypt::encrypt($posPayment->id)) }}"  data-title="{{__('Purchase Details')}}" class="action-item text-primary mt-2">
                                                {{ ucfirst(!empty($posPayment->customer) ? $posPayment->customer->name : '') }}
                                            </a>
                                        @else
                                            <a href="{{  route('pos.show',\Crypt::encrypt($posPayment->id)) }}"  data-title="{{__('Purchase Details')}}" class="action-item text-primary mt-2">
                                                {{ ucfirst('-') }}
                                            </a>
                                        @endif
                                    @endif
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    @endforeach
</div>
@endsection
