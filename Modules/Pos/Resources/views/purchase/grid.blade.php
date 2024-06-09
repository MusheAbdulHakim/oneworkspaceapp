@extends('layouts.main')
@section('page-title')
    {{ __('Manage Purchase') }}
@endsection
@section('page-breadcrumb')
    {{ __('Manage Purchase') }}
@endsection
@push('scripts')
    <script>
        $('.copy_link').click(function (e) {
            e.preventDefault();
            var copyText = $(this).attr('href');

            document.addEventListener('copy', function (e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('Success', 'Url copied to clipboard', 'success');
        });
    </script>
@endpush
@section('page-action')
<div>
    <a href="{{ route('purchase.index') }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"title="{{ __('List View') }}">
        <i class="ti ti-list text-white"></i>
    </a>
    @if(module_is_active('ProductService'))
    <a href="{{ route('category.index') }}"data-size="md"  class="btn btn-sm btn-primary" data-bs-toggle="tooltip"data-title="{{__('Setup')}}" title="{{__('Setup')}}"><i class="ti ti-settings"></i></a>
        @permission('purchase create')
            <a href="{{ route('purchase.create',0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{__('Create')}}">
                <i class="ti ti-plus"></i>
            </a>
        @endpermission
    @endif
</div>
@endsection
@section('content')
<div class="row">
<div class="col-sm-12">
    <div class="row">
        @foreach ($purchases as $purchase)
        <div class="col-lg-4">
            <div class="card hover-shadow-lg">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col-10">
                            <h6 class="mb-0">
                                <a href="{{ route('purchase.show',\Crypt::encrypt($purchase->id)) }}">{{ App\Models\Purchase::purchaseNumberFormat($purchase->purchase_id) }}</a>
                            </h6>
                        </div>
                        <div class="col-2 text-end">
                            <div class="actions">
                                <div class="dropdown">
                                    <a href="#" class="action-item" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></a>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        @permission('purchase edit')
                                            <a href="{{ route('purchase.edit',\Crypt::encrypt($purchase->id)) }}" class="dropdown-item" data-bs-toggle="tooltip" data-title="{{__('Edit Quote')}}"><i class="ti ti-pencil"></i>{{__('Edit')}}</a>
                                        @endpermission
                                        @permission('purchase delete')
                                            {!! Form::open(['method' => 'DELETE', 'route' =>['purchase.destroy', $purchase->id]]) !!}
                                                <a href="#!" class="dropdown-item show_confirm" data-bs-toggle="tooltip">
                                                    <i class="ti ti-trash"></i>{{ __('Delete') }}
                                                </a>
                                            {!! Form::close() !!}
                                        @endpermission
                                        @permission('purchase show')
                                            <a href="{{ route('purchase.show',\Crypt::encrypt($purchase->id)) }}" data-size="md"class="dropdown-item" data-bs-toggle="tooltip"  data-title="{{__('Details')}}">
                                                <i class="ti ti-eye"></i>{{__('View')}}
                                            </a>
                                        @endpermission
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="p-3 border border-dashed">

                        @if($purchase->status == 0)
                            <span class="purchase_status badge bg-secondary p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                        @elseif($purchase->status == 1)
                            <span class="purchase_status badge bg-warning p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                        @elseif($purchase->status == 2)
                            <span class="purchase_status badge bg-danger p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                        @elseif($purchase->status == 3)
                            <span class="purchase_status badge bg-info p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                        @elseif($purchase->status == 4)
                            <span class="purchase_status badge bg-primary p-2 px-3 rounded">{{ __(App\Models\Purchase::$statues[$purchase->status]) }}</span>
                        @endif
                        <div class="row align-items-center mt-3">
                            <div class="col-6">
                                <h6 class="mb-0">{{currency_format_with_sym($purchase->getTotal())}}</h6>
                                <span class="text-sm text-muted">{{__('Total Amount')}}</span>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-0">{{currency_format_with_sym($purchase->getDue())}}</h6>
                                <span class="text-sm text-muted">{{__('Due Amount')}}</span>
                            </div>
                        </div>
                        <div class="row align-items-center mt-3">
                            <div class="col-6">
                                <h6 class="mb-0">{{currency_format_with_sym($purchase->getTotalTax())}}</h6>
                                <span class="text-sm text-muted">{{__('Total Tax')}}</span>
                            </div>
                            <div class="col-6">
                                <h6 class="mb-0">{{company_date_formate($purchase->purchase_date)}}</h6>
                                <span class="text-sm text-muted">{{__('Purchase Date')}}</span>
                             </div>
                        </div>
                    </div>
                    @if (\Auth::user()->type != 'Client')
                        <div class="user-group pt-2">
                                <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                @if ($purchase->user != NUll) title="{{ $purchase->user->name }}" @else title="{{$purchase->vender_name}}"@endif
                                    @if ($purchase->user != NUll) src="{{ get_file($purchase->user->avatar) }}" @else src="{{ get_file('uploads/users-avatar/avatar.png') }}" @endif
                                    class="rounded-circle " width="25" height="25">
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
</div>
@endsection
