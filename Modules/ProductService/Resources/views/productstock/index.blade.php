@extends('layouts.main')
@section('page-title')
    {{__('Manage Product Stock')}}
@endsection
@push('script-page')
@endpush
@section('page-breadcrumb')
{{__('Product')}}
{{__('Product Stock')}}
@endsection
@section('page-action')
<div>
    @if (URL::previous() == URL::current())
    <a href="{{ route('product-service.index') }}" class="btn-submit btn btn-sm btn-primary " data-toggle="tooltip"
        title="{{ __('Back') }}">
        <i class=" ti ti-arrow-back-up"></i> </a>
    @else
    <a href="{{ url(URL::previous()) }}" class="btn-submit btn btn-sm btn-primary " data-toggle="tooltip"
        title="{{ __('Back') }}">
        <i class=" ti ti-arrow-back-up"></i> </a>
    @endif
</div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="products">
                            <thead>
                            <tr>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Sku') }}</th>
                                <th>{{ __('Current Quantity') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($productServices as $productService)
                                <tr class="font-style">
                                    <td>{{ $productService->name }}</td>
                                    <td>{{ $productService->sku }}</td>
                                    <td>{{ $productService->quantity }}</td>

                                    <td class="Action">
                                        <div class="action-btn bg-info ms-2">
                                            <a data-size="md"  class="mx-3 btn btn-sm d-inline-flex align-items-center" data-url="{{ route('productstock.edit', $productService->id) }}" data-ajax-popup="true"  data-size="xl" data-bs-toggle="tooltip" title="{{__('Add Quantity')}}" data-title="{{ __('Add Quantity') }}">
                                                <i class="ti ti-plus text-white"></i>
                                            </a>
                                        </div>


                                    </td>

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
