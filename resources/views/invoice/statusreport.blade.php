@extends('layouts.main')
@section('page-title')
    {{ __('Invoices') }}
@endsection
@section('page-breadcrumb')
    {{ __('Invoices') }}
@endsection
@section('page-action')
    <div>

        <a href="{{ route('invoice.index') }}"  data-bs-toggle="tooltip" data-bs-original-title="{{__('All Invoice')}}" class="btn btn-sm btn-primary btn-icon">
            <i class="ti ti-filter"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="row">
                @foreach ($statues as $key => $status)
                    @php
                        $invoices = App\Models\Invoice::where('workspace', getActiveWorkSpace())
                            ->where('status', $key)
                            ->get();
                        $countstatus = $invoices->count();
                        $totalAmount = 0;
                        foreach ($invoices as $invoice) {
                            $totalAmount += $invoice->getDue();
                        }

                        $total = $totalDueAmount != 0 ? number_format($totalAmount / $totalDueAmount, 4) : 0;
                    @endphp

                    <div class="col-lg-4" id="{{ $key }}">
                        <div class="card hover-shadow-lg">
                            <div class="card-header">
                                <div class="float-end">
                                    <button class="btn btn-sm btn-primary btn-icon count">
                                        {{ $countstatus }}
                                    </button>
                                </div>
                                <h4 class="mb-0">{{ $status }}</h4>
                            </div>
                            <div class="card-body">
                                <div class="p-3">
                                    <div class="row align-items-center mt-3">
                                        <div class="col-md-6">
                                            <h6 class="mb-0">{{ currency_format_with_sym($totalAmount) }}</h6>
                                            <span class="text-sm text-muted">{{ __('Total Amount') }}</span>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <h6 class="mb-0">{{ $total * 100 }}{{ __('%') }}
                                            </h6>
                                            <span class="text-sm text-muted">{{ __('Total Percentage') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@endsection
