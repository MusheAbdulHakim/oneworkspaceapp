@extends('layouts.main')
@section('page-title')
    {{ __('Proposal') }}
@endsection
@section('page-breadcrumb')
    {{ __('Proposal') }}
@endsection
@section('page-action')
    <div>
        @if (module_is_active('ProductService') && (module_is_active('Account') || module_is_active('Taskly')))
            @permission('category create')
                <a href="{{ route('category.index') }}"data-size="md"  class="btn btn-sm btn-primary" data-bs-toggle="tooltip"data-title="{{__('Setup')}}" title="{{__('Setup')}}"><i class="ti ti-settings"></i></a>
            @endpermission
            @permission('proposal manage')
                <a href="{{ route('proposal.index') }}" data-bs-toggle="tooltip" data-bs-original-title="{{ __('List View') }}"
                    class="btn btn-sm btn-primary btn-icon m-1">
                    <i class="ti ti-list"></i>
                </a>
                <a href="{{ route('proposal.stats.view') }}"  data-bs-toggle="tooltip" data-bs-original-title="{{__('Quick Stats')}}" class="btn btn-sm btn-primary btn-icon">
                    <i class="ti ti-filter"></i>
                </a>
            @endpermission
            @permission('proposal create')
                <a href="{{ route('proposal.create', 0) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip"
                    data-bs-original-title="{{ __('Create') }}">
                    <i class="ti ti-plus"></i>
                </a>
            @endpermission
        @endif
    </div>
