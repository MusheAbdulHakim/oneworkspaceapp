@extends('layouts.main')
@section('page-title')
    {{ __('Bank Balance Transfer') }}
@endsection
@section('page-breadcrumb')
    {{ __('Bank Balance Transfer') }}
@endsection
@section('page-action')
    <div>
        @permission('bank account create')
            <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md"
                data-title="{{ __('Create New Transfer') }}" data-url="{{ route('bank-transfer.create') }}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="mt-2" id="multiCollapseExample1">

            <div class="card">
                <div class="card-body">
                    {{ Form::open(array('route' => array('bank-transfer.index'),'method' => 'GET','id'=>'transfer_form')) }}
                    <div class="row align-items-center justify-content-end">
                        <div class="col-xl-10">
                            <div class="row">

                                <div class="col-3">
                                </div>

                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-2  month">
                                    <div class="btn-box">
                                        {{ Form::label('date', __('Date'), ['class' => 'form-label']) }}
                                        {{ Form::text('date', isset($_GET['date']) ? $_GET['date'] : null, ['class' => 'form-control flatpickr-to-input', 'placeholder' => 'Select Date']) }}

                                    </div>
                                </div>
                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-2 date">
                                    <div class="btn-box">
                                        {{ Form::label('f_account', __('From Account'), ['class' => 'form-label']) }}
                                        {{ Form::select('f_account', $account, isset($_GET['f_account']) ? $_GET['f_account'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Account']) }}
                                    </div>
                                </div>
                                    <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mb-2">
                                        <div class="btn-box">
                                            {{ Form::label('t_account', __('To Account'), ['class' => 'form-label']) }}
                                        {{ Form::select('t_account', $account, isset($_GET['t_account']) ? $_GET['t_account'] : '', ['class' => 'form-control ', 'placeholder' => 'Select Account']) }}
                                        </div>
                                    </div>
                            </div>
                        </div>
                        <div class="col-auto mt-4">
                            <div class="row">
                                <div class="col-auto">

                                    <a  class="btn btn-sm btn-primary" onclick="document.getElementById('transfer_form').submit(); return false;" data-bs-toggle="tooltip" title="{{__('Apply')}}" data-original-title="{{__('apply')}}">
                                        <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                                    </a>

                                    <a href="{{route('bank-transfer.index')}}" class="btn btn-sm btn-danger " data-bs-toggle="tooltip"  title="{{ __('Reset') }}" data-original-title="{{__('Reset')}}">
                                        <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                                    </a>


                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th> {{__('Date')}}</th>
                                    <th> {{__('From Account')}}</th>
                                    <th> {{__('To Account')}}</th>
                                    <th> {{__('Amount')}}</th>
                                    <th> {{__('Reference')}}</th>
                                    <th> {{__('Description')}}</th>
                                    @if(Laratrust::hasPermission('bank transfer edit') || Laratrust::hasPermission('bank transfer delete'))
                                        <th width="10%"> {{__('Action')}}</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transfers as $transfer)
                                    <tr class="font-style">
                                        <td>{{ company_date_formate( $transfer->date) }}</td>
                                        <td>{{ !empty($transfer->fromBankAccount) ? $transfer->fromBankAccount->bank_name . ' ' . $transfer->fromBankAccount->holder_name : '' }}</td>
                                        <td>{{ !empty($transfer->toBankAccount) ? $transfer->toBankAccount->bank_name . ' ' . $transfer->toBankAccount->holder_name : '' }}</td>
                                        <td>{{  currency_format_with_sym( $transfer->amount)}}</td>
                                        <td>{{  $transfer->reference}}</td>
                                        <td>
                                            <p style="white-space: nowrap;
                                                width: 200px;
                                                overflow: hidden;
                                                text-overflow: ellipsis;">{{  !empty($transfer->description) ? $transfer->description : '' }}
                                            </p>
                                        </td>
                                        @if(Laratrust::hasPermission('bank transfer edit') || Laratrust::hasPermission('bank transfer delete'))
                                            <td class="Action">
                                                <span>
                                                     @permission('bank transfer edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a  class="mx-3 btn btn-sm  align-items-center"
                                                                data-url="{{ route('bank-transfer.edit',$transfer->id) }}"
                                                                data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                                data-title="{{ __('Edit Transfer') }}"
                                                                data-bs-original-title="{{ __('Edit') }}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @permission('bank transfer delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {{Form::open(array('route'=>array('bank-transfer.destroy', $transfer->id),'class' => 'm-0'))}}
                                                            @method('DELETE')
                                                                <a
                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                                    aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$transfer->id}}"><i
                                                                        class="ti ti-trash text-white text-white"></i></a>
                                                            {{Form::close()}}
                                                        </div>
                                                    @endpermission
                                                </span>
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
