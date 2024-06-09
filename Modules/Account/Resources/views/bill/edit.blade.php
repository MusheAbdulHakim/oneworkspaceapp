@extends('layouts.main')
@section('page-title')
    {{ __('Bill Edit') }}
@endsection
@section('page-breadcrumb')
    {{ __('Bill Edit') }}
@endsection
@push('scripts')
    <script src="{{ asset('public/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/jquery.repeater.min.js') }}"></script>
    <script>
        $(document).ready(function () {
           $("input[name='bill_type_radio']:checked").trigger('change');
        });

        $(document).on('click', '[data-repeater-delete]', function ()
        {
            var el = $(this).parent().parent();
            var id = $(el.find('.id')).val();

            $.ajax({
                url: '{{route('bill.product.destroy')}}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function (data) {
                    if (data.error) {
                            toastrs('Error', data.error, 'error');
                    } else {
                            toastrs('Success', data.success, 'success');
                    }
                },
                error: function (data) {
                    toastrs('Error','{{ __("something went wrong please try again") }}', 'error');
                },
            });
        });
        $(document).ready(function () {
            $("#vendor").trigger('change');
        });
        $(document).on('change', '#vendor', function () {
            $('#vendor_detail').removeClass('d-none');
            $('#vendor_detail').addClass('d-block');
            $('#vendor-box').removeClass('d-block');
            $('#vendor-box').addClass('d-none');
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
                success: function (data) {
                    if (data != '') {
                        $('#vendor_detail').html(data);
                    } else {
                        $('#vendor-box').removeClass('d-none');
                        $('#vendor_detail').removeClass('d-block');
                        $('#vendor_detail').addClass('d-none');
                    }

                },

            });
        });

        $(document).on('click', '#remove', function () {
            $('#vendor-box').removeClass('d-none');
            $('#vendor_detail').removeClass('d-block');
            $('#vendor_detail').addClass('d-none');
        })
    </script>
    <script>
            $(document).on('keyup', '.quantity', function () {
                var quntityTotalTaxPrice = 0;

                var el = $(this).parent().parent().parent().parent();

                var quantity = $(this).val();
                var price = $(el.find('.price')).val();
                var discount = $(el.find('.discount')).val();
                if(discount.length <= 0)
                {
                    discount = 0 ;
                }

                var totalItemPrice = (quantity * price) - discount;

                var amount = (totalItemPrice);


                var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
                $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

                $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));

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

            $(document).on('keyup change', '.price', function () {
                var el = $(this).parent().parent().parent().parent();
                var price = $(this).val();
                var quantity = $(el.find('.quantity')).val();
                if(quantity.length <= 0)
                {
                    quantity = 1 ;
                }
                var discount = $(el.find('.discount')).val();
                if(discount.length <= 0)
                {
                    discount = 0 ;
                }
                var totalItemPrice = (quantity * price)-discount;

                var amount = (totalItemPrice);

                var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
                $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

                $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));

                var totalItemTaxPrice = 0;
                var itemTaxPriceInput = $('.itemTaxPrice');
                for (var j = 0; j < itemTaxPriceInput.length; j++) {
                    totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                }


                var totalItemPrice = 0;
                var inputs_quantity = $(".quantity");
                var priceInput = $('.price');
                for (var j = 0; j < priceInput.length; j++) {
                    if(inputs_quantity[j].value <= 0)
                    {
                        inputs_quantity[j].value = 1 ;
                    }
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

            $(document).on('keyup change', '.discount', function () {
                var el = $(this).parent().parent().parent();
                var discount = $(this).val();
                if(discount.length <= 0)
                {
                    discount = 0 ;
                }

                var price = $(el.find('.price')).val();
                var quantity = $(el.find('.quantity')).val();
                var totalItemPrice = (quantity * price) - discount;


                var amount = (totalItemPrice);


                var totalItemTaxRate = $(el.find('.itemTaxRate')).val();
                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (totalItemPrice));
                $(el.find('.itemTaxPrice')).val(itemTaxPrice.toFixed(2));

                $(el.find('.amount')).html(parseFloat(itemTaxPrice)+parseFloat(amount));

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
                    if (itemDiscountPriceInput[k].value == '') {
                            itemDiscountPriceInput[k].value = parseFloat(0);
                        }
                    totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                }


                $('.subTotal').html(totalItemPrice.toFixed(2));
                $('.totalTax').html(totalItemTaxPrice.toFixed(2));

                $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
                $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));
            })



    </script>
        <script>
            $(document).on('change', '.item', function()
            {
                items($(this));
            });
            function items(data)
            {
                var in_type = $('#bill_type').val();
                if (in_type == 'product') {
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
                            if(item.product != null)
                            {
                                $(el.parent().parent().find('.price')).val(item.product.sale_price);
                                $(el.parent().parent().parent().find('.pro_description')).val(item.product.description);

                            }else{
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
                                    taxes += '<span class="badge bg-primary p-2 px-3 rounded mt-1 mr-1">' +
                                        item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' +
                                        '</span>';
                                    tax.push(item.taxes[i].id);
                                    totalItemTaxRate += parseFloat(item.taxes[i].rate);
                                }
                            }
                            var itemTaxPrice = 0;
                            if(item.product != null)
                            {
                                var itemTaxPrice = parseFloat((totalItemTaxRate / 100) * (item.product
                                .sale_price * 1));
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

                            var totalItemPrice = 0;
                            var inputs_quantity = $(".quantity");
                            var priceInput = $('.price');

                            for (var j = 0; j < priceInput.length; j++) {


                                var itemTotal = (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));

                                totalItemPrice += itemTotal;
                            }

                            var totalItemTaxPrice = 0;
                            var itemTaxPriceInput = $('.itemTaxPrice');
                            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                                if(item.product != null){
                                    $(el.parent().parent().find('.amount')).html(parseFloat(item.totalAmount)+parseFloat(itemTaxPriceInput[j].value));
                                }
                            }

                            var totalItemDiscountPrice = 0;
                            var itemDiscountPriceInput = $('.discount');

                            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                            }

                            $('.subTotal').html(totalItemPrice.toFixed(2));
                            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                            $('.totalAmount').html((parseFloat(totalItemPrice) - parseFloat(totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));
                            $('.totalDiscount').html(totalItemDiscountPrice.toFixed(2));

                        },
                    });
                }
            }
        </script>
    @if (module_is_active('Taskly') )
        <script>
            $(document).on('change', '.item', function() {
                var iteams_id = $(this).val();
                var el = $(this);
                $(el.parent().parent().find('.price')).val(0);
                $(el.parent().parent().find('.amount')).html(0);
                $(el.parent().parent().find('.taxes')).val(0);
                var bill_type =  $("#bill_type").val();
                if (bill_type == 'project') {
                    $("#tax_project").change();
                }
            });

            $(document).on('change', '#tax_project', function() {
                var tax_id = $(this).val();
                if (tax_id.length != 0) {
                    $.ajax({
                        type: 'post',
                        url: "{{ route('get.taxes') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            tax_id: tax_id,
                        },
                        beforeSend: function() {
                            $("#loader").removeClass('d-none');
                        },
                        success: function(response) {
                            var response = jQuery.parseJSON(response);
                            if (response != null) {
                                $("#loader").addClass('d-none');
                                var TaxRate = 0;
                                if (response.length > 0) {
                                    $.each(response, function(i) {
                                        TaxRate = parseInt(response[i]['rate']) + TaxRate;
                                    });
                                }
                                $(".itemTaxRate").val(TaxRate);
                                $(".price").change();
                            } else {
                                $(".itemTaxRate").val(0);
                                $(".price").change();
                                $('.section_div').html('');
                                toastrs('Error', 'Something went wrong please try again !', 'error');
                            }
                        },
                    });
                }
                else
                {
                    $(".itemTaxRate").val(0);
                    $('.taxes').html("");
                    $(".price").change();
                    $("#loader").addClass('d-none');
                }
            });
        </script>
    @endif

    <script>
        $(document).on('change',"[name='bill_type_radio']", function() {
                var val = $(this).val();
                $(".bill_div").empty();
                var bill_module = '{{$bill->bill_module}}';
                if(val == 'product')
                {
                    $(".discount_apply_div").removeClass('d-none');
                    $(".tax_project_div").addClass('d-none');
                    $(".discount_project_div").addClass('d-none');

                    var label = `{{ Form::label('category_id', __('Category'),['class'=>'form-label']) }} {{ Form::select('category_id', $category,null, array('class' => 'form-control','required'=>'required')) }}`;
                    $(".bill_div").append(label);
                    $("#bill_type").val('product');

                    if(bill_module == 'account')
                    {
                        $("#acction_type").val('edit');
                    }
                    else
                    {
                        $("#acction_type").val('create');
                    }
                    SectionGet(val);
                }
                else if(val == 'project')
                {
                    $(".discount_apply_div").addClass('d-none');
                    $(".tax_project_div").removeClass('d-none');
                    $(".discount_project_div").removeClass('d-none');

                    var label  = ` {{ Form::label('project', __('Project'),['class'=>'form-label']) }} {{ Form::select('project',$projects,$bill->category_id, array('class' => 'form-control','required'=>'required')) }}`
                    $(".bill_div").append(label);
                    $("#bill_type").val('project');
                    var project_id = $("#project").val();

                    if(bill_module == 'taskly')
                    {
                        $("#acction_type").val('edit');
                    }
                    else
                    {
                        $("#acction_type").val('create');
                    }

                    SectionGet(val,project_id);
                }

                choices();
            });
        function SectionGet(type = 'product',project_id = "0",title = 'Project'){
            var acction = $("#acction_type").val();
            $.ajax({
                    type: 'post',
                    url: "{{ route('bill.section.type') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        type: type,
                        project_id: project_id,
                        acction: acction,
                        bill_id: {{$bill->id}},
                    },
                    beforeSend: function () {
                            $("#loader").removeClass('d-none');
                        },
                    success: function (response)
                    {
                        if(response != false)
                        {
                            $('.section_div').html(response.html);
                            $("#loader").addClass('d-none');
                            $('.pro_name').text(title)
                             // for item SearchBox ( this function is  custom Js )
                            JsSearchBox();
                        }
                        else
                        {
                            $('.section_div').html('');
                            toastrs('Error', 'Something went wrong please try again !', 'error');
                        }
                    },
                });
        }
        $(document).on('change', "#project", function() {
            var title = $(this).find('option:selected').text();
            var project_id = $(this).val();
            SectionGet('project', project_id,title);

        });
    </script>
    @if ($bill->bill_module =='account')
    <script>
            $(document).ready(function () {
                SectionGet('product');
        });
    </script>
 @elseif (module_is_active('Taskly') && $bill->bill_module =='taskly')
    <script>
            $(document).ready(function () {
                $("#project").trigger("change");
        });
    </script>
 @endif
 <script>
    setTimeout(() => {
        $('#due_date').trigger('click');
    }, 1500);
 </script>
