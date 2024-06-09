@extends('layouts.main')
@section('page-title')
    {{ __('Proposal') }}
@endsection
@section('page-breadcrumb')
    {{ __('Proposal') }}
@endsection
@section('page-action')
    <div>
        @stack('addButtonHook')
        @if (module_is_active('ProductService'))
            @permission('category create')
                <a href="{{ route('category.index') }}"data-size="md"  class="btn btn-sm btn-primary" data-bs-toggle="tooltip"data-title="{{__('Setup')}}" title="{{__('Setup')}}"><i class="ti ti-settings"></i></a>
            @endpermission
        @endif
        @if ((module_is_active('ProductService') && module_is_active('Account')) || module_is_active('Taskly'))
            @permission('proposal manage')
                <a href="{{ route('proposal.grid.view') }}"  data-bs-toggle="tooltip" data-bs-original-title="{{__('Grid View')}}" class="btn btn-sm btn-primary btn-icon">
                    <i class="ti ti-layout-grid"></i>
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
                    {{ Form::open(['route' => ['proposal.index'], 'method' => 'GET', 'id' => 'frm_submit']) }}
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
                                <a href="{{ route('proposal.index') }}" class="btn btn-sm btn-danger "
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
            <div class="card">
                <div class="card-body table-border-style">
                    <div class="table-responsive">
                        <table class="table mb-0 pc-dt-simple" id="assets">
                            <thead>
                                <tr>
                                    <th> {{ __('Proposal') }}</th>
                                    @if (\Auth::user()->type != 'client')
                                        <th> {{ __('Customer') }}</th>
                                    @endif
                                    <th>{{ __('Account Type') }}</th>
                                    <th> {{ __('Issue Date') }}</th>
                                    <th> {{ __('Status') }}</th>
                                    <th > {{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($proposals as $proposal)
                                <tr class="font-style">
                                        <td class="Id">
                                            <a href="{{ route('proposal.show', \Crypt::encrypt($proposal->id)) }}"
                                                class="btn btn-outline-primary">{{ \App\Models\Proposal::proposalNumberFormat($proposal->proposal_id) }}
                                            </a>
                                        </td>
                                        @if (\Auth::user()->type != 'client')
                                            <td> {{ !empty($proposal->customer) ? $proposal->customer->name : '' }} </td>
                                        @endif
                                        <td>{{$proposal->account_type }}</td>
                                        <td>{{ company_date_formate($proposal->issue_date) }}</td>
                                        <td>
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
                                        </td>
                                            <td class="Action">
                                                <span>
                                                    <div class="action-btn bg-primary ms-2">
                                                        <a href="#" class="btn btn-sm  align-items-center cp_link" data-link="{{route('pay.proposalpay',\Illuminate\Support\Facades\Crypt::encrypt($proposal->id))}}" data-bs-toggle="tooltip" title="{{__('Copy')}}" data-original-title="{{__('Click to copy proposal link')}}">
                                                            <i class="ti ti-file text-white"></i>
                                                        </a>
                                                    </div>
                                                    @if ($proposal->is_convert == 0 && $proposal->is_convert_retainer ==0)
                                                        @permission('proposal convert invoice')
                                                            <div class="action-btn bg-success ms-2">
                                                                {!! Form::open([
                                                                    'method' => 'get',
                                                                    'route' => ['proposal.convert', $proposal->id],
                                                                    'id' => 'proposal-form-' . $proposal->id,
                                                                ]) !!}
                                                                <a href="#"
                                                                    class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                    data-bs-toggle="tooltip" title=""
                                                                    data-bs-original-title="{{ __('Convert to Invoice') }}"
                                                                    aria-label="Delete"
                                                                    data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                    data-confirm-yes="proposal-form-{{ $proposal->id }}">
                                                                    <i class="ti ti-exchange text-white"></i>
                                                                </a>
                                                                {{ Form::close() }}
                                                            </div>
                                                        @endpermission
                                                    @elseif($proposal->is_convert ==1)

                                                        @permission('invoice show')
                                                            <div class="action-btn bg-success ms-2">
                                                                <a href="{{ route('invoice.show', \Crypt::encrypt($proposal->converted_invoice_id)) }}"
                                                                    class="mx-3 btn btn-sm  align-items-center"
                                                                    data-bs-toggle="tooltip"
                                                                    title="{{ __('Already convert to Invoice') }}">
                                                                    <i class="ti ti-eye text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission
                                                    @endif
                                                    @if (module_is_active('Retainer'))
                                                        @include('retainer::setting.convert_retainer', ['proposal' => $proposal ,'type' =>'list'])
                                                    @endif
                                                    @permission('proposal duplicate')
                                                        <div class="action-btn bg-secondary ms-2">
                                                            {!! Form::open([
                                                                'method' => 'get',
                                                                'route' => ['proposal.duplicate', $proposal->id],
                                                                'id' => 'duplicate-form-' . $proposal->id,
                                                            ]) !!}
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="{{ __('Duplicate') }}"
                                                                aria-label="Delete"
                                                                data-text="{{ __('You want to confirm duplicate this proposal. Press Yes to continue or Cancel to go back') }}"
                                                                data-confirm-yes="duplicate-form-{{ $proposal->id }}">
                                                                <i class="ti ti-copy text-white text-white"></i>
                                                            </a>
                                                            {{ Form::close() }}
                                                        </div>
                                                    @endpermission
                                                    @permission('proposal show')
                                                        <div class="action-btn bg-warning ms-2">
                                                            <a href="{{ route('proposal.show', \Crypt::encrypt($proposal->id)) }}"
                                                                class="mx-3 btn btn-sm  align-items-center"
                                                                data-bs-toggle="tooltip" title="{{ __('Show') }}"
                                                                data-original-title="{{ __('Detail') }}">
                                                                <i class="ti ti-eye text-white text-white"></i>
                                                            </a>
                                                        </div>
                                                    @endpermission
                                                    @if (module_is_active('ProductService') && ($proposal->proposal_module == 'taskly' ? module_is_active('Taskly') : module_is_active('Account')) && ($proposal->proposal_module == 'cmms' ? module_is_active('CMMS') : module_is_active('Account')))

                                                        @permission('proposal edit')
                                                            <div class="action-btn bg-info ms-2">
                                                                <a href="{{ route('proposal.edit', \Crypt::encrypt($proposal->id)) }}"
                                                                    class="mx-3 btn btn-sm  align-items-center"
                                                                    data-bs-toggle="tooltip"
                                                                    data-bs-original-title="{{ __('Edit') }}">
                                                                    <i class="ti ti-pencil text-white"></i>
                                                                </a>
                                                            </div>
                                                        @endpermission
                                                    @endif
                                                    @permission('proposal delete')
                                                        <div class="action-btn bg-danger ms-2">
                                                            {{ Form::open(['route' => ['proposal.destroy', $proposal->id], 'class' => 'm-0']) }}
                                                            @method('DELETE')
                                                            <a href="#"
                                                                class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                                data-bs-toggle="tooltip" title=""
                                                                data-bs-original-title="Delete" aria-label="Delete"
                                                                data-confirm="{{ __('Are You Sure?') }}"
                                                                data-text="{{ __('This action can not be undone. Do you want to continue?') }}"
                                                                data-confirm-yes="delete-form-{{ $proposal->id }}"><i
                                                                    class="ti ti-trash text-white text-white"></i></a>
                                                            {{ Form::close() }}
                                                        </div>
                                                    @endpermission
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
