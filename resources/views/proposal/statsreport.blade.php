@extends('layouts.main')
@section('page-title')
    {{ __('Proposal') }}
@endsection
@section('page-breadcrumb')
    {{ __('Proposal') }}
@endsection
@section('page-action')
    <div>
        <a href="{{ route('proposal.index') }}"  data-bs-toggle="tooltip" data-bs-original-title="{{__('All Proposal')}}" class="btn btn-sm btn-primary btn-icon">
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
                        $proposals = App\Models\Proposal::where('workspace', getActiveWorkSpace())
                            ->where('status', $key)
                            ->get();
                        $countstatus = $proposals->count();

                        $total = $countstatus != 0 ? number_format($countstatus / $total_proposals, 4) : 0;
                        $totalpercentage = $total*100;
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
                                            <h6 class="mb-0">{{ $countstatus }}</h6>
                                            <span class="text-sm text-muted">{{ __('Total') }}</span>
                                        </div>
                                        <div class="col-md-6 text-end">
                                            <h6 class="mb-0">{{ $totalpercentage }}{{ __('%') }}
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
