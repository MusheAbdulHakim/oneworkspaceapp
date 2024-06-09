@extends('layouts.main')
@section('page-title')
    {{ __('Invoice Edit') }}
@endsection
@section('page-breadcrumb')
    {{ __('Invoice Edit') }}
@endsection

@section('content')
    <div class="row">
        {{ Form::model($invoice, ['route' => ['invoice.update', $invoice->id], 'method' => 'PUT', 'class' => 'w-100', 'enctype' => 'multipart/form-data']) }}
        @if ($invoice->invoice_module == 'account')
            <input type="hidden" name="invoice_type" id="invoice_type" value="product">
        @elseif ($invoice->invoice_module == 'taskly')
            <input type="hidden" name="invoice_type" id="invoice_type" value="project">
        @elseif ($invoice->invoice_module == 'cmms')
            <input type="hidden" name="invoice_type" id="invoice_type" value="parts">
        @elseif ($invoice->invoice_module == 'rent')
            <input type="hidden" name="invoice_type" id="invoice_type" value="rent">
        @elseif ($invoice->invoice_module == 'lms')
            <input type="hidden" name="invoice_type" id="invoice_type" value="course">
        @elseif ($invoice->invoice_module == 'legalcase')
            <input type="hidden" name="invoice_type" id="invoice_type" value="case">
        @elseif ($invoice->invoice_module == 'sales')
            <input type="hidden" name="invoice_type" id="invoice_type" value="sales">
        @elseif ($invoice->invoice_module == 'newspaper')
            <input type="hidden" name="invoice_type" id="invoice_type" value="newspaper">
        @elseif ($invoice->invoice_module == 'childcare')
            <input type="hidden" name="invoice_type" id="invoice_type" value="childcare">
        @elseif ($invoice->invoice_module == 'mobileservice')
            <input type="hidden" name="invoice_type" id="invoice_type" value="mobileservice">
        @elseif ($invoice->invoice_module == 'vehicleinspection')
            <input type="hidden" name="invoice_type" id="invoice_type" value="vehicleinspection">
        @elseif ($invoice->invoice_module == 'machinerepair')
            <input type="hidden" name="invoice_type" id="invoice_type" value="machinerepair">
        @elseif ($invoice->invoice_module == 'cardealership')
            <input type="hidden" name="invoice_type" id="invoice_type" value="cardealership">
        @elseif ($invoice->invoice_module == 'musicinstitute')
            <input type="hidden" name="invoice_type" id="invoice_type" value="musicinstitute">
        @elseif ($invoice->invoice_module == 'Fleet')
            <input type="hidden" name="invoice_type" id="invoice_type" value="fleet">
        @elseif ($invoice->invoice_module == 'RestaurantMenu')
            <input type="hidden" name="invoice_type" id="invoice_type" value="restaurantmenu">
        @endif

        <input type="hidden" name="acction_type" id="acction_type" value="edit">
        <div class="col-12">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <div class="card">
                <div class="card-body">
                    <div class="row">

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6">
                            <div class="row" id="customer-box">
                                <div class="form-group col-md-6" id="account-box">
                                    <label class="require form-label">{{ __('Account Type') }}</label>
                                    <select
                                        class="form-control {{ !empty($errors->first('account_type')) ? 'is-invalid' : '' }}"
                                        name="account_type" required="" id="account_type">
                                        <option value="">{{ __('Select Account Type') }}</option>

                                        @stack('account_type')
                                    </select>
                                    <p class="text-danger d-none" id="account_validation">
                                        {{ __('Account Type field is required.') }}</p>

                                </div>
                                <div class="form-group col-md-6 customer">
                                    {{ Form::label('customer_id', __('Customer'), ['class' => 'form-label']) }}

                                    <select name="customer_id" class="form-control" id="customer" required
                                        data-url="{{ route('invoice.customer') }}">
                                        <option value="">{{ __('Please Select') }}</option>

                                    </select>
                                </div>
                            </div>
                            <div id="customer_detail" class="d-none">
                            </div>
                        </div>


                        <div class="col-md-6">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="require form-label">{{ __('Billing Type') }}</label>
                                        <select
                                            class="form-control {{ !empty($errors->first('Billing Type')) ? 'is-invalid' : '' }}"
                                            name="invoice_type_radio" required="" id="billing_type">

                                        </select>
                                        <div class="invalid-feedback">
                                            {{ $errors->first('billing_type') }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="invoice_template" class="form-label">{{ __('Template') }}</label>
                                        <select class="form-control" name="invoice_template" id="invoice_template">
                                            <option value="">{{ __('Select Template') }}</option>
                                            @foreach (templateData()['templates'] as $key => $template)
                                                <option value="{{ $key }}"
                                                    {{ $invoice->invoice_template == $key ? 'selected' : '' }}>
                                                    {{ $template }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('issue_date', __('Issue Date'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            {{ Form::date('issue_date', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Customer', 'onchange' => 'Calculate()']) }}

                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('due_date', __('Due Date'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            {{ Form::date('due_date', null, ['class' => 'form-control ', 'required' => 'required', 'placeholder' => 'Select Customer']) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group invoice_div">
                                        @if (module_is_active('Account'))
                                            {{ Form::label('category_id', __('Category'), ['class' => 'form-label category_id']) }}
                                            {{ Form::select('category_id', $category, null, ['class' => 'form-control category_id', 'required' => 'required']) }}
                                        @endif
                                        @if (module_is_active('Taskly'))
                                            {{ Form::label('project', __('Project'), ['class' => 'form-label project_id']) }}
                                            {{ Form::select('project', $projects, $invoice->category_id, ['class' => 'form-control project_id']) }}
                                        @endif
                                        @if (module_is_active('CMMS'))
                                            {{ Form::label('work_order', __('Work Orders'), ['class' => 'form-label work_order']) }}
                                            {{ Form::select('work_order', $work_order, null, ['class' => 'form-control work_order']) }}
                                        @endif
                                        @if (module_is_active('LMS'))
                                            {{ Form::label('course_order', __('Course Order'), ['class' => 'form-label course_order']) }}
                                            {{ Form::select('course_order', [], $invoice->category_id, ['class' => 'form-control course_order']) }}
                                        @endif
                                        @if (module_is_active('RentalManagement'))
                                            {{ Form::label('rent_type', __('Rent Type'), ['class' => 'form-label']) }}
                                            {{ Form::select('rent_type', $rent_type, null, ['class' => 'form-control rent_type', 'onchange' => 'Calculate()']) }}
                                        @endif
                                        @if (module_is_active('RestaurantMenu'))
                                            {{ Form::label('restaurant_order', __('Restaurant Order'), ['class' => 'form-label restaurant_order']) }}
                                            {{ Form::select('restaurant_order', [], $invoice->category_id, ['class' => 'form-control restaurant_order']) }}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('invoice_number', __('Invoice Number'), ['class' => 'form-label']) }}
                                        <div class="form-icon-user">
                                            <input type="text" class="form-control" value="{{ $invoice_number }}"
                                                readonly>
                                        </div>
                                    </div>
                                </div>

                                @if (module_is_active('Taskly') || module_is_active('LMS'))
                                    <div
                                        class="col-md-6 tax_project_div {{ module_is_active('Account') || module_is_active('CMMS') ? 'd-none' : '' }}">
                                        <div class="form-group">
                                            {{ Form::label('tax_project', __('Tax'), ['class' => 'form-label']) }}
                                            {{ Form::select('tax_project[]', $taxs, !empty($invoice->items->first()->tax) ? explode(',', $invoice->items->first()->tax) : null, ['class' => 'form-control get_tax multi-select choices', 'multiple' => 'multiple', 'id' => 'tax_project', 'placeholder' => 'Select Tax']) }}
                                        </div>
                                    </div>
                                @endif
                                @if (module_is_active('CustomField') && !$customFields->isEmpty())
                                    <div class="col-md-12">
                                        <div class="tab-pane fade show" id="tab-2" role="tabpanel">
                                            @include('customfield::formBuilder', [
                                                'fildedata' => !empty($invoice->customField)
                                                    ? $invoice->customField
                                                    : '',
                                            ])
                                        </div>
                                    </div>
                                @endif
                                @stack('add_invoices_agent_filed_edit')
                                @stack('add_invoices_field')
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
            <input type="button" value="{{ __('Cancel') }}" onclick="location.href = '{{ route('invoice.index') }}';"
                class="btn btn-light mx-3">
            <input type="submit" id="submit" value="{{ __('Save Changes') }}" class="btn btn-primary">
        </div>
        {{ Form::close() }}
    </div>
@endsection

@push('scripts')

    <script src="{{ asset('public/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('public/js/jquery.repeater.min.js') }}"></script>
    <script src="{{ asset('js/jquery-searchbox.js') }}"></script>
    @if (module_is_active('Account') && $invoice->invoice_module == 'account')
        <script>
            $(document).ready(function() {
                $('.project_id').addClass('d-none');
                $('.tax_project_div').addClass('d-none');
                $('.work_order').addClass('d-none');
                $('.rent_type').addClass('d-none');
                $('.course_order').addClass('d-none');
            });
        </script>
    @elseif (module_is_active('Taskly') && $invoice->invoice_module == 'taskly')
        <script>
            $(document).ready(function() {
                $('.tax_project_div').removeClass('d-none');
                $('.category_id').addClass('d-none');
                $('.project_id').addClass('d-block');
                $('.work_order').addClass('d-none');
                $('.rent_type').addClass('d-none');
                $("#project").trigger("change");
                $('.course_order').addClass('d-none');
            });
        </script>
    @elseif (module_is_active('CMMS') && $invoice->invoice_module == 'cmms')
        <script>
            $(document).ready(function() {
                $('.category_id').addClass('d-none');
                $('.project_id').addClass('d-none');
                $('.rent_type').addClass('d-none');
                $('.course_order').addClass('d-none');
            });
        </script>
    @elseif (module_is_active('LMS') && $invoice->invoice_module == 'lms')
        <script>
            $(document).ready(function() {
                $('.tax_project_div').removeClass('d-none');
                $('.category_id').addClass('d-none');
                $('.project_id').addClass('d-none');
                $('.work_order').addClass('d-none');
                $("#course_order").trigger("change");
                $(".item").trigger("change");
            });
        </script>
    @elseif (module_is_active('RentalManagement') && $invoice->invoice_module == 'rent')
        <script>
            $(document).ready(function() {
                $('.category_id').addClass('d-none');
                $('.project_id').addClass('d-none');
                $('.tax_project_div').addClass('d-none');
                $('.work_order').addClass('d-none');
                $('.course_order').addClass('d-none');
                Calculate();
            });
        </script>
    @elseif (module_is_active('Sales') && $invoice->invoice_module == 'sales')
        <script>
            $(document).ready(function() {

                $('.tax_project_div').removeClass('d-none');
                $('.category_id').addClass('d-none');
                $('.project_id').addClass('d-block');
                $('.work_order').addClass('d-none');
                $('.rent_type').addClass('d-none');
                $('.course_order').addClass('d-none');


            });
        </script>
    @elseif (module_is_active('ChildcareManagement') && $invoice->invoice_module == 'childcare')
        <script>
            $(document).ready(function() {

                $('.category_id').addClass('d-none');
                $('.work_order').addClass('d-none');
                $('.rent_type').addClass('d-none');
                $('.course_order').addClass('d-none');
                $('.student').remove();
            });
        </script>
    @elseif (module_is_active('RestaurantMenu') && $invoice->invoice_module == 'RestaurantMenu')
        <script>
            $(document).ready(function() {
                $(".tax_project_div").addClass('d-none');
                $('.category_id').addClass('d-none');
                $('.project_id').addClass('d-none');
                $('.work_order').addClass('d-none');
                $("#restaurant_order").trigger("change");
                $(".item").trigger("change");
            });
        </script>
    @endif
    <script>
        $('#account_type').on('change', function() {
            var selection = $(this).val();


            $('#customer').empty();
            $('[name="invoice_type_radio"]').empty();

            $('#customer').append('<option value="">Please Select</option>');

            $.ajax({
                type: 'post',
                url: "{{ route('invoice.customers') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    type: selection,
                },
                beforeSend: function() {
                    $(".loader-wrapper").removeClass('d-none');
                },
                success: function(response) {
                    var options = '';

                    if (response.label == 'Inspection Request') {
                        @foreach ($inspectionRequests as $id => $invoice_id)
                            var formattedInvoice =
                                '{{ Modules\VehicleInspectionManagement\Entities\InspectionRequest::inspectionRequestIdFormat($invoice_id) }}';
                            options +=
                                `<option value="{{ $id }}" {{ $invoice->customer_id == $invoice_id ? 'selected' : '' }}> ${formattedInvoice} </option>`;
                        @endforeach
                    } else if (response.label == 'Repair Request') {
                        @foreach ($machineRequests as $id => $invoice_id)
                            var formattedInvoice =
                                '{{ Modules\MachineRepairManagement\Entities\MachineRepairRequest::machineRepairNumberFormat($invoice_id) }}';
                            options +=
                                `<option value="{{ $id }}" {{ $invoice->customer_id == $invoice_id ? 'selected' : '' }}> ${formattedInvoice} </option>`;
                        @endforeach
                    } else {

                        $.each(response.customers, function(indexInArray, valueOfElement) {
                            var selected = '{{ $invoice->user_id }}' == indexInArray ?
                                'selected' : '';
                            options += '<option value="' + indexInArray + '" ' + selected +
                                '>' + valueOfElement + '</option>';
                        });

                    }
                    $('#customer').append(options);
                    $('#customer').trigger('change');

                    $('[for="customer_id"]').html(response.label);

                    var optionsMap = {
                        'Taskly': 'Project Wise',
                        'Account': 'Item Wise',
                        'SalesAgent': 'Item Wise',
                        'LMS': 'Course Wise',
                        'CMMS': 'Parts Wise',
                        'RentalManagement': 'Rent Wise',
                        'LegalCaseManagement': 'Case Wise',
                        'Sales': 'Sale Wise',
                        'Newspaper': 'Newspaper Wise',
                        'ChildcareManagement': 'Child Wise',
                        'MobileServiceManagement': 'Service Wise',
                        'VehicleInspectionManagement': 'Request Wise',
                        'MachineRepairManagement': 'Machine Wise',
                        'CarDealership': 'Deal Wise',
                        'MusicInstitute': 'Student Wise',
                        'Fleet': 'Trips Wise',
                        'RestaurantMenu': 'Order Wise',
                    };

                    if (optionsMap.hasOwnProperty(selection)) {
                        var value = mapSelectionToValue(selection);
                        if (value !== null) {
                            $('[name="invoice_type_radio"]').append('<option value="' + value + '">' +
                                optionsMap[selection] + '</option>').trigger('change');
                        }
                    }


                },
            });
        });

        function mapSelectionToValue(selection) {
            switch (selection) {
                case 'Taskly':
                    return 'project';
                case 'Account':
                    return 'product';
                case 'LMS':
                    return 'course';
                case 'CMMS':
                    return 'parts';
                case 'RentalManagement':
                    return 'rent';
                case 'LegalCaseManagement':
                    return 'case';
                case 'Sales':
                    return 'sales';
                case 'Newspaper':
                    return 'newspaper';
                case 'ChildcareManagement':
                    return 'childcare';
                case 'MobileServiceManagement':
                    return 'mobileservice';
                case 'VehicleInspectionManagement':
                    return 'vehicleinspection';
                case 'MachineRepairManagement':
                    return 'machinerepair';
                case 'CarDealership':
                    return 'cardealership';
                case 'MusicInstitute':
                    return 'musicinstitute';
                case 'SalesAgent':
                    return 'salesagent';
                case 'Fleet':
                    return 'fleet';
                case 'RestaurantMenu':
                    return 'restaurantmenu';
                default:
                    return null;
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $("input[name='invoice_type_radio']:checked").trigger('change');
        });

        $(document).on('click', '[data-repeater-delete]', function() {
            var el = $(this).parent().parent();
            var id = $(el.find('.id')).val();

            $.ajax({
                url: '{{ route('invoice.product.destroy') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id
                },
                cache: false,
                success: function(data) {
                    if (data.error) {
                        toastrs('Error', data.error, 'error');
                    } else {
                        toastrs('Success', data.success, 'success');
                    }
                },
                error: function(data) {
                    toastrs('Error', '{{ __('something went wrong please try again') }}', 'error');
                },
            });
        });

        $(document).on('change', '#customer', function() {
            if ($('#invoice_type').val() == 'mobileservice' || $('#invoice_type').val() == 'vehicleinspection' || $(
                    '#invoice_type').val() == 'machinerepair' || $('#invoice_type').val() == 'cardealership' || $(
                    '#invoice_type').val() == 'product' || $('#invoice_type').val() == 'project' || $(
                    '#invoice_type').val() == 'case' || $('#invoice_type').val() == 'sales' || $('#invoice_type')
                .val() == 'newspaper' || $('#invoice_type').val() == 'fleet') {

                $('.student').remove();
            }
            var student = $('#account_type').val();

            if (student != 'LMS') {
                $('#customer_detail').removeClass('d-none');
                $('#customer_detail').addClass('d-block');
                $('#customer-box').addClass('d-none');
            }
            var id = $(this).val();
            var url = $(this).data('url');
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': jQuery('#token').val()
                },
                data: {
                    'id': id,
                    'type': $('#invoice_type').val()
                },

                cache: false,
                success: function(data) {
                    if ($('#invoice_type').val() == 'childcare' && data.status == 'success') {

                        $('#customer_detail').html(data.html);
                        $('tbody').html(data.tableData)

                    } else if ($('#invoice_type').val() == 'course') {
                        $('#course_order').empty();
                        $('#course_order').append(
                            '<option value="">{{ __('Select Course Order') }}</option>');
                        $.each(data, function(key, value) {
                            var abc = '{{ $invoice->category_id }}';
                            $('#course_order').append('<option value="' + key + '" ' + (key ==
                                abc ? 'selected' : '') + '>' + value + '</option>');
                        });
                    } else if ($('#invoice_type').val() == 'restaurantmenu') {

                        $('#restaurant_order').append(
                            '<option value="">{{ __('Select Restaurant Order') }}</option>');
                        $.each(data, function(key, value) {
                            var abc = '{{ $invoice->category_id }}';
                            $('#restaurant_order').append('<option value="' + key + '" ' + (key ==
                                abc ? 'selected' : '') + '>' + value + '</option>');
                        });
                        $('#customer-box').removeClass('d-none');
                        $('#customer_detail').removeClass('d-block');
                        $('#customer_detail').addClass('d-none');

                    } else {
                        if (data != '') {
                            $('#customer_detail').html(data);
                        } else {
                            $('#customer-box').removeClass('d-none');
                            $('#customer_detail').removeClass('d-block');
                            $('#customer_detail').addClass('d-none');
                        }
                    }
                    $(".loader-wrapper").addClass('d-none');

                },

            });

            $(".loader-wrapper").addClass('d-none');

        });
        $(document).on('click', '#remove', function() {
            $('#customer-box').removeClass('d-none');


            $('#customer_detail').removeClass('d-block');
            $('#customer_detail').addClass('d-none');
        })
    </script>
    <Script>
        $(document).on('keyup', '.quantity', function() {
            var quntityTotalTaxPrice = 0;

            var el = $(this).parent().parent().parent().parent();

            var quantity = $(this).val();
            var price = $(el.find('.price')).val();
            var discount = $(el.find('.discount')).val();
            if (discount.length <= 0) {
                discount = 0;
            }
            if ($('#invoice_type').val() == 'vehicleinspection' || $('#invoice_type').val() == 'machinerepair' || $(
                    '#invoice_type').val() == 'mobileservice') {
                var service_charge = $('.service_charge').val();
                var service = parseFloat(service_charge);
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
            if ($('#invoice_type').val() == 'vehicleinspection' || $('#invoice_type').val() == 'machinerepair' || $(
                    '#invoice_type').val() == 'mobileservice') {
                service = isNaN(service) ? 0 : service;

                subTotal = parseFloat(subTotal) + service;
                $('.totalServiceCharge').html(service.toFixed(2));
            }
            $('.subTotal').html(totalItemPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
        })

        $(document).on('keyup change', '.price', function() {
            var el = $(this).parent().parent().parent().parent();
            var price = $(this).val();
            var quantity = $(el.find('.quantity')).val();
            if (quantity.length <= 0) {
                quantity = 1;
            }
            var discount = $(el.find('.discount')).val();
            if (discount.length <= 0) {
                discount = 0;
            }
            if ($('#invoice_type').val() == 'vehicleinspection' || $('#invoice_type').val() == 'machinerepair' || $(
                    '#invoice_type').val() == 'mobileservice') {
                var service_charge = $('.service_charge').val();
                var service = parseFloat(service_charge);
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
                if (inputs_quantity[j].value <= 0) {
                    inputs_quantity[j].value = 1;
                }
                totalItemPrice += (parseFloat(priceInput[j].value) * parseFloat(inputs_quantity[j].value));
            }

            var inputs = $(".amount");

            var subTotal = 0;
            for (var i = 0; i < inputs.length; i++) {
                subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
            }
            if ($('#invoice_type').val() == 'vehicleinspection' || $('#invoice_type').val() == 'machinerepair' || $(
                    '#invoice_type').val() == 'mobileservice') {
                service = isNaN(service) ? 0 : service;

                subTotal = parseFloat(subTotal) + service;
                $('.totalServiceCharge').html(service.toFixed(2));
            }
            $('.subTotal').html(totalItemPrice.toFixed(2));
            $('.totalTax').html(totalItemTaxPrice.toFixed(2));

            $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
        })

        $(document).on('keyup change', '.discount', function() {
            if ($('#invoice_type').val() != 'case') {
                var el = $(this).parent().parent().parent();
                var discount = $(this).val();
                if (discount.length <= 0) {
                    discount = 0;
                }
                if ($('#invoice_type').val() == 'vehicleinspection' || $('#invoice_type').val() ==
                    'machinerepair' || $('#invoice_type').val() == 'mobileservice') {
                    var service_charge = $('.service_charge').val();
                    var service = parseFloat(service_charge);
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
                if ($('#invoice_type').val() == 'vehicleinspection' || $('#invoice_type').val() ==
                    'machinerepair' || $('#invoice_type').val() == 'mobileservice') {
                    service = isNaN(service) ? 0 : service;

                    subTotal = parseFloat(subTotal) + service;
                    $('.totalServiceCharge').html(service.toFixed(2));

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
            }
        })
    </Script>
    @if (
        (module_is_active('Account') && $invoice->invoice_module == 'account') ||
            (module_is_active('SalesAgent') && $invoice->invoice_module == 'account') ||
            (module_is_active('Sales') && $invoice->invoice_module == 'sales') ||
            (module_is_active('MachineRepairManagement') && $invoice->invoice_module == 'machinerepair') ||
            (module_is_active('CarDealership') && $invoice->invoice_module == 'cardealership') ||
            (module_is_active('MusicInstitute') && $invoice->invoice_module == 'musicinstitute') ||
            (module_is_active('MobileServiceManagement') && $invoice->invoice_module == 'mobileservice') ||
            (module_is_active('Fleet') && $invoice->invoice_module == 'Fleet'))
        <script>
            $(document).on('change', '.item', function() {
                items($(this));
            });

            function items(data) {
                var in_type = $('#invoice_type').val();
                if (in_type == 'product' || in_type == 'sales' || in_type == 'vehicleinspection' || in_type ==
                    'machinerepair' || in_type == 'cardealership' || in_type == 'musicinstitute' || in_type ==
                    'mobileservice' || in_type == 'Fleet') {
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
                                $(el.parent().parent().find('.price')).val(item.product.sale_price);
                                $(el.parent().parent().parent().find('.pro_description')).val(item.product
                                    .description);

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
                                    taxes += '<span class="badge bg-primary p-2 px-3 rounded mt-1 mr-1">' +
                                        item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' +
                                        '</span>';
                                    tax.push(item.taxes[i].id);
                                    totalItemTaxRate += parseFloat(item.taxes[i].rate);
                                }
                            }
                            var itemTaxPrice = 0;
                            if (item.product != null) {
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
                            var priceInput = $('.price');
                            for (var j = 0; j < priceInput.length; j++) {
                                totalItemPrice += parseFloat(priceInput[j].value);
                            }

                            var totalItemTaxPrice = 0;
                            var itemTaxPriceInput = $('.itemTaxPrice');
                            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                                if (item.product != null) {
                                    $(el.parent().parent().find('.amount')).html(parseFloat(item.totalAmount) +
                                        parseFloat(itemTaxPriceInput[j].value));
                                }
                            }

                            var totalItemDiscountPrice = 0;
                            var itemDiscountPriceInput = $('.discount');
                            if ($('#invoice_type').val() == 'vehicleinspection' || $('#invoice_type').val() ==
                                'machinerepair' || $('#invoice_type').val() == 'mobileservice') {
                                var service_charge = $('.service_charge').val();
                                var service = parseFloat(service_charge);
                            }
                            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                            }

                            $('.subTotal').html(totalItemPrice.toFixed(2));
                            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                            if ($('#invoice_type').val() == 'vehicleinspection' || $('#invoice_type').val() ==
                                'machinerepair' || $('#invoice_type').val() == 'mobileservice') {
                                service = isNaN(service) ? 0 : service;

                                $('.totalServiceCharge').html(service.toFixed(2));
                                $('.totalAmount').html((parseFloat(totalItemPrice) - parseFloat(
                                        totalItemDiscountPrice) + parseFloat(totalItemTaxPrice) + service)
                                    .toFixed(2));

                            } else {

                                $('.totalAmount').html((parseFloat(totalItemPrice) - parseFloat(
                                        totalItemDiscountPrice) +
                                    parseFloat(totalItemTaxPrice)).toFixed(2));
                            }

                        },
                    });
                }
            }
        </script>
    @endif
    @if (
        (module_is_active('Taskly') && $invoice->invoice_module == 'taskly') ||
            (module_is_active('LMS') && $invoice->invoice_module == 'lms') || (module_is_active('RestaurantMenu') && $invoice->invoice_module == 'RestaurantMenu'))
        <script>
            $(document).on('change', '.item', function() {
                var iteams_id = $(this).val();
                var el = $(this);
                $(el.parent().parent().find('.price')).val(0);
                $(el.parent().parent().find('.amount')).html(0);
                $(el.parent().parent().find('.taxes')).val(0);
                var invoice_type = $("#invoice_type").val();
                // if (invoice_type == 'project') {
                //     $("#tax_project").change();
                // }
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

                        success: function(response) {
                            var response = jQuery.parseJSON(response);

                            if (response != null) {
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
                } else {
                    $(".itemTaxRate").val(0);
                    $('.taxes').html("");
                    $(".price").change();
                    $("#loader").addClass('d-none');
                }
            });
        </script>
    @endif

    @if (module_is_active('CMMS') && $invoice->invoice_module == 'cmms')
        <script>
            $(document).on('change', '.item', function() {

                items($(this));
            });

            function items(data) {
                var in_type = $('#invoice_type').val();
                if (in_type == 'parts') {
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
                                $(el.parent().parent().find('.price')).val(item.product.sale_price);
                                $(el.parent().parent().parent().find('.pro_description')).val(item.product
                                    .description);

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
                                    taxes += '<span class="badge bg-primary p-2 px-3 rounded mt-1 me-1">' +
                                        item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' +
                                        '</span>';
                                    tax.push(item.taxes[i].id);
                                    totalItemTaxRate += parseFloat(item.taxes[i].rate);
                                }
                            }
                            var itemTaxPrice = 0;
                            if (item.product != null) {
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
                            var priceInput = $('.price');
                            for (var j = 0; j < priceInput.length; j++) {
                                totalItemPrice += parseFloat(priceInput[j].value);
                            }

                            var totalItemTaxPrice = 0;
                            var itemTaxPriceInput = $('.itemTaxPrice');
                            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                                if (item.product != null) {
                                    $(el.parent().parent().find('.amount')).html(parseFloat(item.totalAmount) +
                                        parseFloat(itemTaxPriceInput[j].value));
                                }
                            }



                            var totalItemDiscountPrice = 0;
                            var itemDiscountPriceInput = $('.discount');

                            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                            }

                            $('.subTotal').html(totalItemPrice.toFixed(2));
                            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                            $('.totalAmount').html((parseFloat(totalItemPrice) - parseFloat(
                                totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));

                        },
                    });
                }
            }
        </script>
    @endif

    @if (module_is_active('RentalManagement') && $invoice->invoice_module == 'rent')
        <script>
            $(document).on('change', '.item', function() {

                items($(this));
            });

            function items(data) {
                var in_type = $('#invoice_type').val();
                if (in_type == 'rent') {
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
                                $(el.parent().parent().find('.price')).val(item.product.sale_price);
                                $(el.parent().parent().parent().find('.pro_description')).val(item.product
                                    .description);

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
                                    taxes += '<span class="badge bg-primary p-2 px-3 rounded mt-1 me-1">' +
                                        item.taxes[i].name + ' ' + '(' + item.taxes[i].rate + '%)' +
                                        '</span>';
                                    tax.push(item.taxes[i].id);
                                    totalItemTaxRate += parseFloat(item.taxes[i].rate);
                                }
                            }
                            var itemTaxPrice = 0;
                            if (item.product != null) {
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
                            var priceInput = $('.price');
                            for (var j = 0; j < priceInput.length; j++) {
                                totalItemPrice += parseFloat(priceInput[j].value);
                            }

                            var totalItemTaxPrice = 0;
                            var itemTaxPriceInput = $('.itemTaxPrice');
                            for (var j = 0; j < itemTaxPriceInput.length; j++) {
                                totalItemTaxPrice += parseFloat(itemTaxPriceInput[j].value);
                                if (item.product != null) {
                                    $(el.parent().parent().find('.amount')).html(parseFloat(item.totalAmount) +
                                        parseFloat(itemTaxPriceInput[j].value));
                                }
                            }



                            var totalItemDiscountPrice = 0;
                            var itemDiscountPriceInput = $('.discount');

                            for (var k = 0; k < itemDiscountPriceInput.length; k++) {

                                totalItemDiscountPrice += parseFloat(itemDiscountPriceInput[k].value);
                            }

                            $('.subTotal').html(totalItemPrice.toFixed(2));
                            $('.totalTax').html(totalItemTaxPrice.toFixed(2));
                            $('.totalAmount').html((parseFloat(totalItemPrice) - parseFloat(
                                totalItemDiscountPrice) + parseFloat(totalItemTaxPrice)).toFixed(2));

                        },
                    });
                }
            }
        </script>
    @endif

    <script>
        $(document).on('change', "[name='invoice_type_radio']", function() {

            const val = $(this).val();
            $(".invoice_div").empty();
            var invoice_module = '{{ $invoice->invoice_module }}';
            const handleProductInvoice = () => {
                $(".discount_apply_div").removeClass('d-none');
                $(".tax_project_div").addClass('d-none');
                $(".discount_project_div").addClass('d-none');

                var label =
                    `{{ Form::label('category_id', __('Category'), ['class' => 'form-label']) }} {{ Form::select('category_id', $category, null, ['class' => 'form-control', 'required' => 'required']) }}`;
                $(".invoice_div").append(label);
                $("#invoice_type").val('product');

                if ('{{ $invoice->invoice_module }}' == 'account') {
                    $("#acction_type").val('edit');
                } else {
                    $("#acction_type").val('create');
                }
                SectionGet(val);
            };
            const handleProjectInvoice = () => {
                $(".discount_apply_div").addClass('d-none');
                $(".tax_project_div").removeClass('d-none');
                $(".discount_project_div").removeClass('d-none');

                var label =
                    ` {{ Form::label('project', __('Project'), ['class' => 'form-label']) }} {{ Form::select('project', $projects, $invoice->category_id, ['class' => 'form-control', 'required' => 'required']) }}`
                $(".invoice_div").append(label);
                $("#invoice_type").val('project');
                var project_id = $("#project").val();

                if (invoice_module == 'taskly') {
                    $("#acction_type").val('edit');
                } else {
                    $("#acction_type").val('create');
                }

                SectionGet(val, project_id);
            };
            const handlePartsInvoice = () => {
                $(".discount_apply_div").addClass('d-none');
                $(".tax_project_div").addClass('d-none');
                $(".discount_project_div").addClass('d-none');

                var label =
                    ` {{ Form::label('work_order', __('Work Orders'), ['class' => 'form-label']) }} {{ Form::select('work_order', $work_order, null, ['class' => 'form-control', 'required' => 'required']) }}`
                $(".invoice_div").append(label);
                $("#invoice_type").val('parts');
                SectionGet(val);
            };
            const handleRentInvoice = () => {
                $(".discount_apply_div").addClass('d-none');
                $(".tax_project_div").addClass('d-none');
                $(".discount_project_div").addClass('d-none');

                var label =
                    ` {{ Form::label('rent_type', __('Rent Type'), ['class' => 'form-label']) }} {{ Form::select('rent_type', $rent_type, null, ['class' => 'form-control', 'required' => 'required', 'onchange' => 'Calculate()']) }}`
                $(".invoice_div").append(label);
                $("#invoice_type").val('rent');
                SectionGet(val);
                Calculate();
            };
            const handleCourseInvoice = () => {
                $(".tax_project_div").removeClass('d-none');

                var label =
                    ` {{ Form::label('course_order', __('Course Orders'), ['class' => 'form-label']) }}
                    {{ Form::select('course_order', [], $invoice->category_id, ['class' => 'form-control', 'required' => 'required']) }}`
                $(".invoice_div").append(label);
                $("#invoice_type").val('course');
            };
            const handleCaseInvoice = () => {
                $(".invoice_div").parent().remove();
                $("#invoice_type").val('case');
                SectionGet(val, 0, 'Items');
            };
            const handleSalesInvoice = () => {
                var options = '<option > Please Select </option>';

                @foreach ($sale_invoice as $id => $invoice_id)

                    var formattedInvoice =
                        '{{ Modules\Sales\Entities\SalesInvoice::invoiceNumberFormat($invoice_id) }}';
                    options +=
                        `<option value="{{ $id }}" {{ $invoice->category_id == $invoice_id ? 'selected' : '' }}> ${formattedInvoice} </option>`;
                @endforeach

                var label = `
                    {{ Form::label('sale_invoice', __('Sales Invoice'), ['class' => 'form-label']) }}
                    <select name="sale_invoice" id="sale_invoice" class="form-control" required="required" >
                        ${options}
                    </select>
                `;

                $(".invoice_div").append(label);
                $("#invoice_type").val('sales');
                $(".tax_project_div").addClass('d-none');
                $('#sale_invoice').trigger('change');
            };
            const handleNewspaperInvoice = () => {
                $(".invoice_div").parent().remove();
                $("#invoice_type").val('newspaper');
                SectionGet(val);
            };
            const handleChildcareInvoice = () => {
                $(".invoice_div").parent().remove();
                $("#invoice_type").val('childcare');
                SectionGet(val);
            };
            const handleMobileServiceInvoice = () => {
                $(document).on('keyup change', '.service_charge', function() {
                    var service_charge = $(this).val();
                    var service = parseFloat(service_charge);
                    service = isNaN(service) ? 0 : service;

                    var inputs = $(".amount");

                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    subTotal = subTotal + service;
                    $('.totalServiceCharge').html(service.toFixed(2));

                    $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
                });
                var label = `
                    {{ Form::label('repair_charge', __('Repair Charge'), ['class' => 'form-label']) }}
                    {{ Form::number('repair_charge', $invoice->category_id, ['class' => 'form-control service_charge', 'required' => 'required', 'id' => 'repair_charge', 'placeholder' => 'Enter Repair Charge', 'value' => '0']) }}
                `;
                $(".invoice_div").append(label);
                $("#invoice_type").val('mobileservice');
                SectionGet(val, $('#customer').val());
            };
            const handleVehicleOrMachineInvoice = () => {
                $(document).on('keyup change', '.service_charge', function() {
                    var service_charge = $(this).val();
                    var service = parseFloat(service_charge);
                    service = isNaN(service) ? 0 : service;

                    var inputs = $(".amount");

                    var subTotal = 0;
                    for (var i = 0; i < inputs.length; i++) {
                        subTotal = parseFloat(subTotal) + parseFloat($(inputs[i]).html());
                    }
                    subTotal = subTotal + service;
                    $('.totalServiceCharge').html(service.toFixed(2));

                    $('.totalAmount').html((parseFloat(subTotal)).toFixed(2));
                });

                var label = `
                    {{ Form::label('service_charge', __('Service Charge'), ['class' => 'form-label']) }}
                    {{ Form::number('service_charge', $invoice->category_id, ['class' => 'form-control service_charge', 'required' => 'required', 'id' => 'service_charge', 'placeholder' => 'Enter Inspection Service Charge', 'value' => '0']) }}
                `;
                $(".invoice_div").append(label);
                if (val == 'vehicleinspection') {
                    $("#invoice_type").val('vehicleinspection');
                } else {
                    $("#invoice_type").val('machinerepair');
                }
                SectionGet(val, $('#customer').val());
            };
            const handleCardealershipInvoice = () => {
                $(".invoice_div").parent().remove();
                $("#invoice_type").val('cardealership');
                SectionGet(val);
            };
            const handleMusicInvoice = () => {
                $(".discount_apply_div").addClass('d-none');
                $(".tax_project_div").addClass('d-none');
                $(".discount_project_div").addClass('d-none');

                var label =
                    ` {{ Form::label('student', __('Student'), ['class' => 'form-label']) }} {{ Form::select('student', $music_students, $invoice->category_id, ['class' => 'form-control', 'required' => 'required', 'placeholder' => 'Select Student']) }}`
                $(".invoice_div").append(label);
                $("#invoice_type").val('musicinstitute');
                SectionGet(val);
            };

            const handleFleetInvoice = () => {
                $(".invoice_div").parent().remove();
                $("#invoice_type").val('fleet');
                SectionGet(val);
            };
            const handleRestaurantInvoice = () => {
                $(".tax_project_div").addClass('d-none');
                var label =
                    ` {{ Form::label('restaurant_order', __('Restaurant Orders'), ['class' => 'form-label']) }} {{ Form::select('restaurant_order', [], $invoice->category_id, ['class' => 'form-control', 'required' => 'required']) }}`
                $(".invoice_div").append(label);
                $("#invoice_type").val('restaurantmenu');
            };

            switch (val) {
                case 'salesagent':
                    handleProductInvoice();
                    break;
                case 'product':
                    handleProductInvoice();
                    break;
                case 'project':
                    handleProjectInvoice();
                    break;
                case 'parts':
                    handlePartsInvoice();
                    break;
                case 'rent':
                    handleRentInvoice();
                    break;
                case 'course':
                    handleCourseInvoice();
                    break;
                case 'case':
                    handleCaseInvoice();
                    break;
                case 'sales':
                    handleSalesInvoice();
                    break;
                case 'newspaper':
                    handleNewspaperInvoice();
                    break;
                case 'childcare':
                    handleChildcareInvoice();
                    break;
                case 'mobileservice':
                    handleMobileServiceInvoice();
                    break;
                case 'vehicleinspection':
                case 'machinerepair':
                    handleVehicleOrMachineInvoice();
                    break;
                case 'cardealership':
                    handleCardealershipInvoice();
                    break;
                case 'musicinstitute':
                    handleMusicInvoice();
                    break;
                case 'fleet':
                    handleFleetInvoice();
                    break;
                case 'restaurantmenu':
                    handleRestaurantInvoice();
                    break;
                default:
                    break;
            }

            choices();
        });

        function SectionGet(type = 'product', project_id = "0", title = 'Project', course_order = '0') {

            var acction = $("#acction_type").val();

            $.ajax({
                type: 'post',
                url: "{{ route('invoice.section.type') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    type: type,
                    project_id: project_id,
                    acction: acction,
                    course_order: course_order,
                    invoice_id: {{ $invoice->id }},
                },
                beforeSend: function() {
                    $("#loader").removeClass('d-none');
                },
                success: function(response) {
                    if (response != false) {
                        $('.pro_name').text(title)
                        // for item SearchBox ( this function is  custom Js )
                        JsSearchBox();

                        var value = response.order;
                        if (typeof value != 'undefined' && value.length != 0) {
                            value = JSON.parse(value);
                            $repeater.setList(value);
                            for (var i = 0; i < value.length; i++) {
                                var courseValue = value[i]
                                    .course; // Assuming value[i].course contains the desired value
                                var tr = $('#sortable-table tbody').find('tr').filter(function() {
                                    $(this).find('.item').val() == courseValue;
                                });

                            }

                        }
                        $("#loader").addClass('d-none');
                        $('.section_div').html(response.html);
                        const elementsToRemove = document.querySelectorAll('.bs-pass-para.repeater-action-btn');
                        if (elementsToRemove.length > 0) {
                            elementsToRemove[0].remove();
                        }

                    } else {
                        $('.section_div').html('');
                        toastrs('Error', 'Something went wrong please try again !', 'error');
                    }


                },
            });
        }

        $(document).on('change', "#project", function() {
            var title = $(this).find('option:selected').text();
            var project_id = $(this).val();
            SectionGet('project', project_id, title);
        });
        $(document).on('change', "#sale_invoice", function() {
            var title = 'sales';
            var invoice_id = $(this).val();
            SectionGet('sales', invoice_id, 'sales');

        });
    </script>
    <script>
        $(document).on('change', "#course_order", function() {
            var title = 'Course';
            var project_id = '0';
            var course_order = $(this).val();

            SectionGet('course', project_id, title, course_order);

        });
    </script>
     <script>
        $(document).on('change', "#restaurant_order", function() {

            setTimeout(() => {
                var title = 'Restaurant Menu';
                var project_id = '0';
                var restaurant_order = $('#restaurant_order').val();
                SectionGet('restaurantmenu', project_id, title,restaurant_order);
            }, 3000);
        });
    </script>
    <script>
        setTimeout(() => {
            $('#due_date').trigger('click');
        }, 1500);
    </script>



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

    <script>
        $(document).ready(function() {
            $('#billing_type').on('change', function() {
                if ($(this).val() == 'rent') {
                    $('#due_date').prop('readonly', true);
                } else {
                    $('#due_date').prop('readonly', false);
                }
            });


            var valueToMatch = "{{ $invoice->account_type }}";
            $('#account_type').val(valueToMatch).trigger('change');
        });
    </script>


    <script>
        function Calculate() {

            var rentType = document.getElementById('rent_type').value;
            var startDate = new Date(document.getElementById('issue_date').value);
            if (rentType === '0') {
                var endDate = startDate.toISOString().split('T')[0];
                document.getElementById('due_date').value = endDate;
            } else if (rentType === '1') {
                // Calculate end date for a week
                startDate.setDate(startDate.getDate() + 7);
            } else if (rentType === '2') {
                // Calculate end date for a month
                startDate.setMonth(startDate.getMonth() + 1);
            }

            // Format the date to 'YYYY-MM-DD'
            var endDate = startDate.toISOString().split('T')[0];

            // Set the calculated end date
            document.getElementById('due_date').value = endDate;
        }
    </script>

    <script>
        // window.onload = function() {
        //     $("#loader").removeClass('d-none');
        //     setTimeout(function() {
        //         $("#loader").addClass('d-none');
        //     }, 3000);
        // };
    </script>
@endpush
