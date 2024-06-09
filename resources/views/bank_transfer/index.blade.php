@extends('layouts.main')
@section('page-title')
    {{__('Bank Transfer Request')}}
@endsection
@section('page-breadcrumb')
    {{__('Bank Transfer Request')}}
@endsection
@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table datatable pc-dt-simple" id="test">
                            <thead>
                            <tr>
                                <th>{{__('Order Id')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Price')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Attachment')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bank_transfer_payments as $bank_transfer_payment)
                                <tr>
                                    <td>{{$bank_transfer_payment->order_id}}</td>
                                    <td>{{ company_datetime_formate($bank_transfer_payment->created_at)}}</td>
                                    <td>{{ !empty($bank_transfer_payment->User) ? $bank_transfer_payment->User->name : '' }}</td>
                                    <td>{{$bank_transfer_payment->price.' '.$bank_transfer_payment->price_currency}}</td>
                                    <td>
                                        @if($bank_transfer_payment->status == 'Approved')
                                            <span class="bg-success p-1 px-3 rounded text-white">{{ucfirst($bank_transfer_payment->status)}}</span>
                                        @elseif($bank_transfer_payment->status == 'Pending')
                                            <span class="bg-warning p-1 px-3 rounded text-white">{{ucfirst($bank_transfer_payment->status)}}</span>
                                        @else
                                            <span class="bg-danger p-1 px-3 rounded text-white">{{ucfirst($bank_transfer_payment->status)}}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if (!empty($bank_transfer_payment->attachment) && (check_file($bank_transfer_payment->attachment)))
                                        <div class="action-btn bg-primary ms-2">
                                            <a class="mx-3 btn btn-sm align-items-center" href="{{ get_file($bank_transfer_payment->attachment) }}" download>
                                                <i class="ti ti-download text-white"></i>
                                            </a>
                                        </div>
                                            <div class="action-btn bg-secondary ms-2">
                                                <a class="mx-3 btn btn-sm align-items-center" href="{{ get_file($bank_transfer_payment->attachment) }}" target="_blank"  >
                                                    <i class="ti ti-crosshair text-white" data-bs-toggle="tooltip" data-bs-original-title="{{ __('Preview') }}"></i>
                                                </a>
                                            </div>
                                        @else
                                            <p>-</p>
                                        @endif
                                    </td>
                                    <td class="Action">
                                        <span>
                                            <div class="action-btn bg-primary ms-2">
                                                <a  class="mx-3 btn btn-sm  align-items-center"
                                                    data-url="{{ route('bank-transfer-request.edit', $bank_transfer_payment->id) }}"
                                                    data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                    data-title="{{ __('Request Action') }}"
                                                    data-bs-original-title="{{ __('Action') }}">
                                                    <i class="ti ti-caret-right text-white"></i>
                                                </a>
                                            </div>
                                            <div class="action-btn bg-danger ms-2">
                                                {{Form::open(array('route'=>array('bank-transfer-request.destroy', $bank_transfer_payment->id),'class' => 'm-0'))}}
                                                @method('DELETE')
                                                    <a
                                                        class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$bank_transfer_payment->id}}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                {{Form::close()}}
                                            </div>
                                        </span>
                                    </td>
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
