@extends('layouts.main')
@section('page-title')
{{__('Manage Items')}}
@endsection
@section('page-breadcrumb')
{{ __('Items') }}
@endsection
@section('page-action')
@permission('product&service create')
<div>
        @stack('addButtonHook')
        @permission('product&service import')
            <a href="#"  class="btn btn-sm btn-primary" data-ajax-popup="true" data-title="{{__('Product & Service Import')}}" data-url="{{ route('product-service.file.import') }}"  data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
            </a>
        @endpermission
        <a href="{{ route('product-service.grid') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-title="{{__('Grid View')}}" title="{{ __('Grid View') }}"><i class="ti ti-layout-grid text-white"></i></a>

        <a href="{{ route('category.index') }}"data-size="md"  class="btn btn-sm btn-primary" data-bs-toggle="tooltip"data-title="{{__('Setup')}}" title="{{__('Setup')}}"><i class="ti ti-settings"></i></a>

        <a href="{{ route('productstock.index') }}"data-size="md"  class="btn btn-sm btn-primary" data-bs-toggle="tooltip"data-title="{{__(' Product Stock')}}" title="{{__('Product Stock')}}"><i class="ti ti-shopping-cart"></i></a>

        <a href="{{route('product-service.create')}}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip" data-bs-placement="top" data-title="{{ __('Create New Product') }}" title="{{__('Create')}}"><i class="ti ti-plus text-white"></i></a>


    </div>
@endpermission
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class=" multi-collapse mt-2" id="multiCollapseExample1">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['product-service.index'], 'method' => 'GET', 'id' => 'product_service']) }}
                    <div class="row align-items-center justify-content-end">
                        <div class="col-xl-6">
                            <div class="row">

                                    <div class="col-xl-6 col-lg-3 col-md-6 col-sm-12 col-12">
                                        <div class="btn-box">
                                            {{ Form::label('item_type', __('Item'), ['class' => 'form-label']) }}
                                            {{ Form::select('item_type', $product_type, isset($_GET['item_type']) ? $_GET['item_type'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Item Type']) }}
                                        </div>
                                    </div>

                                <div class="col-xl-6 col-lg-3 col-md-6 col-sm-12 col-12">
                                    <div class="btn-box">
                                        {{ Form::label('category', __('Category'), ['class' => 'form-label']) }}
                                        {{ Form::select('category', $category, isset($_GET['category']) ? $_GET['category'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Category']) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto mt-4">
                            <div class="row">
                                <div class="col-auto">
                                    <a  class="btn btn-sm btn-primary"
                                        onclick="document.getElementById('product_service').submit(); return false;"
                                        data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                        data-original-title="{{ __('apply') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>
                                    <a href="{{ route('product-service.index') }}" class="btn btn-sm btn-danger "
                                        data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                        data-original-title="{{ __('Reset') }}">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-body table-border-style">
                <h5></h5>
                <div class="table-responsive">
                    <table class="table mb-0 pc-dt-simple" id="products">
                        <thead>
                        <tr>
                            <th >{{__('Image')}}</th>
                            <th >{{__('Name')}}</th>
                            <th >{{__('Sku')}}</th>
                            <th>{{__('Sale Price')}}</th>
                            <th>{{__('Purchase Price')}}</th>
                            <th>{{__('Tax')}}</th>
                            <th>{{__('Category')}}</th>
                            <th>{{__('Unit')}}</th>
                            <th>{{__('Quantity')}}</th>
                            <th>{{__('Type')}}</th>
                            @if (Laratrust::hasPermission('product&service delete') || Laratrust::hasPermission('product&service edit'))
                                <th>{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($productServices as $productService)
                            <?php
                                if(check_file($productService->image) == false){
                                    $path = asset('Modules/ProductService/Resources/assets/image/img01.jpg');
                                }else{
                                    $path = get_file($productService->image);
                                }
                            ?>
                            <tr class="font-style">
                                <td>
                                    <a href="{{ $path }}" target="_blank">
                                        <img src=" {{ $path }} " class="wid-75 rounded me-3">
                                    </a>
                                </td>
                                <td class="text-center">{{ $productService->name}}</td>
                                <td class="text-center">{{ $productService->sku }}</td>
                                <td>{{ currency_format_with_sym($productService->sale_price) }}</td>
                                <td>{{ currency_format_with_sym($productService->purchase_price )}}</td>
                                <td>
                                    {!! str_replace(',', ',<br>', $productService->tax_names) !!}
                                </td>
                                <td>{{ optional($productService->categorys)->name?? '' }}</td>
                                <td>{{ optional($productService->units)->name ??'' }}</td>
                                @if($productService->type == 'product' || $productService->type == 'parts')
                                    <td>{{$productService->quantity}}</td>
                                @else
                                    <td>-</td>
                                @endif
                                <td>{{ $productService->type }}</td>
                                @if (Laratrust::hasPermission('product&service delete') || Laratrust::hasPermission('product&service edit'))
                                   <td class="Action">
                                      @permission('product&service edit')
                                        <div class="action-btn bg-warning ms-2">
                                            <a class="mx-3 btn btn-sm  align-items-center"
                                                href="{{ route('product-service.show', $productService->id) }}" data-size="md"
                                                data-bs-toggle="tooltip"
                                                title="{{ __('View') }}">
                                                <i class="ti ti-eye text-white"></i>
                                            </a>
                                        </div>
                                        @endpermission
                                        @permission('product&service edit')

                                            <div class="action-btn bg-info ms-2">
                                                <a href="{{ route('product-service.edit', $productService->id) }}" class="mx-3 btn btn-sm d-inline-flex align-items-center" data-bs-toggle="tooltip" title="{{ __('Edit') }}"> <span class="text-white"> <i class="ti ti-pencil"></i></span></a>
                                            </div>

                                        @endpermission
                                        @permission('product&service delete')
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['product-service.destroy', $productService->id],'id'=>'delete-form-'.$productService->id]) !!}
                                                <a  class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-text="{{ __('Are you sure you want to proceed? This action cannot be undone, and it will delete all associated data.') }}"><i class="ti ti-trash text-white text-white"></i></a>
                                                {!! Form::close() !!}
                                            </div>
                                        @endpermission
                                    </td>
                                @endif
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
