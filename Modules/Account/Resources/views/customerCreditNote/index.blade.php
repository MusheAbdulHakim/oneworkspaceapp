@extends('layouts.main')
@section('page-title')
    {{ __('Manage Credit Notes') }}
@endsection
@section('page-breadcrumb')
    {{ __('Credit Note') }}
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
    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a></li>
    <li class="breadcrumb-item">{{ __('Credit Note') }}</li>
@endsection

@section('page-action')
    <div class="float-end">
        <a data-url="{{ route('create.custom.credit.note') }}" data-ajax-popup="true" data-bs-toggle="tooltip"
            title="{{ __('Create') }}" title="{{ __('Create') }}" data-title="{{ __('Create New Credit Note') }}"
            class="btn btn-sm btn-primary">
            <i class="ti ti-plus"></i>
        </a>
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="custom-credit">
                            <thead>
                            <tr>
                                <th> {{ __('Invoice') }}</th>
                                <th> {{ __('Customer') }}</th>
                                <th> {{ __('Date') }}</th>
                                <th> {{ __('Amount') }}</th>
                                <th> {{ __('Description') }}</th>
                                <th> {{ __('Status') }}</th>
                                <th width="10%"> {{ __('Action') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($invoices as $invoice)

                                @if (!empty($invoice->customcreditNote))

                                    @foreach ($invoice->customcreditNote as $customcreditNote)
                                        <tr>
                                            <td class="Id">
                                                @if (Laratrust::hasPermission('invoice show'))
                                                    <a href="{{ route('invoice.show', \Crypt::encrypt($invoice->id)) }}"
                                                       class="btn btn-outline-primary">{{ App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id) }}</a>
                                                @else
                                                    <a href="#"
                                                       class="btn btn-outline-primary">{{ App\Models\Invoice::invoiceNumberFormat($invoice->invoice_id) }}</a>
                                                @endif
                                            </td>
                                            <td>{{ (!empty($invoice->customer)?$invoice->customer->name:'-') }}</td>
                                            <td>{{ company_date_formate($customcreditNote->date) }}</td>
                                            <td>{{ currency_format_with_sym($customcreditNote->amount) }}</td>

                                            <td>{{!empty($customcreditNote->description)?$customcreditNote->description:'-'}}</td>
                                            <td>
                                                @if ($customcreditNote->status == 0)
                                                    <span
                                                        class="badge fix_badges bg-primary p-2 px-3 rounded">{{ __(Modules\Account\Entities\CustomerCreditNotes::$statues[$customcreditNote->status]) }}</span>
                                                @elseif($customcreditNote->status == 1)
                                                    <span
                                                        class="badge fix_badges bg-info p-2 px-3 rounded">{{ __(Modules\Account\Entities\CustomerCreditNotes::$statues[$customcreditNote->status]) }}</span>
                                                @elseif($customcreditNote->status == 2)
                                                    <span
                                                        class="badge fix_badges bg-secondary p-2 px-3 rounded">{{ __(Modules\Account\Entities\CustomerCreditNotes::$statues[$customcreditNote->status]) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @permission('creditnote edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a data-url="{{ route('invoice.edit.custom-credit',[$customcreditNote->invoice,$customcreditNote->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Credit Note')}}" href="#" class="mx-3 btn btn-sm align-items-center" data-bs-toggle="tooltip" title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                        <i class="ti ti-edit text-white"></i>
                                                    </a>
                                                </div>
                                                @endpermission
                                                @permission('creditnote delete')
                                                <div class="action-btn bg-danger ms-2">
                                                    {{Form::open(array('route'=>array('invoice.custom-note.delete', $customcreditNote->invoice,$customcreditNote->id),'class' => 'm-0'))}}
                                                    @method('DELETE')
                                                    <a href="#"
                                                       class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                       data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                       aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$customcreditNote->id}}"><i
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
