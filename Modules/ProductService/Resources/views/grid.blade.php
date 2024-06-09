@extends('layouts.main')
@section('page-title')
    {{ __('Manage Items') }}
@endsection
@section('title')
    {{ __('Items') }}
@endsection
@section('page-breadcrumb')
    {{ __('Items') }}
@endsection
@section('page-action')
    <div>
        @permission('product&service import')
            <a href="#" class="btn btn-sm btn-primary" data-ajax-popup="true"
                data-title="{{ __('Product & Service Import') }}" data-url="{{ route('product-service.file.import') }}"
                data-toggle="tooltip" title="{{ __('Import') }}"><i class="ti ti-file-import"></i>
            </a>
        @endpermission
        <a href="{{ route('product-service.index') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
            title="{{ __('List View') }}">
            <i class="ti ti-list text-white"></i>
        </a>
        <a href="{{ route('category.index') }}"data-size="md" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip"data-title="{{ __('Setup') }}" title="{{ __('Setup') }}"><i
                class="ti ti-settings"></i></a>

        <a href="{{ route('productstock.index') }}"data-size="md" class="btn btn-sm btn-primary"
            data-bs-toggle="tooltip"data-title="{{ __(' Product Stock') }}" title="{{ __('Product Stock') }}"><i
                class="ti ti-shopping-cart"></i></a>

        @permission('product&service create')
            <a href="{{ route('product-service.create') }}" class="btn btn-sm btn-primary btn-icon" data-bs-toggle="tooltip"
                data-bs-placement="top" data-title="{{ __('Create New Product') }}" title="{{ __('Create') }}"><i
                    class="ti ti-plus text-white"></i></a>
        @endpermission
    </div>
@endsection
@section('filter')
@endsection
@section('content')
    <div class="filters-content">
        <div class="col-sm-12">
            <div class=" multi-collapse mt-2" id="multiCollapseExample1">
                <div class="card">
                    <div class="card-body">
                        {{ Form::open(['route' => ['product-service.grid'], 'method' => 'GET', 'id' => 'product_service']) }}
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
                                        <a class="btn btn-sm btn-primary"
                                            onclick="document.getElementById('product_service').submit(); return false;"
                                            data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                            data-original-title="{{ __('apply') }}">
                                            <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                        </a>
                                        <a href="{{ route('product-service.grid') }}" class="btn btn-sm btn-danger "
                                            data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                            data-original-title="{{ __('Reset') }}">
                                            <span class="btn-inner--icon"><i
                                                    class="ti ti-trash-off text-white-off "></i></span>
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
        <div class="row grid">
            @isset($productServices)
                @foreach ($productServices as $productService)
                    <div class="col-md-6 col-xl-3 All {{ $productService->status }}">
                        <div class="card">
                            <div class="card-header border-0 pb-0">
                                <div class="d-flex align-items-center">
                                    <?php
                                    if (check_file($productService->image) == false) {
                                        $path = asset('Modules/ProductService/Resources/assets/image/img01.jpg');
                                    } else {
                                        $path = get_file($productService->image);
                                    }
                                    ?>
                                    <td>
                                        <a href="{{ $path }}" target="_blank">
                                            <img src=" {{ $path }}" class=" me-3"
                                                style="border-radius: 10px;
                                            max-width: 50px !important;">
                                        </a>
                                    </td>

                                    <h5 class="mb-0">
                                        <a title="{{ $productService->name }}" class="">{{ $productService->name }}</a>
                                    </h5>
                                </div>
                                <div class="card-header-right">
                                    <div class="btn-group card-option">

                                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
                                            aria-haspopup="true" aria-expanded="false">
                                            <i class="feather icon-more-vertical"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @permission('product&service edit')
                                                <a class="dropdown-item"
                                                    href="{{ route('product-service.show', $productService->id) }}">
                                                    <i class="ti ti-eye"></i> <span>{{ __('View') }}</span>
                                                </a>
                                            @endpermission
                                            @permission('product&service edit')
                                                <a class="dropdown-item"
                                                    href="{{ route('product-service.edit', $productService->id) }}">
                                                    <i class="ti ti-pencil"></i> <span>{{ __('Edit') }}</span>
                                                </a>
                                            @endpermission
                                            @permission('product&service delete')
                                                <form id="delete-form-{{ $productService->id }}"
                                                    action="{{ route('product-service.destroy', [$productService->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    <a class="dropdown-item text-danger delete-popup bs-pass-para show_confirm"
                                                        data-confirm="{{ __('Are You Sure?') }}"
                                                        data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                        data-confirm-yes="delete-form-{{ $productService->id }}">
                                                        <i class="ti ti-trash"></i><span>{{ __('Delete') }}</span>
                                                    </a>
                                                    @method('DELETE')
                                                </form>
                                            @endpermission

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row g-2 justify-content-between">
                                    <div class="col-auto"><span
                                            class="badge rounded-pill bg-success">{{ optional($productService->categorys)->name ?? '' }}</span>
                                    </div>
                                    <div class="col-auto">
                                        <p class="mb-0"class="text-center">{{ $productService->sku }}</p>
                                    </div>
                                </div>
                                <div class="card mb-0 mt-3">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <h6 class="mb-0">{{ $productService->quantity }}</h6>
                                                <p class="text-muted text-sm mb-0">{{ __('Quantity') }}</p>
                                            </div>
                                            <div class="col-4">
                                                <h6 class="mb-0 text-center">
                                                    {!! str_replace(',', ',<br>', $productService->tax_names) !!}
                                                </h6>
                                                <p class="text-muted text-sm mb-0 text-center">{{ __('Tax') }}</p>
                                            </div>

                                            <div class="col-4 text-end">
                                                <h6 class="mb-0">{{ $productService->type }}</h6>
                                                <p class="text-muted text-sm mb-0">{{ __('Type') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card mb-0 mt-3">
                                    <div class="card-body p-3">
                                        <div class="row">
                                            <div class="col-6">
                                                <h6 class="mb-0">{{ currency_format_with_sym($productService->sale_price) }}
                                                </h6>
                                                <p class="text-muted text-sm mb-0">{{ __('Sale Price') }}</p>
                                            </div>
                                            <div class="col-6 text-end">
                                                <h6 class="mb-0">
                                                    {{ currency_format_with_sym($productService->purchase_price) }}</h6>
                                                <p class="text-muted text-sm mb-0">{{ __('Purchase Price') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endisset


            @auth('web')
                @permission('product&service create')
                    <div class="col-md-3 All">
                        <a class="btn-addnew-project " style="padding: 90px 10px;" href="{{ route('product-service.create') }}">
                            <div class="bg-primary proj-add-icon">
                                <i class="ti ti-plus"></i>
                            </div>
                            <h6 class="mt-4 mb-2">{{ __('Add Product') }}</h6>
                            <p class="text-muted text-center">{{ __('Click here to add New Product') }}</p>
                        </a>
                    </div>
                @endpermission
            @endauth

        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/letter.avatar.js') }}"></script>
@endpush
