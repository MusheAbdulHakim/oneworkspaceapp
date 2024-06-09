@extends('layouts.main')
@section('page-title')
    {{__('Warehouse Stock Details')}}
@endsection

@push('scripts')
@endpush
@section('page-breadcrumb')
   {{__('Warehouse Stock Details')}}
@endsection
@section('action-btn')
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                            <tr>
                                <th>{{ __('Product') }}</th>
                                <th>{{ __('Quantity') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($warehouse as $warehouses)
                                <tr class="font-style">

                                    <td>{{ !empty($warehouses->product)? $warehouses->product->name:'' }}</td>
                                    <td>{{ $warehouses->quantity }}</td>
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection

