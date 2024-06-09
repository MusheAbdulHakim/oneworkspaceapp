@extends('layouts.main')
@section('page-title')
    {{ __('Purchase Create') }}
@endsection
@section('page-breadcrumb')
    {{ __('Purchase') }},
    {{ __('Purchase Create') }}
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('js/jquery-searchbox.js') }}"></script>

    <script>
        var selector = "body";
        if ($(selector + " .repeater").length) {
            var $dragAndDrop = $("body .repeater tbody").sortable({
                handle: '.sort-handler'
            });
            var $repeater = $(selector + ' .repeater').repeater({
                initEmpty: false,
                defaultValues: {
                    'status': 1
                },
                show: function() {
                    $(this).slideDown();
                    var file_uploads = $(this).find('input.multi');
                    if (file_uploads.length) {
                        $(this).find('input.multi').MultiFile({
                            max: 3,
                            accept: 'png|jpg|jpeg',
                            max_size: 2048
                        });
                    }

                    JsSearchBox();
                },
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        $(this).slideUp(deleteElement);
                        $(this).remove();

                        var inputs = $(".amount");
                        var subTotal = 0;
                        for (var i = 0; i < inputs.length; i++) {
                            subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                        }
                        $('.subTotal').html(subTotal.toFixed(2));
                        $('.totalAmount').html(subTotal.toFixed(2));
                    }
                },
                ready: function(setIndexes) {
                    $dragAndDrop.on('drop', setIndexes);
                },
                isFirstItemUndeletable: true
            });
            var value = $(selector + " .repeater").attr('data-value');
            if (typeof value != 'undefined' && value.length != 0) {
                value = JSON.parse(value);
                $repeater.setList(value);
            }

        }

        $(document).on('change', '#vender', function() {
            $('#vender_detail').removeClass('d-none');
            $('#vender_detail').addClass('d-block');
            $('#vender-box').removeClass('d-block');
            $('#vender-box').addClass('d-none');
            var id = $(this).val();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function(data) {
                    if (data != '') {
                        $('#vender_detail').html(data);
                    } else {
                        $('#vender-box').removeClass('d-none');
                        $('#vender_detail').removeClass('d-block');
                        $('#vender_detail').addClass('d-none');
                    }
                },
            });
        });

        $(document).on('click', '#remove', function() {
            $('#vender-box').removeClass('d-none');
            $('#vender_detail').removeClass('d-block');
            $('#vender_detail').addClass('d-none');
        })


        $(document).on('change', '.item', function() {
            Items($(this));

        });

        function Items(data) {
            var iteams_id = data.val();
            var url = data.data('url');
            var el = data;
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'product_id': iteams_id
                },
                cache: false,
                success: function(data) {
                    var item = JSON.parse(data);
                    $(el.parent().parent().find('.quantity')).val(1);
                    if (item.product != null) {
                        $(el.parent().parent().find('.price')).val(item.product.purchase_price);
                        $(el.parent().parent().parent().find('.pro_description')).val(item.product.description);
                    } else {
                        $(el.parent().parent().find('.price')).val(0);
                        $(el.parent().parent().parent().find('.pro_description')).val('');
                    }
                    var taxes = '';
                    var tax = [];

                    var totalItemTaxRate = 0;
                    if (item.taxes == 0) {
                        taxes += '-';
                    } else {
                        for (var i = 0; i < item.taxes.length; i++) {

                            taxes += '<span class="badge bg-primary p-2 px-3 rounded mt-1 mr-1">' + item.taxes[
                                i].name + ' ' + '(' + item.taxes[i].rate + '%)' + '</span>';
                            tax.push(item.taxes[i].id);
                            totalItemTaxRate += parseFloat(item.taxes[i].rate);

                        }
                    }
                    var itemTaxPrice = 0;
                    if (item.product != null) {
                        var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (item.product.purchase_price *
                            1));
                    }

                    $(el.parent().parent().find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));
                    $(el.parent().parent().find('.itemTaxRate')).val(totalItemTaxRate.toFixed(2));
                    $(el.parent().parent().find('.taxes')).html(taxes);
                    $(el.parent().parent().find('.tax')).val(tax);
                    $(el.parent().parent().find('.unit')).html(item.unit);
                    $(el.parent().parent().find('.discount')).val(0);
                    $(el.parent().parent().find('.amount')).html(item.totalAmount);


                    var inputs = $(".amount");
                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    $('.subTotal').html(subTotal.toFixed(2));


                    var totalItemPrice = 0;
                    var priceInput = $('.price');
                    for (var j = 0; j < priceInput.length; j++) {
                        totalItemPrice += parseFloat(priceInput[j].value);
                    }

                    var totalItemTaxPrice = 0;
                    var itemTaxPriceInput = $('.itemTaxPrice');
                    for (var j = 0; j < itemTaxPriceInput.length; j++) {
                        totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                    }

                    $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                    $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

                },
            });
        }

        $(document).on('keyup', '.quantity', function() {
            var quntityTotalTaxPrice = 0;

            var el = $(this).parent().parent().parent().parent();
            var quantity = $(this).val();
            var price = $(el.find('.price')).val();
            var discount = $(el.find('.discount')).val();

            var totalItemPrice = (quantity * price);
            var amount = (totalItemPrice);
            $(el.find('.amount')).html(amount);

            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));


            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var inputs = $(".amount");
            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            $('.subTotal').html(subTotal.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal) + parseFloat(totalItemTaxPrice)).toFixed(2));

        })

        $(document).on('keyup change', '.price', function() {
            var el = $(this).parent().parent().parent().parent();
            var price = $(this).val();
            var quantity = $(el.find('.quantity')).val();

            var discount = $(el.find('.discount')).val();
            if (discount.length <= 0) {
                discount = 0;
            }
            var totalItemPrice = (quantity * price) - discount;

            var amount = (totalItemPrice);


            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html(parseFloat(itemTaxPrice) + parseFloat(amount));

            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var totalItemPrice = 0;
            var inputs_quantity = $(".quantity");

            var priceInput = $('.price');
            for (var j = 0; j < priceInput.length; j++) {
                totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
            }

            var inputs = $(".amount");

            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }

            $('.subTotal').html(totalItemPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));

        })


        $(document).on('keyup change', '.discount', function() {
            var el = $(this).parent().parent().parent();
            var discount = $(this).val();
            if (discount.length <= 0) {
                discount = 0;
            }

            var price = $(el.find('.price')).val();
            var quantity = $(el.find('.quantity')).val();
            var totalItemPrice = (quantity * price) - discount;


            var amount = (totalItemPrice);


            var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
            var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
            $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

            $(el.find('.amount')).html(parseFloat(itemTaxPrice) + parseFloat(amount));

            var totalItemTaxPrice = 0;
            var itemTaxPriceInput = $('.itemTaxPrice');
            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
            }


            var totalItemPrice = 0;
            var inputs_quantity = $(".quantity");

            var priceInput = $('.price');
            for (var j = 0; j < priceInput.length; j++) {
                totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
            }

            var inputs = $(".amount");

            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }


            var totalItemDiscountPrice = 0;
            var itemDiscountPriceInput = $('.discount');

            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
            }


            $('.subTotal').html(totalItemPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));

        })

        var vendorId = '{{ $vendorId }}';
        if (vendorId > 0) {
            $('#vender').val(vendorId).change();
        }
    </script>
    <script>
        $(document).on('click', '[data-repeater-delete]', function() {
            $(".price").change();
            $(".discount").change();
        });
        // for item SearchBox ( this function is  custom Js )
        JsSearchBox();
        $(document).on('change', '.product_type', function() {
            var product_type = $(this).val();
            var selector = $(this);
            var itemSelect = selector.parent().parent().find('.product_id.item').attr('name');
            console.log(itemSelect);
            $.ajax({
                url: '{{ route('get.item') }}',
                type: 'POST',
                data: {
                    "product_type": product_type,
                    "_token": "{{ csrf_token() }}",
                },
                success: function(data) {
                    selector.parent().parent().find('.product_id').empty();
                    var product_select = `<select class="form-control product_id item js-searchBox" name="${itemSelect}"
                                            placeholder="Select Item" data-url="{{ route('purchases.product') }}" required = 'required'>
                                            </select>`;
                    selector.parent().parent().find('.product_div').html(product_select);

                    selector.parent().parent().find('.product_id').append(
                        '<option value="0"> {{ __('Select Item') }} </option>');
                    $.each(data, function(key, value) {
                        selector.parent().parent().find('.product_id').append(
                            '<option value="' + key + '">' + value +
                            '</option>');
                    });

                    // Initialize your searchBox here if needed
                    Items(selector.parent().parent().find('.product_id'));
                    selector.parent().parent().find(".js-searchBox").searchBox({
                        elementWidth: '250'
                    });
                    selector.parent().parent().find('.unit.input-group-text').text("");
                    selector.parent().parent().find('.taxes .product_tax').text("");
                }
            });
        });
    </script>
