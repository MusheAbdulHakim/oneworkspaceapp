@extends('layouts.main')

@section('page-title')
    {{__('GoHighLevel Invoices')}}
@endsection

@section('page-breadcrumb')
    {{ __('Invoices')}}
@endsection


@section('content')
    @if(!empty($invoices) && count($invoices) > 0)
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="calendars_table" class="table table-bordered dt-responsive pc-dt-simple">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Invoice Number</th>
                                    <th>Name</th>
                                    <th>Currency</th>
                                    <th>Amount</th>
                                    <th>Date Issued</th>
                                    <th>Due Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoices as $i => $invoice)
                                    @if(!empty($invoice) && is_array($invoice))
                                    <tr>
                                        <th>{{++$i}}</th>
                                        <td>{{$invoice['invoiceNumber']}}</td>
                                        <td>{{$invoice['name']}}</td>
                                        <td>{{$invoice['currency']}}</td>
                                        <td>{{$calendar['total']}}</td>
                                        <td>{{$calendar['issueDate']}}</td>
                                        <td>{{$calendar['dueDate']}}</td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        (function() {
            
        });
    </script>
@endpush