@extends('layouts.main')
@section('page-title')
    {{ __('Manage Termination') }}
@endsection
@section('page-breadcrumb')
{{ __('Termination') }}
@endsection
@section('page-action')
<div>
    @permission('termination create')
        <a  class="btn btn-sm btn-primary" data-ajax-popup="true" data-size="md" data-title="{{ __('Create New Termination') }}" data-url="{{route('termination.create')}}" data-toggle="tooltip" title="{{ __('Create') }}">
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
                    <table class="table mb-0 pc-dt-simple" id="assets">
                        <thead>
                            <tr>
                                @if (in_array(\Auth::user()->type, \Auth::user()->not_emp_type))
                                    <th>{{ __('Employee') }}</th>
                                @endif
                                <th>{{ __('Termination Type') }}</th>
                                <th>{{ __('Notice Date') }}</th>
                                <th>{{ __('Termination Date') }}</th>
                                @permission('termination description')
                                    <th>{{ __('Description') }}</th>
                                @endpermission
                                @if (Laratrust::hasPermission('termination edit') || Laratrust::hasPermission('termination delete'))
                                    <th width="200px">{{ __('Action') }}</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($terminations as $termination)
                            <tr>
                                @if (in_array(\Auth::user()->type, \Auth::user()->not_emp_type))
                                    <td>{{ !empty( $termination->user_id) ? $termination->users->name : '' }}</td>
                                @endif
                                <td>{{ !empty($termination->termination_type) ? ($termination->terminationType->name ) ?? '' : '' }}</td>
                                <td>{{ company_date_formate($termination->notice_date) }}</td>
                                <td>{{ company_date_formate($termination->termination_date) }}</td>
                                @permission('termination description')
                                    <td>
                                        <a  class="action-item" data-url="{{ route('termination.description',$termination->id) }}" data-ajax-popup="true" data-bs-toggle="tooltip" title="{{__('Desciption')}}" data-title="{{__('Desciption')}}"><i class="fa fa-comment"></i></a>
                                    </td>
                                @endpermission
                                @if (Laratrust::hasPermission('termination edit') || Laratrust::hasPermission('termination delete'))
                                    <td class="Action">
                                        <span>
                                            @permission('termination edit')
                                                <div class="action-btn bg-info ms-2">
                                                    <a  class="mx-3 btn btn-sm  align-items-center"
                                                        data-url="{{ URL::to('termination/' . $termination->id . '/edit') }}"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="{{ __('Edit Termination') }}"
                                                        data-bs-original-title="{{ __('Edit') }}">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            @endpermission
                                            @permission('termination delete')
                                            <div class="action-btn bg-danger ms-2">
                                                    {{Form::open(array('route'=>array('termination.destroy', $termination->id),'class' => 'm-0'))}}
                                                    @method('DELETE')
                                                        <a 
                                                            class="mx-3 btn btn-sm  align-items-center bs-pass-para show_confirm"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete" data-confirm="{{__('Are You Sure?')}}" data-text="{{__('This action can not be undone. Do you want to continue?')}}"  data-confirm-yes="delete-form-{{$termination->id}}"><i
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

