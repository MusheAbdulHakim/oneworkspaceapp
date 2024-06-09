@extends('layouts.main')
@section('page-title')
    {{ __('Product Stock') }}
@endsection
@section('page-breadcrumb')
    {{ __('Report') }},
    {{ __('Product Stock Report') }}
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th>{{__('Date')}}</th>
                                    <th>{{__('Product Name')}}</th>
                                    <th>{{__('Quantity')}}</th>
                                    <th>{{__('Type')}}</th>
                                    <th>{{__('Description')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($stocks as $stock)
                                    <tr>
                                        <td class="font-style">{{ company_date_formate($stock->created_at)}}</td>
                                        @if(module_is_active('ProductService'))
                                            <td>{{ !empty($stock->name) ? $stock->name : '' }}</td>
                                        @else
                                            <td class="text-info">{{ __('Product & Service Module is Off') }}</td>
                                        @endif
                                        <td class="font-style">{{ $stock->quantity }}</td>
                                        <td class="font-style">{{ ucfirst($stock->type) }}</td>
                                        <td class="font-style">{{$stock->description}}</td>
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

