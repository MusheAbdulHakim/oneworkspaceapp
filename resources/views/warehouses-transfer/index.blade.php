@extends('layouts.main')
@section('page-title')
    {{__('Warehouse Transfer')}}
@endsection
@push('script-page')
@endpush
@section('page-breadcrumb')
    {{__('Warehouse Transfer')}}
@endsection
@section('page-action')
    <div>
        @permission('warehouse create')
            <a data-size="lg" data-url="{{ route('warehouses-transfer.create') }}" data-ajax-popup="true"
               data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create Warehouse Transfer')}}"  class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                            <tr>
                                <th>{{__('From Warehouse')}}</th>
                                <th>{{__('To Warehouse')}}</th>
                                <th>{{__('Product')}}</th>
                                <th>{{__('Quantity')}}</th>
                                <th>{{__('Date')}}</th>
                                @if(Laratrust::hasPermission('warehouse delete'))
                                <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($warehouse_transfers as $warehouse_transfer)

                                <tr class="font-style">
                                    <td>{{ !empty($warehouse_transfer->fromWarehouse)? $warehouse_transfer->fromWarehouse->name:'' }}</td>
                                    <td>{{ !empty($warehouse_transfer->toWarehouse)? $warehouse_transfer->toWarehouse->name:'' }}</td>
                                    @if(!empty($warehouse_transfer->product))
                                        <td>{{ !empty($warehouse_transfer->product)? $warehouse_transfer->product->name:'' }}</td>
                                    @endif
                                    <td>{{ $warehouse_transfer->quantity }}</td>
                                    <td>{{company_date_formate($warehouse_transfer->date) }}</td>

                                    @if(Laratrust::hasPermission('warehouse delete'))
                                        <td class="Action">
                                            <div class="action-btn bg-danger ms-2">
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['warehouses-transfer.destroy', $warehouse_transfer->id],'id'=>'delete-form-'.$warehouse_transfer->id]) !!}
                                                <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}" ><i class="ti ti-trash text-white"></i></a>
                                                {!! Form::close() !!}
                                            </div>
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

@push('scripts')

    <script>
        $(document).ready(function () {
            var w_id = $('#warehouse_id').val();
            getProduct(w_id);
        });
        $(document).on('change', 'select[name=from_warehouse]', function ()
        {
            var warehouse_id = $(this).val();
            getProduct(warehouse_id);
        });

        function getProduct(wid)
        {
            $.ajax({
                url: '{{route('warehouses-transfer.getproduct')}}',
                type: 'POST',
                data: {
                    "warehouse_id": wid,
                    "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#product_id').empty();

                    $("#product_div").html('');
                    $('#product_div').append('<label for="product" class="form-label">{{__('Product')}}</label>');
                    $('#product_div').append('<select class="form-control" id="product_id" name="product_id"></select>');
                    $('#product_id').append('<option value="">{{__('Select Product')}}</option>');

                    $.each(data.ware_products, function (key, value) {
                        $('#product_id').append('<option value="' + key + '">' + value + '</option>');
                    });

                    $('select[name=to_warehouse]').empty();
                    $.each(data.to_warehouses, function(key, value) {
                        var option = '<option value="' + key + '">' + value + '</option>';
                        $('select[name=to_warehouse]').append(option);
                    });
                }

            });
        }

        $(document).on('change', '#product_id', function () {
            var product_id = $(this).val();
            getQuantity(product_id);
        });

        function getQuantity(pid) {

            $.ajax({
                url: '{{route('warehouses-transfer.getquantity')}}',
                type: 'POST',
                data: {
                    "product_id": pid,
                    "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    // console.log(data);
                    $('#quantity').val(data);
                }
            });
        }
    </script>
@endpush
