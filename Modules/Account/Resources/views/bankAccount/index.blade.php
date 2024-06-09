@extends('layouts.main')
@section('page-title')
    {{ __('Manage Bank Account') }}
@endsection

@section('page-breadcrumb')
{{ __('Bank Account') }}
@endsection

@section('page-action')
<div>
    @stack('addButtonHook')
    @permission('bank account create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Account') }}" data-url="{{route('bank-account.create')}}" data-bs-toggle="tooltip"  data-bs-original-title="{{ __('Create') }}">
            <i class="ti ti-plus"></i>
        </a>
    @endpermission
</div>
@endsection
@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                            <tr>
                                <th>{{__('Chart Of Account')}}</th>
                                <th> {{__('Name')}}</th>
                                <th> {{__('Bank')}}</th>
                                <th> {{__('Account Number')}}</th>
                                <th> {{__('Current Balance')}}</th>
                                <th> {{__('Contact Number')}}</th>
                                <th> {{__('Bank Branch')}}</th>
                                <th> {{__('SWIFT')}}</th>
                                <th> {{__('Bank Address')}}</th>
                                @if(Laratrust::hasPermission('bank account edit') || Laratrust::hasPermission('bank account delete'))
                                    <th width="10%"> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($accounts as $account)
                                <tr class="font-style">
                                    <td>{{ (isset($account->chart_account_name)?$account->chart_account_name:'-') }}</td>
                                    <td>{{  $account->holder_name}}</td>
                                    <td>{{  $account->bank_name}}</td>
                                    <td>{{  $account->account_number}}</td>
                                    <td>{{  currency_format_with_sym($account->opening_balance)}}</td>
                                    <td>{{  $account->contact_number}}</td>
                                    <td>{{  $account->bank_branch}}</td>
                                    <td>{{  $account->swift}}</td>

                                    <td>{{  $account->bank_address}}</td>
                                    @if(Laratrust::hasPermission('bank account edit') || Laratrust::hasPermission('bank account delete'))
                                        <td class="Action">
                                            <span>
                                            @if($account->holder_name!='Cash')
                                                    @permission('bank account edit')
                                                        <div class="action-btn bg-info ms-2">
                                                            <a  class="mx-3 btn btn-sm align-items-center" data-url="{{ route('bank-account.edit',$account->id) }}" data-ajax-popup="true" title="{{__('Edit')}}" data-title="{{__('Edit Bank Account')}}"data-bs-toggle="tooltip"  data-size="md"  data-original-title="{{__('Edit')}}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @permission('bank account delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {{Form::open(array('route'=>array('bank-account.destroy', $account->id),'class' => 'm-0'))}}
                                                            @method('DELETE')
                                                                <a
                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                    data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                                    aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$account->id}}"><i
                                                                        class="ti ti-trash text-white text-white"></i></a>
                                                            {{Form::close()}}
                                                        </div>
                                                    @endpermission
                                                @else
                                                    -
                                                @endif
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
