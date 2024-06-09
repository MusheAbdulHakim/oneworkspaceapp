@extends('layouts.main')
@section('page-title')
    {{ __('Item Create') }}
@endsection
@section('page-breadcrumb')
    {{ __('Items') }}
@endsection
@section('page-action')
    <div class="col-auto" style="width: 143px;">
        {{ Form::select('item_type', $product_type, isset($_GET['item_type']) ? $_GET['item_type'] : null, ['id' => 'item_type', 'class' => 'form-control select ', 'required' => 'required']) }}
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
    {{ Form::open(['route' => 'product-service.store', 'enctype' => 'multipart/form-data']) }}
        {{ Form::hidden('type', null, ['id' => 'type']) }}
        <div class="section_div">
        </div>
    {{ Form::close() }}
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var selectedType = $('#item_type').val();
                $('#type').val(selectedType);
        });
        $('#item_type').change(function() {
            var selectedType = $(this).val();
            $('#type').val(selectedType);
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
            $.ajax({
                url: '{{ route('product.section.type') }}',
                type: 'POST',
                data: {
                    "item_type": item_type,
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
    </script>
    <script>
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
