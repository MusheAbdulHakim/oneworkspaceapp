@extends('layouts.main')
@section('page-title')
{{ __('Custom Pages') }}
@endsection
@section('page-breadcrumb')
{{ __('Custom Pages') }}
@endsection
@section('page-action')
<div>
    @permission('user logs history')
        <a data-size="lg" data-url="{{ route('custom_page.create') }}" data-ajax-popup="true"  data-bs-toggle="tooltip" title="{{__('Create')}}" data-title="{{__('Create New Menu')}}"  class="btn btn-sm btn-primary">
            <i class="ti ti-plus text-light"></i>
        </a>
    @endpermission
</div>
@endsection
@section('content')
    <!-- [ Main Content ] start -->
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-border-style">
                        <div class="table-responsive">
                            <table class="table mb-0 pc-dt-simple" id="custom">
                                <thead>
                                <tr>
                                    <th>{{__('No')}}</th>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @if (is_array($pages) || is_object($pages))
                                    @php
                                    $no = 1
                                    @endphp
                                        @foreach ($pages as $key => $value)

                                            <tr>
                                                <td>{{ $no++ }}</td>
                                                <td>{{ $value['menubar_page_name'] }}</td>
                                                <td>
                                                    <span>
                                                        <div class="action-btn bg-info ms-2">
                                                                <a href="#" class="mx-3 btn btn-sm align-items-center" data-url="{{ route('custom_page.edit',$key) }}" data-ajax-popup="true" data-title="{{__('Edit Page')}}" data-size="lg" data-bs-toggle="tooltip"  title="{{__('Edit')}}" data-original-title="{{__('Edit')}}">
                                                                <i class="ti ti-pencil text-white"></i>
                                                            </a>
                                                        </div>
                                                        @if($value['page_slug'] != 'terms_and_conditions' && $value['page_slug'] != 'about_us' && $value['page_slug'] != 'privacy_policy')
                                                            <div class="action-btn bg-danger ms-2">
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['custom_page.destroy', $key],'id'=>'delete-form-'.$key]) !!}

                                                                <a href="#" class="mx-3 btn btn-sm align-items-center bs-pass-para show_confirm" data-bs-toggle="tooltip" title="{{__('Delete')}}" data-original-title="{{__('Delete')}}" data-confirm-yes="{{'delete-form-'.$key}}">
                                                                <i class="ti ti-trash text-white"></i>
                                                            </a>
                                                                {!! Form::close() !!}
                                                            </div>
                                                        @endif
                                                    </span>
                                                </td>

                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <!-- [ Main Content ] end -->
@endsection
