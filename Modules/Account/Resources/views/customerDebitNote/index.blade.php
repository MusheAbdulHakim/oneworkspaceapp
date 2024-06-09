@extends('layouts.main')
@section('page-title')
    {{ __('Manage Debit Notes') }}
@endsection
@section('page-breadcrumb')
    {{ __('Debit Note') }}
@endsection
@push('script-page')
    <script>
        $(document).on('change', '#bill', function() {

            var id = $(this).val();
            var url = "{{ route('bill.get') }}";

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                data: {
                    'bill_id': id,

                },
                success: function(data) {
                    $('#amount').val(data)
                },

            });

        })
    </script>
@endpush

@section('page-action')
    <div class="float-end">
        @permission('debitnote create')
            <a href="#" data-url="{{ route('bill.custom.debit.note') }}" data-ajax-popup="true"
                data-title="{{ __('Create New Debit Note') }}" data-bs-toggle="tooltip" title="{{ __('Create') }}"
                class="btn btn-sm btn-primary">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
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
                                    <th> {{ __('Bill') }}</th>
                                    <th> {{ __('Vendor') }}</th>
                                    <th> {{ __('Date') }}</th>
                                    <th> {{ __('Amount') }}</th>
                                    <th> {{ __('Description') }}</th>
                                    <th> {{ __('Status') }}</th>
                                    <th width="10%"> {{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>

                                @foreach ($bills as $bill)

                                    @if (!empty($bill->customdebitNote))

                                        @foreach ($bill->customdebitNote as $customdebitNote)
                                            <tr class="font-style">
                                                <td class="Id">
                                                    <a href="{{ route('bill.show', \Crypt::encrypt($customdebitNote->bill)) }}"
                                                        class="btn btn-outline-primary">{{ Modules\Account\Entities\Bill::billNumberFormat($bill->bill_id) }}

                                                    </a>
                                                </td>
                                                <td>{{ !empty($bill->vendor) ? $bill->vendor->name : '-' }}</td>
                                                <td>{{ company_date_formate($customdebitNote->date) }}</td>
                                                <td>{{ currency_format_with_sym($customdebitNote->amount) }}</td>
                                                <td>{{ !empty($customdebitNote->description) ? $customdebitNote->description : '-' }}</td>
                                                <td>
                                                    @if ($customdebitNote->status == 0)
                                                        <span
                                                            class="badge fix_badges bg-primary p-2 px-3 rounded">{{ __(Modules\Account\Entities\CustomerDebitNotes::$statues[$customdebitNote->status]) }}</span>
                                                    @elseif($customdebitNote->status == 1)
                                                        <span
                                                            class="badge fix_badges bg-info p-2 px-3 rounded">{{ __(Modules\Account\Entities\CustomerDebitNotes::$statues[$customdebitNote->status]) }}</span>
                                                    @elseif($customdebitNote->status == 2)
                                                        <span
                                                            class="badge fix_badges bg-secondary p-2 px-3 rounded">{{ __(Modules\Account\Entities\CustomerDebitNotes::$statues[$customdebitNote->status]) }}</span>
                                                    @endif
                                                </td>
                                                <td class="Action">
                                                    <span>
                                                        @permission('debitnote edit')
                                                            <div class="action-btn bg-primary ms-2">
                                                                <a data-url="{{ route('bill.debit-custom.edit', [$customdebitNote->bill, $customdebitNote->id]) }}"
                                                                    data-ajax-popup="true"
                                                                    data-title="{{ __('Edit Debit Note') }}" href="#"
                                                                    class="mx-3 btn btn-sm align-items-center"
                                                                    data-bs-toggle="tooltip" title="{{ __('Edit') }}"
                                                                    data-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission
                                                        @permission('debitnote edit')
                                                            <div class="action-btn bg-danger ms-2">
                                                                {!! Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['bill.delete.custom-debit', $customdebitNote->bill, $customdebitNote->id],
                                                                    'id' => 'delete-form-' . $customdebitNote->id,
                                                                ]) !!}

                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm align-items-center show_confirm"
                                                                    data-bs-toggle="tooltip" title="{{ __('Delete') }}"
                                                                    data-original-title="{{ __('Delete') }}"
                                                                    data-confirm="{{ __('Are You Sure?') }}"
                                                                    data-confirm-yes="document.getElementById('delete-form-{{ $customdebitNote->id }}').submit();">
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
