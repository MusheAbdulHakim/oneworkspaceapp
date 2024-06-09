@extends('layouts.admin')
@section('page-title')
    {{__('Manage Credit Notes')}}
@endsection
@push('script-page')
    <script>
        $(document).on('change', '#invoice', function () {

            var id = $(this).val();
            var url = "{{route('invoice.get')}}";

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                data: {
                    'id': id,

                },
                success: function (data) {
                    $('#amount').val(data)
                },

            });

        })
    </script>
@endpush
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item">{{__('Credit Note')}}</li>
@endsection

@section('action-btn')
    <div class="float-end">
        @permission('create credit note')
            <a href="#" data-url="{{ route('invoice.custom.credit.note') }}"data-bs-toggle="tooltip" title="{{__('Create')}}" data-ajax-popup="true" data-title="{{__('Create New Credit Note')}}" class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body table-border-style mt-2">
                    <h5></h5>
                    <div class="table-responsive">
                        <table class="table datatable">
                            <thead>
                            <tr>
                                <th> {{__('Invoice')}}</th>
                                <th> {{__('Customer')}}</th>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Amount')}}</th>
                                <th> {{__('Description')}}</th>
                                <th width="10%"> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($invoices as $invoice)
                                @if(!empty($invoice->creditNote))
                                    @foreach ($invoice->creditNote as $creditNote)
                                        <tr>
                                            <td class="Id">
                                                <a href="{{ route('invoice.show',\Crypt::encrypt($creditNote->invoice)) }}" class="btn btn-outline-primary">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</a>
                                            </td>
                                            <td>{{ (!empty($invoice->customer)?$invoice->customer->name:'-') }}</td>
                                            <td>{{ Auth::user()->dateFormat($creditNote->date) }}</td>
                                            <td>{{ Auth::user()->priceFormat($creditNote->amount) }}</td>
                                            <td>{{!empty($creditNote->description)?$creditNote->description:'-'}}</td>
                                            <td>
                                                @permission('edit credit note')
                                                    <div class="action-btn bg-info ms-2">
                                                        <a data-url="{{ route('invoice.edit.credit.note',[$creditNote->invoice,$creditNote->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Credit Note')}}" href="#" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                            <i class="ti ti-edit text-white"></i>
                                                        </a>
                                                    </div>
                                                @endpermission
                                                @permission('edit credit note')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {{Form::open(array('route'=>array('invoice.delete.credit.note', $creditNote->invoice,$creditNote->id),'class' => 'm-0'))}}
                                                            @method('DELETE')
                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                                    aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$creditNote->id}}"><i
                                                                        class="ti ti-trash text-white text-white"></i></a>
                                                            {{Form::close()}}
                                                        </div>
                                                @endpermission
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