@endpush
@section('content')
    <div class="row">
        {{ Form::model($bill, array('route' => array('bill.update', $bill->id), 'method' => 'PUT','class'=>'w-100', 'enctype' => 'multipart/form-data')) }}
        @if ( $bill->bill_module =='account')
            <input type="hidden" name="bill_type" id="bill_type" value="product">
        @elseif ( $bill->bill_module =='taskly' )
            <input type="hidden" name="bill_type" id="bill_type" value="project">
        @endif
        <input type="hidden" name="acction_type" id="acction_type" value="edit">
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="row" id="vendor-box">
                                <div class="form-group col-md-6" id="account-box">
                                    <label class="require form-label">{{ __('Account Type') }}</label>
                                    <select class="form-control account_type {{ !empty($errors->first('account_type')) ? 'is-invalid' : '' }}"
                                        name="account_type" required="" id="account_type">
                                        <option value="">{{ __('Select Account Type') }}</option>
                                        @if (module_is_active('Account'))
                                        <option value="Accounting" @if ($bill->account_type =='Accounting') selected @endif >{{ __('Accounting') }}</option>
                                        @endif
                                        @if (module_is_active('Taskly'))
                                        <option value="Projects" @if ($bill->account_type =='Projects') selected @endif >{{ __('Projects') }}</option>
                                        @endif
                                    </select>
                                    <p class="text-danger d-none" id="account_validation">{{ __('Account Type field is required.') }}</p>
                                </div>
                                <div class="form-group col-md-6">
                                    @if (module_is_active('Account'))
                                        <div class="form-group" >
                                            {{ Form::label('vendor_id', __('Vendor'),['class'=>'form-label']) }}
                                            {{ Form::select('vendor_id', $vendors, null, array('class' => 'form-control ','id'=>'vendor','data-url'=>route('bill.vendor'),'required'=>'required','placeholder' =>'Select vendor')) }}
                                            @if (empty($vendors->count()))
                                            <div class=" text-xs">
                                                {{ __('Please create vendor/Client first.') }}
                                                <a
                                                    @if (module_is_active('Account')) href="{{ route('vendors.index') }}"  @else href="{{ route('users.index') }}" @endif><b>{{ __('Create vendor/Client') }}</b></a>
                                            </div>
                                        @endif
                                        </div>

                                    @else
                                        <div class="form-group">
                                            {{ Form::label('vendor_id', __('Vendor'),['class'=>'form-label']) }}
                                    {{ Form::select('vendor_id', $vendors, null, array('class' => 'form-control ','id'=>'vendor','data-url'=>route('bill.vendor'),'required'=>'required','placeholder' =>'Select vendor')) }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div id="vendor_detail" class="d-none">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="row">

                                @if(module_is_active('Account') && module_is_active('Taskly'))
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="form-group col-md-6">
                                            <label class="require form-label">{{ __('Billing Type') }}</label>
                                            <select class="form-control {{ !empty($errors->first('Billing Type')) ? 'is-invalid' : '' }}"
                                                name="bill_type_radio" required="" id="billing_type">
                                                <option value="">{{ __('Select Billing Type') }}</option>
                                                <option  class="{{ $bill->bill_module =='account' ? '' : 'd-none' }} " value="product" id="product"  @if ($bill->bill_module =='account') selected @endif>
                                                    {{ __('Item Wise') }}</option>
                                                <option class="{{ $bill->bill_module =='account' ? 'd-none' : '' }} " value="project" id="project1"  @if ($bill->bill_module =='taskly') selected @endif>
                                                    {{ __('Project Wise') }}</option>
                                            </select>
                                            <div class="invalid-feedback">
                                                {{ $errors->first('billing_type') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            @endif
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('bill_date', __('Bill Date'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
                                            {{Form::date('bill_date',null,array('class'=>'form-control ','required'=>'required','placeholder'=>'Select vendor'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('due_date', __('Due Date'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
                                            {{Form::date('due_date',null,array('class'=>'form-control ','required'=>'required','placeholder'=>'Select vendor'))}}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('bill_number', __('bill Number'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
                                            <input type="text" class="form-control" value="{{$bill_number}}" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group bill_div">
                                    @if ($bill->account_type == 'Accounting')
                                            {{ Form::label('category_id', __('Category'),['class'=>'form-label']) }}
                                            {{ Form::select('category_id', $category,null, array('class' => 'form-control','required'=>'required','placeholder'=>'Select Category')) }}
                                        @endif
                                        @if (module_is_active('Taskly') && $bill->account_type == 'Projects')
                                            {{ Form::label('project', __('Project'),['class'=>'form-label']) }}
                                            {{ Form::select('project',$projects,$bill->category_id, array('class' => 'form-control','required'=>'required')) }}
                                       @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('order_number', __('Order Number'),['class'=>'form-label']) }}
                                        <div class="form-icon-user">
                                            {{ Form::number('order_number',null, array('class' => 'form-control')) }}
                                        </div>
                                    </div>
                                </div>
                                @if (module_is_active('Taskly'))
                                    <div class="col-md-6 tax_project_div {{ (module_is_active('Account') ? "d-none" : '')}}">
                                        <div class="form-group">
                                            {{ Form::label('tax_project', __('Tax'),['class'=>'form-label']) }}
                                            {{ Form::select('tax_project[]',$taxs,!empty($bill->items->first()->tax) ? explode(",",$bill->items->first()->tax) :null, array('class' => 'form-control get_tax multi-select choices','multiple'=>'multiple','id' => 'tax_project','placeholder' => 'Select Tax')) }}
                                        </div>
                                    </div>
                                @endif
                                @if(module_is_active('CustomField') && !$customFields->isEmpty())
                                    <div class="col-md-12">
                                        <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                            @include('customfield::formBuilder',['fildedata' => !empty($bill->customField) ? $bill->customField : ''])
                                        </div>
                                    </div>
                                @endif
                                @stack('recurring_div_edit')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="loader" class="card card-flush">
            <div class="card-body">
                <div class="row">
                    <img class="loader" src="{{ asset('public/images/loader.gif') }}" alt="">
                </div>
            </div>
        </div>
        <div class="col-12 section_div">

        </div>
        <div class="modal-footer mb-3">
            <input type="button" value="{{__('Cancel')}}" onclick="location.href = '{{route('bill.index')}}';" class="btn btn-light mx-3">
            <input type="submit" value="{{__('Save Changes')}}" class="btn btn-primary">
        </div>
        {{ Form::close() }}
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('js/jquery-searchbox.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#account_type').on('change', function() {

                if ($(this).val() === 'Accounting') {
                    $('#product').removeClass('d-none').show();
                    $('#project1').addClass('d-none').hide();
                } else {
                    $('#product').addClass('d-none').hide();
                    $('#project1').removeClass('d-none').show();
                }
            });
        });
    </script>
@endpush

