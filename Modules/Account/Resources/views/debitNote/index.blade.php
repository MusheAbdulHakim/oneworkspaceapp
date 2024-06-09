@extends('layouts.admin')
@section('page-title')
    {{__('Manage Debit Notes')}}
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Debit Note')}}</li>
@endsection
@push('script-page')
    <script>
        $(document).on('change', '#bill', function () {

            var id = $(this).val();
            var url = "{{route('bill.get')}}";

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                data: {
                    'bill_id': id,

                },
                success: function (data) {
                    $('#amount').val(data)
                },

            });

        })
    </script>
@endpush

@section('action-btn')
    <div class="float-end">
        @permission('create debit note')
            <a  data-url="{{ route('bill.custom.debit.note') }}" data-ajax-popup="true" data-title="{{__('Create New Debit Note')}}" data-bs-toggle="tooltip" title="{{__('Create')}}"  class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>

        @endpermission
    </div>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Bill')}}</th>
                                <th> {{__('Vendor')}}</th>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Amount')}}</th>
                                <th> {{__('Description')}}</th>
                                <th width="10%"> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($bills as $bill)
                                @if(!empty($bill->debitNote))
                                    @foreach ($bill->debitNote as $debitNote)

                                        <tr class="font-style">
                                            <td class="Id">
                                                <a href="{{ route('bill.show',\Crypt::encrypt($debitNote->bill)) }}" class="btn btn-outline-primary">{{ AUth::user()->billNumberFormat($bill->bill_id) }}
                                                </a>
                                            </td>
                                            <td>{{ (!empty($bill->vendor)?$bill->vendor->name:'-') }}</td>
                                            <td>{{ Auth::user()->dateFormat($debitNote->date) }}</td>
                                            <td>{{ Auth::user()->priceFormat($debitNote->amount) }}</td>
                                            <td>{{!empty($debitNote->description)?$debitNote->description:'-'}}</td>
                                            <td class="Action">
                                                <span>
                                                @permission('edit debit note')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a data-url="{{ route('bill.edit.debit.note',[$debitNote->bill,$debitNote->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Debit Note')}}"  class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                                <i class="ti ti-edit text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @permission('edit debit note')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => array('bill.delete.debit.note', $debitNote->bill,$debitNote->id),'id'=>'delete-form-'.$debitNote->id]) !!}

                                                            <a  class="mx-3 btn btn-sm align-items-center bs-pass-para" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$debitNote->id}}').submit();">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                            {!! Form::close() !!}
                                                        </div>
                                                    @endpermission
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
