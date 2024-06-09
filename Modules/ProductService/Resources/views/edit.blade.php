@extends('layouts.main')
@section('page-title')
    {{ __('Item Edit') }}
@endsection
@section('page-breadcrumb')
    {{ __('Items') }}
@endsection
@section('page-action')
    <div class="col-auto" style="width: 143px;">
        <select class="form-select" name="item_type" id="item_type" required="">
            <option value="">{{ __('Select Type') }}</option>
            <option value="product" @if ($productService->type == 'product') selected @endif>
                {{ __('Product') }}</option>
            <option value="service" @if ($productService->type == 'service') selected @endif>
                {{ __('Service') }}</option>
            <option value="parts" @if ($productService->type == 'parts') selected @endif>
                {{ __('Parts') }}</option>
            <option value="rent" @if ($productService->type == 'rent') selected @endif>
                {{ __('Rent') }}</option>
            <option value="music institute" @if ($productService->type == 'music institute') selected @endif>
                {{ __('Music Institute') }}</option>
            <option value="restaurants" @if ($productService->type == 'restaurants') selected @endif>
                {{ __('Restaurant') }}</option>
        </select>
    </div>
@endsection
@section('content')
    <div class="row">
        <div id="loader" class="card card-flush">
            <div class="card-body">
                <div class="row">
                    <img class="loader" src="{{ asset('public/images/loader.gif') }}" alt="">
                </div>
            </div>
        </div>
    </div>
    {{ Form::model($productService, ['route' => ['product-service.update', $productService->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data']) }}
    {{ Form::hidden('type', $productService->type, ['id' => 'type']) }}
        <div class="section_div">
        </div>
    {{ Form::close() }}
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#item_type').change(function() {
                var selectedType = $(this).val();
                $('#type').val(selectedType);
            });
        });
        
        $(document).ready(function() {
            ItemSectionGet();
        });

        $('#item_type').on('change', function()
        {
            ItemSectionGet();
            $.ajax({
                beforeSend: function() {
                    $(".loader-wrapper").removeClass('d-none');
                },
            });
        });

        function ItemSectionGet() {
            var item_type = $('#item_type').val();
            var action = "edit";
            var item_id = "{{ $productService->id }}";
            $.ajax({
                url: '{{ route('product.section.type') }}',
                type: 'POST',
                data: {
                    "item_type": item_type,
                    "item_id": item_id,
                    "action": action,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    if (response != false) {
                        $('.section_div').html(response.html);
                        $("#loader").addClass('d-none');
                        $(".loader-wrapper").addClass('d-none');
                        JsSearchBox();
                        choices();

                    } else {
                        $('.section_div').html('');
                        toastrs('Error', 'Something went wrong please try again !', 'error');
                    }
                }
            });
        }

        function changetab(tabname) {
            var someTabTriggerEl = document.querySelector('button[data-bs-target="' + tabname + '"]');
            var actTab = new bootstrap.Tab(someTabTriggerEl);
            actTab.show();
        }

        function generateSKU(){
            var sku = 'SKU-' + Math.random().toString(24).substr(2, 7);
            $('input[name=sku]').val(sku.toUpperCase());
        }
    </script>
@endpush
