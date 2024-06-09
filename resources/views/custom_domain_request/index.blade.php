@extends('layouts.main')
@section('page-title')
    {{__('Custom Domain Request')}}
@endsection
@section('page-breadcrumb')
    {{__('Custom Domain Request')}}
@endsection
@section('content')
    <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0 pc-dt-simple" id="users">
                                <thead>
                                    <tr>
                                        <th> {{ __('Company Name') }}</th>
                                        <th> {{ __('Workspace Name') }}</th>
                                        <th> {{ __('Custom Domain') }}</th>
                                        <th> {{ __('Status') }}</th>
                                        <th> {{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($custom_domain_requests as $custom_domain_request)
                                        <tr>
                                            <td>
                                                <div class="font-style font-weight-bold">
                                                    {{ $custom_domain_request->user->name }}</div>
                                            </td>
                                            <td>
                                                <div class="font-style font-weight-bold">
                                                    {{ $custom_domain_request->workspaces->name }}</div>
                                            </td>
                                            <td>
                                                <div class="font-style font-weight-bold">
                                                    {{ $custom_domain_request->domain }}</div>
                                            </td>
                                            <td>
                                                @if ($custom_domain_request->status == 0)
                                                    <span
                                                        class="badge fix_badges bg-danger p-2 px-3 rounded">{{ __(App\Models\CustomDomainRequest::$statues[$custom_domain_request->status]) }}</span>
                                                @elseif($custom_domain_request->status == 1)
                                                    <span
                                                        class="badge fix_badges bg-primary p-2 px-3 rounded">{{ __(App\Models\CustomDomainRequest::$statues[$custom_domain_request->status]) }}</span>
                                                @elseif($custom_domain_request->status == 2)
                                                    <span
                                                        class="badge fix_badges bg-warning p-2 px-3 rounded">{{ __(App\Models\CustomDomainRequest::$statues[$custom_domain_request->status]) }}</span>
                                                @endif
                                            </td>
                                            <td class="Action">

                                                <div class="action-btn bg-primary ms-2">
                                                    <a href="{{route('custom_domain_request.request',[$custom_domain_request->id,1])}}"
                                                        title="{{__('Accept')}}" data-bs-toggle="tooltip">
                                                       <span> <i class="ti ti-check btn btn-sm text-white"></i></span>
                                                    </a>
                                                </div>
                                                <div class="action-btn bg-warning ms-2">
                                                    <a href="{{route('custom_domain_request.request',[$custom_domain_request->id,0])}}"
                                                        title="{{__('Reject')}}" data-bs-toggle="tooltip">
                                                       <span> <i class="ti ti-x btn btn-sm text-white"></i></span>
                                                    </a>
                                                </div>

                                                <div class="action-btn bg-danger ms-2">
                                                    {{ Form::open(['route' => ['custom_domain_request.destroy', $custom_domain_request->id], 'class' => 'm-0']) }}
                                                    @method('DELETE')
                                                    <a href="#"
                                                        class="mx-3 btn btn-sm  align-items-center  show_confirm"
                                                        data-bs-toggle="tooltip" title=""
                                                        data-bs-original-title="Delete" aria-label="Delete"
                                                        data-confirm-yes="delete-form-{{ $custom_domain_request->id }}"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    {{ Form::close() }}
                                                </div>
                                            </td>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- [ Main Content ] end -->
@endsection