@endpush

@php
    $currancy_symbol = !empty(company_setting('defult_currancy_symbol')) ? company_setting('defult_currancy_symbol') : '$';
@endphp

@section('content')
    <div class="row">
        {{ Form::open(['url' => 'purchases', 'enctype' => 'multipart/form-data', 'class' => 'w-100']) }}
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="row" id="vender-box">
                                <div class="form-group col-md-6" id="account-box">
                                    <label class="require form-label">{{ __('Account Type') }}</label>
                                    <select class="form-control account_type {{ !empty($errors->first('account_type')) ? 'is-invalid' : '' }}"
                                        name="account_type" required="" id="account_type">
                                        <option value="">{{ __('Select Account Type') }}</option>
                                        @if (module_is_active('Account'))
                                        <option value="Accounting">{{ __('Accounting') }}</option>
                                        @endif
                                        @if (module_is_active('Taskly'))
                                        <option value="Projects">{{ __('Projects') }}</option>
                                        @endif
                                        @if (module_is_active('CMMS'))
                                        <option value="CMMS">{{ __('CMMS') }}</option>
                                        @endif
                                    </select>
                                    <p class="text-danger d-none" id="account_validation">{{ __('Account Type field is required.') }}</p>
                                </div>
                                <div class="form-group col-md-6">
                                  @if (module_is_active('Account'))
                                        <div class="form-group" >
                                            {{ Form::label('vender_id', __('Vendor'), ['class' => 'form-label']) }}
                                            {{ Form::select('vender_id', $venders, $vendorId, ['class' => 'form-control select', 'id' => 'vender', 'data-url' => route('purchases.vender'), 'required' => 'required']) }}

                                            @if (empty($venders->count()))
                                            <div class=" text-xs">
                                                {{ __('Please create vendor/Client first.') }}
                                                <a
                                                    @if (module_is_active('Account')) href="{{ route('vendors.index') }}"  @else href="{{ route('users.index') }}" @endif><b>{{ __('Create vendor/Client') }}</b></a>
                                            </div>
                                        @endif
                                        </div>

                                    @else
                                        <div class="form-group">
                                            {{ Form::label('vender_name', __('Vendor'), ['class' => 'form-label']) }}
                                            {{ Form::text('vender_name', null, ['class' => 'form-control  ', 'placeholder' => 'Enter vender name', 'required' => 'required']) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div id="vender_detail" class="d-none">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-group col-md-6">
                                            <label class="require form-label">{{ __('Billing Type') }}</label>
                                            <select
                                                class="form-control {{ !empty($errors->first('Billing Type')) ? 'is-invalid' : '' }}"
                                                name="purchase_type" required="" id="billing_type">
                                                @if (module_is_active('Account'))
                                                <option value="product">{{ __('Item Wise') }}</option>
                                                @endif
                                                @if (module_is_active('Taskly'))
                                                <option value="project">{{ __('Project Wise') }}</option>
                                                @endif
                                                @if (module_is_active('CMMS'))
                                                <option value="parts">{{ __('Parts Wise') }}</option>
                                                @endif
                                            </select>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('billing_type') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('warehouse_id', __('Warehouse'), ['class' => 'form-label']) }}
                                        {{ Form::select('warehouse_id', $warehouse, null, ['class' => 'form-control select', 'required' => 'required']) }}
                                    </div>
                                </div>
                                <div class="col-lg-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('category_id', __('Category'), ['class' => 'form-label']) }}
                                        {{ Form::select('category_id', $category, null, ['class' => 'form-control select', 'required' => 'required']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('purchase_date', __('Purchase Date'), ['class' => 'form-label']) }}
                                        {{ Form::date('purchase_date', date('Y-m-d'), ['class' => 'form-control ', 'required' => 'required']) }}

                                    </div>
                                </div>

                                <div class="col-lg-6 col-12">
                                    <div class="form-group">
                                        {{ Form::label('purchase_number', __('Purchase Number'), ['class' => 'form-label']) }}
                                        <input type="text" class="form-control" value="{{ $purchase_number }}" readonly>

                                    </div>
                                </div>

                                @if (module_is_active('CustomField') && !$customFields->isEmpty())
                                    <div class="col-md-12 form-group">
                                        <div class="tab-pane fade show form-label" id="tab-2" role="tabpanel">
                                            @include('customfield::formBuilder')
                                        </div>
                                    </div>
                                @endif
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{ __('Items') }}</h5>

            <div class="card repeater">
                <div class="item-section py-2">
                    <div class="row justify-content-between align-items-center">
                        <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                            <div class="all-button-box me-2">
                                <a href="#" data-repeater-create="" class="btn btn-primary" data-bs-toggle="modal"
                                    data-target="#add-bank">
                                    <i class="ti ti-plus"></i> {{ __('Add item') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0" data-repeater-list="items" id="sortable-table">
                            <thead>
                                <tr>
                                    <th>{{ __('Item Type') }}</th>
                                    <th>{{ __('Items') }}</th>
                                    <th>{{ __('Quantity') }}</th>
                                    <th>{{ __('Price') }} </th>
                                    <th>{{ __('Tax') }} (%)</th>
                                    <th>{{ __('Discount') }}</th>
                                    <th class="text-end">{{ __('Amount') }} <br><small
                                            class="text-danger font-weight-bold">{{ __('After discount & tax') }}</small>
                                    </th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody class="ui-sortable" data-repeater-item>
                                <tr>
                                    <td class="form-group pt-0">
                                        {{ Form::select('product_type', $product_type, null, ['class' => 'form-control product_type ', 'required' => 'required', 'placeholder' => '--']) }}
                                    </td>
                                    <td width="25%" class="form-group pt-0 product_div">
                                        <select name="item" class="form-control product_id item  js-searchBox"
                                            data-url="{{ route('purchases.product') }}" required>
                                            @foreach ($product_services as $key => $product_service)
                                                <option value="{{ $key }}">{{ $product_service }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <div class="form-group price-input input-group search-form">
                                            {{ Form::text('quantity', '', ['class' => 'form-control quantity', 'required' => 'required', 'placeholder' => __('Qty'), 'required' => 'required']) }}

                                            <span class="unit input-group-text bg-transparent"></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group price-input input-group search-form">
                                            {{ Form::text('price', '', ['class' => 'form-control price', 'required' => 'required', 'placeholder' => __('Price'), 'required' => 'required']) }}
                                            <span class="input-group-text bg-transparent">{{ $currancy_symbol }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group">
                                            <div class="input-group">
                                                <div class="taxes"></div>
                                                {{ Form::hidden('tax', '', ['class' => 'form-control tax']) }}
                                                {{ Form::hidden('itemTaxPrice', '', ['class' => 'form-control itemTaxPrice']) }}
                                                {{ Form::hidden('itemTaxRate', '', ['class' => 'form-control itemTaxRate']) }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-group price-input input-group search-form">
                                            {{ Form::text('discount', '', ['class' => 'form-control discount', 'required' => 'required', 'placeholder' => __('Discount')]) }}
                                            <span class="input-group-text bg-transparent">{{ $currancy_symbol }}</span>
                                        </div>
                                    </td>
                                    <td class="text-end amount">
                                        0.00
                                    </td>
                                    <td>
                                        <a href="#" class="bs-pass-para repeater-action-btn" data-repeater-delete>
                                            <div class="repeater-action-btn action-btn bg-danger ms-2">
                                                <i class="ti ti-trash text-white text-white"></i>
                                            </div>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <div class="form-group">
                                            {{ Form::textarea('description', null, ['class' => 'form-control pro_description', 'rows' => '2', 'placeholder' => __('Description')]) }}
                                        </div>
                                    </td>
                                    <td colspan="5"></td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{ __('Sub Total') }} ({{ $currancy_symbol }})</strong></td>
                                    <td class="text-end subTotal">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{ __('Discount') }} ({{ $currancy_symbol }})</strong></td>
                                    <td class="text-end totalDiscount">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td></td>
                                    <td><strong>{{ __('Tax') }} ({{ $currancy_symbol }})</strong></td>
                                    <td class="text-end totalTax">0.00</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td class="blue-text"><strong>{{ __('Total Amount') }}
                                            ({{ $currancy_symbol }})</strong></td>
                                    <td class="blue-text text-end totalAmount"></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <input type="button" value="{{ __('Cancel') }}"
                onclick="location.href = '{{ route('purchases.index') }}';" class="btn btn-light">
            <input type="submit" value="{{ __('Create') }}" class="btn  btn-primary" id="submit">
        </div>
        {{ Form::close() }}
    </div>

@endsection
@push('scripts')
    <script>
        $("#submit").click(function() {
            var skill = $('.account_type').val();
            if (skill == '') {
                $('#account_validation').removeClass('d-none')
                return false;
            } else {
                $('#account_validation').addClass('d-none')
            }
        });
    </script>
@endpush