@endsection
@section('content')
    <div class="row">
        <div class="mt-2" id="multiCollapseExample1">
            <div class="card">
                <div class="card-body">
                    {{ Form::open(['route' => ['proposal.grid.view'], 'method' => 'GET', 'id' => 'frm_submit']) }}
                    <div class="row d-flex align-items-center justify-content-end">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                            <div class="btn-box">
                                {{ Form::label('issue_date', __('Date'), ['class' => 'text-type']) }}
                                {{ Form::text('issue_date', isset($_GET['issue_date']) ? $_GET['issue_date'] : null, ['class' => 'form-control flatpickr-to-input','placeholder' => 'Select Date']) }}
                            </div>
                        </div>
                        @if (\Auth::user()->type != 'client')
                            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                <div class="btn-box">
                                    {{ Form::label('customer', __('Customer'), ['class' => 'text-type']) }}
                                    {{ Form::select('customer', $customer, isset($_GET['customer']) ? $_GET['customer'] : '', ['class' => 'form-control', 'placeholder' => 'Select Client']) }}
                                </div>
                            </div>
                        @endif
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                            <div class="btn-box">
                                {{ Form::label('status', __('Status'), ['class' => 'text-type']) }}
                                {{ Form::select('status', ['' => 'Select Status'] + $status, isset($_GET['status']) ? $_GET['status'] : '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="col-auto float-end ms-2 mt-4">
                            <a href="#" class="btn btn-sm btn-primary"
                                onclick="document.getElementById('frm_submit').submit(); return false;"
                                data-bs-toggle="tooltip" title="{{ __('Apply') }}"
                                data-original-title="{{ __('apply') }}">
                                <span class="btn-inner--icon"><i class="ti ti-search"></i></span>
                            </a>
                            <a href="{{ route('proposal.grid.view') }}" class="btn btn-sm btn-danger "
                                data-bs-toggle="tooltip" title="{{ __('Reset') }}"
                                data-original-title="{{ __('Reset') }}">
                                <span class="btn-inner--icon"><i class="ti ti-trash-off text-white-off "></i></span>
                            </a>
                        </div>
                    </div>
                {{ Form::close() }}
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <div class="row">
                @foreach ($proposals as $proposal)
                    <div class="col-lg-4">
                        <div class="card hover-shadow-lg">
                            <div class="card-header border-0">
                                <div class="row align-items-center">
                                    <div class="col-10">
                                        <h6 class="mb-0">
                                            @if (Laratrust::hasPermission('proposal show'))
                                                <a href="{{ route('proposal.show', \Crypt::encrypt($proposal->id)) }}">{{ \App\Models\Proposal::proposalNumberFormat($proposal->proposal_id) }}</a>
                                            @else
                                                <a href="#">{{ \App\Models\Proposal::proposalNumberFormat($proposal->proposal_id) }}</a>
                                            @endif

                                        </h6>
                                    </div>
                                    <div class="col-2 text-end">
                                        <div class="actions">
                                            <div class="dropdown">
                                                <a href="#" class="action-item" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></a>
                                                <div class="dropdown-menu dropdown-menu-right">
                                                    <a href="#"  data-link="{{route('pay.proposalpay',\Illuminate\Support\Facades\Crypt::encrypt($proposal->id))}}" class="dropdown-item cp_link" >
                                                        <i class="ti ti-copy"></i> {{__('Click to copy proposal link')}}
                                                    </a>
                                                    @if (module_is_active('Retainer'))
                                                        @include('retainer::setting.convert_retainer', ['proposal' => $proposal, 'type' => 'grid'])
                                                    @endif

                                                    @if ($proposal->is_convert == 0 && $proposal->is_convert_retainer ==0)
                                                        @permission('proposal convert invoice')
                                                                {!! Form::open([
                                                                    'method' => 'get',
                                                                    'route' => ['proposal.convert', $proposal->id],
                                                                    'id' => 'proposal-form-' . $proposal->id,
                                                                ]) !!}
                                                                <a href="#!" class="show_confirm dropdown-item" data-confirm-yes="proposal-form-{{ $proposal->id }}">
                                                                    <i class="ti ti-exchange"></i>{{ __('Convert to Invoice') }}
                                                                </a>
                                                                {{ Form::close() }}
                                                        @endpermission


                                                    @elseif($proposal->is_convert ==1)
                                                        @permission('invoice show')
                                                                <a href="{{ route('invoice.show', \Crypt::encrypt($proposal->converted_invoice_id)) }}"
                                                                    class="dropdown-item">
                                                                    <i class="ti ti-eye"></i>{{ __('View Invoice') }}
                                                                </a>
                                                        @endpermission
                                                    @endif
                                                    @permission('proposal duplicate')
                                                    {!! Form::open([
                                                        'method' => 'get',
                                                        'route' => ['proposal.duplicate', $proposal->id],
                                                        'id' => 'duplicate-form-' . $proposal->id,
                                                    ]) !!}
                                                            <a href="#!" class="show_confirm dropdown-item"  data-text="{{ __('You want to confirm duplicate this proposal. Press Yes to continue or Cancel to go back') }}"
                                                            data-confirm-yes="duplicate-form-{{ $proposal->id }}">
                                                            <i class="ti ti-copy"></i> {{ __('Duplicate') }}
                                                            </a>
                                                        {!! Form::close() !!}
                                                    @endpermission
                                                    @if (module_is_active('ProductService') && ( ($proposal->proposal_module == 'taskly') ? module_is_active('Taskly') :  module_is_active('Account')))
                                                        @permission('proposal edit')
                                                            <a href="{{ route('proposal.edit', \Crypt::encrypt($proposal->id)) }}" class="dropdown-item" >
                                                                <i class="ti ti-edit"></i> {{__('Edit')}}
                                                            </a>
                                                        @endpermission
                                                    @endif
                                                    @permission('proposal delete')
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['proposal.destroy', $proposal->id]]) !!}
                                                            <a href="#!" class="show_confirm dropdown-item">
                                                                <i class="ti ti-trash"></i> {{ __('Delete') }}
                                                            </a>
                                                        {!! Form::close() !!}
                                                    @endpermission
                                                    @permission('proposal show')
                                                        <a href="{{route('proposal.show',\Crypt::encrypt($proposal->id))}}" class="dropdown-item" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                        <i class="ti ti-eye"></i> {{__('View')}}
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
                                    @if ($proposal->status == 0)
                                                <span
                                                    class="badge fix_badge bg-primary p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                            @elseif($proposal->status == 1)
                                                <span
                                                    class="badge fix_badge bg-info p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                            @elseif($proposal->status == 2)
                                                <span
                                                    class="badge fix_badge bg-secondary p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                            @elseif($proposal->status == 3)
                                                <span
                                                    class="badge fix_badge bg-warning p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                            @elseif($proposal->status == 4)
                                                <span
                                                    class="badge fix_badge bg-danger p-2 px-3 rounded">{{ __(\App\Models\Proposal::$statues[$proposal->status]) }}</span>
                                            @endif

                                    <div class="row align-items-center mt-3">
                                        <div class="col-6">
                                            <h6 class="mb-0">{{currency_format_with_sym($proposal->getTotal())}}</h6>
                                            <span class="text-sm text-muted">{{__('Total Amount')}}</span>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-0">{{currency_format_with_sym($proposal->getTotalTax())}}</h6>
                                            <span class="text-sm text-muted">{{__('Total Tax')}}</span>
                                        </div>
                                    </div>
                                    <div class="row align-items-center mt-3">
                                        <div class="col-6">
                                            <h6 class="mb-0">{{company_date_formate($proposal->issue_date)}}</h6>
                                            <span class="text-sm text-muted">{{__('Issue Date')}}</span>
                                        </div>
                                        <div class="col-6">
                                            <h6 class="mb-0">{{ !empty($proposal->send_date) ? company_date_formate($proposal->send_date) : '-'}}</h6>
                                            <span class="text-sm text-muted">{{__('Send Date')}}</span>
                                        </div>
                                    </div>
                                </div>
                                @if (\Auth::user()->type != 'Client')
                                    @if (!empty($proposal->customer))
                                        <div class="user-group pt-2">
                                                <img alt="image" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="{{ $proposal->customer->name }}"
                                                    @if ($proposal->customer->avatar) src="{{ get_file($proposal->customer->avatar) }}" @else src="{{ get_file('avatar.png') }}" @endif
                                                    class="rounded-circle " width="25" height="25">
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
            @endforeach
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        $(document).on("click",".cp_link",function() {
            var value = $(this).attr('data-link');
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val(value).select();
                document.execCommand("copy");
                $temp.remove();
                toastrs('success', '{{__('Link Copy on Clipboard')}}', 'success')
        });
    </script>
@endpush
